<?php

namespace App\managers;

use App\Models\Produit;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;

class ProduitManager
{
    public function recupererProduit(): Paginator
    {
        $produit = Produit::orderBy("name", "asc")->paginate(10);

        return $produit;
    }

    public function recupererParIdProduit($id): Produit
    {
        $produit = Produit::findOrFail($id);

        return $produit;
    }

    public function regarderCorbeille(): Paginator
    {
        $corbeille = Produit::onlyTrashed()->orderBy("name", "asc")->paginate(10);

        return $corbeille;
    }

    public function restaurationProduit($id): Produit
    {
        $produit = Produit::onlyTrashed()->findOrFail($id);
        $produit->restore();

        return $produit;
    }

    public function ajouterProduit(array $data): Produit
    {
        $produit = Produit::create($data);

        return $produit;
    }

    public function modifierProduit($id, $data): Produit
    {
        $produit = Produit::findOrFail($id);

        if (!$produit) {
            throw new Exception('Le lien pour ce produit n\'existe pas');
        }

        $produit->update($data);

        return $produit;
    }

    public function softDelete($id): Produit
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return $produit;
    }

    public function destroy($id): Produit
    {
        $produit = Produit::onlyTrashed()->findOrFail($id);
        $produit->forceDelete();

        return $produit;
    }
}
