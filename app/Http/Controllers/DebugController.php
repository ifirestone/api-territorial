<?php

namespace App\Http\Controllers;

use App\Tools\ResponseCodes;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Debugger"},
     *     path="/api/ping",
     *     description="Pruebas de API",
     *     operationId="ping",
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
     *        )
     * ),
     * * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */
    public function ping()
    {
        try {
            return response()->json(['status' => 'successful', 'message' => 'Pong!!!!'], ResponseCodes::OK);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}
