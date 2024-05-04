<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class NotificationsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.notifications';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
       auth()->user()->unreadNotifications->markAsRead();
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
       ]);
    }

    public function destroy($id) {
      try {
         DB::beginTransaction();
         $itemData = auth()->user()->notifications->where('id', $id)->first();
         if($itemData) {
           if($itemData->delete()) {
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Registro eliminado correctamente.',
                     'redirect' => route('client.notifications')
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
