<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProduitController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth.jwt')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refreshToken']);
});

//Produit
Route::middleware('auth.jwt')->group(function () {
    Route::get('produit', [ProduitController::class, 'recupererProduit']);
    Route::get('produit/{id}', [ProduitController::class, 'recupererParIdProduit']);
    Route::post('produit', [ProduitController::class, 'ajouterProduit']);
    Route::put('produit/{id}', [ProduitController::class, 'modifierProduit']);
    Route::delete('produit/{id}', [ProduitController::class, 'softDelete']);

    Route::get('corbeille-produit', [ProduitController::class, 'regarderCorbeille']);
    Route::put('produit-restore/{id}', [ProduitController::class, 'restaurationProduit']);
    Route::delete('produit/force-delete/{id}', [ProduitController::class, 'destroy']);

    Route::get('tri', [ProduitController::class, 'triParTable']);
    Route::get('recherche', [ProduitController::class, 'rechercheProduit']);
});
