@extends('layouts.app')
@section('title','Violations & Discipline')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Violations & Discipline</h1><p>Progressive discipline — offense records are permanent.</p></div></div>
<div class="card" style="margin-bottom:20px;">
  <div class="card-hdr"><h2>Progressive Discipline Scale</h2></div>
  <div class="card-body" style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px;">
    @foreach([1=>'Verbal Warning',2=>'Written Warning',3=>'Final Warning',4=>'Suspension',5=>'Termination'] as $n=>$lbl)
    <div style="text-align:center;padding:12px;border-radius:10px;border:1px solid var(--gray-200);">
      <div style="width:30px;height:30px;border-radius:50%;margin:0 auto 8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;" class="{{ 'v'.$n }}">{{ $n }}</div>
      <div style="font-size:.75rem;font-weight:600;color:var(--gray-700);">{{ $lbl }}</div>
    </div>
    @endforeach
  </div>
</div>
<div class="card" style="margin-bottom:20px;">
  <div class="card-hdr"><h2>Issue Violation Notice</h2></div>
  <div class="card-body">
    <form method="POST" action="{{ route('violations.store') }}" class="form-grid">
      @csrf
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">Employee <span class="req">*</span></label><select name="employee_id" class="form-control" required><option value="">— Select —</option>@foreach($employees as $e)<option value="{{ $e->id }}">{{ $e->full_name }} — Next: Offense #{{ $e->next_offense_number }} ({{ \App\Models\Violation::getAction($e->next_offense_number) }})</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Type <span class="req">*</span></label><select name="type" class="form-control" required><option value="">— Select —</option>@foreach(['Absence','Tardiness','Insubordination','Misconduct','Negligence','Policy Violation','Other'] as $t)<option value="{{ $t }}">{{ $t }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Description <span class="req">*</span></label><input type="text" name="description" class="form-control" placeholder="Brief description of the incident" required></div>
      </div>
      <button type="submit" class="btn btn-danger" style="width:fit-content;" onclick="return confirm('Issue this violation? The employee will be notified automatically.')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>Issue Violation Notice</button>
    </form>
  </div>
</div>
<div class="tbl-wrap">
  <table>
    <thead><tr><th>Employee</th><th>Type</th><th>Offense #</th><th>Action</th><th>Description</th><th>Issued By</th><th>Date</th></tr></thead>
    <tbody>
      @forelse($violations as $v)
      <tr>
        <td><div class="flex-c gap-2"><div class="av av-sm">{{ strtoupper(substr($v->employee->first_name,0,1).substr($v->employee->last_name,0,1)) }}</div><div><div class="td-name">{{ $v->employee->full_name }}</div><div style="font-size:.7rem;color:var(--gray-400);">{{ $v->employee->department }}</div></div></div></td>
        <td><span class="badge b-gray">{{ $v->type }}</span></td>
        <td><span style="font-weight:700;font-size:1rem;color:{{ $v->offense_number>=5?'var(--gray-900)':($v->offense_number>=3?'var(--red)':'var(--amber)') }};">#{{ $v->offense_number }}</span></td>
        <td><span class="badge" style="background:{{ $v->offense_number===1?'#fef3c7':($v->offense_number===2?'#fee2e2':($v->offense_number<=4?'#ede9fe':'var(--gray-900)')) }};color:{{ $v->offense_number===1?'#92400e':($v->offense_number===2?'#991b1b':($v->offense_number<=4?'#4c1d95':'#f9fafb')) }};">{{ $v->action_taken }}</span></td>
        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.875rem;">{{ $v->description }}</td>
        <td class="td-sm">{{ $v->issuedByUser->name ?? 'System' }}</td>
        <td class="td-mono">{{ $v->date_issued->format('M d, Y') }}</td>
      </tr>
      @empty<tr><td colspan="7"><div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg></div><h3>No violations</h3><p>All employees are in good standing.</p></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $violations->links() }}</div>
@endsection
