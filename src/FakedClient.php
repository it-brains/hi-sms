<?php

namespace ITBrains\HiSMS;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;

class FakedClient extends HiSMSClient
{
    use WithFaker;

    public function send(string $to, string $message, ?Carbon $scheduleAt = null)
    {
        return $this->faker->randomNumber(8, true);
    }

    public function getBalance(): int
    {
        return random_int(10, 1000);
    }

    public function getScheduleSmsCount(): int
    {
        return random_int(0, 100);
    }

    public function deleteScheduleSms(): int
    {
        return random_int(0, 100);
    }
}
