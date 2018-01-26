<?php

namespace App\Console\Commands;

use App\Entities\Tercero;
use DB;
use Auth;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Console\Command;

use Carbon\Carbon;
use App\Entities\Liquidaciones;
use App\Order;
use App\Entities\LiquidacionDetalle;

class GetUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para migrar todos los usuarios de good a mercando';

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
        $api_url_good = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $api_url_mercando = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
        $client = new \GuzzleHttp\Client();

        $terceros = DB::table('terceros')->whereNotIn('id', function($q){
            $q->select('tercero_id')->from('terceros_tiendas');
        })->get();

        foreach ($terceros as $tercero) {

            $res_good = $client->request('GET',  $api_url_good . '/admin/customers/search.json?query=email:' . $tercero->email);
            $headers = $res_good->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];

            if ($diferencia < 20) {

                usleep(20000000);
            }

            $results_good = json_decode($res_good->getBody(), true);

            if (count($results_good['customers']) == 1) {

                $this->info('El usuario existe en good');

                $res_mercando = $client->request('GET',  $api_url_mercando . '/admin/customers/search.json?query=email:' . $tercero->email);

                $headers =  $res_mercando->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                $x = explode('/', $headers[0]);
                $diferencia = $x[1] - $x[0];

                if ($diferencia < 20) {

                    usleep(20000000);
                }

                $results_mercando = json_decode($res_mercando->getBody(), true);

                if (count($results_mercando['customers']) == 1) {

                    $this->info('El usuario existe en mercando');

                    $a = DB::table('terceros_tiendas')
                        ->where('tercero_id', $tercero->id)
                        ->where('customer_id_good', $results_good['customers'][0]['id'])
                        ->where('customer_id_mercando', $results_mercando['customers'][0]['id'])
                        ->first();

                    if (count($a) == 0) {

                        DB::table('terceros_tiendas')->insertGetId(
                            [
                                'tercero_id' => $tercero->id,
                                'customer_id_good' =>  $results_good['customers'][0]['id'],
                                'customer_id_mercando' => $results_mercando['customers'][0]['id'],
                            ]
                        );
                    }

                    try {
                        $res = $client->request('put', $api_url_mercando . '/admin/customers/'. $results_mercando['customers'][0]['id'] .'.json', array(
                                'form_params' => array(
                                    'customer' => array(
                                        "email" => $tercero->email,
                                    )
                                )
                            )
                        );

                        $headers =  $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];
                        if ($diferencia < 20) {
                            usleep(10000000);
                        }

                    } catch (ClientException $e) {

                        if ($e->hasResponse()) {

                            $this->info('Problemas al actualizar el email del usuario en good');
                        }
                    }

                }

                if (count($results_mercando['customers']) == 0) {

                    $this->info('El usuario no existe en mercando, se creará.');

                    try {

                        $res = $client->request('post', $api_url_mercando . '/admin/customers.json', array(

                                'form_params' => array(
                                    'customer' => array(
                                        'first_name' => strtolower( $results_good['customers'][0]['first_name']),
                                        'last_name' => strtolower( $results_good['customers'][0]['last_name']),
                                        'email' => strtolower($results_good['customers'][0]['email']),
                                        'verified_email' => true,
                                        'phone' =>  $results_good['customers'][0]['phone'],
                                        'addresses' => [
                                            $results_good['customers'][0]['addresses'],
                                        ],
                                        "password" => $tercero->identificacion,
                                        "password_confirmation" => $tercero->identificacion,
                                        'send_email_invite' => false,
                                        'send_email_welcome' => false
                                    )
                                )
                            )
                        );

                        $headers =  $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];

                        if ($diferencia < 20) {

                            usleep(20000000);
                        }

                        $customer = json_decode($res->getBody(), true);

                        $b = DB::table('terceros_tiendas')
                            ->where('tercero_id', $tercero->id)
                            ->where('customer_id_good', $results_good['customers'][0]['id'])
                            ->where('customer_id_mercando', $customer['customer']['id'])
                            ->first();

                        if (count($b) == 0) {
                            DB::table('terceros_tiendas')->insertGetId(
                                [
                                    'tercero_id' => $tercero->id,
                                    'customer_id_good' =>  $results_good['customers'][0]['id'],
                                    'customer_id_mercando' =>  $customer['customer']['id'],
                                ]
                            );
                        }

                    } catch (ClientException $e) {

                        if ($e->hasResponse()) {

                            $err = json_decode(($e->getResponse()->getBody()), true);

                            foreach ($err['errors'] as $key => $value) {

                                $this->info('Problemas al crear el usuario en mercando' .  $key . ' ' . $value[0]);

                            }
                        }
                    }
                }
            }

            if (count($results_good['customers']) == 0) {

                $this->info('El usuario no existe en good');

                $find = Tercero::with('ciudad')->find($tercero->id);

                try {

                    $resa = $client->request('post', $api_url_good . '/admin/customers.json', array(
                            'form_params' => array(
                                'customer' => array(
                                    'first_name' => strtolower($find->nombres),
                                    'last_name' => strtolower($find->apellidos),
                                    'email' => strtolower($find->email),
                                    'verified_email' => true,
                                    'phone' => $find->telefono,
                                    'addresses' => [

                                        [
                                            'address1' => strtolower($find->direccion),
                                            'city' => strtolower($find->ciudad->nombre),
                                            'province' => '',
                                            "zip" => '',
                                            'first_name' => strtolower($find->nombres),
                                            'last_name' => strtolower($find->apellidos),
                                            'country' => 'CO'
                                        ],

                                    ],
                                    "password" => $find->identificacion,
                                    "password_confirmation" =>  $find->identificacion,
                                    'send_email_invite' => false,
                                    'send_email_welcome' => false
                                )
                            )
                        )
                    );

                    $results_good = json_decode($resa->getBody(), true);

                    $res_mercando = $client->request('GET',  $api_url_mercando . '/admin/customers/search.json?query=email:' . $tercero->email);

                    $headers =  $res_mercando->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                    $x = explode('/', $headers[0]);
                    $diferencia = $x[1] - $x[0];

                    if ($diferencia < 20) {

                        usleep(20000000);
                    }

                    $results_mercando = json_decode($res_mercando->getBody(), true);

                    if (count($results_mercando['customers']) == 1) {

                        $this->info('El usuario existe en mercando');

                        $a = DB::table('terceros_tiendas')
                            ->where('tercero_id', $tercero->id)
                            ->where('customer_id_good', $results_good['customers'][0]['id'])
                            ->where('customer_id_mercando', $results_mercando['customers'][0]['id'])
                            ->first();

                        if (count($a) == 0) {

                            DB::table('terceros_tiendas')->insertGetId(
                                [
                                    'tercero_id' => $tercero->id,
                                    'customer_id_good' =>  $results_good['customers'][0]['id'],
                                    'customer_id_mercando' => $results_mercando['customers'][0]['id'],
                                ]
                            );
                        }

                        try {
                            $res = $client->request('put', $api_url_mercando . '/admin/customers/'. $results_mercando['customers'][0]['id'] .'.json', array(
                                    'form_params' => array(
                                        'customer' => array(
                                            "email" => $tercero->email,
                                        )
                                    )
                                )
                            );

                            $headers =  $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                            $x = explode('/', $headers[0]);
                            $diferencia = $x[1] - $x[0];
                            if ($diferencia < 20) {
                                usleep(10000000);
                            }

                        } catch (ClientException $e) {

                            if ($e->hasResponse()) {

                                $this->info('Problemas al actualizar el email del usuario en good');
                            }
                        }

                    }

                    if (count($results_mercando['customers']) == 0) {

                        $this->info('El usuario no existe en mercando, se creará.');

                        try {

                            $res = $client->request('post', $api_url_mercando . '/admin/customers.json', array(

                                    'form_params' => array(
                                        'customer' => array(
                                            'first_name' => strtolower( $results_good['customers'][0]['first_name']),
                                            'last_name' => strtolower( $results_good['customers'][0]['last_name']),
                                            'email' => strtolower($results_good['customers'][0]['email']),
                                            'verified_email' => true,
                                            'phone' =>  $results_good['customers'][0]['phone'],
                                            'addresses' => [
                                                $results_good['customers'][0]['addresses'],
                                            ],
                                            "password" => $tercero->identificacion,
                                            "password_confirmation" => $tercero->identificacion,
                                            'send_email_invite' => false,
                                            'send_email_welcome' => false
                                        )
                                    )
                                )
                            );

                            $headers =  $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                            $x = explode('/', $headers[0]);
                            $diferencia = $x[1] - $x[0];

                            if ($diferencia < 20) {

                                usleep(20000000);
                            }

                            $customer = json_decode($res->getBody(), true);

                            $b = DB::table('terceros_tiendas')
                                ->where('tercero_id', $tercero->id)
                                ->where('customer_id_good', $results_good['customers'][0]['id'])
                                ->where('customer_id_mercando', $customer['customer']['id'])
                                ->first();

                            if (count($b) == 0) {
                                DB::table('terceros_tiendas')->insertGetId(
                                    [
                                        'tercero_id' => $tercero->id,
                                        'customer_id_good' =>  $results_good['customers'][0]['id'],
                                        'customer_id_mercando' =>  $customer['customer']['id'],
                                    ]
                                );
                            }

                        } catch (ClientException $e) {

                            if ($e->hasResponse()) {

                                $err = json_decode(($e->getResponse()->getBody()), true);

                                foreach ($err['errors'] as $key => $value) {

                                    $this->info('Problemas al crear el usuario en mercando' .  $key . ' ' . $value[0]);

                                }
                            }
                        }
                    }


                } catch (ClientException $e) {

                    $err = json_decode(($e->getResponse()->getBody()), true);

                    foreach ($err['errors'] as $key => $value) {

                        echo $key . ' ' . $value[0] . "\n";
                    }
                }
            }
        }
        $this->info('Los usuarios han sido migrados por completo.');

    }
}
