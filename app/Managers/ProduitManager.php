<?php

namespace App\managers;

use App\Models\Produit;

class ProduitManager
{
    public function ajouterProduit(array $data): Produit
    {
        $produit = Produit::create($data);

        return $produit;
    }
}
