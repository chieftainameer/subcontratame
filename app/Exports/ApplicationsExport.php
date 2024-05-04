<?php

namespace App\Exports;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ApplicationsExport implements FromView, ShouldAutoSize
{
    public $project_id;
    public function __construct($id){
        $this->project_id = $id;
    }
    public function view():View 
    {
        $project = Project::where('id', $this->project_id)
                            ->with(['departures' => function($query){
                                return $query->with(['variants']);
                            }])->first();
        
        return view('exports.applications-export', ['project' => $project]);
    }
}
