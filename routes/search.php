<?php

use Illuminate\Support\Facades\Route;

$router->group(['prefix'=>'search'], function() use($router){
    $router->get('/',[App\Http\Controllers\SearchController::class,'search']);
   $router->get('/get-province/{autonomousCommunity}',[App\Http\Controllers\SearchController::class,'getProvince']);
});