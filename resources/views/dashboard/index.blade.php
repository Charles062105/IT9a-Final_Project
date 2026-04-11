@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="pg-hdr">
  <div class="pg-hdr-l">
    <h1>Good {{ now()->hour<12?'morning':(now()->hour<17?'afternoon':'evening') }}, {{ explode(' ',auth()->user()->name)[0] }} 👋</h1>
    <p>{{ now()->format('l, F d, Y') }}</p>
  </div>
  @if(auth()->user()->isAdmin())
  <div style="display:flex;gap:8px;">
    <a href="{{ route('requests.index') }}" class="btn btn-secondary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>Requests
      @php $pr=\App\Models\User::where('status','pending')->count(); @endphp
      @if($pr>0)<span style="background:var(--red);color:#fff;font-size:.6rem;font-weight:700;padding:1px 7px;border-radius:99px;margin-left:2px;">{{ $pr }}</span>@endif
    </a>
    <a href="{{ route('employees.create') }}" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Employee</a>
  </div>
  @else
  <a href="{{ route('leaves.create') }}" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Apply for Leave</a>
  @endif
</div>

@if(auth()->user()->isAdmin())
<div class="stat-grid">
  <div class="stat-card"><div><div class="sc-label">Total Employees</div><div class="sc-val">{{ $totalEmployees }}</div><div class="sc-sub">Active records</div></div><div class="sc-icon si-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Present Today</div><div class="sc-val">{{ $presentToday }}</div><div class="sc-sub">{{ $attendanceRate }}% attendance rate</div></div><div class="sc-icon si-gr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Pending Leaves</div><div class="sc-val">{{ $pendingLeaves }}</div><div class="sc-sub">Awaiting approval</div></div><div class="sc-icon si-am"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">New Requests</div><div class="sc-val">{{ $pendingRequests }}</div><div class="sc-sub">Registrations</div></div><div class="sc-icon si-bl"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Monthly Payroll</div><div class="sc-val">₱{{ number_format($totalPayroll/1000,1) }}K</div><div class="sc-sub">Gross this month</div></div><div class="sc-icon si-cy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Violations</div><div class="sc-val">{{ $totalViolations }}</div><div class="sc-sub">This month</div></div><div class="sc-icon si-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg></div></div>
</div>
@if($pendingRequests>0)
<div class="alert alert-info" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;">
  <span>{{ $pendingRequests }} account registration {{ $pendingRequests==1?'request':'requests' }} awaiting your approval.</span>
  <a href="{{ route('requests.index') }}" class="btn btn-primary btn-sm">Review Now</a>
</div>
@endif
<div class="g2">
  <div class="card">
    <div class="card-hdr"><h2>Today's Attendance</h2><a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">View all</a></div>
    <div class="tbl-wrap" style="border:none;border-radius:0;box-shadow:none;">
      <table><thead><tr><th>Employee</th><th>Time In</th><th>Status</th></tr></thead><tbody>
        @forelse($todayAttendance as $a)
        <tr>
          <td><div class="flex-c gap-2"><div class="av av-sm">{{ strtoupper(substr($a->employee->first_name,0,1).substr($a->employee->last_name,0,1)) }}</div><span class="td-name">{{ $a->employee->full_name }}</span></div></td>
          <td class="td-mono">{{ $a->time_in?$a->time_in->format('h:i A'):'—' }}</td>
          <td>@if($a->status==='present')<span class="badge b-green">Present</span>@elseif($a->status==='late')<span class="badge b-amber">Late</span>@elseif($a->status==='absent')<span class="badge b-red">Absent</span>@else<span class="badge b-gray">{{ ucfirst($a->status) }}</span>@endif</td>
        </tr>
        @empty<tr><td colspan="3"><div class="empty-state" style="padding:24px;"><p>No records yet today.</p></div></td></tr>
        @endforelse
      </tbody></table>
    </div>
  </div>
  <div class="card">
    <div class="card-hdr"><h2>Pending Leave Requests</h2><a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary btn-sm">View all</a></div>
    <div class="tbl-wrap" style="border:none;border-radius:0;box-shadow:none;">
      <table><thead><tr><th>Employee</th><th>Type</th><th>Dates</th><th></th></tr></thead><tbody>
        @forelse($recentLeaves as $lv)
        <tr>
          <td class="td-name">{{ $lv->employee->full_name }}</td>
          <td><span class="badge b-gray">{{ $lv->type }}</span></td>
          <td class="td-mono" style="font-size:.75rem;">{{ $lv->start_date->format('M d')}}–{{ $lv->end_date->format('M d') }}</td>
          <td>
            <div class="act-grp">
              <form method="POST" action="{{ route('leaves.approve',$lv) }}">@csrf @method('PATCH')<button class="btn btn-success btn-sm">✓</button></form>
              <form method="POST" action="{{ route('leaves.reject',$lv) }}">@csrf @method('PATCH')<button class="btn btn-danger btn-sm">✕</button></form>
            </div>
          </td>
        </tr>
        @empty<tr><td colspan="4"><div class="empty-state" style="padding:20px;"><p>No pending requests.</p></div></td></tr>
        @endforelse
      </tbody></table>
    </div>
  </div>
