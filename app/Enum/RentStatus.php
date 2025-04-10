<?php

namespace App\Enum;

enum RentStatus : int
{
    case RENTED = 1;
    case RETURNED = 2;
    case MISSING = 3;
    case UNPAID = 4;
    case CANCELLED = 5; // or archived
    
    public function label(): string
    {
        return match($this)
        {
            self::RENTED => 'rented',
            self::RETURNED => 'returned',
            self::MISSING => 'missing',
            self::UNPAID => 'unpaid',
            self::CANCELLED => 'cancelled',
        };
    }
}
