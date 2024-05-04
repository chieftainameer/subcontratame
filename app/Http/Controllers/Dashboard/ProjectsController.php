<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ProjectsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.projects';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Project();
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

    public function datatables() {
         $data = $this->model->select('*')->with(['departures' => function($query){
            return $query->with('variables');
         }, 'user'])->orderBy('created_at', 'desc');
         return Datatables::eloquent($data)
                          ->addColumn('company_name', function($data){
                              return $data->user->company_name;
                          })
                          ->make(true);
    }
}
