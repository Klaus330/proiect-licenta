<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SchedulerCouldNotAuthenticate extends Notification implements ShouldQueue
{
    use Queueable;

    protected $scheduler;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($scheduler)
    {
        $this->scheduler = $scheduler;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line("Your {$this->scheduler->name} scheduler could not authenticate. Please make sure the authentication route is correct.")
                    ->action('Notification Action', url(route('schedulers.index', ['site' => $this->scheduler->host->id])))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => "Your {$this->scheduler->name} scheduler could not authenticate. Please make sure the authentication route is correct.",
            'link' => url(route('schedulers.index', ['site' => $this->scheduler->host->id])),
        ];
    }
}
