<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\App;

class SmsServiceFactory
{
    public static function make(): SmsProviderInterface
    {
        $provider = config('services.sms_provider', 'twilio');

        return match ($provider) {
            'orange' => App::make(OrangeSmsProvider::class),
            default => App::make(TwilioSmsProvider::class),
        };
    }
}
