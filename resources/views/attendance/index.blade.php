@extends('layouts.app')
@section('title',auth()->user()->isAdmin()?'Attendance Management':'My Attendance')
@section('content')
<div class="pg-hdr">
  <div class="pg-hdr-l"><h1>{{ auth()->user()->isAdmin()?'Attendance Management':'My Attendance' }}</h1><p>{{ auth()->user()->isAdmin()?'View, filter, and manage all employee attendance records.':'Your personal attendance log and time-in/out.' }}</p></div>
</div>
@if(auth()->user()->isAdmin())
<div class="stat-grid" style="margin-bottom:18px;">
  <div class="stat-card"><div><div class="sc-label">Present Today</div><div class="sc-val">{{ $todayPresent }}</div></div><div class="sc-icon si-gr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Absent Today</div><div class="sc-val">{{ $todayAbsent }}</div></div><div class="sc-icon si-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Late Today</div><div class="sc-val">{{ $todayLate }}</div></div><div class="sc-icon si-am"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Total Employees</div><div class="sc-val">{{ $totalEmps }}</div></div><div class="sc-icon si-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div></div>
</div>
<div class="filter-bar">
  <form method="GET" class="filter-row">
    <div class="form-group" style="flex:2;min-width:150px;margin:0;"><label class="form-label">Employee</label><select name="employee_id" class="form-control"><option value="">All</option>@foreach($employees as $e)<option value="{{ $e->id }}" {{ request('employee_id')==$e->id?'selected':'' }}>{{ $e->full_name }}</option>@endforeach</select></div>
    <div class="form-group" style="flex:1;min-width:120px;margin:0;"><label class="form-label">From</label><input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}"></div>
    <div class="form-group" style="flex:1;min-width:120px;margin:0;"><label class="form-label">To</label><input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}"></div>
    <div class="form-group" style="min-width:110px;margin:0;"><label class="form-label">Status</label><select name="status" class="form-control"><option value="">All</option>@foreach(['present'=>'Present','absent'=>'Absent','late'=>'Late','on_leave'=>'On Leave'] as $v=>$l)<option value="{{ $v }}" {{ request('status')===$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
    <div style="display:flex;gap:6px;padding-bottom:1px;"><button type="submit" class="btn btn-primary btn-sm">Filter</button><a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">Reset</a></div>
  </form>
</div>
<div class="card" style="margin-bottom:18px;">
  <div class="card-hdr"><h2>Mark Employee Absent</h2><span style="font-size:.78rem;color:var(--gray-400);">Auto-triggers violation if applicable</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('attendance.mark-absent') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end;">
      @csrf
      <div class="form-group" style="flex:2;min-width:160px;margin:0;"><label class="form-label">Employee</label><select name="employee_id" class="form-control" required><option value="">— Select —</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->full_name }} ({{ $e->employee_id }})</option>@endforeach</select></div>
      <div class="form-group" style="flex:1;min-width:120px;margin:0;"><label class="form-label">Date</label><input type="date" name="date" class="form-control" value="{{ today()->toDateString() }}" max="{{ today()->toDateString() }}" required></div>
      <div class="form-group" style="flex:2;min-width:150px;margin:0;"><label class="form-label">Remarks</label><input type="text" name="remarks" class="form-control" placeholder="Optional reason"></div>
      <div style="padding-bottom:1px;"><button type="submit" class="btn btn-danger" onclick="return confirm('Mark absent? May trigger a violation notice.')">Mark Absent</button></div>
    </form>
  </div>
</div>
@else
<div class="att-clock" style="max-width:480px;margin-bottom:22px;">
  <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.09em;color:rgba(255,255,255,.35);margin-bottom:10px;">Today</div>
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
  @else<span class="clock-status cs-wait" style="margin:14px auto;display:inline-flex;">Not yet timed in</span>@endif
  <div class="clock-actions">
    @if(!$todayRecord||!$todayRecord->time_in)
      <form id="timeInForm" method="POST" action="{{ route('attendance.time-in') }}">@csrf</form>
      <button id="timeInBtn" class="btn btn-success btn-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>Time In</button>
    @elseif($todayRecord&&!$todayRecord->time_out)
      <form id="timeOutForm" method="POST" action="{{ route('attendance.time-out') }}">@csrf</form>
      <button id="timeOutBtn" class="btn btn-danger btn-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="9" y1="9" x2="15" y2="15"/><line x1="15" y1="9" x2="9" y2="15"/></svg>Time Out</button>
    @else<button class="btn btn-secondary btn-lg" disabled style="opacity:.5;">Done for today</button>@endif
  </div>
</div>
@endif
<div class="tbl-wrap">
  <table>
    <thead><tr>@if(auth()->user()->isAdmin())<th>Employee</th>@endif<th>Date</th><th>Day</th><th>Time In</th><th>Time Out</th><th>Hours</th><th>Status</th><th>Remarks</th></tr></thead>
    <tbody>
      @forelse($attendances as $a)
      <tr>
        @if(auth()->user()->isAdmin())<td><div class="flex-c gap-2"><div class="av av-sm">{{ strtoupper(substr($a->employee->first_name,0,1).substr($a->employee->last_name,0,1)) }}</div><div><div class="td-name">{{ $a->employee->full_name }}</div><div style="font-size:.7rem;color:var(--gray-400);">{{ $a->employee->department }}</div></div></div></td>@endif
        <td class="td-mono">{{ $a->date->format('M d, Y') }}</td>
        <td class="td-sm">{{ $a->date->format('D') }}</td>
        <td class="td-mono">{{ $a->time_in?$a->time_in->format('h:i A'):'—' }}</td>
        <td class="td-mono">{{ $a->time_out?$a->time_out->format('h:i A'):'—' }}</td>
        <td style="font-weight:500;">{{ $a->time_in&&$a->time_out?number_format($a->hours_worked,1).'h':'—' }}</td>
        <td>@if($a->status==='present')<span class="badge b-green">Present</span>@elseif($a->status==='late')<span class="badge b-amber">Late</span>@elseif($a->status==='absent')<span class="badge b-red">Absent</span>@elseif($a->status==='on_leave')<span class="badge b-blue">On Leave</span>@else<span class="badge b-gray">{{ ucfirst($a->status) }}</span>@endif</td>
        <td class="td-sm">{{ $a->remarks??'—' }}</td>
      </tr>
      @empty<tr><td colspan="{{ auth()->user()->isAdmin()?8:7 }}"><div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><h3>No records</h3></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $attendances->links() }}</div>
@endsection
