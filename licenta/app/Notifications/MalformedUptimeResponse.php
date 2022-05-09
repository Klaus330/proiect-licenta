<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MalformedUptimeResponse extends Notification implements ShouldQueue
{
    use Queueable;

    protected $site;
    protected $responseBody;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site, $responseBody)
    {
        $this->site = $site;
        $this->responseBody = $responseBody;
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
                    ->line('There seems to be an issue with your website')
                    ->line("What we expected: {$this->site->check}")
                    ->line("What we get: {$this->responseBody}")
                    ->action('Notification Action', url(route('uptime.index', ['site' => $this->site->id])))
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
            'message' => 'There seems to be an issue with your website',
            'link' => url(route('sites.performance', $this->site->id)),
        ];
    }
}
