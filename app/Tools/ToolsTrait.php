<?php


namespace App\Tools;

trait ToolsTrait
{

    public static function deleted()
    {
        return response()->json(['status' => 'successful', 'message' => 'Recurso borrado'], ResponseCodes::ACCEPTED);
    }
}