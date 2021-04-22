<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\MembresiaDocente;
use App\Curso;
use Carbon\Carbon;

class CurrentMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'current:memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finalizar membresias vencidas';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $membresia = MembresiaDocente::orderBy('id_membresia_usuario', 'desc')
                                        ->where('estado_membresia_usuario', 'adquirido')
                                        ->get();
            $time1 = Carbon::now();
            $time1 = $time1->format('Y-m-d');
            foreach( $membresia as $docenteMembresia ) {
                if($time1 >= $docenteMembresia->fin_membresia_usuario) {
                    $docenteMembresia->estado_membresia_usuario = 'finalizado';
                    $curso=Curso::where('id_usuario', $docenteMembresia->id_usuario)
                                ->where('membresia_curso', 'INICIO')
                                ->update(['membresia_curso' => 'FIN']);
                    $docenteMembresia->save();
                    return response()->json(['mensaje' => ' membresia finalizada']);
                }else {
                    return response()->json(['mensaje' => 'la membresia no puede finalizar debido a que no es la fecha indicada de finalización']);
                }
              }
    }
}
