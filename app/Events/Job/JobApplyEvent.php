<?php

namespace App\Events\Job;

use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobApplyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $email;
    public $employer;

    public function __construct($email, $employer)
    {
        $this->email = $email;
        $this->employer = $employer;
    }
}