</div>

@else
{{-- Employee dashboard --}}
<div class="stat-grid">
  <div class="stat-card"><div><div class="sc-label">Days Present</div><div class="sc-val">{{ $attendanceCount }}</div><div class="sc-sub">This month</div></div><div class="sc-icon si-gr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Absences</div><div class="sc-val">{{ $absenceCount }}</div><div class="sc-sub">This month</div></div><div class="sc-icon si-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Pending Leaves</div><div class="sc-val">{{ $pendingLeaves }}</div><div class="sc-sub">Awaiting approval</div></div><div class="sc-icon si-am"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Violations</div><div class="sc-val">{{ $myViolations }}</div><div class="sc-sub">Total recorded</div></div><div class="sc-icon si-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg></div></div>
</div>
<div class="g2">
  <div class="att-clock">
    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:rgba(255,255,255,.35);margin-bottom:10px;">Today's Attendance</div>
    <div class="clock-time" id="liveClock">--:--:--</div>
    <div class="clock-date" id="liveDate">Loading...</div>
    @if($todayRecord)
      <div class="clock-times">
        <div class="clock-t-item"><div class="clock-t-lbl">Time In</div><div class="clock-t-val in">{{ $todayRecord->time_in?$todayRecord->time_in->format('h:i A'):'—' }}</div></div>
        <div style="width:1px;background:rgba(255,255,255,.1);"></div>
        <div class="clock-t-item"><div class="clock-t-lbl">Time Out</div><div class="clock-t-val out">{{ $todayRecord->time_out?$todayRecord->time_out->format('h:i A'):'—' }}</div></div>
      </div>
      @if($todayRecord->time_out)<span class="clock-status cs-out">Day Complete</span>
      @elseif($todayRecord->status==='absent')<span class="clock-status cs-out">Marked Absent</span>
      @else<span class="clock-status cs-in">Currently In</span>@endif
    @else
      <span class="clock-status cs-wait" style="margin:14px auto;display:inline-flex;">Not yet timed in</span>
    @endif
    <div class="clock-actions">
      @if(!$todayRecord||!$todayRecord->time_in)
        <form id="timeInForm" method="POST" action="{{ route('attendance.time-in') }}">@csrf</form>
        <button id="timeInBtn" class="btn btn-success btn-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Time In</button>
      @elseif($todayRecord&&!$todayRecord->time_out)
        <form id="timeOutForm" method="POST" action="{{ route('attendance.time-out') }}">@csrf</form>
        <button id="timeOutBtn" class="btn btn-danger btn-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>Time Out</button>
      @else
        <button class="btn btn-secondary btn-lg" disabled style="opacity:.5;">Day Complete</button>
      @endif
      <a href="{{ route('attendance.index') }}" class="btn btn-dark btn-lg" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.15);color:rgba(255,255,255,.8);">View History</a>
    </div>
  </div>
  <div class="card">
    <div class="card-hdr"><h2>Recent Notifications</h2><a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm">All</a></div>
    @forelse($recentNotifs as $n)
    <div style="display:flex;gap:10px;padding:13px 18px;border-bottom:1px solid var(--gray-100);{{ !$n->read?'background:#f8f9ff;':'' }}">
      <div style="width:8px;height:8px;border-radius:50%;margin-top:6px;flex-shrink:0;background:{{ $n->type==='danger'?'var(--red)':($n->type==='warning'?'var(--amber)':($n->type==='success'?'var(--green)':'var(--blue)')) }};"></div>
      <div><div style="font-size:.8rem;font-weight:500;color:var(--gray-900);">{{ $n->title }}</div><div style="font-size:.75rem;color:var(--gray-500);margin-top:2px;">{{ Str::limit($n->message,75) }}</div><div style="font-size:.7rem;color:var(--gray-400);margin-top:3px;">{{ $n->created_at->diffForHumans() }}</div></div>
    </div>
    @empty<div class="empty-state" style="padding:32px;"><p>No notifications yet.</p></div>
    @endforelse
  </div>
</div>
@endif
@endsection
