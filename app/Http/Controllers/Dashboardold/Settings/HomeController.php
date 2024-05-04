<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\UtilsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
class HomeController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.settings';
    var $path='';
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new Setting();
        $this->path = str_replace('.','/',$this->folder);
    }

    function index() {
        return view($this->folder.'.index',[
              'jsControllers'=>[
                  0 => 'app/'.$this->path.'/HomeController.js'
                ],
               'cssStyles' => [
                  0 => $this->path.'/style.css'
               ],
               'settings' => $this->model->first()
         ]);
    }

    public function update($id=null) {
        try {
            $data = $this->request->all();
            //dd($data);
            $itemData = $this->model->first();
            if (request()->hasFile('image')) {
                $deleteFile = $itemData->logo;
                $data['logo'] = request()->file('image')->storeAs('/produc', time() . '.' . request()->file('image')->getClientOriginalExtension(), ['disk' => 'public']);
                if ($deleteFile) {
                   Storage::disk('public')->delete($deleteFile);
                }
            }
            $this->model->updateOrCreate(['id'=>($itemData !== null ? $itemData->id : 0 )],$data);
            Cache::forget('settings');
            Cache::set('settings',$this->model->first());
            $data = $this->model->first();
            //Productions
            if(request()->file('firebase_credentials')) {
                $oldFileSetting = $itemData->firebase_credentials;
                $fileName = time().$this->request->firebase_credentials->getClientOriginalName();
                $filePath = $this->request->firebase_credentials->storeAs('', $fileName, ['disk' => 'local']);
                $itemData->firebase_credentials = $filePath;
                if($itemData->save()) {
                    Storage::disk('local')->delete($oldFileSetting);
                }
            }
            // Set Environment Variable Facebook & Google Auths
            UtilsService::overWriteEnvFile('FACEBOOK_CLIENT_ID',$data['facebook_client_id']??'');
            UtilsService::overWriteEnvFile('FACEBOOK_CLIENT_SECRET',$data['facebook_client_secret']??'');
            UtilsService::overWriteEnvFile('GOOGLE_CLIENT_ID',$data['google_client_id']??'');
            UtilsService::overWriteEnvFile('GOOGLE_CLIENT_SECRET',$data['google_client_secret']??'');

            // Privacity
            return $this->successResponse([
                'err' => false,
                'message' => 'Configuración actualizada correctamente',
            ]);
        } catch(\Exception $e) {
            return $this->errorResponse([
                'err' => true,
                'message' => 'No se pudo actualizar la configuración',
                'debug' => [
                   'file'     => $e->getFile(),
                   'line'     => $e->getLine(),
                   'message'  => $e->getMessage(),
                   'trace'    => $e->getTraceAsString()],
            ]);
        }
    }

    public function privacity() {
       return view($this->folder.'.privacity', [
            'jsControllers'=> [
                0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ],
            'data' => cache('settings')
       ]);
    }

    public function terms() {
        return view($this->folder.'.terms', [
             'jsControllers'=> [
                 0 => 'app/'.$this->path.'/HomeController.js',
             ],
             'cssStyles' => [
                 0 => 'app/'.$this->path.'/style.css'
             ],
            'data' => cache('settings')
        ]);
    }
}
