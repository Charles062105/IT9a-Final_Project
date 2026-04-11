<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveUserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) return redirect()->route('welcome');
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->status === 'pending') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('welcome')
                ->with('pending_msg', 'Your account is pending admin approval. You will be notified once approved.');
        }

        if ($user->status === 'rejected') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('welcome')
                ->with('error', 'Your account request was not approved. Please contact HR.');
        }

        return $next($request);
    }
}
