<?php

namespace App\Exceptions;

use App\Enum\StatusCode;

class ProductException extends InternalExceptions
{
    public static function productNotFound() : self
    {
        return static::new(
            StatusCode::ProductNotFound,
        );
    }
    
    public static function productAlreadyAdded() : self
    {
        return static::new(
            StatusCode::ProductAlreadyAdded,
        );
    }
    public static function productCannotBeAdded() : self
    {
        return static::new(
            StatusCode::ProductCannotBeAdded,
        );
    }

    public static function productCreateFailed() : self
    {
        return static::new(
            StatusCode::ProductCreateFailed,
        );
    }

    public static function productValidationFailed() : self
    {
        return static::new(
            StatusCode::ProductValidationFailed,
        );
    }

    public static function productUpdateFailed() : self
    {
        return static::new(
            StatusCode::ProductUpdateFailed,
        );
    }

    public static function productDeleteFailed() : self
    {
        return static::new(
            StatusCode::ProductDeleteFailed,
        );
    }
}
