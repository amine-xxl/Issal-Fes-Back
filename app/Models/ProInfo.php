<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProInfo extends Model
{
    protected $fillable = ['user_id','ligne_id','numero_bus','modele','capacite','trajet','tarif'];

    // ProInfo appartient à un user (chauffeur)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ProInfo appartient à une ligne
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
