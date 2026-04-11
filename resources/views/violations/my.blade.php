@extends('layouts.app')
@section('title','My Violations')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>My Violations</h1><p>Your personal disciplinary record history.</p></div></div>
@if($violations->isEmpty())
<div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div><h3>No violations on record</h3><p>You are in good standing. Keep up the great attendance and conduct!</p></div>
@else
<div class="alert alert-warning"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>You have {{ $violations->count() }} violation record(s). Please contact HR if you have concerns.</div>
<div style="display:flex;flex-direction:column;gap:14px;">
  @foreach($violations as $v)
  <div class="card">
    <div class="card-body" style="display:flex;gap:16px;align-items:flex-start;">
      <div style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0;" class="{{ 'v'.$v->offense_number }}">#{{ $v->offense_number }}</div>
      <div style="flex:1;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;flex-wrap:wrap;">
          <span style="font-size:.9375rem;font-weight:600;color:var(--gray-900);">{{ $v->action_taken }}</span>
          <span class="badge b-gray">{{ $v->type }}</span>
          <span class="td-sm">{{ $v->date_issued->format('F d, Y') }}</span>
        </div>
        <p style="font-size:.875rem;color:var(--gray-600);line-height:1.6;">{{ $v->description }}</p>
        <div style="font-size:.75rem;color:var(--gray-400);margin-top:6px;">Issued by: {{ $v->issuedByUser->name ?? 'HR Department' }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>
@endif
@endsection
