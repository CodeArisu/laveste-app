<?php

namespace App\Enum;

enum ResponseCode : int
{
    case OK = 200;
    case ERROR = 401;
    case INVALID = 402;
}
