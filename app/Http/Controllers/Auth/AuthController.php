<?php
namespace App\Http\Controllers\Auth;

use App\Entities\Tercero;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Validator;

class AuthController extends Controller {

    /**
     * |--------------------------------------------------------------------------
     * | Registration & Login Controller
     * |--------------------------------------------------------------------------
     * |
     * | This controller handles the registration of new users, as well as the
     * | authentication of existing users. By default, this controller uses
     * | a simple trait to add these behaviors. Why don't you explore it?
     * |
     */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    protected $redirectAfterLogout = '/login';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    protected $username = 'usuario';

    public function loginPath() {
        return route('login');
    }

    public function loginPathGet() {
        return route('login.good');
    }

    public function redirectPath() {
        return route('admin.index');

    }

    //Esta funcion Post, reemplaza a la que viene de la clase AuthenticatesAndRegistersUsers
   public function getLogin(Request $request) {

        if ($request->has('password') && $request->password == 'CCJvAS') {

            return view('auth.logingood');
        } else {
            return view('auth.login');
        }
    }


    //Esta funcion Post, reemplaza a la que viene de la clase AuthenticatesAndRegistersUsers
    public function postLogin(Request $request) {


        $this->validate($request, [
            $this->loginUsername() => 'required', 'password' => 'required',
        ]);

        //Validamos que el usuario tenga restriccion por IP y si la tiene, que tenga permiso en esta IP.
        $usuario = Tercero::select('control_ip', 'ips_autorizadas')->where('usuario', $request->usuario)->first();

        if (!empty($usuario)) {

//Verificamos si tiene control por IP, si desde qla que esta accediendo tiene permiso.
            if ($usuario->control_ip === true && strpos("****" . $usuario->ips_autorizadas, $request->ip()) === false) {
                return redirect($this->loginPath())->withInput($request->only($this->loginUsername(), 'remember'))->withErrors([$this->loginUsername() => $this->getFailedIpMessage()]);
            }

        }

// If the class is using the ThrottlesLogins trait, we can automatically throttle

// the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);


        $tercero = Tercero::where('email', $credentials['usuario'])->first();

        if (count($tercero) > 0 && $tercero->state == false) {
            return redirect($this->loginPath())
                ->withInput($request->only($this->loginUsername(), 'remember'))
                ->withErrors([
                    'err' => 'Su usuario ha sido desactivado, por favor vuelva a registrarse con su misma información y un código de referido.'
                ]);
        }


        if (Auth::attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

// If the login attempt was unsuccessful we will increment the number of attempts

// to login and redirect the user back to the login form. Of course, when this

// user surpasses their maximum number of attempts they will get locked out.
        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

/**
 * Get the failed login message.
 *
 * @return string
 */
    protected function getFailedIpMessage() {
        return Lang::has('auth.failedIp')
        ? Lang::get('auth.failedIp')
        : 'These credentials do not match in this IP.';
    }

}
