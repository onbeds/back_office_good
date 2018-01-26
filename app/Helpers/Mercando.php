<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 26/12/17
 * Time: 09:33 AM
 */

namespace App\Helpers;

use App\Helpers\GuzzleHttp;


class Mercando
{
    public static function url()
    {
        return 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
    }

    public static function exist($email)
    {
        $url = self::url();
        $client = GuzzleHttp::client();

        $response = $client->request('GET',  $url . '/admin/customers/search.json?query=email:' . $email);
        $headers = $response->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
        $x = explode('/', $headers[0]);
        $diferencia = $x[1] - $x[0];

        if ($diferencia < 20) {

            usleep(20000000);
        }

        $results = json_decode($response->getBody(), true);

        if (count($results['customers']) == 1) {

            return $results['customers'][0]['id'];
        }

        return 0;
    }
}