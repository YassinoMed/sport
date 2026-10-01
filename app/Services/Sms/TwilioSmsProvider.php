<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;

class TwilioSmsProvider implements SmsProviderInterface
{
    protected string $sid;
    protected string $token;
    protected string $from;

    public function __construct()
    {
        $this->sid = (string) config('services.twilio.account_sid');
        $this->token = (string) config('services.twilio.auth_token');
        $this->from = (string) config('services.twilio.from');
    }

    public function send(string $to, string $message): array
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->sid}/Messages.json";

        try {
            $response = Http::asForm()
                ->withBasicAuth($this->sid, $this->token)
                ->post($url, [
                    'From' => $this->from,
                    'To' => $to,
                    'Body' => $message,
                ]);
        } catch (\Throwable $e) {
            return [false, null, null, $e->getMessage()];
        }

        $providerMessageId = null;
        try {
            $json = $response->json();
            if (is_array($json) && isset($json['sid'])) {
                $providerMessageId = (string) $json['sid'];
            }
        } catch (\Throwable $e) {
        }

        if ($response->successful()) {
            return [true, $providerMessageId, $response->status(), null];
        }

        $error = null;
        try {
            $json = $response->json();
            if (is_array($json) && isset($json['message'])) {
                $error = (string) $json['message'];
            }
        } catch (\Throwable $e) {
        }

        return [false, $providerMessageId, $response->status(), $error];
    }
}
