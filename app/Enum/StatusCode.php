<?php

namespace App\Enum;

enum StatusCode : int
{
    case SUCCESS = 202;
    case ERROR = 502;
    case INVALID = 422;

    case CREATED = 201;

    case NOT_FOUND = 404;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case BAD_REQUEST = 400;
}
