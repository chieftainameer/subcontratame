<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Project;
use App\Models\Setting;
use App\Models\Variant;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class MyOffersController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects.my-offers';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Variant();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
       $variants = $this->model->with(['departure' => function($query){
          return $query->with(['project', 'comments']);
       }])->where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->paginate(6);  

       $percentage_iva = Setting::where('id', 1)->first()->percentage_iva;
       $payment_methods = PaymentMethod::where('status', 1)->get();
       
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'variants' => $variants,
        'percentage_iva' => $percentage_iva,
        'payment_methods' => $payment_methods
       ]);
    }
    
    // public function showMyOffer($id){
    //   $variant = $this->model->where('id', $id)->first();
    //   return view($this->folder.'.show',[
    //      'jsControllers'=>[
    //        0 => 'app/'.$this->path.'/HomeController.js',
    //      ],
    //      'cssStyles' => [
    //          0 => 'app/'.$this->path.'/style.css'
    //      ],
    //      'variant' => $variant
    //     ]);
    // }

    // public function find($id){
    //   $variant = $this->model->where('id', $id)->with(['departure' => function($query){
    //     return $query->with(['project' => function($q){
    //       return $q->with('payment_methods', 'categories');
    //     }, 'variables']);
    //   }, 'payment_methods'])->first();
      
    //   return $this->successResponse([
    //      'err' => false,
    //      'data' =>  $variant
    //   ]);
    // }

    // public function update($id) {
    //   try {
    //      DB::beginTransaction();
    //      $data = $this->request->all();
    //      $itemData = $this->model->find($id);
    //      if($itemData){
    //        if($itemData->fill($data)->isDirty()) {
    //           $itemData->save();
    //           DB::commit();
    //           return $this->successResponse([
    //                  'err' => false,
    //                  'message' => 'Datos actualizados correctamente.'
    //           ]);
    //        } else {
    //           return $this->successResponse([
    //                  'err' => false,
    //                  'message' => 'Ningún dato ha cambiado.'
    //           ]);
    //        }
    //      } else {
    //       DB::rollback();
    //       return $this->errorResponse([
    //         'err' =>true,
    //         'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
    //       ]);
    //      }
    //   }
    //    catch(\Exception $e){
    //       echo $e->getMessage();
    //       DB::rollback();
    //       return $this->errorResponse([
    //         'err' =>true,
    //         'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
    //       ]);
    //    }
    // }
}