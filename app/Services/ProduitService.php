<?php

namespace App\Services;

use App\managers\ProduitManager;
use App\Models\Produit;

class ProduitService
{
    protected ProduitManager $produitManager;

    public function __construct(ProduitManager $produitManager)
    {
        $this->produitManager = $produitManager;
    }

    public function ajouterProduit(array $data): array
    {
        $produits = [];

        foreach ($data as $item) {
            $produits[] = $this->produitManager->ajouterProduit($item);
        }

        return $produits;
    }
}
