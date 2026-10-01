<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class OrangeSmsProvider implements SmsProviderInterface
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $senderAddress; // e.g. 'tel:+216xxxxxxxx'
    protected string $senderName;    // e.g. 'GYM_MOURAD'

    public function __construct()
    {
        $this->clientId = (string) config('services.orange.client_id');
        $this->clientSecret = (string) config('services.orange.client_secret');
        $this->senderAddress = (string) config('services.orange.sender_address');
        $this->senderName = (string) config('services.orange.sender_name');
    }

    public function send(string $to, string $message): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [false, null, null, 'Unable to retrieve Orange Access Token'];
        }

        // Orange API expects 'tel:+216xxxxxxx'
        $formattedTo = str_starts_with($to, '+') ? 'tel:' . $to : 'tel:+' . $to;
        
        $url = "https://api.orange.com/smsmessaging/v1/outbound/{$this->senderAddress}/requests";

        try {
            $response = Http::withToken($token)
                ->post($url, [
                    'outboundSMSMessageRequest' => [
                        'address' => $formattedTo,
                        'senderAddress' => $this->senderAddress,
                        'outboundSMSTextMessage' => [
                            'message' => $message,
                        ],
                        'senderName' => $this->senderName,
                    ],
                ]);
        } catch (\Throwable $e) {
            return [false, null, null, $e->getMessage()];
        }

        if ($response->successful()) {
            return [true, null, $response->status(), null];
        }

        return [false, null, $response->status(), $response->body()];
    }

    private function getAccessToken(): ?string
    {
        return Cache::remember('orange_sms_token', 3600, function () {
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                ])
                ->post('https://api.orange.com/oauth/v2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->successful()) {
                return $response->json('access_token');
            }

            return null;
        });
    }
}
