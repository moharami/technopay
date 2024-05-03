<?php

namespace App\Listeners;

use App\Events\FilterClassNotFoundExceptionOccurred;
use App\Mail\FilterClassNotFoundMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(FilterClassNotFoundExceptionOccurred $event): void
    {
        $admin_email = config('admin.email');
        Mail::to($admin_email)->send(new FilterClassNotFoundMail($event->file));
    }
}
