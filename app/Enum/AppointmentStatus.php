<?php

namespace App\Enum;

enum AppointmentStatus : int
{   
    case Completed = 1;
    case Scheduled = 2;
    case NoShow = 3;
    case Cancelled = 4;

    public function label() : string
    {
        return match($this) 
        {   
            self::Completed => 'completed',
            self::Scheduled => 'scheduled',
            self::NoShow => 'no show',
            self::Cancelled => 'cancelled'
        };
    }
}
