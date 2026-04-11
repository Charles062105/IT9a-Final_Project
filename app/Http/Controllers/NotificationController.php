<?php
namespace App\Http\Controllers;
use App\Models\HrmsNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $notifications = $user->hrmsNotifications()->latest()->paginate(20);
        // Mark all as read
        $user->hrmsNotifications()->where('read', false)->update(['read' => true]);
        return view('notifications.index', compact('notifications'));
    }
}
