<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Pais;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Departure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Models\AutonomousCommunity;

class HomeController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend';
    var $path;
    public function __construct(Request $request) {
       $this->request = $request;
       $this->path = str_replace('.','/',$this->folder);
    }

    public function home(){
        
        // Search Default
        // User logueado
        if(auth()->check()){
            $categories_user = auth()->user()->categories()->get();
            if($categories_user->count()){
                foreach ($categories_user as $key => $value) {
                    $cateUser[] = $value->id;
                }
            }

            // Simple Search
            if(request()->get('mode') === 'simple'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        if(preg_match("/^term,[a-zA-Z0-9]/", $query)){
                            $data = explode(',', $query);
                            $word = $data[1];
                            // There word, There not province
                            $projects = Project::where('status', '1')
                                                ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                    return $query->select('id', 'first_name', 'last_name', 'company_name');
                                                }])
                                                ->where(function($query) use($word){
                                                    $query->where('code', 'like', '%' . $word . '%')
                                                        ->orWhere('title', 'like', '%' . $word . '%')
                                                        ->orWhere('short_description', 'like', '%' . $word . '%')
                                                        ->orWhereHas('user', function($q) use($word){
                                                            return $q->where('first_name', 'like', '%' . $word . '%')
                                                                    ->orWhere('last_name', 'like', '%' . $word . '%')
                                                                    ->orWhere('company_name', 'like', '%' . $word . '%');
                                                        })
                                                        ->orWhereHas('departures', function($q) use($word){
                                                            return $q->where('code', 'like', '%' .$word. '%')
                                                                    ->orWhere('description', 'like', '%' .$word. '%');
                                                        });
                                                })
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(8)
                                                ->withQueryString();    
                        }
                        else{
                            if(isset($cateUser)){
                                $projects = Project::where('status', '1')
                                                ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                    return $query->select('id', 'first_name', 'last_name', 'company_name');
                                                }])
                                                ->whereHas('categories', function($q) use($cateUser){
                                                    return $q->whereIn('categories.id', $cateUser);
                                                })
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(8)
                                                ->withQueryString();
                            }
                            else{
                                $projects = Project::where('status', '1')
                                                ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                    return $query->select('id', 'first_name', 'last_name', 'company_name');
                                                }])
                                                ->orderBy('created_at', 'desc')
                                                ->paginate(8)
                                                ->withQueryString();
                            }
                        }
                    }
                    else{
                        if(isset($cateUser)){
                            $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereHas('categories', function($q) use($cateUser){
                                                return $q->whereIn('categories.id', $cateUser);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                        }
                        else{
                            $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                        }
                    }
            }
            // Advance Search
            else if(request()->get('mode') === 'advance'){
                //dd(request()->get('query'));
                if(request()->get('query')){
                    $request_query = request()->get('query');
                    // if(preg_match("/^type,[A-Z]:field,[a-z]:term,[a-zA-Z0-9]+/", $request_query)){
                    //     dd("esta bien");
                    // }
                    // else{
                    //     dd("no esta bien");
                    // }
                    $data = explode(':', $request_query);
                    //dd($data);
                    $types = []; $fields = []; $terms = [];
                    $filter = '';

                    for ($i=0; $i < count($data); $i++) { 
                        $item = explode(',', $data[$i])[0];
                        if($item === 'type'){
                            $types[] = explode(',', $data[$i])[1];
                        }
                        else if($item === 'field'){
                            $fields[] = explode(',', $data[$i])[1];
                        }
                        else if($item === 'term'){
                            $terms[] = explode(',', $data[$i])[1];
                        }
                    }
                    
                    $queryByFields = [];
                    $arrModelKeys = [];
                    $condition_query = '';

                    for ($i=0; $i < count($types); $i++) { 
                        if($fields[$i] === 'title'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'user'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'company'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'des_project'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'des_departure'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                    }

                    foreach ($queries as $key => $q) {
                    $queryByFields[$q['field']][$key] = [
                        'type' => $q['type'],
                        'term' => $q['term'],
                        'field' => $q['field']
                    ];
                    }

                    foreach ($queryByFields as $key => $queries) {
                        foreach ($queries as $key => $value) {
                            $term = $value['term'];
                            $type = $value['type']; 
                            if($value['field'] === 'title'){
                                if($type === 'nothing'){
                                    $condition_query .= "(title like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (projects.title like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (projects.title like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'user'){
                                if($type === 'nothing'){
                                    $condition_query .= "(users.first_name like '%$term%')"; // or users.last_name like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (users.first_name like '%$term%')"; // or users.last_name like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (users.first_name like '%$term%')"; // or users.last_name like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'company'){
                                if($type === 'nothing'){
                                    $condition_query .= "(users.company_name like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (users.company_name like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (users.company_name like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'des_project'){
                                if($type === 'nothing'){
                                    $condition_query .= "(projects.short_description like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (projects.short_description like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (projects.short_description like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'des_departure'){
                                if($type === 'nothing'){
                                    $condition_query .= "(departures.description like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (departures.description like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (departures.description like '%$term%')";
                                }
                            }
                        }
                    }
                    //dd($condition_query);
                    $projects = DB::table('projects')
                                    ->distinct()
                                    ->join('users', function($join) {
                                        $join->on('users.id', '=', 'projects.user_id')
                                            ->where('users.status', '=', '1');
                                    })
                                    ->join('departures', function($join){
                                        $join->on('departures.project_id', '=', 'projects.id')
                                            ->where('departures.status', '=', '2')
                                            ->where('departures.visible', '=', 1)
                                            ->where('departures.complete', '=', 0);  
                                    })
                                    ->where('projects.status', '=', '1')
                                    ->whereRaw($condition_query)
                                    ->get(['projects.id']);
                    //dd($projects);
                    if($projects){
                        foreach ($projects as $project) {
                            $arrModelKeys[] = $project->id;
                        }
                    }

                    //DB::connection()->enableQueryLog();    

                    // Initialize Query
                    $query = Project::query();

                    $query->where('status', '1')
                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                    }]);

                    $query->whereIn('id', $arrModelKeys);
                    
                    $projects = $query->orderBy('created_at', 'desc')->paginate(8)->withQueryString();

                    //$que = DB::getQueryLog();
                    //dd($que);
                }
                else{
                    if(isset($cateUser)){
                        $projects = Project::where('status', '1')
                                        ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                            return $query->select('id', 'first_name', 'last_name', 'company_name');
                                        }])
                                        ->whereHas('categories', function($q) use($cateUser){
                                            return $q->whereIn('categories.id', $cateUser);
                                        })
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(8)
                                        ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                        ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                            return $query->select('id', 'first_name', 'last_name', 'company_name');
                                        }])
                                        ->orderBy('created_at', 'desc')
                                        ->paginate(8)
                                        ->withQueryString();
                    }
                }

                
            }
            // Filter Search
            else if(request()->get('mode') === 'filter'){
                // Categories
                if(request()->get('op') === '1'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        for ($i=0; $i < count($data); $i++) { 
                            $categories[] = explode(',', $data[$i])[1];
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereHas('categories', function($q) use($categories){
                                                return $q->whereIn('categories.id', $categories);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    

                }
                // Payments
                else if(request()->get('op') === '2'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        for ($i=0; $i < count($data); $i++) { 
                            $payments[] = explode(',', $data[$i])[1];
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereHas('payment_methods', function($q) use($payments){
                                                return $q->whereIn('payment_methods.id', $payments);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    
                }
                // Categories And Payments
                else if(request()->get('op') === '3'){
                    if(request()->get('query')){
                        $query = request()->get('query');

                        $data = explode(':', $query);
                        for ($i=0; $i < count($data); $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            
                            if($type === 'cat'){
                                $categories[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'pay'){
                                $payments[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($categories, $payments){
                                                $query->whereHas('categories', function($q) use($categories){
                                                    return $q->whereIn('categories.id', $categories);
                                                })
                                                ->orWhereHas('payment_methods', function($q) use($payments){
                                                    return $q->whereIn('payment_methods.id', $payments);
                                                });    
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                }
                // Categories and Comunity, Province
                else if(request()->get('op') === '4'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        //dd($data);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) {
                            if(explode(',', $data[$i])[0] === 'cat'){
                                $categories[] = explode(',', $data[$i])[1];
                            } 
                        }
                        for ($i=0; $i < $long; $i++) {
                            if(explode(',', $data[$i])[0] === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            } 
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($categories, $provinces){
                                                return $query->whereIn('province_id', $provinces)
                                                            ->orWhereHas('categories', function($q) use($categories){
                                                                return $q->whereIn('categories.id', $categories);
                                                            });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();    
                    }
                    

                }
                // Payments and Comunity, Province
                else if(request()->get('op') === '5'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            
                            if($type === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'pay'){
                                $payments[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($provinces, $payments){
                                                return $query->whereIn('province_id', $provinces)
                                                            ->orWhereHas('payment_methods', function($q) use($payments){
                                                                return $q->whereIn('payment_methods.id', $payments);
                                                            });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                }
                // Categories, Payments and Community, Province
                else if(request()->get('op') === '6'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            
                            if($type === 'cat'){
                                $categories[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'pay'){
                                $payments[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($categories, $payments, $provinces){
                                                return $query->whereIn('province_id', $provinces)
                                                            ->orWhereHas('categories', function($q) use($categories){
                                                                return $q->whereIn('categories.id', $categories);
                                                            })
                                                            ->orWhereHas('payment_methods', function($q) use($payments){
                                                                return $q->whereIn('payment_methods.id', $payments);
                                                            });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();    
                    }
                    
                }
                // Community, Province
                else if(request()->get('op') === '7'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            if($type === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereIn('province_id', $provinces)
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    
                }
                else{
                    $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                }
            }
            // Search
            else{
                if(isset($cateUser)){
                    $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->whereHas('categories', function($q) use($cateUser){
                                        return $q->whereIn('categories.id', $cateUser);
                                    })
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                }
                else{
                    $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                }
                
            }
        }
        else{
            // Simple Search
            if(request()->get('mode') === 'simple'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(',', $query);
                        $word = $data[1];
                        // There word, There not province
                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($word){
                                                $query->where('code', 'like', '%' . $word . '%')
                                                    ->orWhere('title', 'like', '%' . $word . '%')
                                                    ->orWhere('short_description', 'like', '%' . $word . '%')
                                                    ->orWhereHas('user', function($q) use($word){
                                                        return $q->where('first_name', 'like', '%' . $word . '%')
                                                                ->orWhere('last_name', 'like', '%' . $word . '%')
                                                                ->orWhere('company_name', 'like', '%' . $word . '%');
                                                    })
                                                    ->orWhereHas('departures', function($q) use($word){
                                                        return $q->where('code', 'like', '%' .$word. '%')
                                                                ->orWhere('description', 'like', '%' .$word. '%');
                                                    });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    
            }
            // Advance Search
            else if(request()->get('mode') === 'advance'){
                if(request()->get('query')){
                    $request_query = request()->get('query');
                    $data = explode(':', $request_query);
                    $types = []; $fields = []; $terms = [];
                    $filter = '';

                    for ($i=0; $i < count($data); $i++) { 
                        $item = explode(',', $data[$i])[0];
                        if($item === 'type'){
                            $types[] = explode(',', $data[$i])[1];
                        }
                        else if($item === 'field'){
                            $fields[] = explode(',', $data[$i])[1];
                        }
                        else if($item === 'term'){
                            $terms[] = explode(',', $data[$i])[1];
                        }
                    }
                    
                    $queryByFields = [];
                    $arrModelKeys = [];
                    $condition_query = '';

                    for ($i=0; $i < count($types); $i++) { 
                        if($fields[$i] === 'title'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'user'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'company'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'des_project'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                        else if($fields[$i] === 'des_departure'){
                        $queries[] = [
                            'type' => $types[$i],
                            'field' => $fields[$i],
                            'term' => $terms[$i],
                        ];
                        }
                    }

                    foreach ($queries as $key => $q) {
                    $queryByFields[$q['field']][$key] = [
                        'type' => $q['type'],
                        'term' => $q['term'],
                        'field' => $q['field']
                    ];
                    }

                    foreach ($queryByFields as $key => $queries) {
                        foreach ($queries as $key => $value) {
                            $term = $value['term'];
                            $type = $value['type']; 
                            if($value['field'] === 'title'){
                                if($type === 'nothing'){
                                    $condition_query .= "(title like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (projects.title like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (projects.title like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'user'){
                                if($type === 'nothing'){
                                    $condition_query .= "(users.first_name like '%$term%')"; // or users.last_name like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (users.first_name like '%$term%')"; // or users.last_name like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (users.first_name like '%$term%')"; // or users.last_name like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'company'){
                                if($type === 'nothing'){
                                    $condition_query .= "(users.company_name like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (users.company_name like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (users.company_name like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'des_project'){
                                if($type === 'nothing'){
                                    $condition_query .= "(projects.short_description like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (projects.short_description like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (projects.short_description like '%$term%')";
                                }
                            }
                            else if($value['field'] === 'des_departure'){
                                if($type === 'nothing'){
                                    $condition_query .= "(departures.description like '%$term%')";
                                }
                                else if($type === 'AND'){
                                    $condition_query .= " and (departures.description like '%$term%')";
                                }
                                else if($type === 'OR'){
                                    $condition_query .= " or (departures.description like '%$term%')";
                                }
                            }
                        }
                    }
                    //dd($condition_query);
                    $projects = DB::table('projects')
                                    ->distinct()
                                    ->join('users', function($join) {
                                        $join->on('users.id', '=', 'projects.user_id')->where('users.status', '=', '1');
                                    })
                                    ->join('departures', function($join){
                                        $join->on('departures.project_id', '=', 'projects.id')->where('departures.status', '=', '2')->where('departures.visible', '=', 1)->where('departures.complete', '=', 0);  
                                    })
                                    ->where('projects.status', '=', '1')
                                    ->whereRaw($condition_query)
                                    ->get(['projects.id']);
                    //dd($projects);
                    if($projects){
                        foreach ($projects as $project) {
                            $arrModelKeys[] = $project->id;
                        }
                    }

                    //DB::connection()->enableQueryLog();    

                    // Initialize Query
                    $query = Project::query();

                    $query->where('status', '1')
                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                    }]);

                    $query->whereIn('id', $arrModelKeys);
                    
                    $projects = $query->orderBy('created_at', 'desc')->paginate(8)->withQueryString();

                    //$que = DB::getQueryLog();
                    //dd($que);
                }
                else{
                    $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                }
                
            }
            // Filter Search
            else if(request()->get('mode') === 'filter'){
                
                // Categories
                if(request()->get('op') === '1'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        for ($i=0; $i < count($data); $i++) { 
                            $categories[] = explode(',', $data[$i])[1];
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereHas('categories', function($q) use($categories){
                                                return $q->whereIn('categories.id', $categories);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    

                }
                // Payments
                else if(request()->get('op') === '2'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        for ($i=0; $i < count($data); $i++) { 
                            $payments[] = explode(',', $data[$i])[1];
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereHas('payment_methods', function($q) use($payments){
                                                return $q->whereIn('payment_methods.id', $payments);
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    
                }
                // Categories And Payments
                else if(request()->get('op') === '3'){
                    if(request()->get('query')){
                        $query = request()->get('query');

                        $data = explode(':', $query);
                        for ($i=0; $i < count($data); $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            
                            if($type === 'cat'){
                                $categories[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'pay'){
                                $payments[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($categories, $payments){
                                                $query->whereHas('categories', function($q) use($categories){
                                                    return $q->whereIn('categories.id', $categories);
                                                })
                                                ->orWhereHas('payment_methods', function($q) use($payments){
                                                    return $q->whereIn('payment_methods.id', $payments);
                                                });    
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                    

                }
                // Categories and Comunity, Province
                else if(request()->get('op') === '4'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        //dd($data);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) {
                            if(explode(',', $data[$i])[0] === 'cat'){
                                $categories[] = explode(',', $data[$i])[1];
                            } 
                        }
                        for ($i=0; $i < $long; $i++) {
                            if(explode(',', $data[$i])[0] === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            } 
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($categories, $provinces){
                                                return $query->whereIn('province_id', $provinces)
                                                            ->orWhereHas('categories', function($q) use($categories){
                                                                return $q->whereIn('categories.id', $categories);
                                                            });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                }
                // Payments and Comunity, Province
                else if(request()->get('op') === '5'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        //dd($data);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            
                            if($type === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'pay'){
                                $payments[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($provinces, $payments){
                                                return $query->whereIn('province_id', $provinces)
                                                            ->orWhereHas('payment_methods', function($q) use($payments){
                                                                return $q->whereIn('payment_methods.id', $payments);
                                                            });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();    
                    }
                }
                // Categories, Payments and Community, Province
                else if(request()->get('op') === '6'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            
                            if($type === 'cat'){
                                $categories[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'pay'){
                                $payments[] = explode(',', $data[$i])[1];
                            }
                            else if($type === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->where(function($query) use($categories, $payments, $provinces){
                                                return $query->whereIn('province_id', $provinces)
                                                            ->orWhereHas('categories', function($q) use($categories){
                                                                return $q->whereIn('categories.id', $categories);
                                                            })
                                                            ->orWhereHas('payment_methods', function($q) use($payments){
                                                                return $q->whereIn('payment_methods.id', $payments);
                                                            });
                                            })
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                    return $query->select('id', 'first_name', 'last_name', 'company_name');
                                }])
                                ->orderBy('created_at', 'desc')
                                ->paginate(8)
                                ->withQueryString(); 
                    }
                    

                }
                // Community, Province
                else if(request()->get('op') === '7'){
                    if(request()->get('query')){
                        $query = request()->get('query');
                        $data = explode(':', $query);
                        //dd($data);
                        $long = count($data);
                        for ($i=0; $i < $long; $i++) { 
                            $type =  explode(',', $data[$i])[0];
                            //dd($type);
                            if($type === 'prov'){
                                $provinces[] = explode(',', $data[$i])[1];
                            }
                        }

                        $projects = Project::where('status', '1')
                                            ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                                return $query->select('id', 'first_name', 'last_name', 'company_name');
                                            }])
                                            ->whereIn('province_id', $provinces)
                                            ->orderBy('created_at', 'desc')
                                            ->paginate(8)
                                            ->withQueryString();
                    }
                    else{
                        $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                    }
                }
                else{
                    $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
                }

            }
            // Default Search
            else{
                $projects = Project::where('status', '1')
                                    ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                                        return $query->select('id', 'first_name', 'last_name', 'company_name');
                                    }])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(8)
                                    ->withQueryString();
            }
            
        }
        
        return view('welcome', [
            'projects' => $projects,
            'autonomous_communities' => AutonomousCommunity::where('status', 1)->with('provinces')->get(['id','name']),
            'paises' => Pais::all(),
            'categories' => Category::where('status',1)->get(['id', 'name']),
            'payment_methods' => PaymentMethod::where('status',1)->get(['id', 'name']),
            'jsControllers'=>[
                0 => 'app/'.$this->path.'/HomeController.js',
            ],
            'cssStyles' => [
                0 => 'app/'.$this->path.'/style.css'
            ]
        ]);
    }
    
}
