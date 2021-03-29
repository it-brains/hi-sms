<?php

namespace ITBrains\HiSMS;

use Carbon\Carbon;

abstract class HiSMSClient
{
    public function sendBulk(array $to, string $message, ?Carbon $scheduleAt = null)
    {
        return $this->send(implode(',', $to), $message, $scheduleAt);
    }

    abstract public function send(string $to, string $message, ?Carbon $scheduleAt = null);

    abstract public function getBalance(): int;

    abstract public function getScheduleSmsCount(): int;

    abstract public function deleteScheduleSms(): int;
}
