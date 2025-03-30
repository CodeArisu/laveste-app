<?php

namespace App\Exceptions;

use Exception;

class InternalException extends Exception
{   
    public static function failedRequest($message, $code, $e)
    {   
        return response()->json(
            [
                'message' => $message,
                'code' => $code,
                'exception' => $e
            ]
        );
    }
}
