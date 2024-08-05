<?php

namespace App\managers;

use App\Models\Produit;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProduitManager
{
    public function recupererProduit($limit): Paginator
    {
        $produit = Produit::orderBy("name", "asc")->paginate($limit);

        return $produit;
    }

    public function recupererParIdProduit(int $id): Produit
    {
        $produit = Produit::findOrFail($id);

        $this->linkNotExist($produit);

        return $produit;
    }

    public function regarderCorbeille(): Paginator
    {
        $corbeille = Produit::onlyTrashed()->orderBy("name", "asc")->paginate(10);

        return $corbeille;
    }

    public function restaurationProduit(int $id): Produit
    {
        $produit = Produit::onlyTrashed()->findOrFail($id);

        $this->linkNotExist($produit);

        $produit->restore();

        return $produit;
    }

    public function ajouterProduit(array $data): Produit
    {
        $produit = Produit::create($data);

        return $produit;
    }

    public function modifierProduit(int $id, Produit $data): Produit
    {
        $produit = Produit::findOrFail($id);

        $this->linkNotExist($produit);

        $produit->update($data);

        return $produit;
    }

    public function softDelete(int $id): Produit
    {
        $produit = Produit::findOrFail($id);

        $this->linkNotExist($produit);

        $produit->delete();

        return $produit;
    }

    public function destroy(int $id): Produit
    {
        $produit = Produit::onlyTrashed()->findOrFail($id);

        $this->linkNotExist($produit);

        $produit->forceDelete();

        return $produit;
    }

    public function triParTable(string $table, string $order): Paginator
    {
        $produits = Produit::orderBy($table, $order)->paginate(10);

        return $produits;
    }

    public function rechercheProduit(string $table, string $motCle): Paginator
    {
        $produits = Produit::where($table, 'LIKE', '%' . $motCle . '%')
            ->orderBy($table, 'asc')
            ->paginate(10);

        $this->produitNotFound($produits);

        return $produits;
    }

    private function produitNotFound($data)
    {
        if (!$data) {
            throw new ModelNotFoundException("Aucun produit trouvé.");
        }
    }

    private function linkNotExist($data)
    {
        if (!$data) {
            throw new Exception('Le lien pour ce produit n\'existe pas');
        }
    }
}
