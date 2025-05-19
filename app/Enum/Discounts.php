<?php

namespace App\Enum;

enum Discounts : int
{
    case Regular = 1;
    case Promo = 2;
    case Limited = 3;
    case Senior = 4;

    public function percent() : float
    {
        return match($this)
        {
            self::Regular => .16,
            self::Promo => .18,
            self::Limited => .25,
            self::Senior => .20,
        };
    }

    public function type() : string
    {
        return match($this)
        {
            self::Regular => 'regular',
            self::Promo => 'promo',
            self::Limited => 'limited',
            self::Senior => 'senior',
        };
    }
}
