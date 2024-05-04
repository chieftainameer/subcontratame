<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    var $request;
    var $model;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Rating();
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //dd($data);
         //echo response()->json($data);
         $this->model->fill($data)->save();
         DB::commit();
         return $this->successResponse([
              'err' => false,
              'message' => 'Se ha calificado al usuario correctamente.',
              'redirect' => route('client.projects.my-projects.applications') . '?project=' . $data['project_id']
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
}
