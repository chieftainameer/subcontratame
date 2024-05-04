<?php

use Illuminate\Support\Facades\Route;

$router->group(['prefix'=>'dashboard', 'middleware' => ['auth', 'admin']], function() use($router){
    $router->get('/home',[App\Http\Controllers\Dashboard\HomeController::class,'index'])->name('dashboard');
    
    // User Management
    $router->group(['prefix'=>'users'], function() use($router){
       $router->get('/',[App\Http\Controllers\Dashboard\UsersController::class,'index'])->name('dashboard.users');
       $router->get('/datatables',[App\Http\Controllers\Dashboard\UsersController::class,'datatables']);
       $router->get('/all',[App\Http\Controllers\Dashboard\UsersController::class,'all']);
       $router->get('/:{id}',[App\Http\Controllers\Dashboard\UsersController::class,'find']);
       $router->post('/store',[App\Http\Controllers\Dashboard\UsersController::class,'store']);
       $router->post('/update/{id}',[App\Http\Controllers\Dashboard\UsersController::class,'update']);
       $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\UsersController::class,'destroy']);
       $router->get('/get-province/{autonomousCommunity}',[App\Http\Controllers\Dashboard\UsersController::class,'getProvince']);
       $router->post('/check-mail',[App\Http\Controllers\Dashboard\UsersController::class,'checkEmail']);
       $router->post('/delete',[App\Http\Controllers\Dashboard\UsersController::class,'deleteUsers'])->name('users.deleteUser');

       // Profile User
       $router->group(['prefix'=>'profile'], function() use($router){
         $router->get('/',[App\Http\Controllers\Dashboard\ProfileController::class,'index'])->name('dashboard.users.profile');   
         $router->post('/update/{id}',[App\Http\Controllers\Dashboard\ProfileController::class,'update']);
       });
       
    });

    // Dimensions Management
    $router->group(['prefix'=>'dimensions'], function() use($router){
        $router->get('/',[App\Http\Controllers\Dashboard\DimensionsController::class,'index'])->name('dashboard.dimensions');
        $router->get('/datatables',[App\Http\Controllers\Dashboard\DimensionsController::class,'datatables']);
        $router->post('/store',[App\Http\Controllers\Dashboard\DimensionsController::class,'store']);
        $router->post('/update/{id}',[App\Http\Controllers\Dashboard\DimensionsController::class,'update']);
        $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\DimensionsController::class,'destroy']);
    });

    // Departures
    $router->group(['prefix'=>'departures'], function() use($router){
       $router->get('/',[App\Http\Controllers\Dashboard\DeparturesController::class,'index'])->name('dashboard.departures');
       $router->get('/datatables',[App\Http\Controllers\Dashboard\DeparturesController::class,'datatables']);
       $router->get('/all',[App\Http\Controllers\Dashboard\DeparturesController::class,'all']);
       $router->get('/{id}',[App\Http\Controllers\Dashboard\DeparturesController::class,'find']);
       $router->post('/update/{id}',[App\Http\Controllers\Dashboard\DeparturesController::class,'update']);
       $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\DeparturesController::class,'destroy']);
       $router->post('/delete',[App\Http\Controllers\Dashboard\DeparturesController::class,'deleteDepartures'])->name('departures.deleteDepartures');
    });

    // Project Management
    $router->group(['prefix'=>'projects'], function() use($router){
       $router->get('/',[App\Http\Controllers\Dashboard\ProjectsController::class,'index'])->name('dashboard.projects');
       $router->get('/datatables',[App\Http\Controllers\Dashboard\ProjectsController::class,'datatables']);
       $router->get('/all',[App\Http\Controllers\Dashboard\ProjectsController::class,'all']);
       $router->get('/:{id}',[App\Http\Controllers\Dashboard\ProjectsController::class,'find']);
       $router->post('/store',[App\Http\Controllers\Dashboard\ProjectsController::class,'store']);
       $router->post('/update/{id}',[App\Http\Controllers\Dashboard\ProjectsController::class,'update']);
       $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\ProjectsController::class,'destroy']);
    });

    // Comment Management
    $router->group(['prefix'=>'comments'], function() use($router){
       $router->get('/',[App\Http\Controllers\Dashboard\CommentsController::class,'index'])->name('dashboard.comments');
       $router->get('/datatables',[App\Http\Controllers\Dashboard\CommentsController::class,'datatables']);
       $router->get('/{id}',[App\Http\Controllers\Dashboard\CommentsController::class,'find']);
       $router->post('/destroy-all',[App\Http\Controllers\Dashboard\CommentsController::class,'destroyAll']);
       $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\CommentsController::class,'destroy']);
    });

    // Notification Management
    $router->group(['prefix'=>'notifications'], function() use($router){
       $router->get('/',[App\Http\Controllers\Dashboard\NotificationsController::class,'index'])->name('dashboard.notifications');
       $router->get('/datatables',[App\Http\Controllers\Dashboard\NotificationsController::class,'datatables']);
       $router->get('/all',[App\Http\Controllers\Dashboard\NotificationsController::class,'all']);
       $router->get('/:{id}',[App\Http\Controllers\Dashboard\NotificationsController::class,'find']);
       $router->post('/store',[App\Http\Controllers\Dashboard\NotificationsController::class,'store']);
       $router->post('/update/{id}',[App\Http\Controllers\Dashboard\NotificationsController::class,'update']);
       $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\NotificationsController::class,'destroy']);
    });

    // Setting Management
    $router->group(['prefix'=>'settings'], function() use($router){
         $router->post('/store',[App\Http\Controllers\Dashboard\SettingsController::class,'store']);
         $router->post('/update/{id}',[App\Http\Controllers\Dashboard\SettingsController::class,'update']);
         
         // Categories
         $router->group(['prefix'=>'categories'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'index'])->name('dashboard.settings.categories');
            $router->get('/datatables',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'datatables']);
            $router->get('/all',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'all']);
            $router->get('/:{id}',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'find']);
            $router->post('/store',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'store']);
            $router->post('/update/{id}',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'update']);
            $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\Settings\CategoriesController::class,'destroy']);
         });
         // Payment Methods
         $router->group(['prefix'=>'payment-methods'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'index'])->name('dashboard.settings.payment_methods');
            $router->get('/datatables',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'datatables']);
            $router->get('/all',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'all']);
            $router->get('/:{id}',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'find']);
            $router->post('/store',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'store']);
            $router->post('/update/{id}',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'update']);
            $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\Settings\PaymentMethodsController::class,'destroy']);
         });

         // Gateway
         // $router->group(['prefix'=>'gateway'], function() use($router){
         //    $router->get('/:1',[App\Http\Controllers\Dashboard\Settings\GatewayController::class,'index'])->name('dashboard.setttings.gateway');
         // });

         // Autonomous Community
         $router->group(['prefix'=>'autonomous_community'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'index'])->name('dashboard.settings.autonomous_community');
            $router->get('/datatables',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'datatables']);
            $router->get('/all',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'all']);
            $router->get('/:{id}',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'find']);
            $router->post('/store',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'store']);
            $router->post('/update/{id}',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'update']);
            $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'destroy']);
            $router->get('/get-countries',[App\Http\Controllers\Dashboard\Settings\AutonomousCommunityController::class,'getCountries']);
         });
         // Province
         $router->group(['prefix'=>'province'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'index'])->name('dashboard.settings.province');
            $router->get('/datatables',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'datatables']);
            $router->get('/all',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'all']);
            $router->get('/:{id}',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'find']);
            $router->post('/store',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'store']);
            $router->post('/update/{id}',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'update']);
            $router->get('/destroy/{id}',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'destroy']);
            $router->get('/get-autonomous-community',[App\Http\Controllers\Dashboard\Settings\ProvinceController::class,'getAutonomousCommunity']);
         });
         // Terms And Conditions
         $router->group(['prefix'=>'terms-conditions'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'showTermsConditions'])->name('dashboard.settings.terms_conditions');
         });
         // Privacy Policies
         $router->group(['prefix'=>'privacy-policies'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'showPrivacyPolicies'])->name('dashboard.settings.privacy_policies');
         });
         // Privacy Policies
         $router->group(['prefix'=>'about'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'showAbout'])->name('dashboard.settings.about');
         });
         // Contact
         $router->group(['prefix'=>'contact'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'showContact'])->name('dashboard.settings.contact');
         });
         // Gateway Payment
         $router->group(['prefix'=>'payment-gateway'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'showPaymentGateway'])->name('dashboard.settings.payment_gateway');
         });
         // Prices
         $router->group(['prefix'=>'prices'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'showPrices'])->name('dashboard.settings.prices');
         });
         $router->group(['prefix'=>'plantilla'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\SettingsController::class,'uploadPlantilla'])->name('dashboard.settings.plantilla');
            $router->post('storePlantilla',[App\Http\Controllers\Dashboard\SettingsController::class,'storePlantilla'])->name('dashboard.settings.store.plantilla');
         });
         $router->group(['prefix'=>'pais'], function() use($router){
            $router->get('/',[App\Http\Controllers\Dashboard\Settings\PaisController::class,'index'])->name('dashboard.settings.paises');
            $router->get('/datatables',[App\Http\Controllers\Dashboard\Settings\PaisController::class,'datatables']);
            $router->post('/store',[App\Http\Controllers\Dashboard\Settings\PaisController::class,'store']);
            $router->post('/update/{id}',[App\Http\Controllers\Dashboard\Settings\PaisController::class,'update']);

         });
    });

    // Logout
    $router->get('/logout',[App\Http\Controllers\AuthController::class,'logout'])->name('dashboard.logout');
});