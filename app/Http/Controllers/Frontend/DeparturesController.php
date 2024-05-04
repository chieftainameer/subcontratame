<?php

namespace App\Http\Controllers\Frontend;

use App\Imports\ProjectsImport;
use App\Models\Dimension;
use App\Models\Project;
use App\Models\Plantilla;
use App\Models\Setting;
use App\Models\Variable;
use App\Models\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DeparturesController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects.my-projects.departures';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Departure();
       $this->path = str_replace('.','/',$this->folder);
    }
    
    public function index($project_id) {
        $project = Project::where('id', $project_id)->first();
        $departures = $project->departures()->with('variables')->get();
        $plantilla = Plantilla::first();
        
        $numVariables = 0;
        $numDepartures = 0;
        $priceDeparture = 0;
        $priceVariable = 0;

        foreach ($departures as $departure) {
            if($departure->status === '1'){
               $numDepartures += 1;
            }
            foreach ($departure->variables()->get() as $variable) {
               if($variable->status == '1'){
                  $numVariables += 1;
               }
            }
        }

        if($setting = Setting::where('id',1)->first()){
         if(isset($setting->price_departure)){
            if($setting->price_departure !== null){
               $priceDeparture = $setting->price_departure;
            }
            else{
               $priceDeparture = 0;
            }
         }

         if(isset($setting->price_departure)){
            if($setting->price_variable !== null){
               $priceVariable = $setting->price_variable;
            }
            else{
               $priceVariable = 0;
            }
         }
         $total = $numDepartures * $setting->price_departure + $numVariables * $setting->price_variable;
        }
        else{
         $total = 0;
        }
        
        //dd($numDepartures, $numVariables, $total);
        return view($this->folder.'.index',[
            'jsControllers'=>[
            0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
            'project' => $project,
            'plantilla' => $plantilla,
            'departures' => $project->departures()
                                    ->with('variables')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10),
            'numDepartures' => $numDepartures,
            'numVariables' => $numVariables, 
            'total' => $total,
            'price_departure' => isset($setting->price_departure) ? $setting->price_departure : 0,
            'price_variable' => isset($setting->price_variable) ? $setting->price_variable : 0,
            'dimensions' => Dimension::all()
        ]);
    
    }

    public function getData($project_id){
        if($this->request->ajax()){
            $project = Project::where('id', $project_id)->first();
            $settings = Setting::where('id',1)->first();
            
            $departures = $project->departures()
                                  ->with('variables')  
                                  ->orderBy('complete', 'asc')
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);
            return view('frontend.client.projects.my-projects.partials.departures-data',[
               'project' => $project,
               'departures'=>$departures, 
               'settings' => $settings
            ])->render();  
        }
    }

    public function find($id){
      $data = $this->model->with(['variables' => function($query){
         return $query->orderBy('type');
      },'dimension'])->where('id', $id)->first();

      if(auth()->check()){
         return $this->successResponse([
            'err' => false,
            'data' => $data,
            'payment_methods' => auth()->user()->payment_methods()->get()
         ]);
      }
      else{
         return $this->successResponse([
            'err' => false,
            'data' => $data
         ]);
      }
      
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //dd($data);
         $departure = $this->model->create($data);

         // Is there variables?
         if($data['there_data'] === "1"){
            for ($i=0; $i < count($this->request->get('types')); $i++) { 
               if($this->request->get('types')[$i] === "1" || $this->request->get('types')[$i] === "2"){
                  $departure->variables()->create([
                     'type' => $this->request->get('types')[$i],
                     'description' => $this->request->get('descriptions')[$i],
                     'required' => $this->request->get('requireds')[$i],
                     'visible' => $this->request->get('visibles')[$i],
                     'options' => $this->request->get('options')[$i],
                  ]);
               }
               else if($this->request->get('types')[$i] === "3"){
                  if($this->request->get('downloadFiles')[$i] !== ''){
                     $file = $this->request->get('downloadFiles')[$i];

                     $fileInfo = explode(';base64,', $file);
                     // Doc
                     if($fileInfo[0] === 'data:application/msword'){
                        $file = 'doc';
                     } // Docx
                     else if($fileInfo[0] === 'data:application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
                        $fileExt = 'docx';
                     } // Xls
                     else if($fileInfo[0] === 'data:application/vnd.ms-excel'){
                        $fileExt = 'xls';
                     } // Xlsx
                     else if($fileInfo[0] === 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                        $fileExt = 'xlsx';
                     } // Pdf
                     else if($fileInfo[0] === 'data:application/pdf'){
                        $fileExt = 'pdf';
                     } // Image jpg, jpeg or png
                     else{
                        $fileExt = str_replace('data:image/', '', $fileInfo[0]); 
                     }
                     
                     $file = str_replace(' ', '+', $fileInfo[1]);
                     $fileName = time().'.'.$fileExt;
                     $fullPath = 'projects/departures/variables/'.$fileName;
                     Storage::disk('public')->put($fullPath, base64_decode($file));
                  }
                  else{
                     $fullPath = NULL;
                  }
                  
                  $departure->variables()->create([
                     'type' => $this->request->get('types')[$i],
                     'description' => $this->request->get('descriptions')[$i],
                     'required' => $this->request->get('requireds')[$i],
                     'visible' => $this->request->get('visibles')[$i],
                     'download_file' => $fullPath
                  ]);
               }
               else if($this->request->get('types')[$i] === "4"){
                  $departure->variables()->create([
                     'type' => $this->request->get('types')[$i],
                     'description' => $this->request->get('descriptions')[$i],
                     'required' => $this->request->get('requireds')[$i],
                     'visible' => $this->request->get('visibles')[$i]
                  ]);
               }
               else if($this->request->get('types')[$i] === "5"){
                  // the file is a base64 string or is it empty
                  if($this->request->get('files')[$i] !== ''){
                     $image = $this->request->get('files')[$i];
                     $imageInfo = explode(';base64,', $image);
                     $imgExt = str_replace('data:image/', '', $imageInfo[0]); 
                     $image = str_replace(' ', '+', $imageInfo[1]);
                     $imageName = time().'.'.$imgExt;
                     $fullPath = 'projects/departures/variables/'.$imageName;
                     Storage::disk('public')->put($fullPath, base64_decode($image));
                  }
                  else{
                     $fullPath = NULL;
                  }
                  
                  $departure->variables()->create([
                     'type' => $this->request->get('types')[$i],
                     'description' => $this->request->get('descriptions')[$i],
                     'required' => $this->request->get('requireds')[$i],
                     'visible' => $this->request->get('visibles')[$i],
                     'text' => $this->request->get('texts')[$i],
                     'file' => $fullPath
                  ]);
               }
            }
         }
         
         //dd($this->request->get('project_id'));
         DB::commit();
         //$this->getData($this->request->get('project_id'));
         return $this->successResponse([
              'err' => false,
              'message' => 'Datos registrados correctamente.'
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
         $data = $this->request->all();
         ///dd($data);
         $itemData = $this->model->with('variables')->where('id',$id)->first();
         if($itemData){

            if($itemData->fill($data)->save()) {
              
               if($data['there_data'] === "1"){
                  for ($i=0; $i < count($this->request->get('types')); $i++) { 
                     if($this->request->get('types')[$i] === "1" || $this->request->get('types')[$i] === "2"){
                        if($this->request->get('ids')[$i] === NULL){
                           $itemData->variables()->create([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i],
                              'options' => $this->request->get('options')[$i],
                           ]);
                        }
                        else{
                           $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                              //'departure_id' => $departure->id,
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i],
                              'options' => $this->request->get('options')[$i],
                           ]);
                        }

                        if(isset($itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file) && $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file !== null){
                           Storage::disk('public')->delete($itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file);
                           $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                              'text' => null,
                              'file' => null
                           ]);
                        }
                        
                     }
                     else if($this->request->get('types')[$i] === "3"){

                        if($this->request->get('ids')[$i] === null){

                           if($this->request->get('downloadFiles')[$i] !== ''){
                              $file = $this->request->get('downloadFiles')[$i];
         
                              $fileInfo = explode(';base64,', $file);
                              // Doc
                              if($fileInfo[0] === 'data:application/msword'){
                                 $file = 'doc';
                              } // Docx
                              else if($fileInfo[0] === 'data:application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
                                 $fileExt = 'docx';
                              } // Xls
                              else if($fileInfo[0] === 'data:application/vnd.ms-excel'){
                                 $fileExt = 'xls';
                              } // Xlsx
                              else if($fileInfo[0] === 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                                 $fileExt = 'xlsx';
                              } // Pdf
                              else if($fileInfo[0] === 'data:application/pdf'){
                                 $fileExt = 'pdf';
                              } // Image jpg, jpeg or png
                              else{
                                 $fileExt = str_replace('data:image/', '', $fileInfo[0]); 
                              }
                              
                              $file = str_replace(' ', '+', $fileInfo[1]);
                              $fileName = time().'.'.$fileExt;
                              $fullPath = 'projects/departures/variables/'.$fileName;
                              Storage::disk('public')->put($fullPath, base64_decode($file));
                           }
                           else{
                              $fullPath = NULL;
                           }
                           
                           $itemData->variables()->create([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i],
                              'download_file' => $fullPath
                           ]);
                        }
                        else{
                           $deleteFile = null;
                           if($this->request->get('downloadFiles')[$i] !== null){
                              
                              $deleteFile = $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->download_file;
                              $file = $this->request->get('downloadFiles')[$i];
         
                              $fileInfo = explode(';base64,', $file);
                              // Doc
                              if($fileInfo[0] === 'data:application/msword'){
                                 $file = 'doc';
                              } // Docx
                              else if($fileInfo[0] === 'data:application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
                                 $fileExt = 'docx';
                              } // Xls
                              else if($fileInfo[0] === 'data:application/vnd.ms-excel'){
                                 $fileExt = 'xls';
                              } // Xlsx
                              else if($fileInfo[0] === 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                                 $fileExt = 'xlsx';
                              } // Pdf
                              else if($fileInfo[0] === 'data:application/pdf'){
                                 $fileExt = 'pdf';
                              } // Image jpg, jpeg or png
                              else{
                                 $fileExt = str_replace('data:image/', '', $fileInfo[0]); 
                              }
                              
                              $file = str_replace(' ', '+', $fileInfo[1]);
                              $fileName = time().'.'.$fileExt;
                              $fullPath = 'projects/departures/variables/'.$fileName;
                              Storage::disk('public')->put($fullPath, base64_decode($file));
                           }
                           else{
                              $fullPath = $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->download_file;
                           }

                           $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i],
                              'download_file' => $fullPath
                           ]);

                           if($deleteFile!==null){
                              Storage::disk('public')->delete($deleteFile);
                           }
                        }
                        
                     }
                     else if($this->request->get('types')[$i] === "4"){
                        if($this->request->get('ids')[$i] === NULL){
                           $itemData->variables()->create([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i]
                           ]);
                        }
                        else{
                           $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i]
                           ]);
                        }
                        if(isset($itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file) && $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file !== null){
                           Storage::disk('public')->delete($itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file);
                           $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                              'text' => null,
                              'file' => null
                           ]);
                        }
                     }
                     else if($this->request->get('types')[$i] === "5"){
                        if($this->request->get('ids')[$i] === NULL){
                           if($this->request->get('files')[$i] !== null){
                              $image = $this->request->get('files')[$i];
                              $imageInfo = explode(';base64,', $image);
                              $imgExt = str_replace('data:image/', '', $imageInfo[0]); 
                              $image = str_replace(' ', '+', $imageInfo[1]);
                              $imageName = time().'.'.$imgExt;
                              $fullPath = 'projects/departures/variables/'.$imageName;
                              Storage::disk('public')->put($fullPath, base64_decode($image));
                           }
                           else{
                              $fullPath = NULL;
                           }
                           $itemData->variables()->create([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i],
                              'text' => $this->request->get('texts')[$i],
                              'file' => $fullPath
                           ]);
                        }
                        else{
                           $deleteFile = NULL;
                           if($this->request->get('files')[$i] !== null){
                              $deleteFile = $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file;
                              $image = $this->request->get('files')[$i];
                              $imageInfo = explode(';base64,', $image);
                              $imgExt = str_replace('data:image/', '', $imageInfo[0]); 
                              $image = str_replace(' ', '+', $imageInfo[1]);
                              $imageName = time().'.'.$imgExt;
                              $fullPath = 'projects/departures/variables/'.$imageName;
                              //dd($fullPath);
                              Storage::disk('public')->put($fullPath, base64_decode($image));
                           }
                           else{
                              $fullPath = $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->file;
                           }
                           
                           $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                              'type' => $this->request->get('types')[$i],
                              'description' => $this->request->get('descriptions')[$i],
                              'required' => $this->request->get('requireds')[$i],
                              'visible' => $this->request->get('visibles')[$i],
                              'text' => $this->request->get('texts')[$i],
                              'file' => $fullPath
                           ]);
                           if($deleteFile!==NULL){
                              Storage::disk('public')->delete($deleteFile);
                           }
                        }
                     }
                  }
               }
               
               //dd($this->request->get('variables_delete'));
               // Delete Variables
               if($this->request->get('variables_delete') !== null){
                 for ($i=0; $i < count($this->request->get('variables_delete')); $i++) { 
                  Variable::find($this->request->get('variables_delete')[$i])->delete();
                 } 
               }
              
              
              DB::commit();
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
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
            'debug' => [
               'line' => $e->getLine(),
               'file' => $e->getFile(),
               'error' =>  $e->getMessage()
            ]
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
                     'message' => 'Registro eliminado correctamente.'
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

    public function completed($id){
      try {
         DB::beginTransaction();
         $itemData = $this->model->where('id',$id)->first();
         if($itemData){
            if($itemData->fill(['complete'=>1])->save()) {
              DB::commit();
              return $this->successResponse([
                  'err' => false,
                  'message' => 'La partida ha sido completada correctamente.',
                  'redirect' => route('client.projects.my-projects.departures', ['project_id'=>$this->request->get('project_id')])
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
            'message' => 'No ha sido posible completar la partida, por favor verifique su información e intente nuevamente.'
            
          ]);
         }
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible completar la partida, por favor verifique su información e intente nuevamente.',
            'debug' => [
               'line' => $e->getLine(),
               'file' => $e->getFile(),
               'error' =>  $e->getMessage()
            ]
          ]);
       }
    }

    public function import(Request $request)
    {
        try {
            $project = Project::findOrFail($request->project_id);
            $arrData = Excel::toArray(new ProjectsImport(), $request->file('excel'));

            unset($arrData[0][0]);

            foreach($arrData[0] as $data)
            {
                $project->departures()->create([
                    'code' => $data[0],
                    'description' => $data[1],
                    'execution_date' => Date::excelToDateTimeObject($data[2])->format('Y-m-d'),
                    'quantity' => $data[3],
                    'created_at' => now(),
                    'updated_at' => now(),
                    'dimension_id' => $data[4],
                    'visible' => $data[5],
                ]);
            }

            return redirect()->back();
        }
        catch(\Exception $e)
        {
            return redirect()->back();
        }
    }

    public function deletePartidas(Request $request,$id)
    {
      $project = Project::findOrFail($id);
      $perPage = $request->per_page;
      $partidas = $request->partidas;
      try
      {
         if(!empty($partidas))
         {
            foreach($partidas as $partida)
            {
               $departure = Departure::whereId($partida)->first();
               $departure->delete();
            }
         }

      }
      catch(\Exception $e)
      {
         $request->flash();
         return redirect()->back()->withInput();
      }
     

      $departures = $project->departures()->with('variables')->get();
        
        $numVariables = 0;
        $numDepartures = 0;
        $priceDeparture = 0;
        $priceVariable = 0;

        foreach ($departures as $departure) {
            if($departure->status === '1'){
               $numDepartures += 1;
            }
            foreach ($departure->variables()->get() as $variable) {
               if($variable->status == '1'){
                  $numVariables += 1;
               }
            }
        }

        if($setting = Setting::where('id',1)->first()){
         if(isset($setting->price_departure)){
            if($setting->price_departure !== null){
               $priceDeparture = $setting->price_departure;
            }
            else{
               $priceDeparture = 0;
            }
         }

         if(isset($setting->price_departure)){
            if($setting->price_variable !== null){
               $priceVariable = $setting->price_variable;
            }
            else{
               $priceVariable = 0;
            }
         }
         $total = $numDepartures * $setting->price_departure + $numVariables * $setting->price_variable;
        }
        else{
         $total = 0;
        }

      
      $request->flash();
      return view($this->folder.'.index',[
         'jsControllers'=>[
         0 => 'app/'.$this->path.'/HomeController.js',
         ],
         'cssStyles' => [
             0 => 'app/'.$this->path.'/style.css'
         ],
         'project' => $project,
         'departures' => $project->departures()
                                 ->with('variables')
                                 ->orderBy('created_at', 'desc')
                                 ->paginate($perPage),
         'numDepartures' => $numDepartures,
         'numVariables' => $numVariables, 
         'total' => $total,
         'price_departure' => isset($setting->price_departure) ? $setting->price_departure : 0,
         'price_variable' => isset($setting->price_variable) ? $setting->price_variable : 0,
         'dimensions' => Dimension::all()
     ]);
    }

    
}
