<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class CountriesController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard.countries.';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->model = new Country();
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
}
