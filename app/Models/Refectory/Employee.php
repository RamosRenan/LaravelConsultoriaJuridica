<?php

namespace App\Models\Refectory;

use App\Models\Refectory\EmployeeHasUnit;
use App\Models\Refectory\EmployeeHasSpecialty;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $connection = 'refectory';

    protected $fillable = ['specialty_id', 'name', 'rg', 'cpf'];

    public function units($id) {
        return EmployeeHasUnit::where('employee_id', $id)
            ->join('units', 'units.id', '=', 'employee_has_units.unit_id')
            ->get();
    }

    public function specialties($id) {
        return EmployeeHasSpecialty::where('employee_id', $id)
            ->join('specialties', 'specialties.id', '=', 'employee_has_specialties.specialty_id')
            ->get();
    }
}
