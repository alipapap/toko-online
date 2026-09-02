<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDetail;
use App\Models\Store;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('store', 'employeeDetail')->latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $stores = Store::all();
        $nextEmployeeNumber = $this->generateEmployeeNumber();
        return view('employees.create', compact('stores', 'nextEmployeeNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
        ]);

        $employee = Employee::create([
            'store_id' => $validated['store_id'],
            'name' => $validated['name'],
            'position' => $validated['position'],
        ]);

        EmployeeDetail::create([
            'employee_id' => $employee->id,
            'employee_number' => $this->generateEmployeeNumber(),
            'date_of_joining' => $validated['date_of_joining'],
        ]);

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        $employee->load('store', 'employeeDetail');
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $stores = Store::all();
        $employee->load('employeeDetail');
        return view('employees.edit', compact('employee', 'stores'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'date_of_joining' => 'required|date',
        ]);

        $employee->update([
            'store_id' => $validated['store_id'],
            'name' => $validated['name'],
            'position' => $validated['position'],
        ]);

        // employee_number TIDAK diubah saat edit, tetap sama dari awal dibuat
        EmployeeDetail::updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'employee_number' => optional($employee->employeeDetail)->employee_number ?? $this->generateEmployeeNumber(),
                'date_of_joining' => $validated['date_of_joining'],
            ]
        );

        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil diupdate.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Karyawan berhasil dihapus.');
    }

    /**
     * Generate nomor karyawan otomatis, format: EMP-001, EMP-002, dst.
     */
    private function generateEmployeeNumber(): string
    {
        $lastNumber = EmployeeDetail::orderByDesc('id')->value('employee_number');

        if (!$lastNumber || !preg_match('/(\d+)$/', $lastNumber, $matches)) {
            return 'EMP-001';
        }

        $nextNumber = (int) $matches[1] + 1;
        return 'EMP-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}