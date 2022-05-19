<?php

namespace App\Exceptions;

use Exception;

class SchedulerAuthenticationFailedException extends Exception
{
    protected  $data; 

    public function __construct($data, $message, $code = 200, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getExceptionData()
    {
        return $this->data;
    }
}
