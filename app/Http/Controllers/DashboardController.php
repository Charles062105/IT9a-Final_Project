<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalEmployees  = Employee::where('status', 'active')->count();
$presentToday    = Attendance::whereDate('date', today()->toDateString())->whereIn('status', ['present', 'late'])->count();
            $attendanceRate  = $totalEmployees > 0 ? round(($presentToday / $totalEmployees) * 100) : 0;
            $pendingLeaves   = Leave::where('status', 'pending')->count();
            $pendingRequests = User::where('status', 'pending')->count();
            $totalPayroll    = Payroll::where('period_month', now()->month)->where('period_year', now()->year)->sum('basic_salary');
            $totalViolations = Violation::whereMonth('date_issued', now()->month)->count();
$todayAttendance = Attendance::with('employee')->whereDate('date', today()->toDateString())->latest()->take(10)->get();
            $recentLeaves    = Leave::with('employee')->where('status', 'pending')->latest()->take(6)->get();

            return view('dashboard.index', compact(
                'totalEmployees', 'presentToday', 'attendanceRate',
                'pendingLeaves', 'pendingRequests', 'totalPayroll',
                'totalViolations', 'todayAttendance', 'recentLeaves'
            ));
        }

        // Employee dashboard
        /** @var \App\Models\Employee|null $emp */
        $emp            = $user->employee;
        $attendanceCount = 0;
        $absenceCount    = 0;
        $pendingLeaves   = 0;
        $myViolations    = 0;
        $todayRecord     = null;

        if ($emp) {
            $attendanceCount = Attendance::where('employee_id', $emp->id)
                ->whereMonth('date', now()->month)->where('status', 'present')->count();
            $absenceCount    = Attendance::where('employee_id', $emp->id)
                ->whereMonth('date', now()->month)->where('status', 'absent')->count();
            $pendingLeaves   = Leave::where('employee_id', $emp->id)->where('status', 'pending')->count();
            $myViolations    = Violation::where('employee_id', $emp->id)->count();
            $todayRecord     = $emp->today_attendance;
        }

        $recentNotifs = $user->hrmsNotifications()->latest()->take(5)->get();

        return view('dashboard.index', compact(
            'emp', 'attendanceCount', 'absenceCount',
            'pendingLeaves', 'myViolations', 'todayRecord', 'recentNotifs'
        ));
    }
}
