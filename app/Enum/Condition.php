<?php

namespace App\Enum;

enum Condition : int
{
    case AVAILABLE = 1;
    case SOLD = 2;
    case RESERVED = 3;
    case OUT_OF_STOCK = 4;
    case UNAVAILABLE = 5;
    case REMOVED = 6;
    
    public function label(): string
    {
        return match($this)
        {
            self::AVAILABLE => 'Available',
            self::SOLD => 'Sold',
            self::RESERVED => 'Reserved',
            self::OUT_OF_STOCK => 'Out of Stock',
            self::UNAVAILABLE => 'Unavailable',
            self::REMOVED => 'Removed',
        };
    }
}

