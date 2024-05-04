<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\City;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\FireStorageService;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NotificationsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.notifications';
    var $path;
    var $filePath = 'notifications/';
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Notification();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
        
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'cities' => City::where('status',1)->get()
       ]);
    }

    public function datatables() {
        $model = $this->model->select('*')->join('cities','cities.id','=','notifications.city_id')
        ->select([
            'notifications.id',
            'notifications.type',
            'notifications.title',
            'notifications.subtitle',
            'notifications.description',
            'notifications.image',
            'notifications.date_start',
            'notifications.date_end',
            'notifications.city_id',
            'notifications.status',
            'cities.name as city',
        ]);;
         return Datatables::eloquent($model)->make(true);
     }

     public function store() {
        try {
           DB::beginTransaction();
           $data = $this->request->all();
           //Check if user exists
           unset($data['image']);
           if(request()->hasFile('image')) {
              $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
              $fileFullPath = $this->filePath.$filename;
              $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
              $fileUploaded = FireStorageService::uploadWithDeleteLocalFile('notifications',$filename,$fileFullPath);
              if($fileUploaded){
                   $data['image'] = $fileUploaded->src;
                   $data['image_firebase_uid'] = $fileUploaded->id;
              }
            }
            $notification = $this->model->create($data);
            // Send noification to all users except the one who created the level
            $notification->notifyAllUsers();
            DB::commit();
            return $this->successResponse([
                  'err' => false,
                  'message' => 'Datos registrados correctamente.'
            ]);
          }
          catch(\Exception $e){
            echo $e->getMessage();
            DB::rollback();
            return $this->errorResponse([
              'err' =>true,
              'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.'
            ]);
          }
      }
  
      public function update($id) {
        try {
           DB::beginTransaction();
           $data = $this->request->all();
           $itemData = $this->model->find($id);
              if(!$itemData) {
                  return $this->errorResponse([
                  'err' => true,
                  'message' => __('User not found')
                  ]);
              }
              if(request()->hasFile('image')) {
                  $deleteFile = $itemData->image_firebase_uid;
                  $filename   = time().rand(111,699).'.' .$this->request->file('image')->getClientOriginalExtension();
                  $fileFullPath = $this->filePath.$filename;
                  $this->request->file('image')->storeAs($this->filePath,$filename,'public');
                  $fileUploaded = FireStorageService::uploadWithReplaceAndDeleteLocalFile('notifications',$filename,$fileFullPath,$deleteFile);
                  $data['image'] = $fileUploaded->src;
                  $data['image_firebase_uid'] = $fileUploaded->id;
              }
           if($itemData) {
             if($itemData->fill($data)->isDirty()) {
                $itemData->save();
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
              'err' =>true,
              'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
            ]);
           }
        }
         catch(\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return $this->errorResponse([
              'err' =>true,
              'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
            ]);
         }
      }

      public function destroy($id) {
        try {
           DB::beginTransaction();
           $itemData = $this->model->find($id);
           if($itemData) {
             FireStorageService::delete($itemData->image_firebase_uid); 
             if($itemData->delete()) {
                DB::commit();
                return $this->successResponse([
                       'err' => false,
                       'message' => 'Registro eliminado correctamente.'
                ]);
             } else {
                return $this->errorResponse([
                       'err' => true,
                       'message' => 'No ha sido posible eliminar registro, por favor intente dentro de un momento más.'
                ]);
             }
           } else {
            DB::rollback();
            return $this->errorResponse([
              'err' =>true,
              'message' => 'No ha sido posible eliminar registro, por favor intente dentro de un momento más.'
            ]);
           }
        }
         catch(\Exception $e){
            echo $e->getMessage();
            DB::rollback();
            return $this->errorResponse([
              'err' =>true,
              'message' => 'No ha sido posible eliminar registro.'
            ]);
         }
      }
}
