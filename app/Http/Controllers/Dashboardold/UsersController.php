<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Enterprise;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TypeInsurance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\FireStorageService;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Services\AuthFirebaseService;

class UsersController extends Controller
{
    var $request;
    var $model;
    var $folder = 'dashboard.users';
    var $path;
    var $filePath = 'users/';
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->model = new User();
        $this->path = str_replace('.', '/', $this->folder);
    }

    public function index()
    {
        return view($this->folder . '.index', [
            'jsControllers' => [
                0 => 'app/' . $this->path . '/HomeController.js',
                1 => 'app/GoogleController.js'
            ],
            'cssStyles' => [
                0 => 'app/' . $this->path . '/style.css'
            ],
            'holders' => $this->model->where(['status' => '1', 'role' => '3'])->get(),
            'enterprises' => Enterprise::where('status', '1')->get(),
            'typeinsurances' => TypeInsurance::where('status',1)->get()
        ]);
    }

    public function datatables()
    {
        // retrieve data except current user;
        $data = $this->model->select('*')->where('id', '!=', auth()->user()->id);
        return DataTables::eloquent($data)->make(true);
    }

    public function store()
    {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            //Check if user exists
            if (User::where('dni', $data['dni'])->first()) {
                return $this->errorResponse([
                    'err' => true,
                    'message' => __('Este DNI ya esta asociado a un usuario')
                ]);
            }
            $data['email'] = Str::lower($data['email']);
            if (User::where('email', $data['email'])->first()) {
                return $this->errorResponse([
                    'err' => true,
                    'message' => __('Este correo ya esta asociado a un usuario')
                ]);
            }

            if ($data['medical_license'] != '') {
                if (User::where('medical_license', $data['medical_license'])->first()) {
                    return $this->errorResponse([
                        'err' => true,
                        'message' => __('Esta licencia médica ya esta asociada a un usuario')
                    ]);
                }
            }

            $data['password'] = Hash::make($data['password']);
            unset($data['image']);
            if (request()->hasFile('image')) {
                $filename   = time().rand(111,699).'.' .$this->request->file('image')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                $this->request->file('image')->storeAs($this->filePath,$filename,'public');
                $fileUploaded = FireStorageService::uploadWithDeleteLocalFile('images',$filename,$fileFullPath);
                if($fileUploaded) {
                    $data['image'] = $fileUploaded->src;
                    $data['image_firebase_uid'] = $fileUploaded->id;
                }
            }
            unset($data['signature']);
            if (request()->hasFile('signature')) {
                $filename   = time().rand(111,699).'.' .$this->request->file('signature')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                // primero guardamos en local
                $this->request->file('signature')->storeAs($this->filePath,$filename,'public');
                // Luego subimos al storage de firebase
                $fileUploaded = FireStorageService::uploadWithDeleteLocalFile('signatures',$filename,$fileFullPath);
                // verificamos si se subio correctamente
                if($fileUploaded) {
                    $data['signature'] = $fileUploaded->src;
                    $data['signature_firebase_uid'] = $fileUploaded->id;
                }
            }

            if($user = $this->model->create($data)) {
                $fbUser = app('firebase.auth')->createUser([
                    'email' => request()->email,
                    'emailVerified' => true,
                    'password' => request()->password,
                    'disabled' => request()->status==1,
                ]);
                $user->firebase_uid = $fbUser->uid;
                $user->save();
                DB::commit();
            }
            return $this->successResponse([
                'err' => false,
                'message' => 'Datos registrados correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.',
                'debug' => [
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()
                ],
            ]);
        }
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            $itemData = $this->model->find($id);
            if (!$itemData) {
                return $this->errorResponse([
                    'err' => true,
                    'message' => __('User not found')
                ]);
            }
            unset($data['image']);
            if (request()->hasFile('image')) {
                $deleteFile = $itemData->image_firebase_uid;
                $filename   = time().rand(111,699).'.' .$this->request->file('image')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                $this->request->file('image')->storeAs($this->filePath,$filename,'public');
                $fileUploaded = FireStorageService::uploadWithReplaceAndDeleteLocalFile('images',$filename,$fileFullPath,$deleteFile);
                $data['image'] = $fileUploaded->src;
                $data['image_firebase_uid'] = $fileUploaded->id;
            }
            unset($data['signature']);
            if (request()->hasFile('signature')) {
                $deleteFile = $itemData->signature_firebase_uid;
                $filename   = time().rand(111,699).'.' .$this->request->file('signature')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                // primero guardamos en local
                $this->request->file('signature')->storeAs($this->filePath,$filename,'public');
                // Luego subimos al storage de firebase
                $fileUploaded = FireStorageService::uploadWithReplaceAndDeleteLocalFile('signatures',$filename,$fileFullPath,$deleteFile);
                // verificamos si se subio correctamente
                $data['signature'] = $fileUploaded->src;
                $data['signature_firebase_uid'] = $fileUploaded->id;
            }

            if ($itemData) {
                // remove email and password from $data
                unset($data['email']);
                unset($data['password']);
                if ($itemData->fill($data)->save()) {
                    if ($itemData->isDirty('dni')) {
                        if ($user = User::where('dni',  $itemData->dni)->first()) {
                            if($user->id != $id) {
                                return $this->errorResponse([
                                    'err' => true,
                                    'message' => __('Este DNI ya esta asociado a un usuario')
                                ]);
                            }
                        }
                    }

                    if ($itemData->isDirty('medical_license')) {
                        if ($user = User::where('medical_license',  $itemData->medical_license)->first()) {
                            if($user->id != $id) {
                                return $this->errorResponse([
                                    'err' => true,
                                    'message' => __('Esta Licencia ya esta asociado a un usuario')
                                ]);
                            }
                        }
                    }

                    // remove email and password

                    if($itemData->save()) {
                        // Try update password
                        if (request()->has('password') && \request()->password != '') {
                            $authFirebase = new AuthFirebaseService();
                            if($authFirebase->updatePassword($itemData->firebase_uid, request()->password)) {
                                $itemData->password = Hash::make(request()->password);
                                $itemData->save();
                            } else {
                                return $this->errorResponse([
                                    'err' => true,
                                    'message' => __('No ha sido posible actualizar la contraseña, por favor verifique su información e intente nuevamente.')
                                ]);
                            }
                        }
                        // Try update email
                        if (request()->has('email') && \request()->email != '') {
                            $authFirebase = new AuthFirebaseService();
                            if($authFirebase->updateEmail($itemData->firebase_uid, request()->email)) {
                                $itemData->email = request()->email;
                                $itemData->save();
                            } else {
                                return $this->errorResponse([
                                    'err' => true,
                                    'message' => __('No ha sido posible actualizar el email')
                                ]);
                            }
                        }
                    }
                    DB::commit();
                    return $this->successResponse([
                        'err' => false,
                        'message' => 'Datos actualizados correctamente.'
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
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
                'debug' => [
                   'file'     => $e->getFile(),
                   'line'     => $e->getLine(),
                   'message'  => $e->getMessage(),
                   'trace'    => $e->getTraceAsString()],
            ]);
        }
    }


    public function afiliates($owner) {
        try {
            $afiliates = User::where('status','1')->where('user_id',$owner)->get([
                'id',
                'dni',
                'name',
                'last_name',
                'address',
                'lat',
                'lng'
            ])->map(function($user) {
                $user->name = $user->dni.' '.$user->name.' '.$user->last_name;
                return $user;
            });
            return $this->successResponse([
                'err' => false,
                'message' => 'Datos registrados correctamente.',
                'data' => [
                    'owner' => User::find($owner),
                    'afiliates' => $afiliates
                ]
             ]);
        } catch (\Exception $e) {
            return $this->errorResponse([
               'err' => true,
               'message' => 'No ha sido posible obtener la lista de afiliados del titular',
               'debug' => [
                  'file'     => $e->getFile(),
                  'line'     => $e->getLine(),
                  'message'  => $e->getMessage(),
                  'trace'    => $e->getTraceAsString()],
            ]);
        }
    }

    public function login_as($id)
    {
        try {
            $user = User::find($id);
            if($user){
                if($user->role == '2'){
                    //Auth::loginUsingId($id);
                    auth()->guard('medical')->loginUsingId($id);
                    return $this->successResponse([
                        'err' => false,
                        'message' => 'Usuario iniciado correctamente.',
                        'role' => '2'
                    ]);
                }
                else if($user->role == '5'){
                    auth()->guard('enterprise')->loginUsingId($id);
                    //Auth::loginUsingId($id);
                    return $this->successResponse([
                        'err' => false,
                        'message' => 'Usuario iniciado correctamente.',
                        'role' => '5'
                    ]);
                }
                
            } else {
                return $this->errorResponse([
                    'err' => true,
                    'message' => 'No ha sido posible iniciar sesión, por favor verifique su información e intente nuevamente.'
                ]);
            }
            // if ($user->role == '1') {
            //     return $this->errorResponse([
            //         'err' => true,
            //         'message' => __('Esta funcion no puede ser utilizada para un usuario administrativo.')
            //     ]);
            // } 
            // else if ($user->role == '2') {
            //     return $this->errorResponse([
            //         'err' => true,
            //         'message' => __('Usuario no encontrado.')
            //     ]);
            // }
            // else {
            //     auth()->guard('customers')->login($user);
            //     return $this->successResponse([
            //         'err' => false,
            //         'message' => 'Usuario iniciado correctamente.'
            //     ]);
            // }
        } catch (\Exception $e) {
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible iniciar sesión, por favor verifique su información e intente nuevamente.',
                'debug' => [
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()
                ],
            ]);
        }
    }

    
}
