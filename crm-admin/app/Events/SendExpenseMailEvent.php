<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendExpenseMailEvent
{
    use Dispatchable, SerializesModels;

    public $recipientEmail;
    public $subject;
    public $view;
    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct($recipientEmail, $subject, $view, $data = [])
    {
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }
}
