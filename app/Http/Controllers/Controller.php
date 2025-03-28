<?php

namespace App\Http\Controllers;

use App\Enum\StatusCode;

abstract class Controller
{
    protected function isValidated($validator)
    {   
        if ($validator->fails()) {
            abort(StatusCode::BAD_REQUEST, $validator->errors()->first());
        }
        return;
    }
}
