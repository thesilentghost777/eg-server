<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manquants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendeur_id')->constrained('users')->onDelete('cascade');
            $table->date('date_manquant');
            $table->decimal('total_ventes', 10, 2)->default(0);    // Valeur attendue calculée (flux opérationnel)
            $table->decimal('fond_caisse', 10, 2)->default(0);     // Fond de caisse
            $table->decimal('versement_1', 10, 2)->default(0);
            $table->decimal('versement_2', 10, 2)->default(0);
            $table->decimal('versement_3', 10, 2)->default(0);
            $table->decimal('versement_extra', 10, 2)->default(0);
            $table->decimal('om_final', 10, 2)->default(0);        // Orange Money final
            $table->decimal('momo_final', 10, 2)->default(0);      // MTN MoMo final
            $table->decimal('total_verse', 10, 2)->default(0);     // Somme de tous les versements
            $table->decimal('montant_manquant', 10, 2)->default(0);// Positif = manquant, Négatif = excédent
            $table->text('notes')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->onDelete('set null'); // PDG qui a validé
            $table->timestamps();

            // Un seul manquant validé par vendeur par jour
            $table->unique(['vendeur_id', 'date_manquant']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manquants');
    }
};