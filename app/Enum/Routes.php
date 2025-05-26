<?php

namespace App\Enum;

enum Routes : string
{
    case ProductForm = 'dashboard.product.form';

    public function toRoute()
    {   
        $prefix = redirect();

        return match($this)
        {
            self::ProductForm => $prefix->route($this),
            default => $prefix->back(),
        };
    }
}
