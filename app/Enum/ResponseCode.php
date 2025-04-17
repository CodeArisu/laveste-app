<?php

namespace App\Enum;

enum ResponseCode : int
{
    case OK = 200;
    case ERROR = 401;
    case INVALID = 402;

    public function getResponse()
    {   
        $value = $this->value;

        return match(true)
        {
            $value >= 9_000 => 200,
            default => 500
        };
    }
    
    // cases for authentication
    case UserRegisteredSuccessfully = 9_000;
    case UserLoggedInSuccessfully = 9_001;
    case UserLoggedOffSuccessfully = 9_002;

    //
    case ProductCreatedSuccessfully = 11_000;
    case ProductUpdatedSuccessfully = 11_001;
    case ProductDeletedSuccessfully = 11_002;
}
