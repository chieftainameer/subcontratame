<?php

namespace App\Http\Controllers\Dashboard\Settings;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AutonomousCommunity;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use Yajra\DataTables\Facades\DataTables;

class AutonomousCommunityController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.settings.autonomous_community';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new AutonomousCommunity();
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

    public function datatables(){
        $model = $this->model->select('*')->orderBy('name', 'asc');
        return Datatables::eloquent($model)->make(true); 
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //echo response()->json($data);
         $this->model->fill($data)->save();
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
         if($itemData){
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

    public function getCountries()
    {
      return Pais::orderBy('name_es', 'asc')->get();
    }
}
