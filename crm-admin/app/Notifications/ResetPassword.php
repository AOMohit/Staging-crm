<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends Notification
{
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Notification delivery channel.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail message.
     */
    public function toMail($notifiable)
    {
        // Generate the reset link (same as Laravel default)
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Pass your Blade HTML view
        return (new MailMessage)
            ->subject('Reset Your Password | Adventures Overland')
            ->view('emails.reset-password', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'data' => [
                    'email' => $notifiable->email,
                ],
            ]);
    }
}
