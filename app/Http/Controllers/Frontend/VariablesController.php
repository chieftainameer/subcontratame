<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Variable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VariablesController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.projects.my-projects.departures.variables';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Variable();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function find($id){
        return $this->successResponse([
           'err' => false,
           'data' => Variable::where('id', $id)->first()
        ]);
    }
}
