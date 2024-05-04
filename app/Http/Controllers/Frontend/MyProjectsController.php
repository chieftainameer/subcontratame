<?php

namespace App\Http\Controllers\Frontend;

use App\Jobs\SendRelatedProjects;
use App\Models\Dimension;
use App\Models\User;
use App\Models\Project;
use App\Models\Category;
use App\Models\Province;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Models\AutonomousCommunity;
use App\Http\Controllers\Controller;
use App\Models\Pais;
use Illuminate\Support\Facades\Storage;
use App\Notifications\PublishedProjectNotification;

class MyProjectsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects.my-projects';
    var $path;
    var $filePath = 'projects/';
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Project();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
      $projects = Project::where('user_id',auth()->user()->id)
                           ->with(['categories', 'payment_methods', 'departures' => function($query){
                              return $query->with('variants');
                           }])
                           ->orderBy('created_at', 'desc')
                           ->paginate(6);

      return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'categories' => Category::where('status',1)->get(['id', 'name']),
        'paises' => Pais::get(['id','name_es']),
        'payment_methods' => PaymentMethod::where('status',1)->get(['id','name']),
        'projects' => $projects,
        //'applications' => $applications
       ]);
    }

    public function checkout(){
      
      if(request()->isMethod('GET')){
         
         return view($this->folder.'.checkout',[
            'jsControllers'=>[
              0 => 'app/'.$this->path.'/checkout/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/checkout/style.css'
            ],
            'project_id' => $this->request->get('project')
         ]);
      }

      try {
         DB::beginTransaction();
         $model = $this->model
                     ->with(['departures' => function($query){
                        return $query->with('variables');
                     }, 'categories'])
                     ->find(request()->get('project_id'));
         if($model){
            foreach ($model->departures()->get() as $key => $departure) {
               if($departure->status === '1'){
                  $departure->status = '2';
                  $departure->save();
               }

               foreach ($departure->variables()->get() as $key => $variable) {
                  if($variable->status == '1'){
                     $variable->status = '2';
                     $variable->save();
                  }
               }
            }
            
            if($model->status === '0'){
               $model->status = '1'; // Published
               $model->publication_date = \Carbon\Carbon::now();
               $model->save();
               // Notification
               $this->sendNotificationPublishedProject($model);

            }
            
            Transaction::create([
               'user_id' => auth()->user()->id,
               'reference_number' => null,
               'amount' => request()->get('amount'),
               'status' => '2'
            ]);

            DB::commit();
            
            session()->forget('numDepartures');
            session()->forget('numVariables');
            session()->forget('price_departure');
            session()->forget('price_variable');

            return $this->successResponse([
               'err' => false,
               'message' => 'Pago registrado correctamente',
               'redirect' => route('client.projects.my-projects.departures', ['project_id'=> request()->get('project_id')])
            ]);
         }
         else{
            return $this->errorResponse([
               'err' => true,
               'message' => 'No existe el proyecto'
            ]);
         }
         
      } catch (\Exception $e) {
         DB::rollback();
         echo $e->getMessage();
      }
      
    }

    public function publish(){
      try {
         DB::beginTransaction();
         $model = $this->model
                     ->with(['departures' => function($query){
                        return $query->with('variables');
                     }, 'categories'])
                     ->find(request()->get('project'));
         if($model){
            foreach ($model->departures()->get() as $key => $departure) {
               if($departure->status === '1'){
                  $departure->status = '2';
                  $departure->save();
               }

               foreach ($departure->variables()->get() as $key => $variable) {
                  if($variable->status == '1'){
                     $variable->status = '2';
                     $variable->save();
                  }
               }
            }

            if($model->status === '0'){
               $model->status = '1'; // Published
               $model->publication_date = \Carbon\Carbon::now();
               $model->save();
               // Notification
               $this->sendNotificationPublishedProject($model);

            }
            
            Transaction::create([
               'user_id' => auth()->user()->id,
               'reference_number' => null,
               'amount' => request()->get('amount'),
               'status' => '2'
            ]);

            DB::commit();
            return redirect('/client/projects/my-projects')->with('success', 'Se ha publicado correctamente.');
         }
         else{
            return redirect('/client/projects/my-projects')->with('error', 'No existe el proyecto.');
         }
         
      } catch (\Exception $e) {
         DB::rollback();
         return redirect('/client/projects/my-projects')->with('error', 'No se puede realizar la publicación.');
      }
    }

   private function sendNotificationPublishedProject($project){
      User::with('categories')->where('role',2)->where('id', '<>', auth()->user()->id)
         ->each(function($user) use($project){
            foreach ($project->categories()->get() as $category) {
               foreach ($user->categories()->get() as $category2) {
                  if($category->name == $category2->name){
                     $user->notify(new PublishedProjectNotification($project, $category2->name));
                  }
               }
            }
         });
   }

    // For pagination
    public function getData(){
      if($this->request->ajax()){
         $projects = Project::where('user_id',auth()->user()->id)
                             ->with(['categories', 'payment_methods', 'departures' => function($query){
                                 return $query->with('variants');
                             }])
                             ->orderBy('created_at', 'desc')
                             ->paginate(6);
         
         return view('frontend.client.projects.my-projects.partials.projects-data', ['projects' => $projects])->render();
      }
    }

    public function find($id){
      return $this->successResponse([
         'err' => false,
         'data' => Project::where('user_id',auth()->user()->id)
                          ->where('id', $id)
                          ->with(['categories', 'payment_methods', 'province' => function($query){
                              return $query->with('community');
                          },'departures'])->first() 
      ]);
      
    }
    
    public function store() {

      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //echo response()->json($data);
         if($this->request->hasFile('image')){
            $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
            $fileFullPath = $this->filePath.$filename;
            $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
            $data['image'] = $fileFullPath;
         }
         $data['user_id'] = auth()->user()->id;
         $data['code'] = $this->getCode();
         $project = $this->model->create($data);
         
         $project->categories()->sync(request()->get('categories'));
         $project->payment_methods()->sync(request()->get('payment_methods'));

         DB::commit();
         Log::info('sending mails to related workers');
         $notifiableUsers = $this->sendNotificationToRelatedUsers($this->request->title);
         $this->dispatchRelatedProjectEmails($notifiableUsers,$project);
         Log::info('sent to related workers');

         return $this->successResponse([
              'err' => false,
              'message' => 'Datos registrados correctamente. Debe agregar las partidas y variables al proyecto.'
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

    public function update($id) {
      try {
         DB::beginTransaction();
         $deleteFile = NULL;
         $data = $this->request->all();
         //dd($this->request->get('image'));
         $itemData = $this->model->find($id);
         if($itemData){
            if($this->request->hasFile('image')){
               $deleteFile = $itemData->image;
               $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
               $fileFullPath = $this->filePath.$filename;
               $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
               $data['image'] = $fileFullPath;
            }
            
            $itemData->categories()->sync(request()->get('categories'));
            $itemData->payment_methods()->sync(request()->get('payment_methods'));

           if($itemData->update($data)) {
              DB::commit();
               if($deleteFile!==NULL){
                  Storage::disk('public')->delete($deleteFile);
               }
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Datos actualizados correctamente.'
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

    public function getCode(){
      do {
        $code = Str::upper(Str::random(3) . random_int(100,999));
      } while ($project = Project::where('code', $code)->first());
      
      return $code;      
    }

    public function getProvince($autonomousCommunity){
      return $this->successResponse([
         'err' => false,
         'data' => Province::where('autonomous_community_id', $autonomousCommunity)
                             ->where('status', 1)
                             ->get(['id', 'name']) 
      ]);
   }

   public function getAutonomousCommunity($pais)
   {
      return $this->successResponse([
         'err' => false,
         'data' => AutonomousCommunity::where('country_id',$pais)->get(['id','name'])
      ]);
   }

   public function destroy($id)
   {
       $project = Project::findOrFail($id);
       DB::beginTransaction();
       try {
           // all departures or partidas related this project will be deleted using MyProjectsObserver class
           $project->delete();
           DB::commit();
           return $this->successResponse([
               'err' => false,
               'message' => 'Proyecto eliminado correctamente',
               'redirect' => route('client.projects.my-projects')
           ]);
       }
       catch(\Exception $e)
       {
           DB::rollBack();
           return $this->errorResponse([
               'err' =>true,
               'message' => 'No ha sido posible eliminar el proyecto, por favor intente nuevamente.'
           ]);
       }
   }

   private function sendNotificationToRelatedUsers($title)
    {
        $titleKeywords = explode(' ',$title);
        $condition = '';
        foreach($titleKeywords as $index => $keyword)
        {
            $index == 0
                ? $condition .= " key_words LIKE \"%". $keyword ."%\""
                : $condition .= " OR key_words LIKE \"%". $keyword ."%\"";
        }
        $relatedUsers = DB::table('users')
                        ->whereRaw($condition)
                        ->select(['*'])
                        ->get();

        return $relatedUsers;
    }

    private function dispatchRelatedProjectEmails($notifiableUsers,$project)
    {
        foreach ($notifiableUsers as $usi)
        {
            dispatch(new SendRelatedProjects($usi,$project));
        }
    }


}
