<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendRelatedProjectsMail;
use Illuminate\Support\Facades\Mail;

class SendRelatedProjects implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $userA;
    public $project;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userA,$project)
    {
        $this->userA = $userA;
        $this->project = $project;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendRelatedProjectsMail($this->userA,$this->project);
        Mail::to($this->userA->email)->send($email);
    }
}
