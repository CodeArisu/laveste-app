<?php

namespace App\Enum;

enum PaymentMethods : string
{
    case CASH = 'cash';
    case GCASH = 'gcash';
    case CREDIT_CARD = 'credit_card';
    case DEBIT_CARD = 'debit_card';
    case PAYPAL = 'paypal';

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
