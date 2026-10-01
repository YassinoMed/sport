<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Services\Sms\SmsServiceFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendEventPaymentReminders extends Command
{
    protected $signature = 'events:remind-payments {--days=2} {--dry-run}';
    protected $description = 'Envoie des rappels de paiement pour les événements approchant.';

    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = (bool) $this->option('dry-run');
        
        $targetDate = Carbon::today()->addDays($days)->toDateString();
        
        // Trouver les événements qui commencent dans $days jours
        $events = Event::whereDate('start_at', $targetDate)->get();
        
        if ($events->isEmpty()) {
            $this->info("Aucun événement prévu pour le {$targetDate}.");
            return Command::SUCCESS;
        }

        $smsProvider = SmsServiceFactory::make();
        $sentCount = 0;

        foreach ($events as $event) {
            $this->info("Traitement de l'événement : {$event->name}");
            
            // Trouver les inscriptions non payées ou partiellement payées
            $unpaidRegistrations = EventRegistration::where('event_id', $event->id)
                ->where('paid_amount', '<', $event->registration_fee)
                ->with('user')
                ->get();

            foreach ($unpaidRegistrations as $registration) {
                $user = $registration->user;
                if (!$user || !$user->phone) continue;

                $message = "Rappel : Votre inscription à l'événement '{$event->name}' n'est pas encore réglée. Merci de régulariser avant le " . $event->start_at->format('d/m') . ".";

                if ($dryRun) {
                    $this->line("[Dry-Run] SMS pour {$user->name} ({$user->phone}) : {$message}");
                } else {
                    try {
                        $smsProvider->sendSms($user->phone, $message);
                        $sentCount++;
                    } catch (\Exception $e) {
                        $this->error("Erreur lors de l'envoi à {$user->name} : " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("{$sentCount} rappels envoyés.");
        return Command::SUCCESS;
    }
}
