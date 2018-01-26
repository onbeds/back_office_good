<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Validator;
use App\Customer;
use App\Entities\Network;
use Carbon\Carbon;
use App\Entities\Tercero;
use DB;
use Faker\Factory as Faker;

class GetCustomers extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para obtener todos los clientes de la API shopify';

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
        $api_url = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $client = new \GuzzleHttp\Client();
        $resa = $client->request('GET', $api_url . '/admin/customers/count.json');
        $countCustomers = json_decode($resa->getBody(), true);
        $this->info('Customers: ' . $countCustomers['count']);

        $result = true;
        $h = 1;

        do {
            $this->info('Entrando al do');

            $res = $client->request('GET', $api_url . '/admin/customers.json?limit=250&&page=' . $h);

            $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];
            if ($diferencia < 20) {

                usleep(10000000);

            }

            $results = json_decode($res->getBody(), true);

            foreach ($results['customers'] as $customer) {

                $this->info('Entrando al for');

                $tercero = Tercero::where('email', strtolower($customer['email']))->first();

                $faker = Faker::create();

                $response = Customer::where('network_id', 1)
                    ->where('customer_id', $customer['id'])
                    ->get();

                if(count($response) == 0) {

                    $this->info('Customer no existe');

                    if (count($tercero) == 0) {

                        $usuario = new Tercero();
                        $usuario->nombres = strtolower($customer['first_name']);
                        $usuario->apellidos = strtolower($customer['last_name']);
                        $usuario->direccion = strtolower('dfsdfdfds');
                        $usuario->telefono = strtolower($customer['phone']);
                        $usuario->email = strtolower($customer['email']);
                        $usuario->usuario = strtolower($customer['email']);
                        $usuario->contraseña = bcrypt('secret');
                        $usuario->tipo_id = null;
                        $usuario->ciudad_id = random_int(1, 1000);
                        $usuario->celular = $customer['phone'];
                        $usuario->network_id = 1;
                        $usuario->tipo_cliente_id = null;
                        $usuario->documento_id = 77;
                        $usuario->identificacion = 0000000;
                        $usuario->sexo = 'M';
                        $usuario->fecha_nacimiento = $faker->year(2000);
                        $usuario->customer_id = $customer['id'];
                        $usuario->save();
                    }
                }
            }

            $h++;

            if (count($results['customers']) < 1) {
                $result = false;
            }

            $this->info('Saliendo del do');

        } while($result);

        $this->info('Los clientes han sido descargados correctamente');
    }
}
