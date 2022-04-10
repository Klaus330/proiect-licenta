<?php

namespace App\Exceptions;

use Exception;

class BadHostProvided extends Exception
{
    protected array $data; 
    public function __construct(array $data, $message, $code = 200, Exception $previous = NULL)
    {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getExceptionData()
    {
        return $this->data;
    }
}
