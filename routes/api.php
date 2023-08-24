<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;

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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

// PRODUCTS //

Route::get('/getproducts', [ProductController::class, 'getProducts']);
Route::post('/insertproduct', [ProductController::class, 'insertProduct']);

// USERS

Route::post('/getuser/{user_mail}', [UserController::class, 'getUser']);
