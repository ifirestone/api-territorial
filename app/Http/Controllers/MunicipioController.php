<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

use App\Http\Resources\MunicipioResource;
use App\Exceptions\SomethingWentWrong;

class MunicipioController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/municipios",
     *     description="Listado de municipios",
     *     security={{"token": {}}},
     *     operationId="municipios",
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
            $municipios = Municipio::ProvinceCode($request->province_code)
            ->get();
            return MunicipioResource::collection($municipios);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/municipios/{municipio}/show",
     *     description="Muestra un Municipio",
     *     security={{"token": {}}},
     *     operationId="showMunicipio",
     * @OA\Parameter(
     *          name="municipio",
     *          in="path",
     *          description="ID del municipio",
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
    public function show(Municipio $municipio)
    {
        try {
            return new MunicipioResource($municipio);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}