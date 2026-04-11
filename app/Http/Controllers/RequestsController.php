<?php

namespace App\Http\Controllers;

use App\Models\HrmsNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequestsController extends Controller
{
    public function index(): View
    {
        $pending  = User::where('status', 'pending')->latest()->paginate(15);
        $rejected = User::where('status', 'rejected')->latest()->take(10)->get();
        return view('requests.index', compact('pending', 'rejected'));
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->status !== 'pending') return back()->with('error', 'Account already processed.');
        $user->update(['status' => 'active']);

        HrmsNotification::create([
            'user_id' => $user->id,
            'title'   => 'Account Approved!',
            'message' => 'Your HRMS account has been approved. You can now log in and access the system.',
            'type'    => 'success', 'read' => false,
        ]);

        return back()->with('success', "{$user->name}'s account has been approved. Please link them to an employee record.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        if ($user->status !== 'pending') return back()->with('error', 'Account already processed.');
        $user->update(['status' => 'rejected']);

        HrmsNotification::create([
            'user_id' => $user->id,
            'title'   => 'Account Request Not Approved',
            'message' => 'Your HRMS account request was not approved. ' . ($request->reason ?? 'Please contact HR for more information.'),
            'type'    => 'danger', 'read' => false,
        ]);

        return back()->with('success', "{$user->name}'s account request has been rejected.");
    }
}
