<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commandes extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'date_commande', 'prix_commande'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ligneCommandes()
    {
        return $this->hasMany(LigneCommandes::class, 'id_com');
    }
}
