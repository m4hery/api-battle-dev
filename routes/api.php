<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\BackOffice\StatistiqueController;
use App\Http\Controllers\BackOffice\RemisearticleController;
use App\Http\Controllers\BackOffice\AuthController as BackOfficeAuthController;
use App\Http\Controllers\BackOffice\RemiseBirthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('api.auth')->group(function () {
    Route::apiResource('commandes', CommandeController::class);
    Route::get("commande/group", [CommandeController::class, 'getCommandes']);
    Route::get("commandes/{ref}/produits", [CommandeController::class, 'getProduitByRef']);
    Route::get("bon-achat", [CommandeController::class, 'getBonDachat']);
    Route::post("reset-bon-achat", [CommandeController::class, 'resetBonDachat']);
});
Route::get("commande/{ref}", [CommandeController::class, 'getProduitByRef']);
Route::get("/remise-article", [RemisearticleController::class, 'index']);
Route::get("/remise-birth", [RemiseBirthController::class, 'index']);

Route::prefix('admin')->group(function () {
    Route::post('login', [BackOfficeAuthController::class, 'login']);
    Route::get('/stat/vente-produit', [StatistiqueController::class, 'venteProduit']);
    Route::get('/stat/vente-categorie', [StatistiqueController::class, 'venteCategories']);
    Route::get('/stat/vente-origine', [StatistiqueController::class, 'venteOrigines']);
    Route::get('/stat/vente-sexe', [StatistiqueController::class, 'venteSexe']);
    Route::get('/stat/vente-client', [StatistiqueController::class, 'venteClient']);

    Route::apiResource('remise-articles', RemisearticleController::class);
    Route::post('remise-change/{remisearticle}', [RemisearticleController::class, 'changeActif']);

    Route::apiResource("remise-birth", RemiseBirthController::class);

});
