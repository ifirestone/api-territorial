<?php

namespace App\Http\Controllers;

use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\ProvinciaResource;
use App\Models\Provincia;
use Illuminate\Http\Request;

class ProvinciaController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Territorial"},
     *     path="/api/provincias",
     *     description="Listado Provincias",
     *     operationId="provincia_index",
     * @OA\Parameter(
     *          name="busqueda",
     *          in="query",
     *          description="Parametro de busqueda",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *              example="provincia",
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
            $provincias = Provincia::buscar($request->busqueda)
                ->orderBy('nombre', 'ASC')
                ->paginate(isset($request->paginar) ? $request->paginar : 30);
            return ProvinciaResource::collection($provincias);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }
}
