<?php

namespace App\Enum;

enum AppointmentStatus : int
{
    case Scheduled = 1;
    case NoShow = 2;
    case Cancelled = 3;

    public function label() : string
    {
        return match($this) 
        {
            self::Scheduled => 'scheduled',
            self::NoShow => 'no show',
            self::Cancelled => 'cancelled'
        };
    }
}
