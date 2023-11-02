<?php

namespace App\Http\Controllers;

use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\MunicipioResource;
use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/municipios",
     *     description="Listado Municipios",
     *     operationId="municipios_index",
     * @OA\Parameter(
     *          name="busqueda",
     *          in="query",
     *          description="Parametro de busqueda",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *              example="municipio",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="provincias",
     *          in="query",
     *          description="Provincia Model ID",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *              example="1,2,3",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="paginar",
     *          in="query",
     *          description="Parametro de Paginacion",
     *          required=false,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="30",
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
            $municipios = Municipio::buscar($request->busqueda)
                ->provincias($request->provincias)
                ->orderBy('nombre', 'ASC')
                ->paginate(isset($request->paginar) ? $request->paginar : 30);
            return MunicipioResource::collection($municipios);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}
