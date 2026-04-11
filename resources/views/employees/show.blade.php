@extends('layouts.app')
@section('title', $employee->full_name)
@section('content')
<div class="breadcrumb"><a href="{{ route('employees.index') }}">Employees</a><span class="bc-sep">/</span><span>{{ $employee->full_name }}</span></div>

{{-- Profile header --}}
<div class="card" style="margin-bottom:18px;">
  <div class="card-body" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
    <div class="av av-xl">{{ strtoupper(substr($employee->first_name,0,1).substr($employee->last_name,0,1)) }}</div>
    <div style="flex:1;">
      <h2 style="font-size:1.375rem;font-weight:700;letter-spacing:-.02em;">{{ $employee->full_name }}</h2>
      <p style="font-size:.9rem;color:var(--gray-500);margin-top:3px;">{{ $employee->position }} · {{ $employee->department }}</p>
      <div style="display:flex;gap:8px;margin-top:9px;flex-wrap:wrap;">
        <span class="badge {{ $employee->status==='active'?'b-green':'b-gray' }}">{{ ucfirst($employee->status) }}</span>
        <span class="badge b-gray td-mono">{{ $employee->employee_id }}</span>
        @if($employee->violations->count()>0)
          <span class="badge b-red">{{ $employee->violations->count() }} Violation{{ $employee->violations->count()>1?'s':'' }}</span>
        @endif
      </div>
    </div>
    <a href="{{ route('employees.edit',$employee) }}" class="btn btn-secondary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Edit
    </a>
  </div>
</div>

{{-- Info grid --}}
<div class="g2" style="margin-bottom:18px;">
  <div class="card">
    <div class="card-hdr"><h2>Personal Information</h2></div>
    <div class="card-body">
      <dl style="display:grid;gap:12px;">
        @foreach([
          'Email'              => $employee->user->email,
          'Gender'             => $employee->gender ?? '—',
          'Birth Date'         => optional($employee->birth_date)->format('F d, Y') ?? '—',
          'Civil Status'       => $employee->civil_status ?? '—',
          'Phone'              => $employee->phone ?? '—',
          'Emergency Contact'  => $employee->emergency_contact ?? '—',
          'Emergency Phone'    => $employee->emergency_phone ?? '—',
          'Address'            => $employee->address ?? '—',
        ] as $lbl => $val)
        <div style="display:flex;justify-content:space-between;font-size:.875rem;border-bottom:1px solid var(--gray-100);padding-bottom:10px;gap:12px;">
          <dt style="color:var(--gray-500);flex-shrink:0;">{{ $lbl }}</dt>
          <dd style="font-weight:500;text-align:right;">{{ $val }}</dd>
        </div>
        @endforeach
      </dl>
    </div>
  </div>

  <div class="card">
    <div class="card-hdr"><h2>Job & Government Info</h2></div>
    <div class="card-body">
      <dl style="display:grid;gap:12px;">
        @foreach([
          'Employee ID'   => $employee->employee_id,
          'Department'    => $employee->department,
          'Position'      => $employee->position,
          'Hire Date'     => $employee->hire_date->format('F d, Y'),
          'Basic Salary'  => '₱' . number_format($employee->salary, 2),
          'SSS Number'    => $employee->sss_number ?? '—',
          'PhilHealth'    => $employee->philhealth_number ?? '—',
          'Pag-IBIG'      => $employee->pagibig_number ?? '—',
        ] as $lbl => $val)
        <div style="display:flex;justify-content:space-between;font-size:.875rem;border-bottom:1px solid var(--gray-100);padding-bottom:10px;gap:12px;">
          <dt style="color:var(--gray-500);flex-shrink:0;">{{ $lbl }}</dt>
          <dd style="font-weight:500;text-align:right;{{ $lbl==='Basic Salary'?'color:var(--green);':'' }}">{{ $val }}</dd>
        </div>
        @endforeach
      </dl>
    </div>
  </div>
</div>

{{-- Attendance, Leaves, Violations --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;margin-bottom:18px;">

  {{-- Recent attendance --}}
  <div class="card">
    <div class="card-hdr"><h2>Recent Attendance</h2><a href="{{ route('attendance.index') }}?employee_id={{ $employee->id }}" class="btn btn-secondary btn-sm">View all</a></div>
    <div class="tbl-wrap" style="border:none;border-radius:0;box-shadow:none;">
      <table>
        <thead><tr><th>Date</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($employee->attendances->take(6) as $a)
          <tr>
            <td class="td-mono" style="font-size:.75rem;">{{ $a->date->format('M d') }} ({{ $a->date->format('D') }})</td>
            <td>
              @if($a->status==='present')  <span class="badge b-green">Present</span>
              @elseif($a->status==='late') <span class="badge b-amber">Late</span>
              @elseif($a->status==='absent') <span class="badge b-red">Absent</span>
              @else <span class="badge b-gray">{{ ucfirst($a->status) }}</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="2"><div class="empty-state" style="padding:20px;"><p>No records yet.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Leave history --}}
  <div class="card">
    <div class="card-hdr"><h2>Leave History</h2></div>
    <div class="tbl-wrap" style="border:none;border-radius:0;box-shadow:none;">
      <table>
        <thead><tr><th>Type</th><th>Dates</th><th>Status</th></tr></thead>
        <tbody>
          @forelse($employee->leaves->take(5) as $lv)
          <tr>
            <td><span class="badge b-gray">{{ $lv->type }}</span></td>
            <td class="td-mono" style="font-size:.72rem;">{{ $lv->start_date->format('M d') }}–{{ $lv->end_date->format('M d') }}</td>
            <td>
              @if($lv->status==='approved') <span class="badge b-green">Approved</span>
              @elseif($lv->status==='pending') <span class="badge b-amber">Pending</span>
              @else <span class="badge b-red">Rejected</span>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="3"><div class="empty-state" style="padding:20px;"><p>No leaves.</p></div></td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Violations --}}
  <div class="card">
    <div class="card-hdr"><h2>Violations</h2><a href="{{ route('violations.index') }}" class="btn btn-secondary btn-sm">Issue</a></div>
    @forelse($employee->violations as $v)
    <div style="display:flex;gap:10px;padding:11px 16px;border-bottom:1px solid var(--gray-100);align-items:center;">
      <span style="width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;flex-shrink:0;background:{{ $v->offense_number===1?'#fef3c7':($v->offense_number===2?'#fee2e2':($v->offense_number<=4?'#ede9fe':'var(--gray-900)')) }};color:{{ $v->offense_number===1?'#92400e':($v->offense_number===2?'#991b1b':($v->offense_number<=4?'#4c1d95':'#f9fafb')) }};">#{{ $v->offense_number }}</span>
      <div>
        <div style="font-size:.8rem;font-weight:600;">{{ $v->action_taken }}</div>
        <div style="font-size:.72rem;color:var(--gray-400);">{{ $v->type }} · {{ $v->date_issued->format('M d, Y') }}</div>
      </div>
    </div>
    @empty
    <div class="empty-state" style="padding:28px;"><p>No violations. Good standing.</p></div>
    @endforelse
  </div>

</div>
@endsection
