<?php

namespace App\Http\Controllers;

use App\Exceptions\SomethingWentWrong;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

use Hash;
use App\Tools\ResponseCodes;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public const MODULO = 'usuarios';

    /**
     * @OA\Get(
     *     tags={"Profile"},
     *     path="/api/profile",
     *     description="Obtener el profile del usuario autenticado",
     *     security={{"token": {}}},
     *     operationId="get_profile",
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
    function getProfile()
    {
        try {
            $user = auth()->user();
            return new UserResource($user);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Profile"},
     *     path="/api/profile/update",
     *     description="Actualizar el profile del usuario autenticado",
     *     security={{"token": {}}},
     *     operationId="update_profile",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"nombre", "email", "username"},
     *                  @OA\Property(property="username", type="string", example="admin"),
     *                  @OA\Property(property="email", type="string", example="admin@pruebas.com"),
     *                  @OA\Property(property="nombre", type="string", example="Juan Perez"),
     *                  @OA\Property(property="telefono", type="string", example="123-123-1234"),
     *                  @OA\Property(property="movil", type="string", example="123-123-1234"),
     *                  @OA\Property(property="imagen", type="string", format="binary"),
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
    function update(Request $request)
    {
        //Tomamos el Usuario Con Sesion Iniciada
        $user = auth()->user();

        $request->validate([
            'nombre' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|unique:users,username,' . $user->id,
        ]);

        if (isset($request->imagen)) {
            # Borramos la Imagen Actual
            $this->destroyCloudinary($user->profile->image_name);
            $image = $this->uploadCloudinary($request, "imagen");
        }

        try {
            $user->username = $request->username;
            $user->email = $request->email;
            $user->profile->nombre = $request->nombre;
            $user->profile->telefono = $request->telefono;
            $user->profile->movil = $request->movil;
            if (isset($request->imagen)) {
                $user->profile->image_url = $image['url'];
                $user->profile->image_size = $image['size'];
                $user->profile->image_ext = $image['ext'];
                $user->profile->image_name = $image['name'];
                $user->profile->image_id = $image['id'];
            }
            $user->profile->updated_at = Carbon::now();
            $user->profile->save();
            $user->save();
            return new UserResource($user);
        } catch (\Throwable $th) {
            throw new SomethingWentWrong($th);
        }
    }

    /**
     * @OA\Post(
     *     tags={"Profile"},
     *     path="/api/profile/password",
     *     description="Cambiar la contraseña para el usuario autenticado",
     *     security={{"token": {}}},
     *     operationId="chg_password",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"current_password","password","password_confirmation"},
     *                  @OA\Property(property="current_password", type="string", example="CurrentPasswordHere"),
     *                  @OA\Property(property="password", type="string", example="NewSuperPasswordHere"),
     *                  @OA\Property(property="password_confirmation", type="string", example="NewSuperPasswordHere"),
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
    function password(Request $request)
    {

        $request->validate([
            'current_password' => 'required',
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

        $user = auth()->user();

        if (Hash::check($request->current_password, $user->password)) {
            try {
                $user->password = bcrypt($request->password);
                $user->save();
                return response()->json(['status' => 'successful', 'message' => 'Contraseña cambiada'], ResponseCodes::OK);
            } catch (\Throwable $th) {
                throw new SomethingWentWrong($th);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Contraseña actual incorrecta'], ResponseCodes::UNAUTHORIZED);
        }
    }

    /**
     * @OA\Delete(
     *     tags={"Profile"},
     *     path="/api/profile/destroy_image",
     *     description="Remover Imagen del Profile",
     *     security={{"token": {}}},
     *     operationId="destroy_image",
     * @OA\Response(
     *    response=201,
     *    description="Successful Destroyed",
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
    function destroy()
    {
        $user = auth()->user();

        if ($user->profile->image_id) {
            $this->destroyCloudinary($user->profile->image_name);
            $user->profile->image_url = null;
            $user->profile->image_size = null;
            $user->profile->image_ext = null;
            $user->profile->image_name = null;
            $user->profile->image_id = null;
            $user->profile->save();

            return response()->json(['status' => 'successful', 'message' => 'Imagen de Perfil Eliminada'], ResponseCodes::OK);
        } else {
            return response()->json(['status' => 'error', 'message' => 'No existe imagen de perfil!'], ResponseCodes::NOT_FOUND);
        }
    }
}