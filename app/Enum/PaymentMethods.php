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
            self::CASH => 'Cash',
            self::GCASH => 'Gcash',
            self::CREDIT_CARD => 'Credit Card',
            self::DEBIT_CARD => 'Debit Card',
            self::PAYPAL => 'Paypal',
        };
    }
}
