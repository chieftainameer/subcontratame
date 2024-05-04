<?php 
namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\User;
class UserExport implements FromCollection, WithHeadings {

    public function collection() {
        $data = User::select('*')
                     ->where('id','!=',auth()->user()->id)
                     ->where('role',3)
                     ->where('enterprise_id',auth()->user()->enterprise_id);
        return $data->get()->map(function($user) {
            return [
                $user->id,
                $user->dni,
                $user->name,
                $user->last_name,
                $user->email,
                $user->phone,
                $user->address,
                $user->health_insurance,
                $user->blood_type,
                $user->cp,
                $user->statuc == '1'?__('Current'):__('Expired')
            ];
        });
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('DNI'),
            __('Name'),
            __('Last name'),
            __('Email'),
            __('Phone'),
            __('Address'),
            __('Health Insurance'),
            __('Blood Type'),
            __('CP'),
            __('Status')
        ];
    }
}