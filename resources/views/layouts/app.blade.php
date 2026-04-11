<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Dashboard') — HRMS</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="app-wrap">

{{-- SIDEBAR --}}
<aside class="sidebar" id="appSidebar">
  <div class="sb-brand">
    <div class="sb-logo"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
    <div><div class="sb-name">HRMS</div><div class="sb-sub">Human Resources</div></div>
  </div>

  <nav class="sb-section">
    <div class="sb-section-lbl">Main</div>
    <a href="{{ route('dashboard') }}" class="nav-item {{ Route::is('dashboard') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard
    </a>
    <a href="{{ route('attendance.index') }}" class="nav-item {{ Route::is('attendance.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Attendance
    </a>
    @if(!auth()->user()->isAdmin())
    <a href="{{ route('leaves.index') }}" class="nav-item {{ Route::is('leaves.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>My Leaves
    </a>
    <a href="{{ route('violations.my') }}" class="nav-item {{ Route::is('violations.my') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>My Violations
    </a>
    @endif
    <a href="{{ route('notifications.index') }}" class="nav-item {{ Route::is('notifications.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>Notifications
      @php $uc=auth()->user()->hrmsNotifications()->where('read',false)->count(); @endphp
      @if($uc>0)<span class="nav-badge">{{ $uc }}</span>@endif
    </a>
  </nav>

  @if(auth()->user()->isAdmin())
  <nav class="sb-section">
    <div class="sb-section-lbl">HR Management</div>
    <a href="{{ route('employees.index') }}" class="nav-item {{ Route::is('employees.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Employees
    </a>
    <a href="{{ route('admin.leaves.index') }}" class="nav-item {{ Route::is('admin.leaves.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Leave Requests
    </a>
    <a href="{{ route('payroll.index') }}" class="nav-item {{ Route::is('payroll.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>Payroll
    </a>
    <a href="{{ route('violations.index') }}" class="nav-item {{ Route::is('violations.index') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>Violations
    </a>
    <a href="{{ route('requests.index') }}" class="nav-item {{ Route::is('requests.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>Account Requests
      @php $pr=\App\Models\User::where('status','pending')->count(); @endphp
      @if($pr>0)<span class="nav-badge">{{ $pr }}</span>@endif
    </a>
    <a href="{{ route('users.index') }}" class="nav-item {{ Route::is('users.*') ? 'active' : '' }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>User Accounts
    </a>
  </nav>
  @endif
</aside>

{{-- MAIN --}}
<div class="main-wrap">

  {{-- HEADER --}}
  <header class="app-header">
    <div class="flex-c gap-2">
      <button id="sbToggle" style="display:none;width:34px;height:34px;border:none;background:none;cursor:pointer;border-radius:7px;align-items:center;justify-content:center;color:var(--gray-600);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <span class="header-title">@yield('title','Dashboard')</span>
    </div>
    <div class="header-right">
      {{-- Notifications --}}
      <div class="notif-wrap">
        <button class="notif-btn" id="notifBtn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          @if(auth()->user()->hrmsNotifications()->where('read',false)->count()>0)<span class="notif-dot"></span>@endif
        </button>
        <div class="notif-panel" id="notifPanel">
          <div class="notif-hdr"><h3>Notifications</h3><a href="{{ route('notifications.index') }}">View all</a></div>
          @forelse(auth()->user()->hrmsNotifications()->latest()->take(5)->get() as $n)
          <div class="notif-item {{ !$n->read ? 'unread' : '' }}">
            @if(!$n->read)<div class="notif-udot"></div>@else<div style="width:8px;"></div>@endif
            <div><div class="notif-text">{{ $n->title }}</div><div class="notif-time">{{ $n->created_at->diffForHumans() }}</div></div>
          </div>
          @empty
          <div style="padding:20px;text-align:center;font-size:.8rem;color:var(--gray-400);">No notifications</div>
          @endforelse
        </div>
      </div>
      {{-- User card --}}
      <div class="user-wrap">
        <div class="user-card" id="headerUser">
          <div class="user-av">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
          <div>
            <div class="user-name">{{ explode(' ',auth()->user()->name)[0] }}</div>
            <div class="user-role">{{ auth()->user()->isAdmin()?'Administrator':'Employee' }}</div>
          </div>
          <div class="user-chevron"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></div>
        </div>
        <div class="user-dropdown" id="userDropdown">
          <div class="dd-hdr"><div class="dd-name">{{ auth()->user()->name }}</div><div class="dd-email">{{ auth()->user()->email }}</div></div>
          <a href="{{ route('profile.edit') }}" class="dd-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>My Profile</a>
          @if(!auth()->user()->isAdmin())
          <a href="{{ route('leaves.create') }}" class="dd-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Apply for Leave</a>
          @endif
          <div class="dd-sep"></div>
          <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="dd-item danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Sign Out</button>
          </form>
        </div>
      </div>
    </div>
  </header>

  {{-- Flash messages --}}
  @if(session('success')||session('error')||session('warning')||session('info'))
  <div style="padding:14px 28px 0;">
    @if(session('success'))<div class="alert alert-success" data-dismiss><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert alert-danger" data-dismiss><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>{{ session('error') }}</div>@endif
    @if(session('warning'))<div class="alert alert-warning" data-dismiss><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/></svg>{{ session('warning') }}</div>@endif
    @if(session('info'))<div class="alert alert-info" data-dismiss><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ session('info') }}</div>@endif
  </div>
  @endif

  <main class="page-body">@yield('content')</main>
</div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>
