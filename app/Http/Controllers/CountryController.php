<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

use App\Http\Resources\CountryResource;
use App\Http\Resources\CountryResourceFull;
use App\Exceptions\SomethingWentWrong;

class CountryController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/paises",
     *     description="Listado de paises",
     *     security={{"token": {}}},
     *     operationId="listPaises",
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
    public function index()
    {
        try {
            $paises = Country::all();
            return CountryResource::collection($paises);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/paises/{pais}/show",
     *     description="Muestra un Pais",
     *     security={{"token": {}}},
     *     operationId="showPais",
     * @OA\Parameter(
     *          name="pais",
     *          in="path",
     *          description="ID del pais",
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
    public function show(Country $pais)
    {
        try {
            return new CountryResourceFull($pais);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}