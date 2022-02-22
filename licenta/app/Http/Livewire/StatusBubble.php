<?php

namespace App\Http\Livewire;

use Livewire\Component;

class StatusBubble extends Component
{
    public $status;

    public  $success = [
        'message' => 'Success'
    ];
    
    public  $failed = [
        'message' => 'Failed'
    ];
    
    public  $pending = [
        'message' => 'Pending'
    ];

    public function render()
    {
        return view('livewire.status-bubble');
    }

    public function getMessageProperty()
    {
        return match($this->type()){
            'success' => $this->success['message'],
            'pending' => $this->pending['message'],
            'failed' => $this->failed['message']
        };
    }

    public function getBackgroundProperty()
    {
        return match($this->type()){
            'success' => 'text-green-400',
            'pending' => 'text-blue-400',
            'failed' => 'text-red-400'
        };
    }

    public function type()
    {
      return match($this->status){
            true => 'success',
            false => 'failed',
            default  => 'pending'
        };
    }
}
