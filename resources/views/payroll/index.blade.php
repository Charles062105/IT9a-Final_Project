@extends('layouts.app')
@section('title','Payroll')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Payroll</h1><p>Salary records — create and edit only, no deletion.</p></div><a href="{{ route('payroll.create') }}" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Record</a></div>
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px;">
  <div class="stat-card"><div><div class="sc-label">Gross Payroll</div><div class="sc-val">₱{{ number_format($grossTotal/1000,1) }}K</div></div><div class="sc-icon si-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Total Deductions</div><div class="sc-val">₱{{ number_format($totalDeductions/1000,1) }}K</div></div><div class="sc-icon si-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg></div></div>
  <div class="stat-card"><div><div class="sc-label">Net Payroll</div><div class="sc-val">₱{{ number_format($netTotal/1000,1) }}K</div></div><div class="sc-icon si-gr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div></div>
</div>
<div class="tbl-wrap">
  <table>
    <thead><tr><th>Employee</th><th>Period</th><th>Basic Salary</th><th>Deductions</th><th>Net Pay</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($payrolls as $p)
      <tr>
        <td><div class="td-name">{{ $p->employee->full_name }}</div><div style="font-size:.7rem;color:var(--gray-400);">{{ $p->employee->employee_id }}</div></td>
        <td class="td-mono">{{ date('F',mktime(0,0,0,$p->period_month,1)) }} {{ $p->period_year }}</td>
        <td>₱{{ number_format($p->basic_salary,2) }}</td>
        <td style="color:var(--red);">-₱{{ number_format($p->total_deductions,2) }}</td>
        <td style="font-weight:700;color:var(--green);">₱{{ number_format($p->net_pay,2) }}</td>
        <td><span class="badge {{ $p->status==='released'?'b-green':'b-amber' }}">{{ ucfirst($p->status) }}</span></td>
        <td><a href="{{ route('payroll.edit',$p) }}" class="btn btn-secondary btn-sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</a></td>
      </tr>
      @empty<tr><td colspan="7"><div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h3>No payroll records</h3></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $payrolls->links() }}</div>
@endsection
