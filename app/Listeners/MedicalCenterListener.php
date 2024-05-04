<?php

namespace App\Listeners;

use App\Events\NewService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MedicalCenterListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewService  $event
     * @return void
     */
    public function handle(NewService $event)
    {
        switch($event->service->type == 1) {
            case 1: {
                // event(new \App\Events\MecicalCenterRequestConsultant($event->service)); 
                break;}
            case 2: {
                // event(new \App\Events\MecicalCenterRequestConsultation($event->service));
                break;}
        }
    }
}
