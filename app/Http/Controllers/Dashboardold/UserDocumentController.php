<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\UserDocument;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
class UserDocumentController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.users.documents';
    var $path;
    
    public function __construct(Request $request) {
        $this->request = $request;
        $this->model = new UserDocument();
        $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
        return view($this->folder.'.index',[
            'jsControllers'=> [
                0 => 'app/'.$this->path.'/HomeController.js',
            ]
        ]);
    }

    public function datatables() {
        $data = $this->model->select('*')->where('user_id', request()->get('user'));
        return Datatables::eloquent($data)
        ->addColumn('name', function ($item) {
            return $item->document->name;
        })
        ->addColumn('type', function ($item) {
            return $item->document->type;
        })
        ->make(true);
    }
}
