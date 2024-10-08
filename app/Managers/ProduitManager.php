<?php

namespace App\managers;

use App\Models\Produit;
use App\Models\ProduitHistory;
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
        $data['created_by'] = auth()->user()->id ?? null;
        $data['updated_by'] = auth()->user()->id ?? null;

        $produit = Produit::create($data);

        $this->ajouterHistoriqueProduit($produit, 'INSERT', null, $data);

        return $produit;
    }

    public function modifierProduit(int $id, array $data): Produit
    {
        $produit = Produit::findOrFail($id);

        $this->linkNotExist($produit);

        $previousData = $produit->toArray(); // Capture les données précédentes pour l'historique
        $data['updated_by'] = auth()->user()->id ?? null;

        $produit->update($data);

        // Ajouter un historique pour la mise à jour
        $this->ajouterHistoriqueProduit($produit, 'UPDATE', $previousData, $data);

        return $produit;
    }

    public function softDelete(int $id): Produit
    {
        $produit = Produit::findOrFail($id);

        $this->linkNotExist($produit);

        $previousData = $produit->toArray();

        $produit->delete();

        $this->ajouterHistoriqueProduit($produit, 'DELETE', $previousData, null, 'Produit supprimé logiquement.');

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

    protected function ajouterHistoriqueProduit(Produit $produit, string $actionType, ?array $previousData, ?array $changedData, ?string $comment = null)
    {
        ProduitHistory::create([
            'product_id' => $produit->id,
            'action_type' => $actionType,
            'changed_by' => auth()->user()->id ?? null, // Utilisateur connecté
            'changed_data' => json_encode($changedData),
            'previous_data' => $previousData ? json_encode($previousData) : null,
            'comment' => $comment,
        ]);
    }
}
