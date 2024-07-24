<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="",
 *      title="Puraani Jeans APIs",
 *      description="Puraani Jeans API documentation",
 *      @OA\Contact(
 *          email="developer@puranijeans.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description=L5_SWAGGER_CONST_ENV
 * )
 *
 * @OA\SecurityScheme(
 *     type="apiKey",
 *     description="user access token",
 *     name="Authorization",
 *     in="header",
 *     securityScheme="user_access_token"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
