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
    
    public  $info = [
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
            'info' => $this->info['message'],
            'error' => $this->error['message']
        };
    }

    public function getBackgroundProperty()
    {
        return match($this->type()){
            'success' => 'text-green-400',
            'info' => 'text-blue-400',
            'error' => 'text-red-400'
        };
    }

    public function type()
    {
      return match($this->status){
            true => 'success',
            false => 'error',
            default  => 'info'
        };
    }
}
