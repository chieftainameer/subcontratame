<?php

use Illuminate\Support\Facades\Route;

$router->group(['prefix'=>'client'], function() use($router){

   $router->get('/login',[App\Http\Controllers\Frontend\AuthController::class,'login'])->name('client.login');
   $router->post('/login',[App\Http\Controllers\Frontend\AuthController::class,'login']);
   $router->get('/register',[App\Http\Controllers\Frontend\AuthController::class,'register'])->name('client.register');
   $router->post('/register',[App\Http\Controllers\Frontend\AuthController::class,'register']);
   $router->get('/reset-password',[App\Http\Controllers\Frontend\AuthController::class,'resetPassword'])->name('client.reset-password');
   $router->post('/reset-password',[App\Http\Controllers\Frontend\AuthController::class,'resetPassword']);
   $router->post('/send-password',[App\Http\Controllers\Frontend\AuthController::class,'sendPassword']);
   $router->get('/complete',[App\Http\Controllers\Frontend\AuthController::class,'completeRegistration'])->name('client.complete');
   $router->post('/complete',[App\Http\Controllers\Frontend\AuthController::class,'completeRegistration']);

   //$router->get('/:1',[App\Http\Controllers\Frontend\AuthController::class,'index']);
   $router->get('/get-province/{autonomousCommunity}',[App\Http\Controllers\Frontend\AuthController::class,'getProvince']);
   $router->get('/get-autonomous-community/{pais}',[App\Http\Controllers\Frontend\AuthController::class,'getAutonomousCommunity']);

   $router->post('/check-mail',[App\Http\Controllers\Dashboard\UsersController::class,'checkEmail']);
   

   $router->get('/projects',[App\Http\Controllers\Frontend\ProjectsController::class,'index'])->name('client.projects');

   $router->get('/departures/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'find']);

   // Authenticate
   $router->group(['prefix'=>'/', 'middleware' => ['auth', 'regular.user']], function() use($router){
      
      $router->get('/', [App\Http\Controllers\Frontend\HomeController::class, 'home'])->name('client.home');

      // Projects
      $router->group(['prefix'=>'projects'], function() use($router){
        
        //$router->get('/{id}',[App\Http\Controllers\ ::class,'index']);
        //$router->get('/datatables',[App\Http\Controllers\Frontend\ProjectsController::class,'datatables']);
        $router->get('/all',[App\Http\Controllers\Frontend\ProjectsController::class,'all']);
        $router->get('/:{id}',[App\Http\Controllers\Frontend\ProjectsController::class,'find']);
        $router->post('/store',[App\Http\Controllers\Frontend\ProjectsController::class,'store']);
        $router->post('/update/{id}',[App\Http\Controllers\Frontend\ProjectsController::class,'update']);
        $router->get('/paginate',[App\Http\Controllers\Frontend\ProjectsController::class,'paginateDepartures'])
        ->name('paginate.departues');
        //$router->get('/destroy/{id}',[App\Http\Controllers\Frontend\ProjectsController::class,'destroy']);   
        
        $router->group(['prefix'=>'favorites'], function() use($router){
           $router->get('/',[App\Http\Controllers\Frontend\FavoriteProjectsController::class,'index'])->name('client.projects.favorites');
           $router->post('/store',[App\Http\Controllers\Frontend\FavoriteProjectsController::class,'store']);
           $router->get('/destroy/{id}',[App\Http\Controllers\Frontend\FavoriteProjectsController::class,'destroy']);
        });

        // My Project
        $router->group(['prefix'=>'my-projects'], function() use($router){
         $router->get('/',[App\Http\Controllers\Frontend\MyProjectsController::class,'index'])->name('client.projects.my-projects');   
         $router->get('/all',[App\Http\Controllers\Frontend\MyProjectsController::class,'all']);
         $router->get('/find/{id}',[App\Http\Controllers\Frontend\MyProjectsController::class,'find']);
         $router->post('/store',[App\Http\Controllers\Frontend\MyProjectsController::class,'store']);
         $router->post('/update/{id}',[App\Http\Controllers\Frontend\MyProjectsController::class,'update']);
         $router->get('/get-data',[App\Http\Controllers\Frontend\MyProjectsController::class,'getData']);
         $router->get('/get-province/{autonomousCommunity}',[App\Http\Controllers\Frontend\MyProjectsController::class,'getProvince']);
         $router->get('/get-autonomous-community/{pais}',[App\Http\Controllers\Frontend\MyProjectsController::class,'getAutonomousCommunity']);
         $router->get('/get-code',[App\Http\Controllers\Frontend\MyProjectsController::class,'getCode']);
         $router->get('/checkeout',[App\Http\Controllers\Frontend\MyProjectsController::class,'checkout'])->name('client.projects.my-projects.checkout');
         $router->post('/checkout',[App\Http\Controllers\Frontend\MyProjectsController::class,'checkout']);
         $router->get('/publish',[App\Http\Controllers\Frontend\MyProjectsController::class,'publish'])->name('client.projects.my-projects.publish');
         $router->get('/destroy/{id}',[App\Http\Controllers\Frontend\MyProjectsController::class,'destroy'])->name('client.projects.my-projects.destroy');

         
        // Payment
        $router->group(['prefix'=>'payment'], function() use($router){
            $router->get('/{string}',[App\Http\Controllers\Frontend\PaymentController::class,'charge'])->name('client.projects.my-projects.payment');
            $router->post('/process-payment/{string}',[App\Http\Controllers\Frontend\PaymentController::class,'processPayment'])->name('client.projects.my-projects.process-payment');   

            // Routes Success and Cancel
            $router->get('/success',[App\Http\Controllers\Frontend\PaymentController::class,'successPayment'])->name('client.projects.my-projects.payment.success');
            $router->get('/failed',[App\Http\Controllers\Frontend\PaymentController::class,'failed'])->name('client.projects.my-projects.payment.failed');
         }); 
         
         // Departures
         $router->group(['prefix'=>'departures'], function() use($router){
            $router->get('/{project_id}',[App\Http\Controllers\Frontend\DeparturesController::class,'index'])->name('client.projects.my-projects.departures');
            $router->get('/get-data/{project_id}',[App\Http\Controllers\Frontend\DeparturesController::class,'getData']);
            $router->post('/store',[App\Http\Controllers\Frontend\DeparturesController::class,'store']);
            $router->get('/find/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'find']);
            $router->post('/update/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'update']);
            $router->get('/destroy/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'destroy']);
            $router->post('/completed/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'completed']);
            $router->post('/import',[App\Http\Controllers\Frontend\DeparturesController::class,'import'])
                 ->name('import.test');
            $router->post('/deletePartidas/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'deletePartidas'])
            ->name('delete.partidas');
            
            // Variables
            $router->group(['prefix'=>'variables'], function() use($router){
               $router->get('/{id}',[App\Http\Controllers\Frontend\VariablesController::class,'find']);
               $router->get('/destroy/{id}',[App\Http\Controllers\Frontend\VariablesController::class,'destroy']);
            });
           
         });

         // Applications
         $router->group(['prefix'=>'applications'], function() use($router){
            $router->get('/',[App\Http\Controllers\Frontend\ApplicationsController::class,'index'])->name('client.projects.my-projects.applications');
            $router->get('/export',[App\Http\Controllers\Frontend\ApplicationsController::class,'export'])->name('client.projects.my-projects.applications.export');
            $router->get('/find/{id}',[App\Http\Controllers\Frontend\VariantsController::class,'find']);
            $router->post('/rating/store',[App\Http\Controllers\Frontend\RatingController::class,'store']);
            $router->post('/filter',[App\Http\Controllers\Frontend\ApplicationsController::class,'filter'])->name('applications.filter');
         });   
        });

        // My Offers
        $router->group(['prefix'=>'my-offers'], function() use($router){
           $router->get('/',[App\Http\Controllers\Frontend\MyOffersController::class,'index'])->name('client.projects.my-offers');
           $router->get('/{id}',[App\Http\Controllers\Frontend\VariantsController::class,'find']);
           $router->post('/update/{id}',[App\Http\Controllers\Frontend\VariantsController::class,'update']);
        });

        // Departures
        $router->group(['prefix'=>'departures'], function() use($router){
            // find departures
            $router->get('/{id}',[App\Http\Controllers\Frontend\DeparturesController::class,'find']);

            // Apply Departure
            $router->group(['prefix'=>'apply'], function() use($router){
               $router->post('/store',[App\Http\Controllers\Frontend\VariantsController::class,'store']);   
               $router->post('/update/{id}',[App\Http\Controllers\Frontend\VariantsController::class,'update']);
               
            });
            
            // Comments
            $router->group(['prefix'=>'comments'], function() use($router){
               $router->get('/{id}',[App\Http\Controllers\Frontend\CommentsController::class,'find']);
               $router->post('/store',[App\Http\Controllers\Frontend\CommentsController::class,'store'])->name('client.projects.departures.comments.store');
               $router->post('/update/{id}',[App\Http\Controllers\Frontend\CommentsController::class,'update']);
               $router->post('/report/{id}',[App\Http\Controllers\Frontend\CommentsController::class,'report']);
               $router->post('/destroy/{id}',[App\Http\Controllers\Frontend\CommentsController::class,'destroy']);
            });
        });

      });

      // Notifications
      $router->group(['prefix'=>'notifications'], function() use($router){
         $router->get('/',[App\Http\Controllers\Frontend\NotificationsController::class,'index'])->name('client.notifications');
         $router->get('/destroy/{id}',[App\Http\Controllers\Frontend\NotificationsController::class,'destroy']);
      });

      // Profile
      $router->group(['prefix'=>'profile'], function() use($router){
         $router->get('/',[App\Http\Controllers\Frontend\ProfileController::class,'index'])->name('client.profile');
         $router->post('/update/{id}',[App\Http\Controllers\Frontend\ProfileController::class,'update']);
         $router->get('/get-province/{autonomousCommunity}',[App\Http\Controllers\Frontend\ProfileController::class,'getProvince']);
      });

      // Logout
      $router->get('/logout',[App\Http\Controllers\Frontend\AuthController::class,'logout'])->name('client.logout');
   });


});