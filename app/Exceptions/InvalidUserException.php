<?php

namespace App\Exceptions;

use App\Enum\StatusCode;
use Illuminate\Http\JsonResponse;
use Throwable;

class InvalidUserException extends InternalExceptions
{       
    public static function InvalidUserCredentials() : self
    {
        return new self('Invalid user or User does not exists', StatusCode::INVALID->value);
    }  
}
