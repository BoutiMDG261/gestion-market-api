<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProduitRequest;
use App\Services\ProduitService;
use Exception;
use Illuminate\Http\JsonResponse;

class ProduitController extends Controller
{
    protected ProduitService $produitService;

    public function __construct(ProduitService $produitService)
    {
        $this->produitService = $produitService;
    }

    public function ajouterProduit(StoreProduitRequest $request): JsonResponse
    {
        try {
            $validatedProduit = $request->validated();
            $produit = $this->produitService->ajouterProduit($validatedProduit);
            return response()->json([
                'message' => 'Ajout de produit réussi.',
                'produit' => $produit
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'ajout du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
