<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subject;
    public $view;
    public $data;

    
    /**
     * Create a new message instance.
     */
    public function __construct($subject, $view, $data)
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
       

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
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
        try{
            $attachments = [];
            if (isset($this->data['attachment']))
                {
                    if (is_array($this->data['attachment'])) {
                        foreach ($this->data['attachment'] as $filePath) {
                            $localPath = str_replace(url('/storage'), storage_path('app/public'), $filePath);
                            $localPath = str_replace('/app/public/app/public', '/app/public', $localPath);
                            $attachments[] = Attachment::fromPath($localPath);
                        }
                    } else {
                        $localPath = $this->data['attachment'];
                        if (file_exists($localPath)) {
                            $attachments[] = Attachment::fromPath($localPath);
                        }
                    }
                }

            //if (isset($this->data['attachment'])) {
            //$attachments[] = $this->data['attachment'];
            //}

            // if (isset($this->data['attachment2'])) {
            //     $attachments[] = $this->data['attachment2'];
            // }

            return $attachments;
        }
        catch(\Throwable $e){
            Log::error('Error  Attachment: ' . $e->getMessage());
            throw new \Exception("Error Attachment in email:".$e->getMessage());
        }
    }

}