<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\UtilsService;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    var $request;
    var $model;
    var $folder='dashboard';
    var $path;
    public function __construct() {
       $this->request = request();
       $this->model = new Transaction();
       $this->path = str_replace('.','/',$this->folder);
    }

    public function index() {
       $transactions = Transaction::where('status','1')->get();
       $sales = $transactions->filter(function($transaction) { return $transaction->type=='0'; })->sum('amount');
       $distributed = $transactions->filter(function($transaction) { return $transaction->type=='1'; })->sum('amount');
       $balance = $sales - $distributed;
       return view($this->folder.'.index', [
        'jsControllers'=>[
          0 => 'app/'.$this->path.'/HomeController.js',
        ],
        'cssStyles' => [
            0 => 'app/'.$this->path.'/style.css'
        ],
        'data' => [
            'users' => UtilsService::number_kmb(User::count()),
            'sales' => UtilsService::number_kmb($sales),
            'orders' => UtilsService::number_kmb($transactions->filter(function($transaction) { return $transaction->type=='0'; })->count()),
            'balance' => UtilsService::number_kmb($balance),
        ]
       ]);
    }

    public function datatables() {
         $data = $this->model->with('user')->select('*')->orderBy('created_at', 'desc');
         return Datatables::eloquent($data)->make(true);
     }

    
    public function details($code) {
        $transaction = Transaction::where('id',$code)->with(['order' => function($query){
            return $query->with('orderitems')->select('*');
        }])->first();
        
        return view($this->folder.'.transactions.details',[
         'jsControllers'=>[
           0 => 'app/'.$this->path.'/HomeController.js',
         ],
         'cssStyles' => [
             0 => 'app/'.$this->path.'/style.css'
         ],
         'icon' => 'compass',
         'title' => 'Transactions - Details',
         'transaction' => $transaction
        ]);
     }

    
    public function management($code) {
        $transaction = Transaction::where('id',$code)->with(['order' => function($query) {
            return $query->with('orderitems')->select('*');
        }])->first();
        
        return view($this->folder.'.transactions.management',[
         'jsControllers'=>[
           0 => 'app/'.$this->path.'/HomeController.js',
         ],
         'cssStyles' => [
             0 => 'app/'.$this->path.'/style.css'
         ],
         'icon' => 'compass',
         'title' => 'Transactions - Management',
         'transaction' => $transaction,
        ]);
     }

    
    public function approve($code) {
        try {
            DB::beginTransaction();
            $transaction = Transaction::where('id', $code)->with('order')->first();
            $transaction->update(['status' => '1']);
            if($transaction->type == '0') { 
                // Compra
                $user = User::find($transaction->user_id);
                $newBalance = $user->balance + $transaction->amount;
                $user->update(['balance' => $newBalance]);
                // Generate rewards
                    $transaction = Transaction::where('id', $code)->with('order')->first();
                    $order = $transaction->order;
                    $order->payment_status = $transaction->status == '1' ? 1 : 0;
                    $order->save();
            }
            else{ // Is Purshase
                $transaction->order->update(['payment_status' => 1]);
            }
            DB::commit();
            return $this->successResponse([
               'err' => false,
               'message' => 'Transacctión aprobada correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
               'err' => true,
               'message' => 'Problemas al aprobar transacción. ' . $e->getMessage()
            ]);
        }
    }

    public function cancel($code){
        try {
            DB::beginTransaction();
           $transaction_user = Transaction::where('id',$code)->first();
           if($transaction_user->type === '0'){ // Is Purshase
               Transaction::where('referred_order',$transaction_user->order_id)->update(['status' => '2']);
           }
           $transaction_user->update(['status' => '2']);
            DB::commit();
            return $this->successResponse([
               'err' => false,
               'message' => 'Transacción cancelada correctamente.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse([
               'err' => true,
               'message' => 'Problemas al aprobar transacción.'
            ]);
        }
    }

    public function logout() {
        if(auth()->guard('admin')->check()) {
            auth()->guard('admin')->logout();
        }
        else if(auth()->guard('enterprise')->check()) {
            auth()->guard('enterprise')->logout();
        }
        else if(auth()->guard('medical')->check()) {
            auth()->guard('medical')->logout();
        }
        else if(auth()->guard('web')->check()) {
            auth()->guard('web')->logout();
        }
        //auth()->guard('web')->logout();
        return redirect('/');
    }
}
