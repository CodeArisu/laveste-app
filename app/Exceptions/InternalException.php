<?php

namespace App\Exceptions;

use Exception;

class InternalException extends Exception
{
    public static function failedRequest() : self
    {   
        return new self('Failed to send request');
    }
}
