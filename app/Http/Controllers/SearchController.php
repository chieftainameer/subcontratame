<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(){

    }

    public function getProvince($autonomousCommunity){
        return $this->successResponse([
           'err' => false,
           'data' => Province::where('autonomous_community_id', $autonomousCommunity)
                               ->where('status', 1)
                               ->get(['id', 'name']) 
        ]);
    }
}
