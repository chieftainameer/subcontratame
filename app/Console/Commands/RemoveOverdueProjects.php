<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RemoveOverdueProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'terminate-old:projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will remove all the projects whose `final_date` coincide with current date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $projects = Project::all();
     
        $projects->each(function($project){
            // if the project final_date is equal to current date then we'll remove it
            $project->final_date >= now()
                ? $this->deleteOldProjects($project)
                : null ;
        });
    }

    private function deleteOldProjects($project)
    {
        try {
            Log::info('Deleting overdue project: ' . $project->id . "\n");
            $project->delete();
            Log::info('Deleted overdue project: ' . $project->id . "\n");
        }
        catch(\Exception $e)
        {
            Log::alert("Error - deleting overdue project: " . $project->id . "\n");
            Log::error($e->getMessage() . "\n");
        }
    }
}
