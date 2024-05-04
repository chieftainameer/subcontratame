<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\FavoriteProject;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FavoriteProjectsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects.favorites';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new FavoriteProject();
       $this->path = str_replace('.','/',$this->folder);
    }
    public function index() {
       $favorite_projects = $this->model->where('user_id', auth()->user()->id)->paginate(8);
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'favorite_projects' => $favorite_projects
       ]);
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //echo response()->json($data);
          
         $foundProject = $this->model
                ->where('project_id', $data['project_id'])
                ->where('user_id', auth()->user()->id)
                ->first();
         if(!$foundProject){
            $project = Project::with(['province' => function($query){
                return $query->with('community');
            }])
            ->where('id', $data['project_id'])->first();

            $this->model->fill([
                'project_id' => $project->id,
                'user_id' => auth()->user()->id,
                'image' => $project->image,
                'code' => $project->code,
                'title' => $project->title,
                'location' => $project->province()->first()->community()->first()->name . ', ' . $project->province()->first()->name,
                'expiration_date' => $project->final_date
            ])->save();
            
            DB::commit();
            return $this->successResponse([
                'err' => false,
                'message' => 'Proyecto guardado correctamente.',
                'type' => 'success'
            ]);
         }
         else{
            return $this->successResponse([
                'err' => false,
                'message' => 'Proyecto ya ha sido guardado.',
                'type' => 'info'
            ]);
         }
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

    public function destroy($id) {
      try {
         DB::beginTransaction();
         $itemData = $this->model->find($id);
         if($itemData) {
           if($itemData->delete()) {
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Proyecto eliminado correctamente.',
                     'redirect' => route('client.projects.favorites')
              ]);
           } else {
              return $this->errorResponse([
                     'err' => true,
                     'message' => 'No ha sido posible eliminar registro, por favor intente dentro de un momento más.'
              ]);
           }
         } else {
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible eliminar registro, por favor intente dentro de un momento más.'
          ]);
         }
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible eliminar registro.'
          ]);
       }
    }
}
