<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Province;
use App\Models\LegalForm;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Models\AutonomousCommunity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordNotification;
use Illuminate\Support\Facades\Storage;
use App\Mail\CompleteRegistrationNotification;

class AuthController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client';
    var $path;
    var $filePath = 'users/';
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new User();
       $this->path = str_replace('.','/',$this->folder);
    }
    public function login(){

        if($this->request->isMethod('GET')){
            return view($this->folder . '.login', [
                'jsControllers' => [
                    0 => 'app/' . $this->path . '/auth/HomeController.js',
                ],
                'cssStyles' => [
                    0 => 'app/' . $this->path . '/auth/style.css'
                ],
            ]);
        }
        
        $data = $this->request->all();
        
        if($user = User::where('email', $data['email'])->first()){

            if(Hash::check(request()->get('password'), $user->password)){
                Auth::login($user);
                // Si es administrador
                if(Auth::user()->isAdmin()){
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

        // Datos de acceso incorrectos, intente nuevamente.
        return $this->errorResponse([
           'err' => true,
           'message' => 'Datos de acceso incorrectos, intente nuevamente.'
        ]);
    }

    public function register(){
        
        if($this->request->isMethod('GET')){
            return view($this->folder . '.register', [
                'jsControllers' => [
                    0 => 'app/' . $this->path . '/HomeController.js',
                ],
                'cssStyles' => [
                    0 => 'app/' . $this->path . '/style.css'
                ],
                'setting' => Setting::where('id',1)->first()
            ]);
        }
        else{
            try {
                DB::beginTransaction();
                $data = $this->request->all();
                //dd($data);
                $data['password'] = Hash::make($data['password']);
                $data['i_agree'] = $data['i_agree'] === '1' ? 1 : 0;
                
                $user = $this->model->create($data);
                
                // Generate token to complete registration
                $token = Str::random(30) . time() . Str::random(30);
                $user->token_complete = $token;
                $user->save();

                DB::commit();
                
                // Send Email
                Mail::to($user->email)->send(new CompleteRegistrationNotification($user));
                
                return $this->successResponse([
                   'err' => false,
                   'message' => 'Registro creado correctamente. Se te ha enviado un correo para completar tu registro. '
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return $this->errorResponse([
                   'err' => true,
                   'message' => 'Error:  ' . $e->getMessage(),
                   'debug' => [
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()],
                ]);
            }
        }

    }

    public function completeRegistration(){
        if($this->request->isMethod('GET')){
            $token = $this->request->get('token');
            if($user = $this->model->where('token_complete', $token)->first()){
                
                auth()->login($user);

                return view('frontend.client.complete', [
                    'jsControllers' => [
                        0 => 'app/' . $this->path . '/HomeController.js',
                    ],
                    'cssStyles' => [
                        0 => 'app/' . $this->path . '/style.css'
                    ],
                    'token' => $user->token_complete,
                    'legal_forms' => LegalForm::where('status',1)->get(['id', 'name']),
                    'autonomous_communities' => AutonomousCommunity::where('status',1)->get(['id', 'name']),
                    'categories' => Category::where('status',1)->get(['id', 'name']),
                    'payment_methods' => PaymentMethod::where('status', 1)->get(['id', 'name']),
                    'user' => $user
                ]);
            }
            else{
                return view('frontend.client.complete', [
                    'jsControllers' => [
                        0 => 'app/' . $this->path . '/HomeController.js',
                    ],
                    'cssStyles' => [
                        0 => 'app/' . $this->path . '/style.css'
                    ],
                    'user' => NULL
                ]);
            }
        }

        // POST
        try {
          DB::beginTransaction();
           $deleteFile = NULL;
           $data = $this->request->all(); 
           //dd($data);         
           $itemData = $this->model->find($this->request->get('id'));
           if (request()->hasFile('image')) {
                $deleteFile = $itemData->image;
                $filename   = time().rand(111,699).'.' .$this->request->file('image')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                $this->request->file('image')->storeAs($this->filePath,$filename,'public');
                $data['image'] = $fileFullPath;
            }
            
            if ($itemData) {
                $data['status'] = 1;
                $data['token_complete'] = NULL;
                if ($itemData->fill($data)->isDirty()) {
                    $itemData->save();
                    $numCategories = count(request()->get('categories'));
                    for ($i=0; $i < $numCategories; $i++) { 
                        $itemData->categories()->attach(request()->get('categories')[$i]);
                    }
                    $numPaymentMethods = count(request()->get('payment_methods'));
                    for ($i=0; $i < $numPaymentMethods; $i++) { 
                        $itemData->payment_methods()->attach(request()->get('payment_methods')[$i]);
                    }
                    if($deleteFile){
                        Storage::disk('public')->delete(config('app.url') . '/storage/' . $deleteFile);
                    }
                    DB::commit();
                    return $this->successResponse([
                        'err' => false,
                        'message' => 'Registro completado correctamente.',
                        'redirect' => route('client.projects.my-projects')
                    ]);
                } else {
                    return $this->successResponse([
                        'err' => false,
                        'message' => 'Ningún dato ha cambiado.'
                    ]);
                }
            } else {
                DB::rollback();
                return $this->errorResponse([
                    'err' => true,
                    'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
                ]);
            }
        }
        catch(\Exception $e){
        //   echo $e->getMessage();
           DB::rollback();
         return $this->errorResponse([
                       'err'=> true,
                       'message' =>'No ha sido posible procesar solicitud, por favor verifique su información e intente nuevamente.',
                       'debug' => [
                        'file'     => $e->getFile(),
                        'line'     => $e->getLine(),
                        'message'  => $e->getMessage(),
                        'trace'    => $e->getTraceAsString()],
                      ]);
        }
        
        
    }

    public function sendPassword(){
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            if($user = User::where('email', $data['email'])->first()){
                $token = Str::random(30) . time() . Str::random(30);
                $user->token_password = $token;
                $user->save();
                DB::commit();

                // Send Email
                Mail::to($user->email)->send(new ResetPasswordNotification($user));

                return $this->successResponse([
                   'err' => false,
                   'message' => 'Se ha enviado un correo con el enlace para que reestablezca su contraseña.',
                   'show' => 0
                ]);

            }
            else{
                return $this->successResponse([
                   'err' => false,
                   'message' => 'El correo es inválido. Por favor ingrese un correo válido.',
                   'show' => 1
                ]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
               'err' => true,
               'message' => 'Error: ' . $e->getMessage(),
               'debug' => [
                'file'     => $e->getFile(),
                'line'     => $e->getLine(),
                'message'  => $e->getMessage(),
                'trace'    => $e->getTraceAsString()],
            ]);
        }
    }

    public function resetPassword(){
        if($this->request->isMethod('GET')){
            if($user = User::where('token_password', $this->request->get('token'))->first()){
                return view($this->folder . '.reset', [
                    'jsControllers' => [
                        0 => 'app/' . $this->path . '/reset-password/HomeController.js',
                    ],
                    'cssStyles' => [
                        0 => 'app/' . $this->path . '/reset-password/style.css'
                    ],
                    'user' => $user
                ]);
            }
            else{
                return view($this->folder . '.reset', [
                    'user' => NULL
                ]);
            }
            
        }

        try {
          DB::beginTransaction();
          $data = $this->request->all();
          $itemData = $this->model->find($data['id']);
          $data['password'] = Hash::make($data['password']);
          $data['token_password'] = NULL;
          $itemData->fill($data)->save();
          DB::commit();
          return $this->successResponse([
                       'err'=> false,
                       'message' =>'Se ha cmbiado la contraseña correctamente'
          ]);
        }
        catch(\Exception $e){
            //   echo $e->getMessage();
           DB::rollback();
           return $this->errorResponse([
                       'err'=> true,
                       'message' =>'No ha sido posible procesar solicitud, por favor verifique su información e intente nuevamente.',
                       'debug' => [
                        'file'     => $e->getFile(),
                        'line'     => $e->getLine(),
                        'message'  => $e->getMessage(),
                        'trace'    => $e->getTraceAsString()],
                      ]);
        }
    }

    public function logout(){
        auth()->logout();
        return redirect()->to(route('home'));
    }

    public function getProvince($autonomousCommunity){
        return $this->successResponse([
           'err' => false,
           'data' => Province::where('autonomous_community_id', $autonomousCommunity)
                               ->where('status', 1)
                               ->get(['id', 'name']) 
        ]);
    }

    public function getAutonomousCommunity($pais)
    {
       return $this->successResponse([
          'err' => false,
          'data' => AutonomousCommunity::where('country_id',$pais)->get(['id','name'])
       ]);
    }
}