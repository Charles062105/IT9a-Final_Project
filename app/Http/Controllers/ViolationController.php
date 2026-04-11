<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Violation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ViolationController extends Controller
{
    // Admin: all violations
    public function index(): View
    {
        $violations = Violation::with('employee', 'issuedByUser')->latest()->paginate(20);
        $employees  = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('violations.index', compact('violations', 'employees'));
    }

    // Employee: view own violations
    public function myViolations(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $emp  = $user->employee;
        $violations = $emp
            ? Violation::where('employee_id', $emp->id)->latest()->get()
            : collect();
        return view('violations.my', compact('violations', 'emp'));
    }

    // Admin: issue new violation
    public function store(Request $request): RedirectResponse
    {
        $v = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'type'        => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        $emp        = Employee::findOrFail($v['employee_id']);
        $offenseNum = Violation::where('employee_id', $emp->id)->count() + 1;
        $action     = Violation::getAction($offenseNum);

        Violation::create([
            'employee_id'    => $emp->id,
            'type'           => $v['type'],
            'offense_number' => $offenseNum,
            'description'    => $v['description'],
            'action_taken'   => $action,
            'issued_by'      => Auth::id(),
            'date_issued'    => today(),
        ]);

        if ($emp->user_id) {
            HrmsNotification::create([
                'user_id' => $emp->user_id,
                'title'   => "Violation Notice — {$action}",
                'message' => "A {$v['type']} violation has been recorded against you (Offense #{$offenseNum}). Action: {$action}. Please report to HR.",
                'type'    => $offenseNum >= 5 ? 'danger' : ($offenseNum >= 3 ? 'warning' : 'info'),
                'read'    => false,
            ]);
        }

        return redirect()->route('violations.index')
            ->with('success', "{$action} issued to {$emp->full_name} (Offense #{$offenseNum}).");
    }
    // No destroy — business rule
}
