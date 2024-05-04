<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PaymentMethod;
use App\Models\Project;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Exports\ApplicationsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ApplicationsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects.my-projects.applications';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Project();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
        $project = $this->model->where('id', request()->get('project'))
                        ->with(['departures' => function($query){
                            return $query->whereStatus(2)->whereVisible(1)->with(['variants']);
                        }])->first();
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'project' => $project,
        'paymentMethods' => PaymentMethod::whereStatus(1)->get(),
       ]);
    }

    public function export(){
        $name = time() . rand(100,999);
        return Excel::download(new ApplicationsExport(request()->get('project')), $name . '.xlsx');
    }

    public function filter(Request $request)
    {
        $project = $this->model->whereId($request->project_id)
                ->with(['departures' => function($query) use ($request){
                    return $query->whereIn('id',$request->departures)
                        ->with(['variants' => function($query) use ($request){
                            return $query->whereIn('includes',$request->services)->orWhereIn('tipo',$request->tipo)
                                ->whereHas('payment_methods',function($query) use ($request){
                                    return $query->whereIn('payment_method_id',$request->paymentMethods);
                            });
                        }]);
                }])->first();
        $request->flash();

        return view($this->folder.'.index',[
            'jsControllers'=>[
                0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
            'project' => $project,
            'paymentMethods' => PaymentMethod::whereStatus(1)->get(),
        ]);
    }
}
