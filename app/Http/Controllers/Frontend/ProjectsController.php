<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Project;
use App\Models\Setting;
use App\Models\Departure;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProjectsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Project();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
        //DB::connection()->enableQueryLog();
        $percentage_iva = Setting::where('id', 1)->first()->percentage_iva;
        $payment_methods = PaymentMethod::where('status', 1)->get();
        $project = $this->model
                        ->with(['departures' => function($query){
                            return $query->with(['variables', 'comments'])->where('complete',0);
                        }, 'payment_methods'])
                        ->where('id', request()->get('project'))
                        ->where('status', '1')
                        ->first();
                       
            //$que = DB::getQueryLog();
            return view($this->folder.'.index',[
                'jsControllers'=>[
                0 => 'app/'.$this->path.'/HomeController.js',
                ],
                'cssStyles' => [
                    0 => 'app/'.$this->path.'/style.css'
                ],
                'project' => $project,
                'payment_methods' => $payment_methods,
                'percentage_iva' => $percentage_iva,
                'perPage' => 10
            ]);
        
       
    }

    public function getData(){
        if($this->request->ajax()){
            $projects = Project::where('status','1')
                             ->with('categories', 'payment_methods', 'departures')
                             ->orderBy('created_at', 'desc')
                             ->paginate(8);
            return view('frontend.client.projects.partials.projects-data', ['projects' => $projects])->render();
        }
    }

    public function paginateDepartures()
    {
        $percentage_iva = Setting::where('id', 1)->first()->percentage_iva;
        $payment_methods = PaymentMethod::where('status', 1)->get();
        $perPage = request()->get('perPage');
        $project = $this->model
                        ->with(['departures' => function($query){
                            return $query->with(['variables', 'comments'])->where('complete',0);
                        }, 'payment_methods'])
                        ->where('id', request()->get('project'))
                        ->where('status', '1')
                        ->first();
            //$que = DB::getQueryLog();
            request()->flash();
            return view($this->folder.'.index',[
                'jsControllers'=>[
                0 => 'app/'.$this->path.'/HomeController.js',
                ],
                'cssStyles' => [
                    0 => 'app/'.$this->path.'/style.css'
                ],
                'project' => $project,
                'payment_methods' => $payment_methods,
                'percentage_iva' => $percentage_iva,
                'perPage' => $perPage
            ]);
    }
}
