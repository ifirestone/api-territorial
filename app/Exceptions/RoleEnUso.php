<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class RoleEnUso extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Role de usuario en uso no puede ser modificado o borrado'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}