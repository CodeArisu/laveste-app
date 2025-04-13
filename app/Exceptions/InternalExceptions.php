<?php

namespace App\Exceptions;

use App\Enum\StatusCode;
use Exception;

class InternalExceptions extends Exception
{   
    protected StatusCode $statusCode;

    public static function new(
        StatusCode $code,
        ?string $message = null,
        ?int $statusCode = null
    ) : static
    {
        $exception = new Static (
            $message ?? $code->getErrorMessage(),
            $statusCode ?? $code->value
        );

        return $exception;
    }

    public function getStatusCode() : StatusCode
    {
        return $this->statusCode;
    }
}
