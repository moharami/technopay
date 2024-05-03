<?php

namespace App\Listeners;

use App\Events\FilterClassNotFoundExceptionOccurred;
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
        //
    }
}
