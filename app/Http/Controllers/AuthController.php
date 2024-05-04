<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    var $request;
    var $model;
    var $folder = 'auth';
    var $path = '';
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->model = new User();
        $this->path = str_replace('.', '/', $this->folder);
    }

    public function login()
    {
        try {
            if (request()->isMethod('GET')) {
                return view('auth.login', [
                    'jsControllers' => [
                        0 => 'app/' . $this->path . '/HomeController.js',
                    ],
                    'cssStyles' => [
                        0 => 'app/' . $this->path . '/style.css'
                    ],
                    //'settings' => cache('settings')
                ]);
            }
            
            if($user = User::where('email', request()->get('email'))->first()){
                if(Hash::check(request()->get('password'), $user->password)){
                    Auth::login($user);
                    // Es usuario normal
                    if(!Auth::user()->isAdmin()){
                        Auth::logout();
                        return $this->errorResponse([
                            'err' => true,
                            'message' => 'Datos de acceso incorrectos, intente nuevamente.'
                        ]);
                    }

                    return $this->successResponse([
                        'err' => false,
                        'message' => 'Acceso correcto, redireccionando...',
                        'authenticated' => 1
                    ]);

                }
                else{
                    return $this->errorResponse([
                        'err' => true,
                        'message' => 'Datos de acceso incorrectos, intente nuevamente.'
                    ]);
                }
            }

            return $this->errorResponse([
                'err' => true,
                'message' => 'Datos de acceso incorrectos, intente nuevamente.'
            ]);

            // auth()->attempt(['email' => request()->get('email') , request()->get('password'), 'role' => 1]);
            // if (!auth()->check()) {
            //     return $this->errorResponse([
            //         'err' => true,
            //         'message' => 'Wrong email or password',
            //         'password' => request()->get('password'),
            //         'email' => request()->get('email')
            //     ]);
            // }
            // return $this->successResponse([
            //     'err'        => false,
            //     //'type'  => $type,
            //     'referenced' => false,
            //     'message'    => __('Login success'),
            // ]);
        }
        catch(\Exception $e) {
            return $this->errorResponse([
                'err' => true,
                'message' => 'Error al iniciar sesión',
                'exception' => $e->getMessage()
            ]);
        }
        
        auth()->attempt(request()->only('email', 'password'));
        if (!auth()->check()) {
            return $this->errorResponse([
               'err' => true,
               'message' => 'Error: '.$e->getMessage(),
               'debug' => [
                  'file'     => $e->getFile(),
                  'line'     => $e->getLine(),
                  'message'  => $e->getMessage(),
                  'trace'    => $e->getTraceAsString()],
            ]);
        }
        // $type = "dashboard";
        // switch(auth()->user()->role) {
        //     case 1: { $type = "dashboard"; break; }
        //     case 2: { $type = "medicals"; break; }
        //     case 3: { $type = "customers"; break; }
        //     case 4: { $type = "afiliates"; break; }
        //     case 5: { $type = "enterprises"; break; }
        //     case 6: { $type = "callcenter"; break; }
        //     default: { $type = ""; break; }
        // }
        return $this->successResponse([
            'err'        => false,
            'type'  => $type,
            'referenced' => false,
            'message'    => __('Login success'),
        ]);

        return $this->errorResponse([
            'err' => true,
            'message' => 'Wrong email or password'
        ]);
    }

    
    public function logout()
    {
        if(\auth('web')->check()) {
            \auth('web')->logout();
        }
        return redirect()->route('login');
    }
}
