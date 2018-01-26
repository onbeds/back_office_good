<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 26/12/17
 * Time: 09:22 AM
 */

namespace App\Helpers;

use Carbon\Carbon;
use App\Helpers\GuzzleHttp;
use GuzzleHttp\Exception\ClientException;


class GiftCard
{
    public static function gift($url, $value, $id)
    {
        $client = GuzzleHttp::client();
        $send = [
            'form_params' => [
                'gift_card' => [
                    "note" => "This is a note",
                    "initial_value" => $value,
                    "template_suffix" => "gift_cards.birthday.liquid",
                    "currency" => "COP",
                    "customer_id" => $id,
                    "expires_on" => Carbon::now()->addMonth()
                ]
            ]
        ];

        try {

            $response = $client->request('post', $url . '/admin/gift_cards.json', $send);

            $headers = $response->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];

            if ($diferencia < 10) {
                usleep(500000);
                
            }

            $result = json_decode($response->getBody(), true);

            return $result['gift_card'];

        } catch (ClientException $e) {

            if ($e->hasResponse()) {

                return $e->getResponse()->getBody();

            }
        }
    }

    public static function test($url, $id)
    {
        $client = GuzzleHttp::client();

        try {

            $response = $client->request('get', $url . '/admin/customers/'. $id .'.json');

            $headers = $response->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];

            if ($diferencia < 10) {
                usleep(500000);
            }

            $result = json_decode($response->getBody(), true);

            return $result;

        } catch (ClientException $e) {

            if ($e->hasResponse()) {

                return null;
            }
        }
    }
}