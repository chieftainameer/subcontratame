<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.profile';
    var $path='';
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new User();
        $this->path = str_replace('.','/',$this->folder);
    }
    
    function index() {
        return view($this->folder.'.index',[
              'jsControllers'=>[
                  0 => 'app/'.$this->path.'/HomeController.js'
                ],
               'cssStyles' => [
                  0 => 'app/'.$this->path.'/style.css'
               ],
               'data' => auth()->user()
         ]);
    }

    public function update($id=null) {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         unset($data['image']);
         $itemData = $this->model->find(auth()->user()->id);
         if($itemData && $data['password']!='') {
             if(Hash::check($data['password'], \auth()->user()->password)) {
                 if($data['newpassword']!='') {
                    $data['password'] = bcrypt($data['newpassword']);
                 } else { unset($data['password']);}
                 
             } else {
                 return $this->errorResponse([
                     'err' => true,
                     'message' => 'El password ingresado para actualizar los datos es incorrecto, por favor verifique e intente nuevamente.'
                 ]);
             }
            if(request()->hasFile('image')) {
                $deleteFile = $itemData->image;
                $itemData->image = request()->file('image')->storeAs('/users',time().'.'.request()->file('image')->getClientOriginalExtension(),['disk'=>'public']);
                if($deleteFile) {
                    Storage::disk('public')->delete($deleteFile);
                }
            }
            $itemData->save();
            DB::commit();
            return $this->successResponse([
                    'err' => false,
                    'message' => 'Datos actualizados correctamente.'
            ]);
         } else {
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'El password actual, es requerido para actualizar los datos.'
          ]);
         }
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
          ]);
       }
    }
    
    public function theme_mode() {
        try {
          DB::beginTransaction();
          $user = User::find(auth()->user());
          if($user->theme_mode=='light') {
              $user->theme_mode = 'dark';
          } else {
              $user->theme_mode = 'light';
          }
          $user->save();
          return $this->successResponse([
              'err' => false,
              'message' => 'Tema actualizado correctamente.'
          ]);
          DB::commit();
         return $this->successResponse([
                       'err'=> false,
                       'message' =>'Solicitud procesada correctamente'
                      ]);
        }
        catch(\Exception $e){
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
}
