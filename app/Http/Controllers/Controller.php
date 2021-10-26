<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


/**
* @OA\Info(
*    title="API Territorial Republica Dominicana",
*    version="2.0",
*    description="Documentación API Territorial Republica Dominicana",
*   @OA\Contact(
*       name="API Support",
*       url= "http://www.fmt.com.do/support",
*       email = "info@fmt.com.do",
*   ),
* ),
*/
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}