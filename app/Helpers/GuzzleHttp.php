<?php
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 26/12/17
 * Time: 09:46 AM
 */

namespace App\Helpers;


use GuzzleHttp\Exception\ClientException;
class GuzzleHttp
{
    public static function client()
    {
        return  new \GuzzleHttp\Client();
    }

    public static function url_test()
    {
        //return 'https://'. env('API_KEY_TEST') . ':' . env('API_PASSWORD_TEST') . '@' . env('API_SHOP_TEST');
        return 'https://c17edef9514920c1d2a6aeaf9066b150:afc86df7e11dcbe0ab414fa158ac1767@mall-hello.myshopify.com';  // api hello
    }

//usuarios
    public static function api_usuarios($api, $email, $data, $metodo) {


        if ($api == 'good') {
      	    $api = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        }

        if($api == 'mercando'){
      	    $api = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');
        }

        $client = self::client();

        $id = '';

        try {
      
            if($metodo == 'actualizar'){

                $good = $client->request('GET', $api . '/admin/customers/search.json?query=email:'.$email);
                $headers = $good->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
                $x = explode('/', $headers[0]);
                $diferencia = $x[1] - $x[0];
                if ($diferencia < 20) {
                    usleep(10000000);
                }
 
               $results = json_decode($good->getBody(), true);

                if (count($results['customers']) > 0) {                 
                    $id = '/'.$results['customers'][0]['id'];
                    self::cambio_usario($api, $id, $data, 'put');
                }
                else{
                    self::cambio_usario($api, $id, $data, 'post');
                }                   
            }
            if($metodo == 'ingresar'){ 
                self::cambio_usario($api, $id, $data, 'post');
            }

        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return redirect()->back()->with(['err' => 'Se actualizó su email en el backoffice pero el usuario no existe en la tienda']);
            }
        }
    }

    public static function cambio_usario($api, $id, $data, $metodo){
        try {
             
            $client = self::client();

            $res = $client->request($metodo, $api . '/admin/customers' . $id . '.json', array(
                'form_params' => array(
                       'customer' => $data
                    )
                )
            );

            return $results = json_decode($res->getBody(), true);

            $headers = $res->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];
            if ($diferencia < 20) {
                usleep(10000000);
            }
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                return redirect()->back()->with(['err' => 'Se actualizó su email en el backoffice pero el usuario no existe en tienda']);
            }
        }
    }

}