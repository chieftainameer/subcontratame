<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\City;
use App\Models\Country;
use App\Models\HelpSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class HelpSupportsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.help_supports';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new HelpSupport();
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

    // public function all(){
    //   $data = $this->model->where('attended_by',auth()->user()->id)->with('country')->orderBy('status', 'asc')->get();
    //   return $this->successResponse([
    //     'err' => false,
    //     'data' => $data
    //   ]);
    // }

    public function databases(){
      $data = $this->model->with('user')->with('country')->orderBy('status', 'asc')->get();
      return Datatables::of($data)
                        ->addColumn('name', function($data){
                          return $data->user->name . ' ' . $data->user->last_name;
                        })
                        ->make(true);
    }

    public function update($id) {
      try {
         DB::beginTransaction();
         $dataRequest = $this->request->all();
         $itemData = $this->model->find($id);
         if($itemData){
           $data = ['status' => $dataRequest['status']];
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
       catch(\Exception $e){
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
