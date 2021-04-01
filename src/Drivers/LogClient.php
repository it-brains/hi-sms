<?php

namespace ITBrains\HiSMS\Drivers;

use Carbon\Carbon;
use ITBrains\HiSMS\HiSMSClient;
use Log;

class LogClient extends HiSMSClient
{
    public function send(string $to, string $message, ?Carbon $scheduleAt = null)
    {
        Log::channel($this->getChannel())->log($this->getLevel(), "Message to '{$to}': \"{$message}\"");
    }

    public function getBalance(): int
    {
        // So it's like unlimited
        return 10000;
    }

    public function getScheduleSmsCount(): int
    {
        // Because all sms go to log file immediately
        return 0;
    }

    public function deleteScheduleSms(): int
    {
        // Because all sms go to log file immediately
        return 0;
    }

    private function getChannel(): ?string
    {
        return $this->getConfig('channel');
    }

    private function getLevel(): string
    {
        return $this->getConfig('level');
    }

    private function getConfig($key)
    {
        return config("hi-sms.log.{$key}");
    }
}
