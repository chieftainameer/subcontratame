<?php

namespace App\Imports;

use App\Models\Departure;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ProjectsImport implements ToModel
{
    public function model(array $row)
    {
        return new Departure([
            'code' => $row[0],
            'description' => $row[1],
            'execution_date' => Date::excelToDateTimeObject($row[2])->format('Y-m-d'),
            'quantity' => intval($row[3]),
            'created_at' => now(),
            'updated_at' => now(),
            'status' => intval($row[6]),
            'dimension_id' => intval($row[7]),
            'visible' => intval($row[8]),
            'complete' => intval($row[9]),
            'project_id' => intval($row[10])
        ]);
    }
}