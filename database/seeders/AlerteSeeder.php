<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Alerte;
use Illuminate\Database\Seeder;

class AlerteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Alerte::create([
            'ligne_id' => 1,
            'type' => 'retard',
            'message' => 'Retard de 15 minutes sur la ligne L1 suite à un accident.',
            'statut' => 'active',
        ]);
    }
}
