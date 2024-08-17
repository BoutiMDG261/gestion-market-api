<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ProduitHistory extends Model
{
    protected $table = 'product_history';

    public $timestamps = false;

     // Définir les champs qui peuvent être remplis en masse (mass assignment)
     protected $fillable = [
        'product_id',
        'action_type',
        'changed_by',
        'changed_data',
        'previous_data',
        'comment',
        'timestamp',
    ];

     // Les relations

    /**
     * Relation avec le modèle Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'product_id');
    }

    /**
     * Relation avec le modèle User pour l'utilisateur qui a fait la modification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
