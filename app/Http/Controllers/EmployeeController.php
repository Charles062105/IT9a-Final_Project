<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::with('user')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%$s%")
                  ->orWhere('last_name', 'like', "%$s%")
                  ->orWhere('employee_id', 'like', "%$s%")
                  ->orWhere('department', 'like', "%$s%")
                  ->orWhere('position', 'like', "%$s%");
            });
        }
        if ($request->filled('department')) $query->where('department', $request->department);
        if ($request->filled('status'))     $query->where('status', $request->status);

        $employees   = $query->paginate(15)->withQueryString();
        $departments = Employee::distinct()->pluck('department')->filter()->sort()->values();

        return view('employees.index', compact('employees', 'departments'));
    }

    public function show(Employee $employee): View
    {
        $employee->load('leaves', 'user', 'violations', 'attendances');
        return view('employees.show', compact('employee'));
    }

    public function create(): View
    {
        $users = User::where('status', 'active')->whereDoesntHave('employee')->orderBy('name')->get();
        return view('employees.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'            => ['required', 'exists:users,id', 'unique:employees,user_id'],
            'employee_id'        => ['required', 'string', 'max:20', 'unique:employees,employee_id'],
            'first_name'         => ['required', 'string', 'max:100'],
            'last_name'          => ['required', 'string', 'max:100'],
            'gender'             => ['nullable', 'in:Male,Female,Other'],
            'birth_date'         => ['nullable', 'date', 'before:today'],
            'address'            => ['nullable', 'string', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'emergency_contact'  => ['nullable', 'string', 'max:100'],
            'emergency_phone'    => ['nullable', 'string', 'max:20'],
            'civil_status'       => ['nullable', 'string', 'max:30'],
            'department'         => ['required', 'string', 'max:100'],
            'position'           => ['required', 'string', 'max:100'],
            'hire_date'          => ['required', 'date'],
            'salary'             => ['required', 'numeric', 'min:0'],
            'status'             => ['required', 'in:active,inactive'],
            'sss_number'         => ['nullable', 'string', 'max:20'],
            'philhealth_number'  => ['nullable', 'string', 'max:20'],
            'pagibig_number'     => ['nullable', 'string', 'max:20'],
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee record created successfully.');
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'        => ['required', 'string', 'max:20', 'unique:employees,employee_id,' . $employee->id],
            'first_name'         => ['required', 'string', 'max:100'],
            'last_name'          => ['required', 'string', 'max:100'],
            'gender'             => ['nullable', 'in:Male,Female,Other'],
            'birth_date'         => ['nullable', 'date', 'before:today'],
            'address'            => ['nullable', 'string', 'max:255'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'emergency_contact'  => ['nullable', 'string', 'max:100'],
            'emergency_phone'    => ['nullable', 'string', 'max:20'],
            'civil_status'       => ['nullable', 'string', 'max:30'],
            'department'         => ['required', 'string', 'max:100'],
            'position'           => ['required', 'string', 'max:100'],
            'hire_date'          => ['required', 'date'],
            'salary'             => ['required', 'numeric', 'min:0'],
            'status'             => ['required', 'in:active,inactive'],
            'sss_number'         => ['nullable', 'string', 'max:20'],
            'philhealth_number'  => ['nullable', 'string', 'max:20'],
            'pagibig_number'     => ['nullable', 'string', 'max:20'],
        ]);

        $employee->update($validated);

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee record updated successfully.');
    }
    // No destroy — business rule: employees are not deleted
}
