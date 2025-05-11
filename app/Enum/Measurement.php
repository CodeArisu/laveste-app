<?php

namespace App\Enum;

enum Measurement : string
{
    case XS = 'extra_small';
    case S = 'small';
    case M = 'medium';
    case L = 'large';
    case XL = 'extra_large';
    case XXL = 'double_extra_large';

    public function label(): string
    {
        return match($this)
        {
            self::XS => 'Extra Small',
            self::S => 'Small',
            self::M => 'Medium',
            self::L => 'Large',
            self::XL => 'Extra Large',
            self::XXL => 'XXL',
        };
    }
}
