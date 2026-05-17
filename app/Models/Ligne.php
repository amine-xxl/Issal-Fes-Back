<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ligne extends Model
{
    protected $fillable = ['numero','depart','arrivee','description'];

    // Une ligne peut avoir plusieurs tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Une ligne peut avoir plusieurs alertes
    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    // Une ligne peut avoir plusieurs Infos Proffessionnelles (proInfos)
    public function proInfos()
    {
        return $this->hasMany(ProInfo::class);
    }
}
