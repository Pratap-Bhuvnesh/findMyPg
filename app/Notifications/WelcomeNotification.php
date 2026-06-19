<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;
    protected $message;
    protected $verificationUrl;
    /**
     * Create a new notification instance.
     */
    public function __construct($verificationUrl,$message)
    {
        $this->message = $message;
         $this->verificationUrl = $verificationUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
   /*  public function via(object $notifiable)
    {
        return (new MailMessage)
        ->subject('Welcome to PGAdda')
        ->greeting('Hello '.$notifiable->name)
        ->line('Your account has been created successfully.')
        ->line('Thank you for joining us!');
        //return ['database'];
    } */
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
        ->subject('Activate Your Account')
        ->line('Thank you for registering.')
        ->action('Verify Email', $this->verificationUrl)
        ->line('Please verify your email to activate your account.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Registration Successful',
            'message' => $this->message,
            'status' => 'active',            
        ];
    }
}
