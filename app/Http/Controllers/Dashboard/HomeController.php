<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    var $request;
    var $folder='dashboard';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->path = str_replace('.','/',$this->folder);
    }
    public function index() {
       return view($this->folder.'.index',[
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'num_users' => User::where('id', '<>', auth()->user()->id)->count() 
       ]);
    }
}
