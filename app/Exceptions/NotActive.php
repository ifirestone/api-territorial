<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;


class NotActive extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'No se encuentra activo'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}