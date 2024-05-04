<?php

namespace App\Http\Controllers\Dashboard\Settings;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
   var $request;
   var $model;
   var $folder='dashboard.settings.documents';
   var $path;
   public function __construct(Request $request) {
      $this->request = $request;
      $this->model = new Document();
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
