<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendMailEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $recipientEmail;
    public $subject;
    public $view;
    public $data;
   
    /**
     * Create a new event instance.
     */
    public function __construct($recipientEmail, $subject, $view, $data)
    {
        $this->recipientEmail = $recipientEmail;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }
}