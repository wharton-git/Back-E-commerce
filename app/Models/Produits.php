<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LigneCommandes;

class Produits extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation', 'description', 'prix', 'stock' ,'type','couleur','reduction', 'image'
    ];

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommandes::class, 'article');
    }
}
