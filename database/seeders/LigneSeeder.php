<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Ligne;
use Illuminate\Database\Seeder;

class LigneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ligne::create([
            'numero' => 'L1',
            'depart' => 'Adarissa',
            'arrivee' => 'Bab Ftouh',
            'description' => 'Ligne principale centre-ville',
        ]);

        Ligne::create([
            'numero' => 'L2',
            'depart' => 'Aeroport Fes-Saiss',
            'arrivee' => 'Centre-ville',
            'description' => 'Liaison aéroport - ville nouvelle',
        ]);
    }
}
