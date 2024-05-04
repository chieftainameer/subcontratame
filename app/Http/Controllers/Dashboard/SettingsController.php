<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Services\UtilsService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Plantilla;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Request as FacadesRequest;

class SettingsController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.settings';
    var $path;
    
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Setting();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function store() {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         //echo response()->json($data);
         $this->model->fill($data)->save();
         DB::commit();
         return $this->successResponse([
              'err' => false,
              'message' => 'Datos registrados correctamente.',
              'direction' => $data['direction']
         ]);
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.',
            'direction' => $data['direction']
          ]);
       }
    }

    public function update($id) {
      try {
         DB::beginTransaction();
         $data = $this->request->all();
         $itemData = $this->model->find($id);
         //dd($data,$itemData);
         if($itemData){
           if($itemData->fill($data)->isDirty()) {
              $itemData->save();
              //dd($data);
              if(isset($data['stripe_public_key']) && $data['stripe_public_key'] !== null){
                UtilsService::overWriteEnvFile('STRIPE_KEY',$data['stripe_public_key']??'');
                Artisan::call('config:clear');
              }
              if(isset($data['stripe_secret_key']) && $data['stripe_secret_key'] !== null){
                UtilsService::overWriteEnvFile('STRIPE_SECRET',$data['stripe_secret_key']??'');
                Artisan::call('config:clear');
              }
              DB::commit();
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Datos actualizados correctamente.',
                     'direction' => $data['direction']
              ]);
           } else {
              return $this->successResponse([
                     'err' => false,
                     'message' => 'Ningún dato ha cambiado.',
                     'direction' => $data['direction']
              ]);
           }
         } else {
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
            'direction' => $data['direction']
          ]);
         }
      }
       catch(\Exception $e){
          echo $e->getMessage();
          DB::rollback();
          return $this->errorResponse([
            'err' =>true,
            'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
            'direction' => $data['direction']
          ]);
       }
    }

    public function showTermsConditions(){
        $setting = Setting::where('id',1)->first();
        return view($this->folder.'.terms',[
            'jsControllers'=>[
              0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
            'setting' => $setting
        ]);
    }

    public function showPrivacyPolicies(){
        $setting = Setting::where('id',1)->first();
        return view($this->folder.'.privacity',[
            'jsControllers'=>[
              0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
            'setting' => $setting
        ]);
    }
    public function showAbout(){
      $setting = Setting::where('id',1)->first();
      return view($this->folder.'.about',[
          'jsControllers'=>[
            0 => 'app/'.$this->path.'/HomeController.js',
          ],
          'cssStyles' => [
              0 => 'app/'.$this->path.'/style.css'
          ],
          'setting' => $setting
      ]);
  }

    public function showContact(){
        $setting = Setting::where('id',1)->first();
        return view($this->folder.'.contact',[
            'jsControllers'=>[
              0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
            'setting' => $setting
        ]); 
    }

    public function showPrices(){
      $setting = Setting::where('id',1)->first();
      return view($this->folder.'.prices',[
          'jsControllers'=>[
            0 => 'app/'.$this->path.'/HomeController.js',
          ],
          'cssStyles' => [
              0 => 'app/'.$this->path.'/style.css'
          ],
          'setting' => $setting
      ]); 
    }

    public function showPaymentGateway(){
      $setting = Setting::where('id',1)->first();
      return view($this->folder.'.gateway',[
          'jsControllers'=>[
            0 => 'app/'.$this->path.'/HomeController.js',
          ],
          'cssStyles' => [
              0 => 'app/'.$this->path.'/style.css'
          ],
          'setting' => $setting
      ]);
    }

    public function uploadPlantilla()
    {
      $plantilla = Plantilla::first();
      return view('dashboard.settings.plantilla',['plantilla' => $plantilla]);
    }

    public function storePlantilla()
    {
      
        $file = $this->request->file('plantilla');

        $extension = $file->getClientOriginalExtension(); 
        $filename = time().'.' . $extension;
        
          $file->storeAs('public/uploads', $filename);
           Plantilla::updateOrCreate(
            [
              'id' => 1
            ],
            [
              'plantilla' => $filename
            ]
           );
        
        
        return redirect()->route("dashboard.settings.plantilla");
    }


    // public function index() {
    //    return view($this->folder.'.index',[
    //     'jsControllers'=>[
    //       0 => 'app/'.$this->path.'/HomeController.js',
    //     ],
    //     'cssStyles' => [
    //         0 => 'app/'.$this->path.'/style.css'
    //     ]
    //    ]);
    // }
}
