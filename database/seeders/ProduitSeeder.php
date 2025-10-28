<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProduitSeeder extends Seeder
{
    /**
     * Exécuter le seeder.
     */
    public function run(): void
    {
        $produits = [
            ['nom' => 'Croissant au beurre', 'prix' => 300, 'categorie' => 'patisserie'],
            ['nom' => 'Pain au chocolat', 'prix' => 400, 'categorie' => 'patisserie'],
            ['nom' => 'Chausson aux pommes', 'prix' => 350, 'categorie' => 'patisserie'],
            ['nom' => 'Donut sucré', 'prix' => 250, 'categorie' => 'patisserie'],
            ['nom' => 'Éclair au chocolat', 'prix' => 500, 'categorie' => 'patisserie'],
            ['nom' => 'Beignet sucré camerounais', 'prix' => 100, 'categorie' => 'patisserie'],
            ['nom' => 'Tarte aux fruits', 'prix' => 600, 'categorie' => 'patisserie'],
            ['nom' => 'Gâteau yaourt', 'prix' => 700, 'categorie' => 'patisserie'],
            ['nom' => 'Madeleine', 'prix' => 200, 'categorie' => 'patisserie'],
            ['nom' => 'Brioche sucrée', 'prix' => 400, 'categorie' => 'patisserie'],
        ];

        foreach ($produits as $produit) {
            DB::table('produits')->insert([
                'nom' => $produit['nom'],
                'prix' => $produit['prix'],
                'categorie' => $produit['categorie'],
                'actif' => true,
                'synced_clients' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
