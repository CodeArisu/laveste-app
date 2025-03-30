<?php

namespace App\Enum;

enum Condition : int
{
    case OK = 1;
    case DAMAGED = 2;
    case MAINTENANCE = 3;
    
    public function label(): string
    {
        return match($this)
        {
            self::OK => 'ok',
            self::DAMAGED => 'damaged',
            self::MAINTENANCE => 'maintenance'
        };
    }
}

