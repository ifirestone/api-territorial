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
            if(env('APP_ENV') == 'local') {
                return response()->json(['status' => 'error', 'message' => 'Something Went Wrong', 'file' => $this->th->getFile(), 'line' => $this->th->getLine(), 'trace' => $this->th->getMessage()], ResponseCodes::UNPROCESSABLE_ENTITY);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Something Went Wrong'], ResponseCodes::UNPROCESSABLE_ENTITY);
            }

    }
}