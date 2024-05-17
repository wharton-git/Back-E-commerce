<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produits extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation', 'description', 'prix', 'stock' ,'type', 'image'
    ];

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class, 'article');
    }
}
