<?php

use Illuminate\Database\Seeder;
use App\RecursoTipo;
class RecursosTiposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $Tipos = [
            [
                'nombre_recurso_tipo' => 'Documento PDF',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nombre_recurso_tipo' => 'Video',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nombre_recurso_tipo' => 'Presentacion',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        RecursoTipo::insert($Tipos);
    }
}
