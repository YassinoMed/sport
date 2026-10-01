<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\UserPayment;
use App\Services\Sms\SmsServiceFactory;
use App\Services\Sms\SmsProviderInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:remind {--days=3} {--dry-run}';
    protected $description = 'Identifie les athlètes avec des paiements en attente et envoie des rappels.';

    public function handle()
    {
        $days = (int) $this->option('days');
        if ($days < 0 || $days > 365) {
            $this->error('Option --days invalide.');
            return Command::FAILURE;
        }

        $currentMonth = now()->startOfMonth()->toDateString();
        $athletesToEnsureDue = \App\Models\User::role('user')
            ->whereDoesntHave('payments', function ($query) use ($currentMonth) {
                $query->where('billing_month', $currentMonth)
                    ->where('status', 'paid');
            })
            ->get();

        foreach ($athletesToEnsureDue as $athlete) {
            UserPayment::firstOrCreate(
                ['user_id' => $athlete->id, 'billing_month' => $currentMonth],
                [
                    'status' => 'due',
                    'amount_cents' => 0,
                    'method' => 'cash',
                    'start_date' => Carbon::parse($currentMonth)->startOfMonth(),
                    'end_date' => Carbon::parse($currentMonth)->endOfMonth(),
                ]
            );
        }

        $targetDate = Carbon::today()->addDays($days)->toDateString();
        $targetBillingMonth = Carbon::parse($targetDate)->startOfMonth()->toDateString();

        $payments = UserPayment::query()
            ->with('user')
            ->where('status', 'due')
            ->where(function ($q) use ($targetDate, $targetBillingMonth) {
                $q->whereDate('end_date', $targetDate)
                    ->orWhere(function ($q) use ($targetBillingMonth) {
                        $q->whereNull('end_date')->whereDate('billing_month', $targetBillingMonth);
                    });
            })
            ->whereHas('user', fn ($q) => $q->role('user'))
            ->get();

        $this->info("Found {$payments->count()} due payments with end_date = {$targetDate}");

        $dryRun = (bool) $this->option('dry-run');
        $smsProvider = SmsServiceFactory::make();
        $twilioCountry = (string) config('services.twilio.default_country_code');

        $canSendSms = !$dryRun;

        if (!$canSendSms) {
            $this->warn('Mode --dry-run activé. Aucun SMS ne sera envoyé.');
        }

        $sent = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($payments as $payment) {
            $user = $payment->user;
            if (!$user) {
                $skipped++;
                continue;
            }

            $rawPhone = (string) $user->phone;
            $to = $this->normalizePhone($rawPhone, $twilioCountry);
            if ($to === null) {
                $this->line("Skip: {$user->name} (phone manquant/invalide)");
                $this->logSmsActivity($user->id, $user->name, $payment->id, null, false, true, null, null, 'phone_missing_or_invalid', ['raw_phone' => $rawPhone]);
                $skipped++;
                continue;
            }

            $amount = $payment->amount_cents !== null ? number_format(((int) $payment->amount_cents) / 100, 2, '.', ' ') : null;
            $billingMonth = $payment->billing_month ? Carbon::parse($payment->billing_month)->format('m/Y') : null;
            $endDate = $payment->end_date
                ? Carbon::parse($payment->end_date)->format('d/m/Y')
                : Carbon::parse($payment->billing_month)->endOfMonth()->format('d/m/Y');

            $message = "Bonjour {$user->name}, rappel: votre paiement"
                . ($billingMonth ? " ({$billingMonth})" : '')
                . ($amount !== null ? " de {$amount}" : '')
                . " arrive à échéance le {$endDate}. Merci.";

            if (!$canSendSms) {
                $this->line("Dry-run: {$user->name} -> {$to}");
                $this->logSmsActivity($user->id, $user->name, $payment->id, $to, true, false, null, null, 'dry_run', ['message' => $message]);
                $sent++;
                continue;
            }

            [$ok, $providerMessageId, $httpStatus, $error] = $smsProvider->send($to, $message);
            $this->logSmsActivity($user->id, $user->name, $payment->id, $to, $ok, false, $providerMessageId, $httpStatus, $error, ['message' => $message]);

            if ($ok) {
                $sent++;
                $this->line("Sent: {$user->name} -> {$to}");
            } else {
                $failed++;
                $this->error("Failed: {$user->name} -> {$to}" . ($error ? " ({$error})" : ''));
            }
        }

        $this->info("Done. sent={$sent}, skipped={$skipped}, failed={$failed}");
        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    private function normalizePhone(string $phone, string $defaultCountryCode): ?string
    {
        $value = trim($phone);
        if ($value === '') {
            return null;
        }

        $value = preg_replace('/[^\d\+]/', '', $value);
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, '00')) {
            $value = '+' . substr($value, 2);
        }

        if (str_starts_with($value, '+')) {
            return strlen($value) >= 8 ? $value : null;
        }

        $cc = trim($defaultCountryCode);
        $cc = ltrim($cc, '+');
        if ($cc !== '' && str_starts_with($value, '0')) {
            $value = '+' . $cc . substr($value, 1);
        }

        return str_starts_with($value, '+') ? $value : null;
    }

    private function logSmsActivity(
        int $userId,
        string $userName,
        int $paymentId,
        ?string $to,
        bool $ok,
        bool $skipped,
        ?string $providerMessageId,
        ?int $httpStatus,
        ?string $error,
        array $extraPayload = []
    ): void {
        $payload = array_merge([
            'channel' => 'sms',
            'provider' => config('services.sms_provider', 'twilio'),
            'payment_id' => $paymentId,
            'to' => $to,
            'ok' => $ok,
            'skipped' => $skipped,
            'provider_message_id' => $providerMessageId,
            'http_status' => $httpStatus,
            'error' => $error,
        ], $extraPayload);

        ActivityLog::create([
            'user_id' => $userId,
            'user_name' => $userName,
            'action' => 'SMS Rappel Paiement',
            'url' => 'console:payments:remind',
            'method' => 'CLI',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'scheduler',
            'payload' => $payload,
        ]);
    }
}
