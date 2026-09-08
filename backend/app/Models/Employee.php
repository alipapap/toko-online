<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['store_id', 'name', 'position'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function employeeDetail()
    {
        return $this->hasOne(EmployeeDetail::class);
    }

    protected static function booted(): void
    {
        static::created(function (Employee $employee) {
            $employee->employeeDetail()->create([
                'employee_number' => 'EMP-' . str_pad($employee->id, 4, '0', STR_PAD_LEFT),
                'date_of_joining' => now(),
            ]);
        });
    }
}