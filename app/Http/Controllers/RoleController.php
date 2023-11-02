<?php

namespace App\Http\Controllers;

use App\Exceptions\AlreadyDone;
use App\Exceptions\NotEmpty;
use App\Exceptions\RoleEnUso;
use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\ModuloResource;
use App\Http\Resources\PermisoResource;
use App\Http\Resources\RoleResourceFull;
use App\Models\Modulo;
use App\Models\Permiso;
use App\Models\Role;
use App\Models\RoleModulo;
use App\Models\RolePermiso;
use App\Tools\ResponseCodes;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public const MODULO = 'roles';

    /**
     * @OA\Get(
     *     tags={"Roles"},
     *     path="/api/roles",
     *     description="Listado de roles",
     *     security={{"token": {}}},
     *     operationId="role_index",
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
    public function roles()
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('read');
        try {
            $roles = Role::all();
            return RoleResourceFull::collection($roles);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Roles"},
     *     path="/api/roles/permisos",
     *     description="Get All Permissions",
     *     security={{"token": {}}},
     *     operationId="get_permisos",
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
    public function permisos()
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('read');
        try {
            $permisos = Permiso::all();
            return PermisoResource::collection($permisos);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Roles"},
     *     path="/api/roles/modulos",
     *     description="Mostrar todos los modulos registrados",
     *     security={{"token": {}}},
     *     operationId="get_modulos",
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
    public function modulos()
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('read');
        try {
            $modulos = Modulo::all();
            return ModuloResource::collection($modulos);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Roles"},
     *     path="/api/roles/store",
     *     description="Crear un nuevo rol",
     *     security={{"token": {}}},
     *     operationId="role_store",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"nombre","descripcion"},
     *                  @OA\Property(property="nombre", type="string", description="Nombre del Role", example="Vendedores"),
     *                  @OA\Property(property="descripcion", type="string", description="Descripcion del nuevo rol", example="Role de Pruebas"),
     *       ),
     *      ),
     *   ),
     * @OA\Response(
     *    response=201,
     *    description="Successful Stored",
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
    public function store(Request $request)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('create');

        $this->validate($request, [
            'nombre'  => 'required|unique:roles',
            'descripcion' => 'required',
        ]);

        try {
            $role = new Role;
            $role->nombre = $request->nombre;
            $role->descripcion = $request->descripcion;
            $role->save();
            return new RoleResourceFull($role);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }


    /**
     * @OA\Get(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/show",
     *     description="Mostrar un role",
     *     security={{"token": {}}},
     *     operationId="role_show",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Id del role",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
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
    public function show(Role $role)
    {
        return new RoleResourceFull($role);
    }


    /**
     * @OA\Post(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/update",
     *     description="Actualizar un role",
     *     security={{"token": {}}},
     *     operationId="role_update",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"nombre","descripcion"},
     *                  @OA\Property(property="nombre", type="string", description="Nombre del Horario", example="Vendedor Actualizado"),
     *                  @OA\Property(property="descripcion", type="string", description="Descripcion del Role", example="My New Awesome Role"),
     *       ),
     *      ),
     *   ),
     * @OA\Response(
     *    response=201,
     *    description="Successful Stored",
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
    public function update(Request $request, Role $role)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('update');

        $this->validate($request, [
            'nombre'  => 'required|unique:roles,nombre,' . $role->id,
            'descripcion' => 'required',
        ]);

        if (auth()->user()->role->id == $role->id) {
            throw new RoleEnUso();
        }

        try {
            $role->nombre = $request->nombre;
            $role->descripcion = $request->descripcion;
            $role->save();
            return new RoleResourceFull($role);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/delete",
     *     description="Borrar un role",
     *     security={{"token": {}}},
     *     operationId="role_destroy",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Deleted",
     *    @OA\JsonContent(@OA\Property(property="status", type="string", example="successful"),
     *                     @OA\Property(property="message", type="string", example="Recurso borrado"),
     *        )
     * ),
     * @OA\Response(
     *    response=404,
     *    description="Error recurso no encontrado",
     *    @OA\JsonContent(@OA\Property(property="status", type="string", example="error"),
     *                     @OA\Property(property="message", type="string", example="Recurso no encontrado"),
     *        )
     * ),
     * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */
    public function destroy(Role $role)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('destroy');

        if ($role->usuarios ? $role->usuarios->count() > 0 : false) {
            throw new NotEmpty();
        } else {
            $role->delete();
            return $this->deleted();
        }
    }

    /**
     * @OA\Post(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/attachModule",
     *     description="Vincular un modulo a un role",
     *     security={{"token": {}}},
     *     operationId="attachModule",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="modulo",
     *          in="query",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Response(
     *    response=201,
     *    description="Successful Stored",
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
    public function attachModule(Request $request, Role $role)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('create');

        $this->validate($request, [
            'modulo'  => 'required',
        ]);

        if ($role->id == auth()->user()->role->id) {
            throw new RoleEnUso();
        }

        $modulo = Modulo::findOrFail($request->modulo);

        foreach ($role->modulos as $item) {
            if ($item->modulo_id == $modulo->id) {
                throw new AlreadyDone();
            }
        }
        try {
            $roleModulo = new RoleModulo();
            $roleModulo->role_id = $role->id;
            $roleModulo->modulo_id = $modulo->id;
            $roleModulo->save();
            return response()->json(['status' => 'Successful', 'message' => 'Modulo agregado'], ResponseCodes::OK);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/detachModule",
     *     description="Desvincular un Modulo a un role",
     *     security={{"token": {}}},
     *     operationId="detachModule",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * * @OA\Parameter(
     *          name="modulo",
     *          in="query",
     *          description="Id del modulo",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Response(
     *    response=200,
     *    description="Modulo desvinculado",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="Modulo desvinculado"),
     *        )
     *      ),
     * * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * * * @OA\Response(
     *    response=422,
     *    description="Modulo no se encuentra asociado",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Modulo no se encuentra asociado")
     *        )
     *     ),
     * )
     */
    public function detachModule(Request $request, Role $role)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('destroy');

        $this->validate($request, [
            'modulo'  => 'required',
        ]);

        if ($role->id == auth()->user()->role->id) {
            throw new RoleEnUso();
        }

        $roleModulos = RoleModulo::where('modulo_id', $request->modulo)->where('role_id', $role->id)->get();

        if ($roleModulos->count() > 0) {
            foreach ($roleModulos as $item) {
                $item->delete();
            }
            return response()->json(['status' => 'Successful', 'message' => 'Modulo desvinculado'], ResponseCodes::OK);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Modulo no se encuentra asociado'], ResponseCodes::UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/attachPermission",
     *     description="Vincular Permiso a un role",
     *     security={{"token": {}}},
     *     operationId="attachPermission",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="permiso",
     *          in="query",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Response(
     *    response=201,
     *    description="Successful Stored",
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
    public function attachPermission(Request $request, Role $role)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('create');

        $this->validate($request, [
            'permiso'  => 'required',
        ]);

        if ($role->id == auth()->user()->role->id) {
            throw new RoleEnUso();
        }

        $permiso = Permiso::findOrFail($request->permiso);

        foreach ($role->permisos as $item) {
            if ($item->permiso_id == $permiso->id) {
                throw new AlreadyDone;
            }
        }
        try {
            $rolePermiso = new RolePermiso();
            $rolePermiso->role_id = $role->id;
            $rolePermiso->permiso_id = $permiso->id;
            $rolePermiso->save();
            return response()->json(['status' => 'Successful', 'message' => 'Permiso agregado'], ResponseCodes::OK);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Roles"},
     *     path="/api/roles/{role}/detachPermission",
     *     description="Desvincular Permiso de un role",
     *     security={{"token": {}}},
     *     operationId="detachPermission",
     * @OA\Parameter(
     *          name="role",
     *          in="path",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="permiso",
     *          in="query",
     *          description="Model Id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *              format="integer",
     *              example="1",
     *          )
     *      ),
     * @OA\Response(
     *    response=200,
     *    description="Permiso desvinculado",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="Permiso desvinculado"),
     *        )
     * ),
     * * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Permiso no se encuentra asociado",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Permiso no se encuentra asociado")
     *        )
     *     ),
     * )
     */
    public function detachPermission(Request $request, Role $role)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('destroy');

        $this->validate($request, [
            'permiso'  => 'required',
        ]);

        if ($role->id == auth()->user()->role->id) {
            throw new RoleEnUso();
        }

        $rolePermisos = RolePermiso::where('permiso_id', $request->permiso)->where('role_id', $role->id)->get();

        if ($rolePermisos->count() > 0) {
            foreach ($rolePermisos as $item) {
                $item->delete();
            }
            return response()->json(['status' => 'Successful', 'message' => 'Permiso desvinculado'], ResponseCodes::OK);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Permiso no se encuentra asociado'], ResponseCodes::UNPROCESSABLE_ENTITY);
        }
    }
}