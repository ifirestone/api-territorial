<?php

namespace App\Http\Controllers;

use App\Models\Distrito;
use Illuminate\Http\Request;

use App\Http\Resources\DistritoResource;
use App\Exceptions\SomethingWentWrong;

class DistritoController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/distritos",
     *     description="Listado de distritos",
     *     security={{"token": {}}},
     *     operationId="distritos",
     * @OA\Parameter(
     *          name="province_code",
     *          in="query",
     *          description="Code de la provincia",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="2",
     *          )
     *      ),
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
    public function index(Request $request)
    {
        try {
            $distritos = Distrito::ProvinceCode($request->province_code)
            ->get();
            return DistritoResource::collection($distritos);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/distritos/{distrito}/show",
     *     description="Muestra un Distrito",
     *     security={{"token": {}}},
     *     operationId="showDistrito",
     * @OA\Parameter(
     *          name="distrito",
     *          in="path",
     *          description="ID del distrito",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="2",
     *          )
     *      ),
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
    public function show(Distrito $distrito)
    {
        try {
            return new DistritoResource($distrito);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}