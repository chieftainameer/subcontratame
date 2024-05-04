<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\ProjectsImport;
use App\Models\Dimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DimensionsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.dimensions';
    var $path;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new Dimension();
        $this->path = str_replace('.','/',$this->folder);
    }


    public function datatables(){
        $model = $this->model->select('*');

        return Datatables::eloquent($model)
            ->addColumn('name', function($dimension){
                return $dimension->name;
            })
            ->make(true);
    }

    public function index()
    {
        return view($this->folder.'.index',[
            'jsControllers'=>[
                0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
        ]);
    }

    public function store() {
        try {
            DB::beginTransaction();
            $data = $this->request->all();

            $this->model->fill($data)->save();
            //dd($data);
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

                if($itemData->fill($data)->save()) {
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
