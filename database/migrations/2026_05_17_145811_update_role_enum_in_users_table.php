<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Commande SQL pour modifier l'enum et ajouter 'admin'
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'chauffeur', 'admin') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Commande SQL pour supprimer 'admin'
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('client', 'chauffeur') NOT NULL");
        });
    }
};
