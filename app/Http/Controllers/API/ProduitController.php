<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProduitRequest;
use App\Http\Requests\UpdateProduitRequest;
use App\Services\ProduitService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    protected ProduitService $produitService;

    public function __construct(ProduitService $produitService)
    {
        $this->produitService = $produitService;
    }

    public function recupererProduit(Request $request): JsonResponse
    {
        $limit = $request->get('per_page', 10);
        try {
            $produit = $this->produitService->recupererProduit($limit);
            return response()->json([
                'message' => 'Recupération des produits réussi.',
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du recupération des produits.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function recupererParIdProduit($id): JsonResponse
    {
        try {
            $produit = $this->produitService->recupererParIdProduit($id);
            return response()->json([
                'message' => "Recupération du produit: '{$produit->name}' réussi.",
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du recupération des produits.',
                'error' => $e->getMessage()
            ], 500);
        }

        return $produit;
    }

    public function regarderCorbeille(): JsonResponse
    {
        try {
            $corbeille = $this->produitService->regarderCorbeille();
            return response()->json([
                'message' => 'Recupération des produits dans la corbeille réussi.',
                'data' => $corbeille
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du recupération des produits.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function restaurationProduit($id): JsonResponse
    {
        try {
            $produit = $this->produitService->restaurationProduit($id);
            return response()->json([
                'message' => 'Restauration du produit réussi.',
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du recupération du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function ajouterProduit(StoreProduitRequest $request): JsonResponse
    {
        try {
            $validatedProduit = $request->validated();
            $produit = $this->produitService->ajouterProduit($validatedProduit);
            return response()->json([
                'message' => 'Ajout de produit réussi.',
                'data' => $produit
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'ajout du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function modifierProduit(UpdateProduitRequest $request, $id): JsonResponse
    {
        try {
            $validatedProduit = $request->validated();
            $produit = $this->produitService->modifierProduit($id, $validatedProduit);
            return response()->json([
                'message' => 'Mis à jour de produit réussi.',
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de l\'ajout du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function softDelete($id): JsonResponse
    {
        try {
            $produit = $this->produitService->softDelete($id);
            return response()->json([
                'message' => 'Produit envoyé dans la corbeille.',
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $produit = $this->produitService->destroy($id);
            return response()->json([
                'message' => 'Produit supprimé définetivement.',
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors de la suppression du produit.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function triParTable(Request $request): JsonResponse
    {
        $table = $request->get('table', 'name');
        $order = $request->get('orderBy', 'asc');

        try {
            $produit = $this->produitService->triParTable($table, $order);
            return response()->json([
                'message' => "Recupération des produits réussi. Trié par {$table}.",
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du recupération des produits.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rechercheProduit(Request $request): JsonResponse
    {
        $table = $request->get('table', 'name');
        $motCle = $request->get('key');

        try {
            $produit = $this->produitService->rechercheProduit($table, $motCle);

            if ($produit->isEmpty()) {
                return response()->json([
                    'message' => 'Aucun produit trouvé.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => "Recupération des produits réussi.",
                'data' => $produit
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur lors du recupération des produits.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
