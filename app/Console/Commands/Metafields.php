<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Customer;
use App\Entities\Tercero;

use DB;


class Metafields extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:metafields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de metadatos a shopify';

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

        $terceros = Tercero::all();
        $results = array();

        foreach ($terceros as $tercero) {

            $ganacia = $tercero->total_price_orders * 0.05;

            if ($ganacia >= 1000) {

                $find = Customer::where('customer_id', $tercero->customer_id)->first();

                if (count($find) > 0) {

                    $update = Tercero::find($tercero->id);
                    $update->ganacias = $update->total_price_orders * 0.05;
                    $update->save();

                    $res = $client->request('get', $api_url . '/admin/customers/' . $update->customer_id . '/metafields.json');
                    $metafields = json_decode($res->getBody(), true);
                    $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                    $x = explode('/', $headers[0]);
                    $diferencia = $x[1] - $x[0];
                    if ($diferencia < 10) {
                        usleep(10000000);
                    }

                    if (isset($metafields['metafields']) && count($metafields['metafields']) == 0) {

                        $resd = $client->request('post', $api_url . '/admin/customers/' . $update->customer_id . '/metafields.json', array(
                            'form_params' => array(
                                'metafield' => array(
                                    'namespace' => 'customers',
                                    'key' => 'referidos',
                                    'value' => ($update->numero_referidos == null) ? 0 : $update->numero_referidos,
                                    'value_type' => 'integer'
                                )
                            )
                        ));

                        $headers = $resd->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];
                        if ($diferencia < 10) {
                            usleep(10000000);
                        }

                        array_push($results, json_decode($resd->getBody(), true));

                        $rese = $client->request('post', $api_url . '/admin/customers/' . $update->customer_id . '/metafields.json', array(
                            'form_params' => array(
                                'metafield' => array(
                                    'namespace' => 'customers',
                                    'key' => 'compras',
                                    'value' => ($update->numero_ordenes_referidos == null) ? 0 : $update->numero_ordenes_referidos,
                                    'value_type' => 'integer'
                                )
                            )
                        ));

                        $headers = $rese->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];
                        if ($diferencia < 10) {
                            usleep(10000000);
                        }

                        array_push($results, json_decode($rese->getBody(), true));

                        $resf = $client->request('post', $api_url . '/admin/customers/' . $update->customer_id . '/metafields.json', array(
                            'form_params' => array(
                                'metafield' => array(
                                    'namespace' => 'customers',
                                    'key' => 'valor',
                                    'value' => '' . ($update->ganacias == null ) ? 0 : number_format($update->ganacias) . '',
                                    'value_type' => 'string'
                                )
                            )
                        ));

                        $headers = $resf->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];
                        if ($diferencia < 10) {
                            usleep(10000000);
                        }

                        array_push($results, json_decode($resf->getBody(), true));

                        $resg = $client->request('post', $api_url . '/admin/customers/' . $tercero->customer_id . '/metafields.json', array(
                            'form_params' => array(
                                'metafield' => array(
                                    'namespace' => 'customers',
                                    'key' => 'redimir',
                                    'value' => '' . ($update->redimido == null ) ? 0 : number_format($update->redimido) . '',
                                    'value_type' => 'string'
                                )
                            )
                        ));

                        $headers = $resg->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];
                        if ($diferencia < 10) {
                            usleep(10000000);
                        }

                        array_push($results, json_decode($resg->getBody(), true));
                    }

                    if (isset($metafields['metafields']) && count($metafields['metafields']) > 0) {

                        foreach ($metafields['metafields'] as $metafield) {

                            if ($metafield['key'] === 'referidos') {
                                $res = $client->request('put', $api_url . '/admin/customers/' . $update->customer_id . '/metafields/' . $metafield['id'] . '.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'customers',
                                                'key' => 'referidos',
                                                'value' => ($update->numero_referidos == null || $update->numero_referidos == 0) ? 0 : $update->numero_referidos,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    )
                                );
                                $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                $x = explode('/', $headers[0]);
                                $diferencia = $x[1] - $x[0];
                                if ($diferencia < 10) {
                                    usleep(10000000);
                                }

                                array_push($results, json_decode($res->getBody(), true));
                            }

                            if ($metafield['key'] === 'compras') {
                                $res = $client->request('put', $api_url . '/admin/customers/' . $update->customer_id . '/metafields/' . $metafield['id'] . '.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'customers',
                                                'key' => 'compras',
                                                'value' => ($update->numero_ordenes_referidos == null || $update->numero_ordenes_referidos == 0) ? 0 : $update->numero_ordenes_referidos,
                                                'value_type' => 'integer'
                                            )
                                        )
                                    )
                                );
                                $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                $x = explode('/', $headers[0]);
                                $diferencia = $x[1] - $x[0];
                                if ($diferencia < 10) {
                                    usleep(10000000);
                                }

                                array_push($results, json_decode($res->getBody(), true));
                            }

                            if ($metafield['key'] === 'valor') {
                                $res = $client->request('put', $api_url . '/admin/customers/' . $update->customer_id . '/metafields/' . $metafield['id'] . '.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'customers',
                                                'key' => 'valor',
                                                'value' => '' . ( $update->ganacias == null || $update->ganacias == 0) ? 0 : number_format($update->ganacias) . '',
                                                'value_type' => 'string'
                                            )
                                        )
                                    )
                                );
                                $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                                $x = explode('/', $headers[0]);
                                $diferencia = $x[1] - $x[0];
                                if ($diferencia < 10) {
                                    usleep(10000000);
                                }

                                array_push($results, json_decode($res->getBody(), true));
                            }
                        }
                    }
                }
            }
        }

        $this->info('metafields enviados correctamente');
    }
}
