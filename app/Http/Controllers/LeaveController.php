<?php

namespace App\Http\Controllers;

use App\Models\HrmsNotification;
use App\Models\Leave;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LeaveController extends Controller
{
    // Employee: view own
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $emp  = $user->employee;
        $leaves = $emp
            ? Leave::where('employee_id', $emp->id)->latest()->paginate(15)
            : collect()->paginate(0);
        return view('leaves.index', compact('leaves'));
    }

    // Admin: view all
    public function adminIndex(Request $request): View
    {
        $query = Leave::with('employee')->latest();
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }
        $leaves    = $query->paginate(20)->withQueryString();
        $employees = Employee::orderBy('first_name')->get();
        return view('leaves.admin', compact('leaves', 'employees'));
    }

    // Employee: show apply form
    public function create(): View
    {
        return view('leaves.create');
    }

    // Employee: submit
    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $emp  = $user->employee;
        if (!$emp) return back()->with('error', 'Your account is not linked to an employee record. Please contact HR.');

        $validated = $request->validate([
            'type'       => ['required', 'string', 'max:50'],
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
            'reason'     => ['required', 'string', 'max:500'],
        ]);

        $validated['employee_id'] = $emp->id;
        $validated['status']      = 'pending';
        Leave::create($validated);

        return redirect()->route('leaves.index')
            ->with('success', 'Leave application submitted. Awaiting admin approval.');
    }

    // Admin: approve
    public function approve(Leave $leave): RedirectResponse
    {
        if ($leave->status !== 'pending') return back()->with('warning', 'Request already processed.');
        $leave->update(['status' => 'approved']);

        if ($leave->employee->user_id) {
            HrmsNotification::create([
                'user_id' => $leave->employee->user_id,
                'title'   => 'Leave Request Approved',
                'message' => "Your {$leave->type} leave (" . Carbon::parse($leave->start_date)->format('M d') . "–" . Carbon::parse($leave->end_date)->format('M d, Y') . ") has been approved.",
                'type'    => 'success', 'read' => false,
            ]);
        }
        return back()->with('success', "Leave approved for {$leave->employee->full_name}.");
    }

    // Admin: reject
    public function reject(Leave $leave): RedirectResponse
    {
        if ($leave->status !== 'pending') return back()->with('warning', 'Request already processed.');
        $leave->update(['status' => 'rejected']);

        if ($leave->employee->user_id) {
            HrmsNotification::create([
                'user_id' => $leave->employee->user_id,
                'title'   => 'Leave Request Rejected',
                'message' => "Your {$leave->type} leave request was not approved. Please contact HR for more information.",
                'type'    => 'warning', 'read' => false,
            ]);
        }
        return back()->with('success', "Leave rejected for {$leave->employee->full_name}.");
    }

    // Employee: cancel own pending
    public function destroy(Leave $leave): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $emp  = $user->employee;
        if (!$emp || $leave->employee_id !== $emp->id) abort(403);
        if ($leave->status !== 'pending') return back()->with('error', 'Only pending requests can be cancelled.');
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave request cancelled.');
    }
}
