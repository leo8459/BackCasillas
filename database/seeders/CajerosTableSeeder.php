<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CajerosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cajeros')->insert([
            [
                'nombre' => 'Gisela Mendez',
                'carnet' => '5174748',
                'email' => 'gis@gmail.com',
                'password' => '$2y$10$PSMjxSWP5yhwaERKupcMeemVtZcoF3WWii1fjp2P9aLVUL22MFNf.', // Contraseña hasheada
                'estado' => 1,
                'created_at' => '2023-11-14 10:36:24',
                'updated_at' => '2023-11-14 10:36:24',
            ],
            [
                'nombre' => 'Pablo Quenallata',
                'carnet' => '95748741',
                'email' => 'pablo@gmail.com',
                'password' => '$2y$10$6dGSBPegANFn8qHGE9pLMuJOzh5SlxeEHHmLiInRU8GePAC89QA4W', // Contraseña hasheada
                'estado' => 1,
                'created_at' => '2023-11-14 09:55:16',
                'updated_at' => '2023-12-17 00:27:08',
            ],
        ]);
    }
}
