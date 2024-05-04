<?php

namespace App\Http\Controllers\Dashboard;

use Carbon\Carbon;
use App\Models\Central;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
USE App\Models\Workday;

class MonitorController extends Controller
{
    var $request;
    var $modelWorkday;
    var $modelService;
    var $folder='dashboard.monitor';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->modelWorkday = new WorkDay();
       $this->modelService = new Service();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
        
       return view($this->folder.'.index', [
        'jsControllers'=>[
          0 => [
            'file' => 'app/'.$this->path.'/HomeController.js',
            'type' => 'module'
          ],
          1 => 'app/GoogleController.js',
          2 => [
            'file' => 'app/'.$this->path.'/FirebaseController.js',
            'type' => 'module'
          ]
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ]
       ]);
    }

    public function vehicles() {
      $workdays = WorkDay::join('ambulances','ambulances.id','=','workdays.ambulance_id')
            ->where('workdays.created_at','>=', Carbon::now()->format('Y-m-d').' 00:00:00')
            ->where('workdays.created_at','<', Carbon::now()->format('Y-m-d').' 23:59:59')
            ->where('ambulances.vehicle_license','like','%'.$this->request->get('term').'%')
            ->where('workdays.type', 2)
            ->select('workdays.id','workdays.lat','workdays.lng','ambulances.vehicle_license as value')
            ->get();
      // filter unique rows filtered by value
      $workdays = $workdays->unique('value');
      return $workdays;
    }

    public function workday($id) {
      $workday = $this->modelWorkday->find($id);
      $workday->service;
      $workday->ambulance;
      $workday->medical;
      $workday->ambulance->central;
      return [
        'workday'   => $workday,
        'patient'   => $workday->service!=null?$workday->service->patient:null
      ];
    }

    public function centrals($id = null) {
      if($id) {
        return Central::find($id);
      } else {
        return Central::where('status',1)->get();
      }
    }

    public function services() {

      if($this->request->method() == 'GET') {
        return $this->successResponse([
          'err' => false,
          'data' => $this->modelService->where('status',0)
                             ->where('type', 2)
                             ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
                             ->orderBy('type_transportation', 'asc')
                             ->get()
       ]);  
      }

      try {
        DB::beginTransaction();
        $data = $this->request->all();
        $itemDataWorkday = $this->modelWorkday->find($data['workday_medical']);
        $medical = $itemDataWorkday->medical;
        $itemDataService = $this->modelService->find($data['service_id']);
        
        $itemDataService->fill([
          'status' => 1,
          'workday_id' => $data['workday_medical'],
          'medical_name' => $medical->name . ' ' . $medical->last_name,
          'medical_id' => $medical->id
        ])->save();

        $itemDataWorkday->fill([
          'status' => '1'
        ])->save();
        
        DB::commit();
        return $this->successResponse([
          'err' => false,
          'message' => 'Servicio asignado correctamente'
        ]);
      } catch (\Exception $th) {
        DB::rollback();
        return $this->errorResponse([
           'err' => true,
           'message' => $th->getMessage()
        ]);
      }

      


      
    }
}
