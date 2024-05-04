<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Workday;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class WorkDaysController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.workdays';
    var $path;
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new Workday();
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
            'leaders' => User::where(['status' => '1', 'role' => '2'])->get(),
            'ambulances' => Ambulance::where(['status' => '1'])->get()
        ]);
    }

    public function datatables() {
         $data = $this->model->select('*')->select([
            'workdays.id',
            'workdays.type',
            'workdays.created_at',
            'workdays.medical_id',
            'workdays.ambulance_id',
            'workdays.status',
            'workdays.description',
            'workdays.changed'
         ]);
         return Datatables::eloquent($data)
                    ->addColumn('name', function($data) { return $data->medical->name??'-';})
                    ->addColumn('last_name', function($data) { return $data->medical->last_name??'-';})
                    ->addColumn('phone', function($data) { return $data->medical->phone??'-';})
                    ->addColumn('image', function($data) { return $data->medical->image??'-';})
                    ->addColumn('vehicle_license', function($data) {
                        if($data->type==2) { return $data->ambulance->vehicle_license??'-'; }
                        return '-';
                    })
                    ->addColumn('vehicle_type', function($data) {
                        if($data->type==2) { 
                            if($data->ambulance) {
                                $vehicle_type = 'I';
                                switch($data->ambulance->vehicle_type) {
                                    case '1': { $vehicle_type = 'I'; break;}
                                    case '2': { $vehicle_type = 'II'; break;}
                                    case '3': { $vehicle_type = 'III'; break;}
                                    case '4': { $vehicle_type = 'IV'; break;}
                                }
                                return $vehicle_type;
                            } else {
                                return '-';
                            }
                        }else {
                            return '-';
                        }
                    })
                    ->make(true);
     }
     public function store() {
       try {
          DB::beginTransaction();
          $data = $this->request->all();
          //echo response()->json($data);
          if($data['ambulance_id']!=null&&$data['type']==2){
            $central = Ambulance::find($data['ambulance_id'])->central;
            $data['lat'] = $central->lat;
            $data['lng'] = $central->lng;
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
          DB::beginTransaction();
          $data = $this->request->all();
          $itemData = $this->model->find($id);
          if($itemData){
            if($data['ambulance_id']!=null&&$data['type']==2){
                $central = Ambulance::find($data['ambulance_id'])->central;
                $data['lat'] = $central->lat;
                $data['lng'] = $central->lng;
            }
            else{ 
                $data['lat'] = null;
                $data['lng'] = null;
            }
            
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
