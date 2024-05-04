<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ambulance;
use App\Models\Central;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;

class CentralsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.centrals';
    var $path;
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new Central();
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
            ]
        ]);
    }

    public function datatables() {
         $data = $this->model->select('*');
         return Datatables::eloquent($data)->make(true);
     }
}
