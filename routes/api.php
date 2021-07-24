<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class,'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class,'logout']);
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::resource('/articles', ArticleController::class);
    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/profil', [AuthController::class,'profil']);
    
    // Route::get('/{id}', [ArticleController::class, 'show']);
    

});