<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $fillable = ['ligne_id','type','message','statut'];

    // Une alerte appartient à une ligne
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
