<?php

namespace App\Exceptions;

use App\Enum\StatusCode;
use Exception;

class GarmentException extends InternalExceptions
{
    public static function garmentNotFound() : self
    {
        return static::new(
            StatusCode::GarmentNotFound,
        );
    }
    
    public static function garmentAlreadyAdded() : self
    {
        return static::new(
            StatusCode::GarmentAlreadyAdded,
        );
    }
    public static function garmentCannotBeAdded() : self
    {
        return static::new(
            StatusCode::GarmentCannotBeAdded,
        );
    }

    public static function garmentCreateFailed() : self
    {
        return static::new(
            StatusCode::GarmentCreateFailed,
        );
    }

    public static function garmentValidationFailed() : self
    {
        return static::new(
            StatusCode::GarmentValidationFailed,
        );
    }

    public static function garmentUpdateFailed() : self
    {
        return static::new(
            StatusCode::GarmentUpdateFailed,
        );
    }

    public static function garmentDeleteFailed() : self
    {
        return static::new(
            StatusCode::GarmentDeleteFailed,
        );
    }
}