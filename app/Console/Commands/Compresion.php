<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class Compresion extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'execute:compresion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para guardar las transacciones de las tiendas de good y mercardo y gurdarlas en la tabla transactions del backoffice';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        DB::select('SELECT fcompresion()');
    }

}
