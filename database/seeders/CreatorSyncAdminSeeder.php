<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class CreatorSyncAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::create([
            'nombre' => 'CreatorSyncAdmin',
            'email' => 'admin@creatorsync.com',
            'password' => Hash::make('Alex_1234'), 
            'rol' => 'admin',
            'avatar' => null,
            'descripcion' => 'Admin de CreatorSync',
            'estado' => 'activo',
        ]);
    }
}