<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResourceLogin;
use Illuminate\Http\Request;
use App\Tools\ResponseCodes;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     * tags={"Login"},
     * path="/api/login",
     * description="Autenticarse en el sistema",
     * operationId="login",
     * @OA\RequestBody(
     *    required=true,
     *     @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema( required={"username","password"},
     *                  @OA\Property(property="username", type="string", description="UserName", example="admin"),
     *                  @OA\Property(property="password", type="string", description="Password", example="admin"),
     *       ),
     *      ),
     *   ),
     * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="error"),
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="json", example="User information"),
     *       @OA\Property(property="token", type="string", example="bearer token for user"),
     *        )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'activo' => 0]) || Auth::attempt(['email' => $request->username, 'password' => $request->password, 'activo' => 0])) {

            Auth::logout();
            return response(['status' => 'error', 'message' => 'Usuario No esta Activo'], ResponseCodes::UNAUTHORIZED);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password, 'activo' => 1]) || Auth::attempt(['email' => $request->username, 'password' => $request->password, 'activo' => 1])) {
        } else {
            return response(['status' => 'error', 'message' => 'Credenciales Invalidas'], ResponseCodes::UNAUTHORIZED);
        }

        $user = auth()->user();
        $accessToken = auth()->user()->createToken(env('TOKEN_SECRET'))->accessToken;
        return response(['user' => new UserResourceLogin($user), 'access_token' => $accessToken], ResponseCodes::OK);
    }

    /**
     * @OA\Post(
     *     tags={"Login"},
     *     path="/api/logout",
     *     description="Revocar autorizacion en el sistema",
     *     security={{"token": {}}},
     *     operationId="logout",
     * @OA\Response(
     *    response=200,
     *    description="Successful Response",
     *    @OA\JsonContent(
     *       @OA\Property(property="status", type="string", example="successful"),
     *       @OA\Property(property="message", type="string", example="User has been logged out"),
     *        )
     *     ),
     * * @OA\Response(
     *    response=401,
     *    description="Bad Request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenticated")
     *        )
     *     ),
     * )
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['status' => 'successful', 'message' => 'El usuario ha cerrado sesión'], ResponseCodes::OK);
    }
}