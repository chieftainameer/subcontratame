<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.users.profile';
    var $path;
    var $filePath = 'users/';

    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new User();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ]
       ]);
    }

    public function update($id) {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         $itemData = $this->model->find($id);
         if($itemData){
            $deleteFile = NULL;
            if($this->request->hasFile('image')){
                $deleteFile = $itemData->image;
                $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
                $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
                $data['image'] = $this->filePath.$filename;
            }
            
            if($data['password']){
                $data['password'] = Hash::make($data['password']);
            }
            else{
                $data['password'] = $itemData->password;
            }



           if($itemData->fill($data)->save()) {
              if($deleteFile !== NULL){
                Storage::disk('public')->delete($deleteFile);
              }  
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Datos actualizados correctamente.',
                     'redirect' => route('dashboard.users.profile')
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
            'err' =>true,
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
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
}
