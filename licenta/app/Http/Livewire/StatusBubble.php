<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StatusBubble extends Component
{
    public $status;

    public  $success = [
        'message' => 'Success'
    ];
    
    public  $error = [
        'message' => 'Failed'
    ];
    
    public  $pending = [
        'message' => 'Pending'
    ];

    public  $info = [
        'message' => 'Info'
    ];

    public function render()
    {
        return view('livewire.status-bubble');
    }

    public function getMessageProperty()
    {
        return match($this->status){
            'success' => $this->success['message'],
            'info' => $this->info['message'],
            'pending' => $this->pending['message'],
            'error' => $this->error['message']
        };
    }

    public function getBackgroundProperty()
    {
        return match($this->status){
            'success' => 'text-green-400',
            'info' => 'text-blue-400',
            'pending' => 'text-blue-400',
            'error' => 'text-red-400'
        };
    }
}
