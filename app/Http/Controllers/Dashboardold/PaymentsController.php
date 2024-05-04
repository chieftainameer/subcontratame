<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Code;
use App\Models\User;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PaymentsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.payments';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Payment();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
       $enterprises = Enterprise::where('status','1')->get();
       $settings = Setting::where('id',1)->first();
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'enterprises' => $enterprises,
        'settings' => $settings,
       ]);
    }

    public function datatables(){
        //$data = $this->model->select('*')->with('user');
        $data = Payment::select('*')->where('type', '0')->with('user');
        // $data = Code::select('*')->with('enterprise');
        return Datatables::eloquent($data)
                          ->addColumn('name', function ($data){
                            // $enterprise = Enterprise::where('id',$data->user->enterprise_id)->first();
                            // return $enterprise->name;
                            return $data->user->name;
                          })
                          ->make(true);
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         $code = $this->getCode();
         //dd($code);
         //echo response()->json($data);
          Code::create([
              'code'          => $code,
              'enterprise_id' => $data['enterprise_id'],
              'date'          => \Carbon\Carbon::now()->format('Y-m-d'),
              'quantity'      => $data['quantity'],
              'date_start'    => \Carbon\Carbon::now()->format('Y-m-d'),
              'date_end'      => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'),
              'price_per_code' => Setting::where('id',1)->first()->price_per_code,
              'total_amount'  => $data['quantity'] * Setting::where('id',1)->first()->price_per_code,
              'status'        => '1'
          ]); 

          // dd(Enterprise::find($data['enterprise_id']));

          $this->model->fill([
              'user_id'       => Enterprise::find($data['enterprise_id'])->user_id,
              'code'          => $code,
              'date'          => \Carbon\Carbon::now()->format('Y-m-d'),
              'quantity'      => $data['quantity'],
              'date_start'    => \Carbon\Carbon::now()->format('Y-m-d'),
              'date_end'      => \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'),
              'total_amount'  => $data['quantity'] * Setting::find(1)->price_per_code,
              'status'        => '1',
          ])->save();
        
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

    public function getCode(){
      do {
        $code = mt_rand(100000,999999);
      } while ($found = Code::where('code',$code)->first());

      return $code;
      
    }
}
