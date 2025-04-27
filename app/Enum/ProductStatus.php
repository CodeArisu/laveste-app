<?php

namespace App\Enum;

enum ProductStatus : int
{
    case AVAILABLE = 1;
    case RESERVED = 2;
    case UNAVAILABLE = 3;
    case ARCHIVED = 4;
    case PENDING = 5;

    public function label(): string
    {
        return match($this)
        {
            self::AVAILABLE => 'available',
            self::RESERVED => 'reserved',
            self::UNAVAILABLE => 'unavailable',
            self::ARCHIVED => 'archived',
            self::PENDING => 'pending',
        };
    }
}
