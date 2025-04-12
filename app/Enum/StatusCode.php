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
    
    case NO_CONTENT = 204;
    case NOT_IMPLEMENTED = 501;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case TOO_MANY_REQUESTS = 429;
    case REQUEST_TIMEOUT = 408;
    case UNSUPPORTED_MEDIA_TYPE = 415;
    case NOT_ACCEPTABLE = 406;
    case CONFLICT = 409;
    case PRECONDITION_FAILED = 412;
    case LENGTH_REQUIRED = 411;
    case METHOD_NOT_ALLOWED = 405;
    case GONE = 410;
    case REQUEST_ENTITY_TOO_LARGE = 413;
    case REQUEST_URI_TOO_LONG = 414;
}
