<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Site;

class UptimeMonitorRegistered extends Notification implements ShouldQueue
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
        return ['database', 'mail'];
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
                    ->line('Your uptime monitor has been successfully registered.')
                    ->action('Back to see your monitors', url(route('sites.index')))
                    ->line("if you have any feedback, or if Oopsee doesn't work the way you hoped. We'd love to hear about it.
                    Reach out directly by replying to this email, or contact support@oopsee.com
                    ")
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
            'message' => 'Your uptime monitor has been successfully registered.',
            'link' => url(route('sites.show', $this->site->id)),
        ];
    }
}
