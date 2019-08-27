<?php

namespace App\Models\Dentist;

use App\Models\Dentist\DentistHasUnit;
use App\Models\Dentist\DentistHasSpecialty;

use Illuminate\Database\Eloquent\Model;

class Dentist extends Model
{
    protected $connection = 'dentist';

    protected $fillable = ['specialty_id', 'name', 'rg', 'cpf'];

    public function units($id) {
        return DentistHasUnit::where('dentist_id', $id)
            ->join('units', 'units.id', '=', 'dentist_has_units.unit_id')
            ->get();
    }

    public function specialties($id) {
        return DentistHasSpecialty::where('dentist_id', $id)
            ->join('specialties', 'specialties.id', '=', 'dentist_has_specialties.specialty_id')
            ->get();
    }
}
