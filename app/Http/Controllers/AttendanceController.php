<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\HrmsNotification;
use App\Models\Violation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $query = Attendance::with('employee')->latest('date')->latest('id');
            if ($request->filled('employee_id')) $query->where('employee_id', $request->employee_id);
            if ($request->filled('date_from'))   $query->whereDate('date', '>=', $request->date_from);
            if ($request->filled('date_to'))     $query->whereDate('date', '<=', $request->date_to);
            if ($request->filled('status'))      $query->where('status', $request->status);

            $attendances  = $query->paginate(25)->withQueryString();
            $employees    = Employee::where('status', 'active')->orderBy('first_name')->get();
            $todayPresent = Attendance::whereDate('date', today()->toDateString())->whereIn('status', ['present', 'late'])->count();
            $todayAbsent  = Attendance::whereDate('date', today()->toDateString())->where('status', 'absent')->count();
            $todayLate    = Attendance::whereDate('date', today()->toDateString())->where('status', 'late')->count();
            $totalEmps    = Employee::where('status', 'active')->count();

            return view('attendance.index', compact(
                'attendances', 'employees', 'todayPresent', 'todayAbsent', 'todayLate', 'totalEmps'
            ));
        }

        $emp         = $user->employee;
        $todayRecord = $emp?->today_attendance;
        $attendances = $emp
            ? Attendance::where('employee_id', $emp->id)->latest('date')->paginate(20)
            : collect()->paginate(0);

        return view('attendance.index', compact('attendances', 'emp', 'todayRecord'));
    }

    public function timeIn(): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $emp  = $user->employee;
        if (!$emp) return back()->with('error', 'No employee record linked to your account.');

        $existing = Attendance::where('employee_id', $emp->id)->whereDate('date', today()->toDateString())->first();
        if ($existing?->time_in) return back()->with('warning', 'You have already timed in today.');

        $now    = now();
        $cutoff = today()->setHour(8)->setMinute(1);
        $status = $now->gt($cutoff) ? 'late' : 'present';

        Attendance::updateOrCreate(
            ['employee_id' => $emp->id, 'date' => today()->toDateString()],
            ['time_in' => $now, 'status' => $status]
        );

        return back()->with('success', 'Time in recorded at ' . $now->format('h:i A') . ($status === 'late' ? ' (Late)' : '') . '.');
    }

    public function timeOut(): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $emp  = $user->employee;
        if (!$emp) return back()->with('error', 'No employee record linked.');

        $record = Attendance::where('employee_id', $emp->id)->whereDate('date', today()->toDateString())->first();
        if (!$record?->time_in)  return back()->with('error', 'You have not timed in yet today.');
        if ($record->time_out)   return back()->with('warning', 'You have already timed out today.');

        $record->update(['time_out' => now()]);
        return back()->with('success', 'Time out recorded at ' . now()->format('h:i A') . '.');
    }

    public function markAbsent(Request $request): RedirectResponse
    {
        $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'date'        => ['required', 'date', 'before_or_equal:today'],
            'remarks'     => ['nullable', 'string', 'max:255'],
        ]);

        $emp = Employee::findOrFail($request->employee_id);

        $record = Attendance::updateOrCreate(
            ['employee_id' => $emp->id, 'date' => $request->date],
            ['status' => 'absent', 'time_in' => null, 'time_out' => null, 'remarks' => $request->remarks]
        );

        $this->handleAbsenceViolation($emp, $request->date);

        return back()->with('success', "{$emp->full_name} marked absent for " . \Carbon\Carbon::parse($request->date)->format('M d, Y') . '.');
    }

    private function handleAbsenceViolation(Employee $emp, string $date): void
    {
        // Count absences in last 30 days
        $recentAbsences = Attendance::where('employee_id', $emp->id)
            ->where('status', 'absent')
            ->whereDate('date', '>=', now()->subDays(30)->toDateString())
            ->count();

        // Issue violation at 1st, 2nd, 3rd, 5th absence
        if (!in_array($recentAbsences, [1, 2, 3, 5])) return;

        $offenseNum  = Violation::where('employee_id', $emp->id)->where('type', 'Absence')->count() + 1;
        $action      = Violation::getAction($offenseNum);

        Violation::create([
            'employee_id'    => $emp->id,
            'type'           => 'Absence',
            'offense_number' => $offenseNum,
            'description'    => "Unexcused absence on " . \Carbon\Carbon::parse($date)->format('F d, Y') . ". This is offense #{$offenseNum} for absences.",
            'action_taken'   => $action,
            'issued_by'      => Auth::id(),
            'date_issued'    => today(),
        ]);

        if ($emp->user_id) {
            $msg = match($offenseNum) {
                1 => "You received a Verbal Warning for your absence on " . \Carbon\Carbon::parse($date)->format('M d, Y') . ".",
                2 => "You received a Written Warning. This is your 2nd absence offense. Please ensure regular attendance.",
                3, 4 => "FINAL WARNING: Your absences have resulted in a Final Warning/Suspension notice. Please report to HR immediately.",
                default => "Your continued absences have resulted in a Termination notice. Please report to HR immediately.",
            };
            HrmsNotification::create([
                'user_id' => $emp->user_id,
                'title'   => "Violation Notice — {$action}",
                'message' => $msg,
                'type'    => $offenseNum >= 5 ? 'danger' : ($offenseNum >= 3 ? 'warning' : 'info'),
                'read'    => false,
            ]);
        }
    }
}
