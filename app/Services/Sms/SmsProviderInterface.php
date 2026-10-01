<?php

namespace App\Services\Sms;

interface SmsProviderInterface
{
    /**
     * @return array [bool $success, ?string $messageId, ?int $httpStatus, ?string $error]
     */
    public function send(string $to, string $message): array;
}
