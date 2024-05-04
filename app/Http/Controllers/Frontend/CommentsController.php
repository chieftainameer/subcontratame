<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\CommentProjectNotification;

class CommentsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontent.client.projects';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Comment();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function find($id){
      return $this->successResponse([
         'err' => false,
         'data' => $this->model->find($id)
      ]);
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //echo response()->json($data);
         $data['user_id'] = auth()->user()->id;
         $registered = $this->model->create($data);
         // Notification
         $comment = $this->model->where('id', $registered->id)->with(['departure' => function($query){
            return $query->with('project');
         }, 'user'])->first();
         //dd($comment);
         $this->sendNotificationComment($comment);
         DB::commit();
         return redirect()->to(route('client.projects') . '?project=' . $data['project_id'])->with('success', 'Datos registrados correctamente.');
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

    public function update($id) {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         $itemData = $this->model->find($id);
         if($itemData){
           if($itemData->fill($data)->isDirty()) {
              $itemData->save();
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Comentario actualizado correctamente.',
                     'redirect' => route('client.projects') . '?project=' . $this->request->get('project_id')
              ]);
           } else {
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Ningún dato ha cambiado.'
              ]);
           }
         } else {
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
          ]);
         }
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
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
                     'message' => 'Comentario eliminado correctamente.',
                     'redirect' => route('client.projects') . '?project=' . $this->request->get('project_id')
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

    public function report($id){
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //dd($data);
         $itemData = $this->model->find($id);
         if($itemData){
           $data['reported'] = 1;
           if($itemData->fill($data)->save()) {
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Comentario reportado correctamente.',
                     'redirect' => route('client.projects') . '?project=' . $this->request->get('project_id')
              ]);
           } else {
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Ningún dato ha cambiado.'
              ]);
           }
         } else {
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible reportar, por favor verifique su información e intente nuevamente.'
          ]);
         }
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible reportar, por favor verifique su información e intente nuevamente.'
          ]);
       }
    }

    private function  sendNotificationComment($comment){
      if($comment->departure()->first()->project()->first()->user_id !== auth()->user()->id){
         $user = User::where('id', $comment->departure()->first()->project()->first()->user_id)->first();
         $user->notify(new CommentProjectNotification($comment));
      }
    }
}
