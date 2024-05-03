<?php

namespace App\Sms;

use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send($phoneNumber, $message)
    {
       Log::info('sms sent');
    }

}