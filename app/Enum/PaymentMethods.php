<?php

namespace App\Enum;

enum PaymentMethods : int
{
    case CASH = 1;
    case GCASH = 2;
    case CREDIT_CARD = 3;
    case DEBIT_CARD = 4;
    case PAYPAL = 5;

    public function label(): string
    {
        return match($this) {
            self::CASH => 'cash',
            self::GCASH => 'gcash',
            self::CREDIT_CARD => 'credit Card',
            self::DEBIT_CARD => 'debit Card',
            self::PAYPAL => 'paypal',
        };
    }
}
