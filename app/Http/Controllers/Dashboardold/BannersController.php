<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\City;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\FireStorageService;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BannersController extends Controller
{
    var $request;
    var $model;
    var $folder = 'dashboard.banners';
    var $path;
    var $filePath = 'banners/';
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->model = new Banner();
        $this->path = str_replace('.', '/', $this->folder);
    }

    public function index()
    {

        return view($this->folder . '.index', [
            'jsControllers' => [
                0 => 'app/' . $this->path . '/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/' . $this->path . '/style.css'
            ],
            'cities' => City::where('status',1)->get()
        ]);
    }

    public function datatables()
    {
        $model = $this->model->select('*')->join('cities','cities.id','=','banners.city_id')
        ->select([
            'banners.id',
            'banners.name',
            'banners.image',
            'banners.date_start',
            'banners.date_end',
            'banners.link',
            'banners.city_id',
            'banners.status',
            'cities.name as city',
        ]);
        return Datatables::eloquent($model)->make(true);
    }

    public function store()
    {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            //Check if user exists
            if (request()->hasFile('image')) {
                $filename = time().rand(111,999).'.'.$this->request->file('image')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                $this->request->file('image')->storeAs($this->filePath,$filename, 'public');
                $fileUploaded = FireStorageService::uploadWithDeleteLocalFile('banners',$filename,$fileFullPath);
                if($fileUploaded){
                    $data['image'] = $fileUploaded->src;
                    $data['image_firebase_uid'] = $fileUploaded->id;
                }
            }
            $this->model->create($data);
            DB::commit();
            return $this->successResponse([
                'err' => false,
                'message' => 'Datos registrados correctamente.'
            ]);
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.'
            ]);
        }
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            $itemData = $this->model->find($id);
            if (!$itemData) {
                return $this->errorResponse([
                    'err' => true,
                    'message' => __('User not found')
                ]);
            }
            if (request()->hasFile('image')) {
                $deleteFile = $itemData->image_firebase_uid;
                $filename   = time().rand(111,699).'.' .$this->request->file('image')->getClientOriginalExtension();
                $fileFullPath = $this->filePath.$filename;
                $this->request->file('image')->storeAs($this->filePath,$filename,'public');
                $fileUploaded = FireStorageService::uploadWithReplaceAndDeleteLocalFile('banners',$filename,$fileFullPath,$deleteFile);
                $data['image'] = $fileUploaded->src;
                $data['image_firebase_uid'] = $fileUploaded->id;
            }
            if ($itemData) {
                if ($itemData->fill($data)->isDirty()) {
                    $itemData->save();
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
                    'err' => true,
                    'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
                'debug' => [
                   'file'     => $e->getFile(),
                   'line'     => $e->getLine(),
                   'message'  => $e->getMessage(),
                   'trace'    => $e->getTraceAsString()],
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $itemData = $this->model->find($id);
            if ($itemData) {
                FireStorageService::delete($itemData->image_firebase_uid); //Delete file from firebase storage
                if ($itemData->delete()) {
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
                    'err' => true,
                    'message' => 'No ha sido posible eliminar registro, por favor intente dentro de un momento más.'
                ]);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible eliminar registro.'
            ]);
        }
    }
}
