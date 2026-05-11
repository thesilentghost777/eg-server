<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prix_produits_historique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->decimal('prix', 10, 2);
            $table->timestamp('date_debut');
            $table->timestamp('date_fin')->nullable(); // null = prix actuel
            $table->timestamps();

            $table->index(['produit_id', 'date_debut', 'date_fin']);
        });

        // Initialisation : insérer le prix actuel de chaque produit
        // avec date_debut = created_at du produit et date_fin = null
        $produits = DB::table('produits')->get();
        foreach ($produits as $produit) {
            DB::table('prix_produits_historique')->insert([
                'produit_id' => $produit->id,
                'prix'       => $produit->prix,
                'date_debut' => $produit->created_at ?? now(),
                'date_fin'   => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prix_produits_historique');
    }
};