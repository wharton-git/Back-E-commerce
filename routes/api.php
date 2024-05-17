<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommandesController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

}); 

Route::post('register', [UserController::class, 'store']);
Route::post('/detail/{id}', [ProduitsController::class, 'show']);

Route::get('all', [ProduitsController::class, 'index']);
Route::post('upload', [ProduitsController::class, 'store']);
Route::post('type', [ProduitsController::class, 'getType']);
Route::get('user',[UserController::class,'index']);
Route::get('mostpurchased',[CommandesController::class,'mostPurchased']);
Route::post('commandes', [CommandesController::class, 'commandeUser']);
Route::post('commande',[CommandesController::class,'store']);
Route::post('detailCommande',[CommandesController::class,'storeDetail']);
