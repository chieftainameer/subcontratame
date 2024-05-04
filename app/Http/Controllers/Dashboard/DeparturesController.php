<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Variable;
use App\Models\Departure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DeparturesController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.departures';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Departure();
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
        $data = $this->model->with(['project', 'variables'])->select('*');
        return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('checkbox','<input type="checkbox" name="delete_departures" value={{$id}} />')
        ->rawColumns(['checkbox'])
        ->make(true);
    }

    public function find($id){
        return $this->successResponse([
           'err' => false,
           'data' => $this->model->where('id',$id)->with('variables')->first() 
        ]);
    }

    public function update($id) {
        try {
           DB::beginTransaction();
           $data = $this->request->all();
           //dd($data);
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
                                'status'  => $this->request->get('statuses')[$i],
                             ]);
                          }
                          else{
                             $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                                'type' => $this->request->get('types')[$i],
                                'description' => $this->request->get('descriptions')[$i],
                                'required' => $this->request->get('requireds')[$i],
                                'visible' => $this->request->get('visibles')[$i],
                                'options' => $this->request->get('options')[$i],
                                'status'  => $this->request->get('statuses')[$i],
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
                              'download_file' => $fullPath,
                              'status'  => $this->request->get('statuses')[$i],
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
                              'download_file' => $fullPath,
                              'status'  => $this->request->get('statuses')[$i],
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
                                'visible' => $this->request->get('visibles')[$i],
                                'status'  => $this->request->get('statuses')[$i]
                             ]);
                          }
                          else{
                             $itemData->variables()->where('id', $this->request->get('ids')[$i])->first()->update([
                                'type' => $this->request->get('types')[$i],
                                'description' => $this->request->get('descriptions')[$i],
                                'required' => $this->request->get('requireds')[$i],
                                'visible' => $this->request->get('visibles')[$i],
                                'status'  => $this->request->get('statuses')[$i]
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
                          //dd($this->request->get('types')[$i]);  
                          if($this->request->get('ids')[$i] === NULL){
                           //dd($this->request->get('statuses')[$i], 'NULL'); 
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
                                'file' => $fullPath,
                                'status'  => $this->request->get('statuses')[$i],
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
                                'file' => $fullPath,
                                'status'  => $this->request->get('statuses')[$i],
                             ]);
                             if($deleteFile!==NULL){
                                Storage::disk('public')->delete($deleteFile);
                             }
                          }
                       }
                    }
                 }
                 
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

      public function deleteDepartures()
      {
         $ids =$this->request->get('ids');

         try
         {
            foreach($ids as $id)
            {
               DB::beginTransaction();
               $itemData = $this->model->find($id);
               if($itemData) {
                  if($itemData->delete()) {
                        DB::commit();
                     } 
                  } 
            }

            return $this->successResponse([
               'err' => false,
               'message' => 'Registros eliminado correctamente.'
            ]);
         }
         catch(\Exception $e){
            return $this->errorResponse([
               'err' =>true,
               'message' => 'No ha sido posible eliminar registro.'
             ]);
         }
         
      }
}
