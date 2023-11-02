<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class UsuarioEnUso extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Usuario en uso no puede ser modificado o borrado'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}