<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\TempNotificationDeparture;
use App\Notifications\ApplyNewProjectNotification;
use App\Notifications\ApplyEditProjectNotification;

class VariantsController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.projects.departures.variants';
    var $path;
    var $filePath = 'variants/';

    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Variant();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function find($id){
        $variant = $this->model->where('id', $id)->with(['departure' => function($query){
            return $query->with(['project' => function($q){
              return $q->with('payment_methods', 'categories');
            }, 'variables']);
          }, 'payment_methods'])->first();
          
          return $this->successResponse([
             'err' => false,
             'data' =>  $variant
          ]);
    }

    public function store() {
      try {
         DB::beginTransaction();
         $files_variants = array();
         $simple_options = array();
         $name_options = array();
         $multiple_options = array();


         $data = $this->request->all();
         
         // Upload Files
         if(isset($this->request->upload_files) && count($this->request->upload_files)){
            for ($i=0; $i < count($this->request->upload_files); $i++) { 
                $files = $this->request->file($this->request->upload_files[$i]);
                if($this->request->hasFile($this->request->upload_files[$i])){
                    foreach ($files as $file) {
                        $filename = time().rand(111,999).'.'.$file->getClientOriginalExtension();
                        $fileFullPath = $this->filePath.$filename;
                        $file->storeAs($this->filePath,$filename, 'public');    
                        array_push($files_variants, ['file' => $fileFullPath]);
                    }
                }
            }
            if(count($files_variants)){
                $data['upload_information'] = json_encode($files_variants);
            }
            //dd($data['upload_information']);  
         }
         
         // Simple Option
         if(isset($this->request->simple_option) && count($this->request->simple_option)){
            for ($i=0; $i < count($this->request->simple_option); $i++) { 
                $json_data = json_decode($this->request->simple_option[$i]);
                array_push($simple_options, ['description' => $json_data->description, 'option' => $json_data->option]);       
            }
            if(count($simple_options)){
                $data['simple_option'] = json_encode($simple_options);
            }
         }

         // Multiple Option
         if(isset($this->request->multiple_option) && count($this->request->multiple_option)){
            for ($i=0; $i < count($this->request->multiple_option); $i++) { 
                $json_data = json_decode($this->request->multiple_option[$i]);
                array_push($multiple_options, ['description' => $json_data->description, 'option' => $json_data->option]);       
            }
            
            if(count($multiple_options)){
                $data['multiple_option'] = json_encode($multiple_options);
            }
         }

         // User id
         $data['user_id'] = auth()->user()->id;
         $model = $this->model->create($data);
         //dd($variant->payment_methods()->get());
         $model->payment_methods()->sync($this->request->get('payment_methods'));  
         
         // Notification
         $variant = $this->model->where('id', $model->id)->with(['departure' => function($query){
            return $query->with('project');
         }])->first();
         
         $this->sendApplyNewNotification($variant);
         //$this->saveNewDeparture($variant);
         
         DB::commit();
         return $this->successResponse([
              'err' => false,
              'message' => 'Haz aplicado a la partida correctamente.',
              'redirect' => route('client.projects.my-offers')
         ]);
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.',
            'debug' => [
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]
          ]);
       }
    }

    public function update($id) {
      try {
         DB::beginTransaction();
         $files_variants = array();
         $simple_options = array();
         $name_options = array();
         $multiple_options = array();

         $data = $this->request->all();
         
         $itemData = $this->model->find($id);

         //dd($this->request->upload_files);
         if($itemData){

            if($itemData->upload_information !== null){
                $upload_information = json_decode($itemData->upload_information);
                foreach ($upload_information as $item) {
                    array_push($files_variants, ['file' => $item->file]);
                }
            }

            if($itemData->simple_option !== null){
                $simple_option = json_decode($itemData->simple_option);
                foreach ($simple_option as $item) {
                    array_push($simple_options, ['option' => $item->option, 'description' => $item->description]);
                }
            }

            if($itemData->multiple_option !== null){
                $multiple_option = json_decode($itemData->simple_option);
                foreach ($multiple_option as $item) {
                    array_push($multiple_options, ['option' => $item->option, 'description' => $item->description]);
                }
            }

            // Upload Files
            if(isset($this->request->upload_files) && count($this->request->upload_files)){
                for ($i=0; $i < count($this->request->upload_files); $i++) { 
                    $files = $this->request->file($this->request->upload_files[$i]);
                    if($this->request->hasFile($this->request->upload_files[$i])){
                        //dd($this->request->upload_files[$i]);
                        foreach ($files as $file) {
                            $filename = time().rand(111,999).'.'.$file->getClientOriginalExtension();
                            $fileFullPath = $this->filePath.$filename;
                            $file->storeAs($this->filePath,$filename, 'public');    
                            array_push($files_variants, ['file' => $fileFullPath]);
                        }
                    }
                }
                if(count($files_variants)){
                    $data['upload_information'] = json_encode($files_variants);
                }
                //dd($data['upload_information']);  
            }
            
            // Simple Option
            if(isset($this->request->simple_option) && count($this->request->simple_option)){
                for ($i=0; $i < count($this->request->simple_option); $i++) { 
                    $json_data = json_decode($this->request->simple_option[$i]);
                    array_push($simple_options, ['description' => $json_data->description, 'option' => $json_data->option]);       
                }
                if(count($simple_options)){
                    $data['simple_option'] = json_encode($simple_options);
                }
            }

            // Multiple Option
            if(isset($this->request->multiple_option) && count($this->request->multiple_option)){
                for ($i=0; $i < count($this->request->multiple_option); $i++) { 
                    $json_data = json_decode($this->request->multiple_option[$i]);
                    array_push($multiple_options, ['description' => $json_data->description, 'option' => $json_data->option]);       
                }
                
                if(count($multiple_options)){
                    $data['multiple_option'] = json_encode($multiple_options);
                }
            }

            // User id
            //$data['user_id'] = auth()->user()->id;
            $itemData->update($data);
            
            //dd($variant->payment_methods()->get());
            $itemData->payment_methods()->sync($this->request->get('payment_methods'));  

            // Notification
         $variant = $this->model->where('id', $itemData->id)->with(['departure' => function($query){
            return $query->with('project');
         }])->first();
         
         $this->sendApplyEditNotification($variant);
         //$this->saveEditDeparture($variant);
         
         DB::commit();
         return $this->successResponse([
              'err' => false,
              'message' => 'Haz editado la oferta de la partida correctamente.',
              'redirect' => route('client.projects.my-offers')
         ]);

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

    private function saveNewDeparture($variant){
        $data['user_id'] = $variant->departure()->first()->project()->first()->user_id; // Owner Project
        $data['variant_id'] = $variant->id; 
        $data['code'] = $variant->departure()->first()->code;
        $data['type'] = 1; // New

        TempNotificationDeparture::create($data);
    } 

    private function saveEditDeparture($variant){
        $data['user_id'] = $variant->departure()->first()->project()->first()->user_id; // Owner Project
        $data['variant_id'] = $variant->id;
        $data['code'] = $variant->departure()->first()->code;
        $data['type'] = 2; // Edit

        $notification = TempNotificationDeparture::where('user_id', $data['user_id'])
                                        ->where('variant_id', $data['variant_id'])
                                        ->where('code', $data['code'])
                                        ->where('type', $data['type'])
                                        ->first();
        if(!$notification){
            TempNotificationDeparture::create($data);
        }
        
    }

    private function sendApplyNewNotification($variant){
        $user = User::where('id', $variant->departure()->first()->project()->first()->user_id)->first();
        $user->notify(new ApplyNewProjectNotification($variant));
    }

    private function sendApplyEditNotification($variant){
        $user = User::where('id', $variant->departure()->first()->project()->first()->user_id)->first();
        $user->notify(new ApplyEditProjectNotification($variant));
    }

    
}
