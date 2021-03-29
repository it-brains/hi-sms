<?php

namespace ITBrains\HiSMS\Facades;

use Illuminate\Support\Facades\Facade;
use ITBrains\HiSMS\HiSMSClient;

class HiSMSFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HiSMSClient::class;
    }
}
