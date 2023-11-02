<?php

namespace App\Exceptions;

use App\Tools\ResponseCodes;

use Exception;

class SomethingWentWrong extends Exception
{

    private \Throwable $th;
    /**
     * Create a new exception instance.
     *
     * @return void
     */
    public function __construct(\Throwable $th)
    {
        $this->th = $th;
    }

    public function render()
    {
        if (env('APP_DEBUG') == true) {
            return response()->json(['status' => 'error', 'message' => 'Algo salió mal!!', 'file' => $this->th->getFile(), 'line' => $this->th->getLine(), 'trace' => $this->th->getMessage()], ResponseCodes::UNPROCESSABLE_ENTITY);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Algo salió mal!!'], ResponseCodes::UNPROCESSABLE_ENTITY);
        }
    }
}