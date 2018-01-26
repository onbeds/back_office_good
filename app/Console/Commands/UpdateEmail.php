<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class UpdateEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:update-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return mixed
     */
    public function handle()
    {
        $terceros = DB::select('select id, email, usuario from terceros WHERE email <> usuario');

        if (count($terceros) > 0) {

            foreach ($terceros as $tercero) {

            }

        }

    }
}
