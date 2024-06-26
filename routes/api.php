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

Route::post('/user/{id}',[UserController::class,'update']);
Route::post('/userLogin/{id}',[UserController::class,'updateLogin']);
Route::post('/userPassword/{id}',[UserController::class,'updatePassword']);
Route::post('/userAdresse/{id}',[UserController::class,'updateAdresse']);
Route::post('/userCard/{id}',[UserController::class,'updateCard']);
Route::post('/userPhone/{id}',[UserController::class,'updatePhone']);
Route::post('/addSolde/{id}',[UserController::class,'addSolde']);
Route::post('/subSolde/{id}',[UserController::class,'subSolde']);
Route::delete('/produit/{id}',[ProduitsController::class,'remove']);
Route::put('/produit/{id}',[ProduitsController::class,'edit']);


Route::get('getDailyTotalPrices',[CommandesController::class,'getDailyTotalPrices']);
Route::get('topClients',[CommandesController::class,'topClients']);
Route::get('topOneClients',[CommandesController::class,'topOneClients']);
Route::get('topProducts',[CommandesController::class,'topProducts']);