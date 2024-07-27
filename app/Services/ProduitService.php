<?php

namespace App\Services;

use App\managers\ProduitManager;
use App\Models\Produit;
use Illuminate\Contracts\Pagination\Paginator;

class ProduitService
{
    protected ProduitManager $produitManager;

    public function __construct(ProduitManager $produitManager)
    {
        $this->produitManager = $produitManager;
    }

    public function recupererProduit(): Paginator
    {
        $produit = $this->produitManager->recupererProduit();

        return $produit;
    }

    public function recupererParIdProduit($id): Produit
    {
        $produit = $this->produitManager->recupererParIdProduit($id);

        return $produit;
    }

    public function regarderCorbeille(): Paginator
    {
        $corbeille = $this->produitManager->regarderCorbeille();

        return $corbeille;
    }

    public function restaurationProduit($id): Produit
    {
        $produit = $this->produitManager->restaurationProduit($id);

        return $produit;
    }

    public function ajouterProduit(array $data): array
    {
        $produits = [];

        foreach ($data as $item) {
            $produits[] = $this->produitManager->ajouterProduit($item);
        }

        return $produits;
    }

    public function modifierProduit($id, $data): Produit
    {
        $produits = $this->produitManager->modifierProduit($id, $data);

        return $produits;
    }

    public function softDelete($id): Produit
    {
        $produit = $this->produitManager->softDelete($id);

        return $produit;
    }

    public function destroy($id): Produit
    {
        $produit = $this->produitManager->destroy($id);

        return $produit;
    }
}
