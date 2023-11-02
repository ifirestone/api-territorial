<?php

namespace App\Http\Controllers;

use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\SectorResource;
use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/sectores",
     *     description="Listado Sectores",
     *     operationId="sectores_index",
     * @OA\Parameter(
     *          name="busqueda",
     *          in="query",
     *          description="Parametro de busqueda",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *              example="sector",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="municipios",
     *          in="query",
     *          description="Municipio Model ID",
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
            $sectores = Sector::buscar($request->busqueda)
                ->municipios($request->municipios)
                ->orderBy('nombre', 'ASC')
                ->paginate(isset($request->paginar) ? $request->paginar : 30);
            return SectorResource::collection($sectores);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}
