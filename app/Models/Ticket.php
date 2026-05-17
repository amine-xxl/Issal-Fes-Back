<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = ['user_id','ligne_id','prix','statut','date_achat'];

    // Un ticket appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un ticket appartient à une ligne
    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }
}
