<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
/**
 * @OA\Info(
 *    title="Ot'gether API",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *     cookieAuth:         # arbitrary name for the security scheme; will be used in the "security" key later
 *     type: apiKey
 *     in: cookie
 *     name: laravel_session  
 * )
 * security:
 *  - cookieAuth: []
 * 
 */
{
    use AuthorizesRequests, ValidatesRequests;
}
