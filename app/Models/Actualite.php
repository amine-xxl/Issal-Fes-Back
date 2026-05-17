<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actualite extends Model
{
    protected $fillable = ['titre','contenu','image'];
    //Pas de relation avec les autres tables,cette table est indépendante
}
