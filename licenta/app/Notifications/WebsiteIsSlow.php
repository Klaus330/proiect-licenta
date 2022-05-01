<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Site;

class WebsiteIsSlow extends Notification implements ShouldQueue
{
    use Queueable;

    protected Site $site;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
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
                    ->line('Your website seems to be too slow.')
                    ->line("It's current performance: {$this->site->stats->last()->duration} ms")
                    ->action('See full report', url('/'))
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
            'message' => 'Your website seems to be too slow.',
            'link' => url(route('sites.performance', ['site' => $this->site->id])),
        ];
    }
}
