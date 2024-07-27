<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProduitController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth.jwt')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth.jwt');
    Route::post('refresh', [AuthController::class, 'refreshToken'])->middleware('auth.jwt');
});

//
Route::post('ajout-produit', [ProduitController::class, 'ajouterProduit']);
