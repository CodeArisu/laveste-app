<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function isValidated($validator)
    {  
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid Input(s)',
                'errors' => $validator->errors(),
            ], 422); // Using standard 422 Unprocessable Entity status
        }
        return;
    }
}
