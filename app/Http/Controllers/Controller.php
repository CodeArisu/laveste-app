<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function isValidated($validated) {
        return $validated->fails() ?? response()->json([
            'message' => 'Invalid Input(s)',
            'error' => $validated->errors(),
        ], 403);
    }
}
