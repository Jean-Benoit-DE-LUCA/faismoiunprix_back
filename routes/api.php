<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OfferController;

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
Route::get('/getproducts/{product_id}', [ProductController::class, 'getProductById']);

Route::post('/insertproduct', [ProductController::class, 'insertProduct']);
Route::post('/insertproductimages/folder/{last_insert_id}', [ProductController::class, 'insertProductImagesFolder']);

// USERS //

Route::post('/getuser/{user_mail}', [UserController::class, 'getUser']);
Route::post('/registeruser', [UserController::class, 'registerUser']);

// OFFERS //

Route::post('/insertoffer', [OfferController::class, 'insertOffer']);
Route::get('/getuserofferssent/{user_id}', [OfferController::class, 'getUserOffersSent']);
Route::get('/getuseroffersreceived/{user_id}', [OfferController::class, 'getUserOffersReceived']);
Route::get('/getoffer/{offer_id}', [OfferController::class, 'getOfferId']);
