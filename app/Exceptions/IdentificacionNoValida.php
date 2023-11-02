<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class IdentificacionNoValida extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Numero Identificación no valido'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}