<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationsSection extends Component
{
    public $unreadNotifications;

    public $listeners = [
        'markNotificationAsRead' => 'markNotificationAsRead',
    ];

    public function mount()
    {
        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }

    public function render()
    {
        return view('livewire.notifications-section');
    }

    public function markNotificationAsRead($notificationId)
    {
        if($notificationId === 'all'){
            $this->unreadNotifications = collect([]);
            $this->unreadNotifications->each(function($item){
                $item->markAsRead();
            });
        }else{
            $this->unreadNotifications->where('id', $notificationId)->markAsRead();
            $this->unreadNotifications = $this->unreadNotifications->where('id', '!=', $notificationId);
        }
    }
}
