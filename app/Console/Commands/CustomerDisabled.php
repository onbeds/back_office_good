<?php

namespace App\Console\Commands;

use Faker;
use App\Entities\Tercero;
use App\Helpers\GuzzleHttp;
use Illuminate\Console\Command;

class CustomerDisabled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:disabled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para desabilitar customer en tienda good y en mercando';

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
        $faker = Faker\Factory::create();

        $terceros = Tercero::where('state', false)->get();

        foreach ($terceros as $tercero) {

            $password = $faker->uuid;

            $data = array(
                "password" => $password,
                "password_confirmation" => $password
            );


            $this->info('Password: ' . $password);
            $this->info('Desavilitando a usuario con email ' . $tercero->email);

            GuzzleHttp::api_usuarios('good', $tercero->email, $data, 'actualizar');
            GuzzleHttp::api_usuarios('mercando', $tercero->email, $data, 'actualizar');

        }

        $this->info('Los clientes han sido desavilitados');
    }
}
