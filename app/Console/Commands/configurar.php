<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class configurar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'configurar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'comando de configuracion inicial para el proyecto';

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

        $migracion = shell_exec('php artisan migrate');
        $this->info($migracion);
        $migracion = shell_exec('php artisan migrate:refresh --seed');
        $this->info($migracion);
        $pass = shell_exec('php artisan passport:install');
        $this->info($pass);
        $personal = shell_exec('php artisan passport:client --personal --name=LMS-BACKEND');
        $this->info($personal);
        $storage = shell_exec('php artisan storage:link');
        $this->info($storage);
        return true;
    }
}
