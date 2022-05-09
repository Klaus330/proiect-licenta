<?php

namespace App\Http\Livewire;

use Livewire\Component;

class NotificationMenu extends Component
{

    public $listeners = [
        'markNotificationAsRead' => 'markNotificationAsRead',
    ];

    public $unreadNotifications;

    public function mount()
    {
        $this->unreadNotifications = auth()->user()->unreadNotifications;
    }

    public function render()
    {
        return view('livewire.notification-menu');
    }

    public function markNotificationAsRead($notificationId)
    {
        if($notificationId === 'all'){
            $this->unreadNotifications = collect([]);
        }else{
            $this->unreadNotifications = $this->unreadNotifications->where('id', '!=', $notificationId);
        }
    }
}
