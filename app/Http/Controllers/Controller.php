<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
# Custome Fuctions to default BaseController
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiResponser;
    var $request;
    var $model;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function all()
    {
        return $this->successResponse([
            'err' => false,
            'data' => $this->model->all()
        ]);
    }

    public function datatables()
    {
        $data = $this->model->select('*');
        return Datatables::eloquent($data)->make(true);
    }

    public function store()
    {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            $this->model->fill($data)->save();
            DB::commit();
            return $this->successResponse([
                'err' => false,
                'message' => 'Datos registrados correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible crear registro, por favor verifique su información e intente nuevamente.',
                'debug' => [
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()
                ],
            ]);
        }
    }

    public function update($id)
    {
        try {
            DB::beginTransaction();
            $data = $this->request->all();
            $itemData = $this->model->find($id);
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
                    'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
                ]);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible editar registro, por favor verifique su información e intente nuevamente.',
                'debug' => [
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()
                ],
            ]);
        }
    }

    public function find($id)
    {
        $itemData = $this->model->find($id);
        if ($itemData) {
            return $this->successResponse($itemData);
        } else {
            return $this->errorResponse([
                'err' => true,
                'message' => 'No existe el elemento solicitado.'
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $itemData = $this->model->find($id);
            if ($itemData) {
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
                    'message' => 'El registro que intenta eliminar ya no existe.'
                ],401);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
                'err' => true,
                'message' => 'No ha sido posible eliminar registro.',
                'debug' => [
                    'file'     => $e->getFile(),
                    'line'     => $e->getLine(),
                    'message'  => $e->getMessage(),
                    'trace'    => $e->getTraceAsString()
                ],
            ]);
        }
    }
}
