<?php

namespace App\Listeners;

use App\Events\FilterClassNotFoundExceptionOccurred;
use App\Facade\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSmsNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FilterClassNotFoundExceptionOccurred $event): void
    {
        $mobile = config('admin.mobile');
        Sms::send($mobile, 'filter not found for '. $event->file);
    }
}
