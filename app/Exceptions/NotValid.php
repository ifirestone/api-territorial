<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;

class NotValid extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Identificación No Valida'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}