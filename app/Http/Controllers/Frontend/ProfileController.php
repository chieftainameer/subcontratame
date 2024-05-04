<?php

namespace App\Http\Controllers\Frontend;

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

class ProfileController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.profile';
    var $path;
    var $filePath = 'users/';
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new User();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
         $user = User::where('id', auth()->user()->id)->with(['province' => function($query){
            return $query->with('community');
         }])->first();
         
       if($user->status === 2){
         return redirect()->to(route('client.complete') . '?token=' . $user->token_complete);
       }

       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'user' => $user,
        'legal_forms' => LegalForm::where('status',1)->get(['id', 'name']),
        'autonomous_communities' => AutonomousCommunity::where('status',1)->get(['id', 'name']),
        'categories' => Category::where('status',1)->get(['id', 'name']),
        'payment_methods' => PaymentMethod::where('status', 1)->get(['id', 'name']),
       ]);
    }

    public function update($id) {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //dd(isset($data['password']));
         $itemData = $this->model->with('categories', 'payment_methods')->where('id', $id)->first();
         if($itemData){
           $deleteFile = $itemData->image;
           if(request()->hasFile('image')){
                $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
                $data['image'] = $fileFullPath;
           }
          
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
           if(isset($data['password'])){
            $data['password'] = Hash::make($data['password']);
           }
           else{
            $data['password'] = $itemData->password;
           }
           if($itemData->update($data)) {
              $itemData->categories()->sync($data['categories']);
              $itemData->payment_methods()->sync($data['payment_methods']);  
              if($deleteFile){
                Storage::disk('public')->delete(asset('storage') .'/' . $deleteFile);
              }
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Datos actualizados correctamente.',
                     'redirect' => route('client.profile')
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

    public function getProvince($autonomousCommunity){
      return $this->successResponse([
         'err' => false,
         'data' => Province::where('autonomous_community_id', $autonomousCommunity)
                             ->where('status', 1)
                             ->get(['id', 'name']) 
      ]);
   }
}
