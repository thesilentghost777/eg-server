<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        //Table client
        Schema::create('clients', function (Blueprint $table) {
            $table->uuid('client_id')->primary();
            $table->string('device_info')->nullable(); // Description de l'appareil
            $table->timestamps();
        });

        // Table des produits
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('prix', 10, 2);
            $table->enum('categorie', ['boulangerie', 'patisserie']);
            $table->boolean('actif')->default(true);
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des vendeurs actifs
        Schema::create('vendeurs_actifs', function (Blueprint $table) {
            $table->id();
            $table->enum('categorie', ['boulangerie', 'patisserie'])->unique();
            $table->foreignId('vendeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('connecte_a')->nullable();
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des réceptions
        Schema::create('receptions_pointeur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pointeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('producteur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->foreignId('vendeur_assigne_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('verrou')->default(false);
            $table->timestamp('date_reception');
            $table->text('notes')->nullable();
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des retours
        Schema::create('retours_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pointeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendeur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->enum('raison', ['perime', 'abime', 'autre']);
            $table->text('description')->nullable();
            $table->boolean('verrou')->default(false);
            $table->timestamp('date_retour');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des inventaires
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_sortant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendeur_entrant_id')->constrained('users')->onDelete('cascade');
            $table->enum('categorie', ['boulangerie', 'patisserie']);
            $table->boolean('valide_sortant')->default(false);
            $table->boolean('valide_entrant')->default(false);
            $table->timestamp('date_inventaire');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des détails d'inventaire
        Schema::create('inventaire_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventaire_id')->constrained('inventaires')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite_restante');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des sessions de vente
        Schema::create('sessions_vente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_id')->constrained('users')->onDelete('cascade');
            $table->enum('categorie', ['boulangerie', 'patisserie']);
            $table->decimal('fond_vente', 10, 2)->default(0);
            $table->decimal('orange_money_initial', 10, 2)->default(0);
            $table->decimal('mtn_money_initial', 10, 2)->default(0);
            $table->decimal('montant_verse', 10, 2)->nullable();
            $table->decimal('orange_money_final', 10, 2)->nullable();
            $table->decimal('mtn_money_final', 10, 2)->nullable();
            $table->decimal('manquant', 10, 2)->nullable();
            $table->enum('statut', ['ouverte', 'fermee'])->default('ouverte');
            $table->foreignId('fermee_par')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_ouverture');
            $table->timestamp('date_fermeture')->nullable();
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table des ventes
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_vente_id')->constrained('sessions_vente')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 10, 2);
            $table->enum('mode_paiement', ['cash', 'orange_money', 'mtn_money'])->default('cash');
            $table->timestamp('date_vente');
            $table->json('synced_clients')->nullable();
            $table->timestamps();
        });

        // Table de configuration PDG
        Schema::create('config_pdg', function (Blueprint $table) {
            $table->id();
            $table->string('code_inscription_pdg');
            $table->timestamps();
        });

        DB::table('config_pdg')->insert([
            'code_inscription_pdg' => 'PDG2025SECURE',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('config_pdg');
        Schema::dropIfExists('ventes');
        Schema::dropIfExists('sessions_vente');
        Schema::dropIfExists('inventaire_details');
        Schema::dropIfExists('inventaires');
        Schema::dropIfExists('retours_produits');
        Schema::dropIfExists('receptions_pointeur');
        Schema::dropIfExists('vendeurs_actifs');
        Schema::dropIfExists('produits');
    }
};
