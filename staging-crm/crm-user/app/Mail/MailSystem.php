<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailSystem extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;
    public $subject;
    public $view;
   
    /**
     * Create a new message instance.
     */
    
    public function __construct($subject,$mailData,$view)
    {
        
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->view = $view;
        // dd($this->view);
       
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // dd($this->subject);
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
