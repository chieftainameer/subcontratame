<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Service;
use App\Models\Workday;
use App\Models\Ambulance;
use Illuminate\Http\Request;
use App\Models\ServiceReport;
use Illuminate\Support\Facades\DB;
use App\Models\ServicePrescription;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ServicesController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.services';
    var $path;
    
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new Service();
        $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
        return view($this->folder.'.index',[
        'jsControllers'=>[
            0 => 'app/'.$this->path.'/HomeController.js',
            1 => 'app/GoogleController.js'
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'patients' => User::where('status','1')
                          ->where('role', '3')
                          ->orWhere('role', '4')->get(),
        'medicals' => User::where('status','1')->where('role','2')->get(),
        'ambulances' => Ambulance::where('status','1')->get()
        ]);
    }

    public function datatables()
    {
        $data = $this->model->select('*')->orderBy('id','desc');
        return Datatables::eloquent($data)->make(true);
    }

    public function store() {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            $workday = null;

            $patient = User::find(request()->input('user_id'));
            //dd($patient);

            // Queries
            if($data['type'] == '1'){
                $medical = User::find(request()->input('medical_id'));
                $data['date'] = date('Y-m-d');
                $data['patient_name'] = $patient!=null?($patient->name .' '. $patient->last_name):null;
                $data['medical_name'] = $medical!=null?($medical->name .' '. $medical->last_name):null;
            }
            // Ambulance
            else if($data['type'] == '2'){
                $ambulance = Ambulance::find(request()->input('ambulance_id'));
                if($ambulance){
                    $workday = Workday::whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))
                                  ->where('ambulance_id',$ambulance->id)
                                  ->first();
                    $medical = null;
                    if($workday){
                        $data['workday_id'] = $workday->id;
                        $data['medical_id'] = $workday->medical->id;
                        $medical = $workday->medical;
                    }
                    $data['medical_name'] = $medical!=null?($medical->name .' '. $medical->last_name):null;
                    $data['ambulance_name'] = $ambulance!=null?$ambulance->name:null;
                }
                $data['date'] = date('Y-m-d');
                $data['patient_name'] = $patient!=null?($patient->name .' '. $patient->last_name):null;
                $data['boarding_address'] = $data['address'];
                $data['boarding_lat'] = $data['lat'];
                $data['boarding_lng'] = $data['lng'];
            }
            
            $this->model->fill($data)->save();
            if($workday){
                $workday->service_id = $this->model->id;
                $workday->save();
            }

            DB::commit();
            return $this->successResponse([
                    'err' => false,
                    'message' => 'Datos registrados correctamente.'
            ]);
            // $data['date'] = date('Y-m-d');
            // $data['patient_name'] = $patient!=null?($patient->name .' '. $patient->last_name):null;
            // $data['medical_name'] = $medical!=null?($medical->name .' '. $medical->last_name):null;
            // $data['ambulance_name'] = $ambulance!=null?$ambulance->name:null;
            
            
        }
         catch(\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
              'err' =>true,
              'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.',
              'debug' => [
                 'file'     => $e->getFile(),
                 'line'     => $e->getLine(),
                 'message'  => $e->getMessage(),
                 'trace'    => $e->getTraceAsString()],
            ]);
         }
      }

    
      public function update($id) {
      try {
        DB::beginTransaction();
        $data = $this->request->all();
        
        $patient = User::find(request()->input('user_id'));
        $medical = User::find(request()->input('medical_id'));
        $ambulance = Ambulance::find(request()->input('ambulance_id'));
            
        //dd("patient => ". $patient, "Medical => " . $medical, "Ambulance => " . $ambulance);

        $data['date'] = date('Y-m-d');
        $data['patient_name'] = $patient!=null?($patient->name .' '. $patient->last_name):null;
        $data['ambulance_name'] = $ambulance != null?($ambulance->name .' '. $ambulance->last_name):null;
        $itemData = $this->model->find($id);
        
        if($itemData->ambulance_id !== null){
            $workday = Workday::whereDate('created_at',\Carbon\Carbon::now()->format('Y-m-d'))
                              ->where('ambulance_id',$itemData->ambulance_id)
                              ->first();
            if($workday){
                $data['workday_id'] = $workday->id;
                $data['medical_id'] = $workday->medical->id;
            }
        }

        $data['medical_name'] = $medical!=null?($medical->name .' '. $medical->last_name):null;
                
        //dd($data, $itemData);
        if($itemData){
            $data['boarding_address'] = $data['address'];
            $data['boarding_lat'] = $data['lat'];
            $data['boarding_lng'] = $data['lng'];
        if($itemData->fill($data)->isDirty()) {
            $itemData->save();
            if($workday){
                $workday->service_id = $itemData->id;
                $workday->save();
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

    public function prescription(Service $service) {
        if(request()->isMethod('GET')) {
            return view($this->folder.'.prescription', [
                'jsControllers'=>[
                    0 => 'app/'.$this->path.'/PrescriptionController.js',
                ],
                'cssStyles' => [
                    0 => 'app/'.$this->path.'/style.css'
                ],
                'patients' => User::where([
                    'status' => '1',
                    'role' => '3'
                ])->get(),
                'service' => $service,
                'medical' => $service->medical,
                'patient' => $service->patient,
                'prescription' => $service->prescription,
                'report' => $service->report
            ]);
        } else {
            try {
                ServicePrescription::updateOrCreate(['service_id' => $service->id],
                    [
                        'service_id' => $service->id,
                        'prescription' => $this->request->prescription,
                        'cie10' => request()->cie10,
                        'type' => $this->request->type
                    ]);
                return $this->successResponse([
                    'message' => 'Datos guardados correctamente'
                ]);
            } catch(\Exception $e) {
                return $this->errorResponse([
                    'err' => true,
                    'message' => 'No ha sido posible guardar los datos',
                    'debug' => [
                        'file'     => $e->getFile(),
                        'line'     => $e->getLine(),
                        'message'  => $e->getMessage(),
                        'trace'    => $e->getTraceAsString()],
                ]);
            }
        }   
    }

    public function report(Service $service) {
        if(request()->isMethod('GET')) {
            return view($this->folder.'.report', [
                'jsControllers'=>[
                    0 => 'app/'.$this->path.'/ReportController.js',
                ],
                'cssStyles' => [
                    0 => 'app/'.$this->path.'/style.css'
                ],
                'patients' => User::where([
                    'status' => '1',
                    'role' => '3'
                ])->get(),
                'service' => $service,
                'medical' => $service->medical,
                'patient' => $service->patient,
                'report'  => $service->report
            ]);
        } else {
            try {
                $data = request()->all();
                $data['service_id'] = $service->id;
                ServiceReport::updateOrCreate([
                    'service_id' => $service->id
                ],$data);
                return $this->successResponse([
                    'message' => 'Datos guardados correctamente'
                ]);
            } catch(\Exception $e) {
                return $this->errorResponse([
                    'err' => true,
                    'message' => 'No ha sido posible guardar los datos',
                    'debug' => [
                        'file'     => $e->getFile(),
                        'line'     => $e->getLine(),
                        'message'  => $e->getMessage(),
                        'trace'    => $e->getTraceAsString()],
                ]);
            }
        }   
    }
}