<?php

namespace App\Http\Controllers;

use App\Models\Provincia;
use Illuminate\Http\Request;

use App\Http\Resources\ProvinciaResource;
use App\Http\Resources\ProvinciaResourceFull;
use App\Exceptions\SomethingWentWrong;

class ProvinciaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/provincias",
     *     description="Listado de provincias",
     *     security={{"token": {}}},
     *     operationId="provincias",
     * @OA\Parameter(
     *          name="country_code",
     *          in="query",
     *          description="Code del pais",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="8089",
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
            $provincias = Provincia::CountryCode($request->country_code)->orderBy('name','asc')->get();
            return ProvinciaResource::collection($provincias);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/provincias/{provincia}/show",
     *     description="Muestra una provincia con sus municipios y distritos",
     *     security={{"token": {}}},
     *     operationId="showProvincia",
     * @OA\Parameter(
     *          name="provincia",
     *          in="path",
     *          description="ID de la provincia",
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
    public function show(Provincia $provincia)
    {
        try {
            return new ProvinciaResourceFull($provincia);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

}