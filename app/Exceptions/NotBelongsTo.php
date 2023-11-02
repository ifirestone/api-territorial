<?php

namespace App\Exceptions;

use Exception;
use App\Tools\ResponseCodes;

class NotBelongsTo extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'No pertenece al usuario'], ResponseCodes::UNAUTHORIZED);
    }
}