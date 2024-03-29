<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *    title="API Territorial Republica Dominicana",
 *    version="2.0",
 *    description="Documentación API Territorial Republica Dominicana",
 *   @OA\Contact(
 *       name="API Support",
 *       url= "http://www.madlab.com.do",
 *       email = "soporte@madlab.com.do",
 *   ),
 * ),
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="http",
 *   name="Authorization",
 *   in="header",
 *   scheme="Bearer"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
