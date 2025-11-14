<?php

namespace App\Listeners;

use App\Events\SendExpenseMailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Log;

class SendExpenseMailListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendExpenseMailEvent $event): void
    {
        try {
            if (setting('mail_status') != 1) {
                return;
            }

            $recipientEmail = $event->recipientEmail;
            $subject = $event->subject;
            $view = $event->view;
            $data = $event->data;

            $mail = new SendMail($subject, $view, $data);
            Mail::to($recipientEmail)->send($mail);

            Log::info("SendExpenseMailListener: Sent expense mail to {$recipientEmail} subject: {$subject}");
        } catch (\Throwable $e) {
            Log::error('Error sending expense mail: ' . $e->getMessage());
            throw $e;
        }
    }
}
