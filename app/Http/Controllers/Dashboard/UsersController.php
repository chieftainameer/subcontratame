<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Category;
use App\Models\Province;
use App\Models\LegalForm;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Models\AutonomousCommunity;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.users';
    var $path;
    var $filePath = 'users/';
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new User();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function datatables(){
        $model = $this->model->select('*')
                        ->with(['categories', 'payment_methods', 'province' => function($query){
                           return $query->with('community');
                        }])
                        ->where('id', '<>', auth()->user()->id);
        return Datatables::eloquent($model)
                        ->addColumn('name', function($user){
                           return $user->first_name . ' ' . $user->last_name;
                        })
                        ->addColumn('checkbox','<input type="checkbox" name="delete_departures" value={{$id}} />')
                        ->rawColumns(['checkbox'])
                        ->make(true); 
    }

    public function index() {
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'legal_forms' => LegalForm::where('status',1)->get(['id', 'name']),
        'autonomous_communities' =>  AutonomousCommunity::where('status', 1)->get(['id', 'name']),
        'categories' => Category::where('status',1)->get(['id', 'name']),
        'payment_methods' => PaymentMethod::where('status',1)->get(['id', 'name'])
       ]);
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         $data['hide_email'] = $data['hide_email'] == 'on' ? 1 : 0;
         $data['hide_cellphone'] = $data['hide_cellphone'] == 'on' ? 1 : 0;
         // dd($data);
         //echo response()->json($data);
         if($this->request->hasFile('image')){
            $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
            $fileFullPath = $this->filePath.$filename;
            $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
            $data['image'] = $fileFullPath;
         }
         $data['password'] = Hash::make($data['password']);
         $this->model->fill($data)->save();

         
         if($data['role'] === '2'){
            $numCategories = count(request()->get('categories'));
            for ($i=0; $i < $numCategories; $i++) { 
               $this->model->categories()->attach(request()->get('categories')[$i]);
            }
            $numPaymentMethods = count(request()->get('payment_methods'));
            for ($i=0; $i < $numPaymentMethods; $i++) { 
               $this->model->payment_methods()->attach(request()->get('payment_methods')[$i]);
            }
         }
         
         //dd($data);
         DB::commit();
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
         if(isset($data['hide_email']))
           {
             $data['hide_email'] = $data['hide_email'] == 'on' ? 1 : 0;
           }
           else{
            $data['hide_email'] = 0;
           }

           if(isset($data['hide_cellphone']))
           {
             $data['hide_cellphone'] = $data['hide_cellphone'] == 'on' ? 1 : 0;
           }
           else{
            $data['hide_cellphone'] = 0;
           }
         $itemData = $this->model->find($id);
         if($itemData){
            $deleteFile = NULL;
            if($this->request->hasFile('image')){
                $deleteFile = $itemData->image;
                $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
                $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
                $data['image'] = $this->filePath.$filename;
            }
            if($data['password']===NULL){ $data['password'] = $itemData->password;}
            else{$data['password'] = Hash::make($data['password']);}


            $itemData->categories()->sync(request()->get('categories'));
            
            $itemData->payment_methods()->sync(request()->get('payment_methods'));
            
            if($itemData->fill($data)->save()) {
              if($deleteFile !== NULL){
                Storage::disk('public')->delete($deleteFile);
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
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
          ]);
       }
    }

    public function checkEmail(){
      if($user=User::where('email',$this->request->email)->first()){
         return response()->json(false);
      }
      return response()->json(true);
    }

    public function getProvince($autonomousCommunity){
      return $this->successResponse([
         'err' => false,
         'data' => Province::where('autonomous_community_id', $autonomousCommunity)
                             ->where('status', 1)
                             ->get(['id', 'name']) 
      ]);
   }

   public function deleteUsers()
   {
      $ids = $this->request->get('ids');

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
