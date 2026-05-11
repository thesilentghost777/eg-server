<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Créer la table des raisons de retour
        Schema::create('raisons_retour', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();       // slug ex: "perime", "abime", "mauvaise_qualite"
            $table->string('libelle');              // label affiché ex: "Périmé", "Mauvaise qualité"
            $table->boolean('actif')->default(true);
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // 2. Insérer les 3 raisons existantes comme point de départ
        DB::table('raisons_retour')->insert([
            [
                'code'       => 'perime',
                'libelle'    => 'Périmé',
                'actif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'abime',
                'libelle'    => 'Abîmé',
                'actif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'autre',
                'libelle'    => 'Autre',
                'actif'      => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 3. Modifier la colonne raison de retours_produits : enum -> string(100)
        // Les données existantes sont conservées (les codes matchent)
        DB::statement('ALTER TABLE retours_produits MODIFY COLUMN raison VARCHAR(100) NOT NULL');
    }

    public function down(): void
    {
        // Remettre l'enum (attention : si de nouvelles raisons ont été créées, elles seront perdues)
        DB::statement("ALTER TABLE retours_produits MODIFY COLUMN raison ENUM('perime','abime','autre') NOT NULL");
        Schema::dropIfExists('raisons_retour');
    }
};