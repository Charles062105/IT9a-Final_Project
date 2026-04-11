<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\HrmsNotification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('welcome');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','string','lowercase','email','max:255','unique:'.User::class],
            'password' => ['required','confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'employee',
            'status'   => 'pending',
        ]);

        // Notify all admins about new registration request
        $admins = User::where('role','admin')->where('status','active')->get();
        foreach ($admins as $admin) {
            HrmsNotification::create([
                'user_id' => $admin->id,
                'title'   => 'New Account Request',
                'message' => "{$request->name} ({$request->email}) has submitted an account registration request.",
                'type'    => 'info',
                'read'    => false,
            ]);
        }

        return redirect()->route('welcome')
            ->with('pending_msg', 'Registration submitted! Your account is pending admin approval. You will be notified once approved.');
    }
}
