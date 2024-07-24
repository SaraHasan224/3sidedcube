<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\MetadataController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public route
//Route::get('/public', function () {
//    return response()->json(['message' => 'This is a public route']);
//});

// Protected route
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', [AuthController::class, "login"]);
Route::post('register', [AuthController::class, "register"]);

Route::get('customer', [CustomerController::class, 'getCustomer'])->middleware(['tokenValidation']);

Route::get('/all-post',  [PostController::class, 'getAllPosts']);

Route::middleware(['tokenValidation'])->group(function () {

    Route::get('/all-posts',  [PostController::class, 'getAllPosts']);
    Route::get('/post/{id}',  [PostController::class, 'getPostDetails']);
    Route::post('/post/add',  [PostController::class, 'addPost']);
    Route::post('/post/edit/{id}',  [PostController::class, 'editPost']);
    Route::post('/post/delete/{id}',  [PostController::class, 'deletePost']);

});
