<?php

namespace App\Exceptions;

use Exception;
use App\Tools\ResponseCodes;

class AlreadyDone extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'Ya existe'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}
