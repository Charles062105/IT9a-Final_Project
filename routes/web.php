<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\ViolationController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

// Landing page
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : view('welcome');
})->name('welcome');

// ── All authenticated + active users ─────────────
Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Attendance
    Route::get('/attendance',            [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/time-in',   [AttendanceController::class, 'timeIn'])->name('attendance.time-in');
    Route::post('/attendance/time-out',  [AttendanceController::class, 'timeOut'])->name('attendance.time-out');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Profile
    Route::get('/profile',              [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',            [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password',   [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Leaves — employee only (admin blocked)
    Route::middleware('employee')->group(function () {
        Route::get('/leaves',          [LeaveController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create',   [LeaveController::class, 'create'])->name('leaves.create');
        Route::post('/leaves',         [LeaveController::class, 'store'])->name('leaves.store');
        Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');
    });

    // Violations: employee views own
    Route::get('/my-violations', [ViolationController::class, 'myViolations'])->name('violations.my');
});

// ── Admin only ────────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {

    // Employees — no delete
    Route::get('/employees',                 [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create',          [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees',                [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}',      [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}',      [EmployeeController::class, 'update'])->name('employees.update');

    // Admin attendance management
    Route::post('/attendance/mark-absent',   [AttendanceController::class, 'markAbsent'])->name('attendance.mark-absent');

    // Leaves — admin manages all
    Route::get('/admin/leaves',              [LeaveController::class, 'adminIndex'])->name('admin.leaves.index');
    Route::patch('/leaves/{leave}/approve',  [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::patch('/leaves/{leave}/reject',   [LeaveController::class, 'reject'])->name('leaves.reject');

    // Payroll — no delete
    Route::get('/payroll',                  [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/create',           [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll',                 [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payroll}/edit',   [PayrollController::class, 'edit'])->name('payroll.edit');
    Route::put('/payroll/{payroll}',        [PayrollController::class, 'update'])->name('payroll.update');

    // Violations — no delete
    Route::get('/violations',              [ViolationController::class, 'index'])->name('violations.index');
    Route::post('/violations',             [ViolationController::class, 'store'])->name('violations.store');

    // Account requests
    Route::get('/requests',                [RequestsController::class, 'index'])->name('requests.index');
    Route::patch('/requests/{user}/approve', [RequestsController::class, 'approve'])->name('requests.approve');
    Route::patch('/requests/{user}/reject',  [RequestsController::class, 'reject'])->name('requests.reject');

    // User accounts
    Route::get('/users', function () {
        $users = User::with('employee')->where('status', 'active')->latest()->paginate(20);
        return view('users.index', compact('users'));
    })->name('users.index');

    Route::patch('/users/{user}/toggle-role', function (User $user) {
        if ($user->id === Auth::id()) return back()->with('error', 'You cannot change your own role.');
        $user->update(['role' => $user->role === 'admin' ? 'employee' : 'admin']);
        return back()->with('success', "{$user->name}'s role updated.");
    })->name('users.toggle-role');
});
