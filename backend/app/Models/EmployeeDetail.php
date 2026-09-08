<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDetail extends Model
{
    protected $fillable = ['employee_id', 'employee_number', 'date_of_joining'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}