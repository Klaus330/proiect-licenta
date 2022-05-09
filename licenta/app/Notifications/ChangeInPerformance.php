<?php

namespace App\Notifications;

use App\Models\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeInPerformance extends Notification implements ShouldQueue
{
    use Queueable;

    protected Site $site;
    private $currentDuration;
    private $previousDuration;
    private $diffInDuration;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
        $latestStats = $this->site->stats()->get();
        $this->currentDuration = $latestStats[0]->duration;
        $this->previousDuration = $latestStats[1]->duration;
        $this->diffInDuration = $this->currentDuration - $this->previousDuration;
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
                    ->greeting('Oh no,')
                    ->line('Your site seems to have some performance issue.')
                    ->line("The current request transfer time is: {$this->currentDuration} ms")
                    ->line("The previous request transfer time is: {$this->previousDuration} ms")
                    ->line("Difference in duration: {$this->diffInDuration} ms")
                    ->action("See full report", url("/"))
                    ->line("Thank you for using our application!");
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
            'message' => 'Your site seems to have some performance issue.',
            'link' => url(route('sites.performance', $this->site->id)),
        ];
    }
}
