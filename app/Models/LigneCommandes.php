<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneCommandes extends Model
{
    use HasFactory;

    protected $fillable = ['id_com', 'article', 'qte', 'prix_article'];

    public function produit()
    {
        return $this->belongsTo(Produits::class, 'article');
    }
}
