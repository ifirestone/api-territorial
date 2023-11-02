<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class NotEnable extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Servicio no esta habilitado'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}