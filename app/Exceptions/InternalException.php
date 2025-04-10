<?php

namespace App\Exceptions;

use Exception;
use PHPUnit\Event\Code\Throwable;

class InternalException extends Exception
{   
    public function __construct($message = '', $code = 0, $e)
    {
        parent::__construct($message, (int)$code, $e);
    }

    public static function failedRequest($message, $code, $e)
    {   
        return response()->json(
            [
                'message' => $message,
                'code' => (int)$code,
                'exception' => $e->getMessage(),
            ],
            
        );
    }
}
