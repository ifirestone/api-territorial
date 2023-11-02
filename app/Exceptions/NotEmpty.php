<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;
use Exception;


class NotEmpty extends Exception
{
    public function render()
    {
        return response()->json(['status' => 'error', 'message' => 'No puede ser borrado, recurso en uso'], ResponseCodes::UNPROCESSABLE_ENTITY);
    }
}