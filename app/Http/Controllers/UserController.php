<?php

namespace App\Http\Controllers;

use App\Exceptions\SomethingWentWrong;
use App\Exceptions\UsuarioEnUso;
use App\Http\Resources\UserResource;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use App\Tools\ResponseCodes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public const MODULO = 'usuarios';

    /**
     * @OA\Get(
     *     tags={"Usuarios"},
     *     path="/api/usuarios",
     *     description="Listado de Usuarios",
     *     security={{"token": {}}},
     *     operationId="user_index",
     * @OA\Parameter(
     *          name="busqueda",
     *          in="query",
     *          description="Parametro de busqueda",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="string",
     *              example="admin",
     *          )
     *      ),
     * @OA\Parameter(
     *          name="estado",
     *          in="query",
     *          description="Filtra los usuarios entre activos y no activos",
     *          required=false,
     *          @OA\Schema(
     *              type="string",
     *              format="activos | inactivos",
     *              example="activos",
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
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('read');

        try {
            $usuarios = User::estado($request->estado)
                ->buscar($request->busqueda)
                ->join('profiles', 'profiles.user_id', '=', 'users.id')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->orderBy('users.id', 'DESC')
                ->select('users.*')
                ->paginate(env('PAGINAR'));
            return UserResource::collection($usuarios);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Usuarios"},
     *     path="/api/usuarios/store",
     *     description="Crear un nuevo usuario",
     *     security={{"token": {}}},
     *     operationId="user_store",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"nombre","email","username","role","password","password_confirmation"},
     *                  @OA\Property(property="nombre", type="string", description="Nombre del usuario", example="Juan Perez"),
     *                  @OA\Property(property="email", type="string", description="Email del usuario", example="user@email.aqui"),
     *                  @OA\Property(property="username", type="string", description="Nombre de Usuario", example="admin"),
     *                  @OA\Property(property="role", type="integer", description="Model Id", example="1"),
     *                  @OA\Property(property="telefono", type="string", description="Telefono Contacto", example="(999) 555-5555"),
     *                  @OA\Property(property="movil", type="string", description="Movil Contacto", example="(999) 444-3333"),
     *                  @OA\Property(property="password", type="string", description="Password", example="SuperStrongPasswordHere"),
     *                  @OA\Property(property="password_confirmation", type="string", description="Password Confirmation", example="SuperStrongPasswordHere"),
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
            'nombre'  => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'role' => 'required',
            'password' => 'required|confirmed',
        ]);

        $role = Role::findOrFail($request->role);

        try {
            $user = new User();
            $user->role_id = $role->id;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->email_verified_at = Carbon::now();
            $user->save();

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->nombre = $request->nombre;
            $profile->telefono = $request->telefono;
            $profile->movil = $request->movil;
            $profile->save();

            return new UserResource($user);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Get(
     *     tags={"Usuarios"},
     *     path="/api/usuarios/{usuario}/show",
     *     description="Mostrar un usuario",
     *     security={{"token": {}}},
     *     operationId="user_show",
     * @OA\Parameter(
     *          name="usuario",
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
    public function show(User $usuario)
    {
        return new UserResource($usuario);
    }


    /**
     * @OA\Post(
     *     tags={"Usuarios"},
     *     path="/api/usuarios/{usuario}/update",
     *     description="Actualizar un usuario",
     *     security={{"token": {}}},
     *     operationId="user_update",
     * @OA\Parameter(
     *          name="usuario",
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
     *       @OA\Schema( required={"nombre","username","email","role"},
     *                  @OA\Property(property="nombre", type="string", description="Nombre del usuario", example="Juan Perez"),
     *                  @OA\Property(property="email", type="string", description="Email del usuario", example="user@email.aqui"),
     *                  @OA\Property(property="username", type="string", description="Nombre de Usuario", example="admin"),
     *                  @OA\Property(property="role", type="integer", description="Role ID Asignado", example="1"),
     *                  @OA\Property(property="telefono", type="string", description="Telefono Contacto", example="(999) 555-5555"),
     *                  @OA\Property(property="movil", type="string", description="Movil Contacto", example="(999) 444-3333"),
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
    public function update(Request $request, User $usuario)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('update');

        if (auth()->user()->id == $usuario->id) {
            throw new UsuarioEnUso();
        }

        $this->validate($request, [
            'nombre'  => 'required',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'username' => 'required|unique:users,username,' . $usuario->id,
            'role' => 'required',
        ]);

        $role = Role::findOrFail($request->role);

        try {
            $usuario->role_id = $role->id;
            $usuario->email = $request->email;
            $usuario->username = $request->username;
            $usuario->save();

            $usuario->profile->nombre = $request->nombre;
            $usuario->profile->telefono = $request->telefono;
            $usuario->profile->movil = $request->movil;
            $usuario->profile->save();
            return new UserResource($usuario);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Usuarios"},
     *     path="/api/usuarios/{usuario}/resetpassword",
     *     description="Reiniciar la clave de acceso de un usuario",
     *     security={{"token": {}}},
     *     operationId="reset_password",
     * @OA\Parameter(
     *          name="usuario",
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
     *       @OA\Schema( required={"password","password_confirmation"},
     *                  @OA\Property(property="password", type="string", description="Password", example="TheNewSuperPassword"),
     *                  @OA\Property(property="password_confirmation", type="string", description="Password Confirmation", example="TheNewSuperPassword"),
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
    public function resetPassword(Request $request, User $usuario)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('update');

        $request->validate([
            'password' => [
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()
            ],
            'password_confirmation' => 'required|same:password',
        ]);

        if ($usuario->id == auth()->user()->id) {
            throw new UsuarioEnUso();
        } else {
            try {
                $usuario->password = bcrypt($request->password);
                $usuario->save();
                return response()->json(['status' => 'Successful', 'message' => 'Contraseña cambiada'], ResponseCodes::OK);
            } catch (\Throwable $th) {
                throw new SomethingWentWrong($th);
            }
        }
    }


    /**
     * @OA\Delete(
     *     tags={"Usuarios"},
     *     path="/api/usuarios/{usuario}/delete",
     *     description="Borrar un usuario",
     *     security={{"token": {}}},
     *     operationId="user_destroy",
     * @OA\Parameter(
     *          name="usuario",
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
    public function destroy(User $usuario)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('destroy');

        if ($usuario->id == auth()->user()->id) {
            throw new UsuarioEnUso();
        } else {
            $usuario->delete();
            return $this->deleted();
        }
    }

    /**
     * @OA\Post(
     *     tags={"Usuarios"},
     *     path="/api/usuarios/{usuario}/toggle",
     *     description="Activar o des-activar un usuario",
     *     security={{"token": {}}},
     *     operationId="user_toggle",
     * @OA\Parameter(
     *          name="usuario",
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
     *    response=201,
     *    description="Successful Activated or Deactivated",
     *    @OA\JsonContent(@OA\Property(property="data", type="Json", example="[...]"),
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
    public function toggle(User $usuario)
    {
        auth()->user()->hasModulo(self::MODULO);
        auth()->user()->hasPermiso('update');

        if ($usuario->id == auth()->user()->id) {
            throw new UsuarioEnUso();
        } else {
            try {
                $usuario->activo ? $usuario->activo = false : $usuario->activo = true;
                $usuario->save();
                return new UserResource($usuario);
            } catch (\Throwable $th) {
                throw new SomethingWentWrong($th);
            }
        }
    }
}