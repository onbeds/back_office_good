<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Entities\Tercero;
use Illuminate\Http\Request;
use Mail;
use Hash;
use Session;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
//use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller {

    /**
     * |--------------------------------------------------------------------------
     * | Password Reset Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller is responsible for handling password reset requests
     * | and uses a simple trait to include this behavior. You're free to
     * | explore this trait and override any methods you wish to tweak.
     * |
     */

   // use ResetsPasswords;
  //  protected $redirectTo = '/dashboard';

    /**
     * Create a new password controller instance.
     *
     * @return void
     */


    public function getEmail() {
        return view('auth.password', compact('nivel'));
    }

    public function postEmail(Request $request) {
        $email = Tercero::where('email', strtolower($request->email))
            ->where('state', true)
            ->first();

        if($email){  
           $token = str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789".uniqid());

            $usuario = Tercero::findOrFail($email['id']);
            $usuario->remember_token = $token;
            $usuario->save();
       
            Mail::send('auth.reset_password', ['token' => $token], function($message) use ($request) {
                $message->from('info@tiendagood.com', 'Recupera tu contraseña');
                $message->subject('Recupera tu contraseña');
                $message->to($request->email);
            }); 

            Session::flash('flash_msg', 'Revise su correo para saber como puede cambiar su contrase\u00f1a');
             return redirect()->action('Auth\PasswordController@getEmail');
        } else {
            Session::flash('flash_msg', 'Su usuario esta inactivo, por favor vuelvase a registrar con su codigo de referido.');
            return redirect()->back();
        }
      // return view('auth.password', compact('nivel'));
    }

    public function getReset($token) {
       $remember_token = Tercero::where('remember_token', $token)->first();
        if($remember_token != ''){ 
            $email = $remember_token['email'];
            $id = $remember_token['id'];
          return view('auth.reset', compact('token', 'email', 'id'));
        }
        else{
             return view('auth.login', compact('nivel'));
        }
    }

    public function postReset(Request $request) {

        $api_url_good = 'https://'. env('API_KEY_SHOPIFY') . ':' . env('API_PASSWORD_SHOPIFY') . '@' . env('API_SHOP');
        $api_url_mercando = 'https://'. env('API_KEY_MERCANDO') . ':' . env('API_PASSWORD_MERCANDO') . '@' . env('API_SHOP_MERCANDO');

        $remember_token = Tercero::where('remember_token', $request->token)->first();

        if($remember_token != ''){

            $email = $remember_token['email'];
            $id = $remember_token['id'];

            $usuario = Tercero::findOrFail($remember_token['id']);
            $usuario->contraseña = bcrypt($request->password);
            //$usuario->remember_token = '';
            $usuario->save();

            if($usuario) {

                $this->api_cambio_password($api_url_good, strtolower($email), $request->password, $remember_token);
                $this->api_cambio_password($api_url_mercando, strtolower($email), $request->password, $remember_token);

            }

            //cambio de clave
            Session::flash('flash_msg', 'El cambio de contrase\u00f1a se realizo correctamente');
            return redirect()->action('Auth\AuthController@getLogin');
        }
        else{
             return view('auth.login', compact('nivel'));
        }
    }

    public function api_cambio_password($api, $email, $password, $datos) {

        $client = new \GuzzleHttp\Client();

        try {

            $good = $client->request('GET', $api . '/admin/customers/search.json?query=email:'. $email );
            $headers = $good->getHeaders()['X-Shopify-Shop-Api-Call-Limit'];
            $x = explode('/', $headers[0]);
            $diferencia = $x[1] - $x[0];
            if ($diferencia < 20) {
                usleep(10000000);
            }

            $results = json_decode($good->getBody(), true);

            if(count($results['customers']) == 1) {

                try {
                    $res = $client->request('put', $api . '/admin/customers/'. $results['customers'][0]['id'] .'.json', array(
                            'form_params' => array(
                                'customer' => array(
                                    "password" => $password,
                                    "password_confirmation" => $password,
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

                        return $e->getResponse()->getBody();
                    }
                }

            } else {

                try {

                    $res = $client->request('post', $api . '/admin/customers.json', array(
                            'form_params' => array(
                                'customer' => array(
                                    'first_name' => strtolower($datos['nombres']),
                                    'last_name' => strtolower($datos['apellidos']),
                                    'email' => strtolower($datos['email']),
                                    'verified_email' => true,
                                    'phone' => $datos['telefono'],
                                    "password" => $password,
                                    "password_confirmation" => $password,
                                    'addresses' => [
                                        [
                                            'address1' => strtolower($datos['direccion']),
                                            'city' => strtolower($datos['ciudad_id']),
                                            'province' => '',
                                            "zip" => '',
                                            'first_name' => strtolower($datos['nombres']),
                                            'last_name' => strtolower($datos['apellidos']),
                                            'country' => 'CO'
                                        ],
                                    ],
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
                        usleep(10000000);
                    }

                } catch (ClientException $e) {
                    if ($e->hasResponse()) {

                        return $e->getResponse()->getBody();
                    }
                }

            }
        } catch (ClientException $e) {
            if ($e->hasResponse()) {

                return redirect()->back()->with(['err' => 'Se actualizó su contraseña en el backoffice pero el usuario no existe en tiendagood']);
            }
        }
    }

}
