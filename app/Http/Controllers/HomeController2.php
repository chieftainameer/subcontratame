<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Category;
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
            //$word = request('word') ? request('word') : null;
            //$province = request('province') ? request('province') : null;
            $categories_user = auth()->user()->categories()->get();
            $categories = null;
            $categories = $categories_user->map(function($category) use($categories){
                return $category->id;
            });

            $cat_user = request('categories') ? json_decode(request('categories')) : null;
            $pay_user = request('payment') ? json_decode(request('payment')) : null;
            $query = request('query') ? json_decode(request('query')) : null;

            if(isset($query) || isset($cat_user) || isset($pay_user)){
                $projects = Project::query();

                $projects->where('status', 1)
                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                            return $query->select('id', 'first_name', 'last_name', 'company_name');
                        }]);

                if(isset($query) && !isset($cat_user) && !isset($pay_user)){
                    
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }
                    
                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(isset($query) && isset($cat_user) && !isset($pay_user)){
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });
                    
                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(isset($query) && !isset($cat_user) && isset($pay_user)){
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }

                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });
                    
                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(!isset($query) && isset($cat_user) && isset($pay_user)){
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });
                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });
                    
                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(!isset($query) && isset($cat_user) && !isset($pay_user)){

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });

                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(!isset($query) && isset($cat_user) && isset($pay_user)){

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });

                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });

                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(!isset($query) && !isset($cat_user) && isset($pay_user)){

                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });

                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
            }

            // if(isset($query) || isset($cat_user) || isset($pay_user)){
            //     if(isset($query) && !isset($cat_user) && !isset($pay_user)){
            //         if(count($query)){
            //             dd("hay uno solo");
            //         }
            //     }
            // }
            
            //dd($pay_user);
            
            // if(isset($word) || isset($province) || isset($cat_user)  || isset($pay_user)){
            //     if(isset($word) && !isset($province) && !isset($cat_user) && !isset($pay_user)){
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && isset($province) && !isset($cat_user) && !isset($pay_user)){
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhere('province_id', $province)
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && !isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && !isset($province) && !isset($cat_user) && isset($pay_user)){
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($category){
            //                             return $q->whereIn('payment_mthods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhere('province_id', $province)
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && isset($province) && isset($cat_user) && isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhere('province_id', $province)
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($category){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && !isset($cat_user) && !isset($pay_user)){
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && !isset($cat_user) && isset($pay_user)){
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orWhereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && isset($cat_user) && isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && !isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->whereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && !isset($province) && isset($cat_user) && isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && !isset($province) && !isset($cat_user) && isset($pay_user)){
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         //dd($payment);
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->whereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            // }
            // else{
            //     $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->whereHas('categories', function($q) use($categories_user){
            //                             return $q->whereIn('categories.id', $categories_user);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            // }
        }
        else{
            //$word = request('word') ? request('word') : null;
            //$province = request('province') ? request('province') : null;
            //dd(request('query'));
            //$queryFormad = '';

            $cat_user = request('categories') ? json_decode(request('categories')) : null;
            $pay_user = request('payment') ? json_decode(request('payment')) : null;
            $query = request('query') ? json_decode(request('query')) : null;
            
            //dd(isset($query) || isset($cat_user) || isset($pay_user));

            if(isset($query) || isset($cat_user) || isset($pay_user)){
                $projects = Project::query();//DB::table('projects');//Project::query();

                $projects->where('status', 1)
                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                            return $query->select('id', 'first_name', 'last_name', 'company_name');
                        }])
                        ->orderBy('created_at', 'desc')
                        ->paginate(8)
                        ->withQueryString();

                //dd($projects->get());
                if(isset($query) && !isset($cat_user) && !isset($pay_user)){
                    
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }
                    
                    // $projects->orderBy('created_at', 'desc')
                    //          ->paginate(8)
                    //          ->withQueryString();
                }
                else if(isset($query) && isset($cat_user) && !isset($pay_user)){
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });
                    
                    // $projects->orderBy('created_at', 'desc')
                    //          ->paginate(8)
                    //          ->withQueryString();
                }
                else if(isset($query) && !isset($cat_user) && isset($pay_user)){
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }

                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });
                    
                    // $projects->orderBy('created_at', 'desc')
                    //          ->paginate(8)
                    //          ->withQueryString();
                }
                else if(!isset($query) && isset($cat_user) && isset($pay_user)){
                    foreach ($query as $q) {
                        if($q->field === 'title'){
                            if($q->condition === 'AND'){
                                $projects->where('title', 'like', '%'.$q->value.'%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('title', 'like', '%'.$q->value.'%');
                            }
                        }
                        else if($q->field === 'user'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                    });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('first_name', 'like', '%' .$value. '%')
                                                 ->orWhere('last_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'company'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('user', function($query) use($value){
                                    return $query->where('compny_name', 'like', '%' .$value. '%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('user', function($query) use($value){
                                    return $query->where('company_name', 'like', '%' .$value. '%');
                                });
                            }
                        }
                        else if($q->field === 'description_project'){
                            if($q->condition === 'AND'){
                                $projects->where('short_description', 'like', '%' .$q->value. '%');
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('short_description', 'like', '%' .$q->value. '%');
                            }
                        }
                        else if($q->field === 'description_departure'){
                            if($q->condition === 'AND'){
                                $value = $q->value;
                                $projects->whereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                            else if($q->condition === 'OR'){
                                $value = $q->value;
                                $projects->orWhereHas('departures', function($query) use($value){
                                    return $query->where('description', 'like', '%'.$value.'%');
                                });
                            }
                        }
                        else if($q->field === 'execution_date'){
                            if($q->condition === 'AND'){
                                $projects->where('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('start_date', \Carbon\Carbon::parse($q->value)->format('Y-m-d'));
                            }
                        }
                        else if($q->field === 'location'){
                            if($q->condition === 'AND'){
                                $projects->where('province_id', $q->value);
                            }
                            else if($q->condition === 'OR'){
                                $projects->orWhere('province_id', $q->value);
                            }
                        }
                    }

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });
                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });
                    
                    // $projects->orderBy('created_at', 'desc')
                    //          ->paginate(8)
                    //          ->withQueryString();
                }
                else if(!isset($query) && isset($cat_user) && !isset($pay_user)){

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });

                    $projects->orderBy('created_at', 'desc')
                             ->paginate(8)
                             ->withQueryString();
                }
                else if(!isset($query) && isset($cat_user) && isset($pay_user)){

                    foreach ($cat_user as $key => $cat) {
                        $category[] = $cat->value;
                    }
                    $projects->whereHas('categories', function($query) use($category){
                        return $query->whereIn('categories.id', $category);
                    });

                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });

                    // $projects->orderBy('created_at', 'desc')
                    //          ->paginate(8)
                    //          ->withQueryString();
                }
                else if(!isset($query) && !isset($cat_user) && isset($pay_user)){

                    foreach ($pay_user as $key => $pay) {
                        $payment[] = $pay->value;
                    }
                    $projects->whereHas('payment_methods', function($query) use($payment){
                        return $query->whereIn('payment_methods.id', $payment);
                    });

                    // $projects->orderBy('created_at', 'desc')
                    //          ->paginate(8)
                    //          ->withQueryString();
                }
            }
            else{
                $projects = DB::table('projects');//Project::query();
                
                $projects->where('status', '1')
                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
                            return $query->select('id', 'first_name', 'last_name', 'company_name');
                        }])
                        ->orderBy('created_at', 'desc')
                        ->paginate(8)
                        ->withQueryString();
            }

            //dd($projects);

            // if(isset($query) || isset($cat_user) || isset($pay_user)){
            //     if(isset($query) && !isset($cat_user) && !isset($pay_user)){
                    
            //         if(count($query) === 1){
                        
            //         }
            //         else{
                        
            //             foreach ($query as $key => $value) {
            //                 if($value->condition === 'AND'){
                                
            //                 }    
            //             }
            //         }
            //     }
                
            // }

            // if(isset($word) || isset($province) || isset($cat_user)  || isset($pay_user)){
            //     if(isset($word) && !isset($province) && !isset($cat_user) && !isset($pay_user)){
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && isset($province) && !isset($cat_user) && !isset($pay_user)){
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhere('province_id', $province)
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && !isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && !isset($province) && !isset($cat_user) && isset($pay_user)){
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($category){
            //                             return $q->whereIn('payment_mthods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhere('province_id', $province)
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(isset($word) && isset($province) && isset($cat_user) && isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('code', 'like', '%' . $word . '%')
            //                         ->orWhere('title', 'like', '%' . $word . '%')
            //                         ->orWhere('short_description', 'like', '%' . $word . '%')
            //                         ->orWhere('province_id', $province)
            //                         ->orWhereHas('user', function($q) use($word){
            //                             return $q->where('first_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('last_name', 'like', '%' . $word . '%')
            //                                     ->orWhere('company_name', 'like', '%' . $word . '%');
            //                         })
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($category){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && !isset($cat_user) && !isset($pay_user)){
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && !isset($cat_user) && isset($pay_user)){
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orWhereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && isset($province) && isset($cat_user) && isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->where('province_id', $province)
            //                         ->orWhereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && !isset($province) && isset($cat_user) && !isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->whereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && !isset($province) && isset($cat_user) && isset($pay_user)){
            //         foreach ($cat_user as $key => $cat) {
            //             $category[] = $cat->value;
            //         }
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->whereHas('categories', function($q) use($category){
            //                             return $q->whereIn('categories.id', $category);
            //                         })
            //                         ->orWhereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            //     else if(!isset($word) && !isset($province) && !isset($cat_user) && isset($pay_user)){
            //         foreach ($pay_user as $key => $pay) {
            //             $payment[] = $pay->value;
            //         }
            //         $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->whereHas('payment_methods', function($q) use($payment){
            //                             return $q->whereIn('payment_methods.id', $payment);
            //                         })
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            //     }
            // }
            // else{
            //     $projects = Project::where('status', '1')
            //                         ->with(['departures', 'categories', 'payment_methods', 'user' => function($query){
            //                             return $query->select('id', 'first_name', 'last_name', 'company_name');
            //                         }])
            //                         ->orderBy('created_at', 'desc')
            //                         ->paginate(8)
            //                         ->withQueryString();
            // }

   
        }

        return view('welcome', [
            'projects' => $projects,
            'autonomous_communities' => AutonomousCommunity::where('status', 1)->get(['id','name']),
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
