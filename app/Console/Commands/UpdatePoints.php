<?php

namespace App\Console\Commands;

use App\Variant;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\ClientException;


class UpdatePoints extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:update-points';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para actualizar puntos de variantes en good';

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
        $client = new \GuzzleHttp\Client();

        $variants = Variant::where('shop', 'good')->get();

        foreach ($variants as $variant) {

            try {

                $res = $client->request('get', $api_url_good . '/admin/variants/'. $variant->id .'/metafields.json');

                $results = json_decode($res->getBody(), true);

                if (count($results['metafields']) > 0) {

                    foreach ($results['metafields'] as $result) {

                        if ($result['key'] == 'points' && $result['namespace'] == 'variants') {

                            try {

                                $res = $client->request('put', $api_url_good . '/admin/variants/'. $variant->id .'/metafields/' . $result['id'] . '.json', array(
                                        'form_params' => array(
                                            'metafield' => array(
                                                'namespace' => 'variants',
                                                'key' => 'points',
                                                'value' => $variant->percentage,
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

                            } catch (ClientException $e) {

                                if ($e->getResponse()) {
                                    continue;
                                }
                            }
                        }
                    }

                } else {

                    try {

                        $res = $client->request('post', $api_url_good . '/admin/variants/'. $variant->id .'/metafields.json', array(
                            'form_params' => array(
                                'metafield' => array(
                                    'namespace' => 'variants',
                                    'key' => 'points',
                                    'value' => $variant->percentage,
                                    'value_type' => 'integer'
                                )
                            )
                        ));

                        $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                        $x = explode('/', $headers[0]);
                        $diferencia = $x[1] - $x[0];
                        if ($diferencia < 20) {

                            usleep(10000000);
                        }

                    } catch (ClientException $e) {

                        if ($e->getResponse()) {
                            continue;
                        }
                    }
                }

            } catch (ClientException $e) {

                if ($e->getResponse()) {
                    continue;
                }
            }
        }
    }
}
