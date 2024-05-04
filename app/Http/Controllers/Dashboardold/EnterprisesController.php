<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enterprise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class EnterprisesController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.enterprises';
    var $path;
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new Enterprise();
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

    public function store() {
      try {
        DB::beginTransaction();
        $data = $this->request->all();
        // validate unique government_identity and email
        $validator = Validator::make($this->request->all(), [
            'government_identity' => 'required|unique:enterprises,government_identity',
            'email' => 'required|unique:enterprises,email',
        ]);
        if($validator->fails()) {
            return $this->errorResponse([
                'status' => true,
                'message' => 'El Correo o RUC ya han sido registrados previamente',
            ]);
        }
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
        // validate unique government_identity and email
        $validator = Validator::make($this->request->all(), [
            'government_identity' => 'required|unique:enterprises,government_identity,'.$id,
            'email' => 'required|unique:enterprises,email,'.$id,
        ]);
        if($validator->fails()) {
            return $this->errorResponse([
                'status' => true,
                'message' => 'Otro usuario tiene asignado el mismo RUC o Email, no es posible actualizar los datos.'
            ]);
        }
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
}