<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CommentsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.comments';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Comment();
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
         $data = $this->model->select('*')->with(['departure' => function($query){
            return $query->with(['project' => function($query){
                return $query->with(['user']);
            }]);
         }, 'user'])->where('reported', 1);
         //dd($data->get());
         return Datatables::of($data)->make(true);
     }
}
