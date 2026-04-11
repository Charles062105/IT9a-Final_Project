<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(): View
    {
        $payrolls        = Payroll::with('employee')->latest()->paginate(20);
        $grossTotal      = Payroll::sum('basic_salary');
        $totalDeductions = Payroll::sum('total_deductions');
        $netTotal        = Payroll::sum('net_pay');
        return view('payroll.index', compact('payrolls', 'grossTotal', 'totalDeductions', 'netTotal'));
    }

    public function create(): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('payroll.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $v = $request->validate([
            'employee_id'          => ['required', 'exists:employees,id'],
            'period_month'         => ['required', 'integer', 'between:1,12'],
            'period_year'          => ['required', 'integer', 'min:2000', 'max:2099'],
            'basic_salary'         => ['required', 'numeric', 'min:0'],
            'sss_deduction'        => ['required', 'numeric', 'min:0'],
            'philhealth_deduction' => ['required', 'numeric', 'min:0'],
            'pagibig_deduction'    => ['required', 'numeric', 'min:0'],
            'tax_deduction'        => ['required', 'numeric', 'min:0'],
            'status'               => ['required', 'in:pending,released'],
        ]);
        Payroll::create($v);
        return redirect()->route('payroll.index')->with('success', 'Payroll record added successfully.');
    }

    public function edit(Payroll $payroll): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('payroll.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll): RedirectResponse
    {
        $v = $request->validate([
            'basic_salary'         => ['required', 'numeric', 'min:0'],
            'sss_deduction'        => ['required', 'numeric', 'min:0'],
            'philhealth_deduction' => ['required', 'numeric', 'min:0'],
            'pagibig_deduction'    => ['required', 'numeric', 'min:0'],
            'tax_deduction'        => ['required', 'numeric', 'min:0'],
            'status'               => ['required', 'in:pending,released'],
        ]);
        $payroll->update($v);
        return redirect()->route('payroll.index')->with('success', 'Payroll record updated.');
    }
    // No destroy — business rule
}
