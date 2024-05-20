<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordTokenNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $token; 
    protected $message;
    protected $subject;
    protected $fromEmail;
    protected $mailer;
    public function __construct($token)
    {
        //
        $this->message = 'use the below code for resetting yor password';
        $this->subject = 'password resetting';
        $this->fromEmail = 'waelhasan12342@gmail.com';
        $this->mailer = 'smtp';
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->mailer('smtp')
        ->subject($this->subject)
        ->greeting('Hello '.$notifiable->name)
        ->line($this->message)
        ->line('code :'.$this->token);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
