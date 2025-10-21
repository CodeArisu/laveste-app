<?php

namespace App\Services;

use App\Traits\DoubleValidation;
use App\Traits\ResponsesTrait;

abstract class BaseServicesClass
{
    use DoubleValidation, ResponsesTrait;
}
