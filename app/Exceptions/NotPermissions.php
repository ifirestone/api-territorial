<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class NotPermissions extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Usuario no posee permisos'], ResponseCodes::UNAUTHORIZED);
    }
}