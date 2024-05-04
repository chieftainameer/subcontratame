<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\PublishedProjectNotification;

class PaymentController extends Controller
{
    var $request;
    var $model;
    var $folder='frontend.client.projects.my-projects.payment';
    var $path;
    
    public function __construct(Request $request) {
       $this->request = $request;
       $this->path = str_replace('.','/',$this->folder);
    }
    
    public function charge(String $product)
    {
        $user = auth()->user();
        return view($this->folder . '.index',[
            'jsControllers'=>[
                0 => 'app/'.$this->path.'/HomeController.js',
            ],
              'cssStyles' => [
                  0 => 'app/'.$this->path.'/style.css'
            ],
            'user'=>$user,
            'intent' => $user->createSetupIntent(),
            'product' => $product,
            'price' => session()->get('total'),
            'project_id' => request()->get('project')
        ]);
    }

    public function processPayment(Request $request, String $product)
    {
        $user = auth()->user();
        $paymentMethod = $request->input('payment_method');
        
        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($paymentMethod);
        try{
            $price = session()->get('total');
            $payment = $user->charge((float)$price*100, $paymentMethod);
            if($payment->status === 'succeeded'){
                session(['project'=>$request->input('project_id')]);
                $this->successPayment($payment);
            }
        }catch (\Exception $e){
            return back()->withErrors(['message' => 'Error. ' . $e->getMessage()]);
        }
        //dd($payment->status);
        return redirect('/client/projects/my-projects')->with('success', 'Su pago se ha realizado correctamente');
    }

    private function successPayment($payment){
        try {
            DB::beginTransaction();
            $model = Project::with(['departures' => function($query){
                           return $query->with('variables');
                        }, 'categories'])
                        ->find(session()->get('project'));
            
            if($model){
               foreach ($model->departures()->get() as $key => $departure) {
                  if($departure->status === '1'){
                     $departure->status = '2';
                     $departure->save();
                  }
   
                  foreach ($departure->variables()->get() as $key => $variable) {
                     if($variable->status == '1'){
                        $variable->status = '2';
                        $variable->save();
                     }
                  }
               }
   
               if($model->status === '0'){
                  $model->status = '1'; // Published
                  $model->publication_date = \Carbon\Carbon::now();
                  $model->save();
                  // Notification
                  $this->sendNotificationPublishedProject($model);
                }
               
               Transaction::create([
                  'user_id' => auth()->user()->id,
                  'reference_number' => $payment->id,
                  'amount' => session()->get('total'),
                  'status' => '2'
               ]);
   
               DB::commit();
               
               // session()->forget('numDepartures');
               // session()->forget('numVariables');
               // session()->forget('price_departure');
               // session()->forget('price_variable');
               // session()->forget('total');
      
            }
         } catch (\Exception $e) {
            DB::rollback();
            //return redirect()->to('/client/projects/my-projects/payment/')
            echo $e->getMessage();
         }

        
    }

   private function sendNotificationPublishedProject($project){
        User::with('categories')->where('role',2)->where('id', '<>', auth()->user()->id)
           ->each(function($user) use($project){
              foreach ($project->categories()->get() as $category) {
                 foreach ($user->categories()->get() as $category2) {
                    if($category->name == $category2->name){
                       $user->notify(new PublishedProjectNotification($project, $category2->name));
                    }
                 }
              }
           });
   }

    
     public function failed(Request $request, $project){
        session()->forget('numDepartures');
        session()->forget('numVariables');
        session()->forget('price_departure');
        session()->forget('price_variable');
    }
}
