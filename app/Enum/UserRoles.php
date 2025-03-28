<?php

namespace App\Enum;

enum UserRoles : int
{
    case ADMINISTRATOR = 1;
    case MANAGER = 2;
    case ACCOUNTANT = 3;
    case EMPLOYEE = 4;
    case GUEST = 5;

    public function label(): string
    {
        return match($this) {
            self::ADMINISTRATOR => 'admin',
            self::MANAGER => 'manager',
            self::ACCOUNTANT => 'accountant',
            self::EMPLOYEE => 'employee',
            self::GUEST => 'guest'
        };
    }
}
