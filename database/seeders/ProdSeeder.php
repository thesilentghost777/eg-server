<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProdSeeder extends Seeder
{
    /**
     * Exécuter le seeder.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'BiG BoSS',
                'numero_telephone' => '657929578',
                'role' => 'pdg',
                'code_pin' => Hash::make('123456'),
                'actif' => true,
                'preferred_language' => 'fr',
                'synced_clients' => json_encode([]),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Samba Tankru',
                'numero_telephone' => '633445566',
                'role' => 'producteur',
                'code_pin' => Hash::make('4321'),
                'actif' => true,
                'preferred_language' => 'fr',
                'synced_clients' => json_encode([]),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
