<?php

namespace App\Notifications;

use App\Models\Scheduler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SchedulerFailed extends Notification implements ShouldQueue
{
    use Queueable;

    protected Scheduler $scheduler;
    protected $response;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Scheduler $scheduler, $response)
    {
        $this->scheduler = $scheduler;
        $this->response = $response;
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
                    ->line("Your {$this->scheduler->name} scheduler has failed.")
                    ->line("The status code was {$this->response->getStatusCode()}.")
                    ->action('Notification Action', url('/'))
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
            'message' => "Your {$this->scheduler->name} scheduler has failed.",
            'link' => url(route('schedulers.index', ['site' => $this->scheduler->site->id])),
        ];
    }
}
