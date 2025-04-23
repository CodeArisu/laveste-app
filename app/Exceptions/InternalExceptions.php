<?php

namespace App\Exceptions;

use App\Enum\StatusCode;
use Exception;

class InternalExceptions extends Exception
{   
    protected string $description;
    protected StatusCode $statusCode;

    public static function new(
        StatusCode $code,
        ?string $message = null,
        ?string $desc = null,
        ?int $statusCode = null
    ) : static
    {
        $exception = new Static (
            $message ?? $code->getMessage(),
            $statusCode ?? $code->getStatusCode(),
        );

        $exception->statusCode = $code;
        $exception->description = $desc ?? $code->getDescription(); 

        return $exception;
    }

    public function getStatusCode() : StatusCode
    {
        return $this->statusCode;
    }

    public function getDescription()
    {
        return $this->description;
    }
}
