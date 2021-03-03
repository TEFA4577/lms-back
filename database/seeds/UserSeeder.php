<?php

use Illuminate\Database\Seeder;
use App\Usuario;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Usuarios = [
            [
                'id_rol' => 1,
                'nombre_usuario' => 'admin',
                'correo_usuario' => 'admin@academia.com',
                'estado_usuario' => 'activo',
                'password_usuario' => bcrypt('password'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 2,
                'nombre_usuario' => 'docente',
                'correo_usuario' => 'docente@academia.com',
                'estado_usuario' => 'activo',
                'password_usuario' => bcrypt('password'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id_rol' => 3,
                'nombre_usuario' => 'estudiante',
                'correo_usuario' => 'estudiante@academia.com',
                'estado_usuario' => 'activo',
                'password_usuario' => bcrypt('password'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        Usuario::insert($Usuarios);
    }
}
