@extends('layouts.app')
@section('title','Edit Payroll')
@section('content')
<div class="breadcrumb"><a href="{{ route('payroll.index') }}">Payroll</a><span class="bc-sep">/</span><span>Edit</span></div>
<div class="card" style="max-width:660px;">
  <div class="card-hdr"><h2>Edit Payroll Record</h2><span class="badge b-gray">{{ date('F',mktime(0,0,0,$payroll->period_month,1)) }} {{ $payroll->period_year }}</span></div>
  <div class="card-body">
    <form method="POST" action="{{ route('payroll.update',$payroll) }}" class="form-grid">
      @csrf @method('PUT')
      <div class="form-group"><label class="form-label">Employee</label><input type="text" class="form-control" value="{{ $payroll->employee->full_name }}" disabled style="background:var(--gray-50);color:var(--gray-500);"></div>
      <div class="form-sec">Salary & Deductions</div>
      <div class="form-grid fg-2">
        <div class="form-group"><label class="form-label">Basic Salary (₱)</label><input type="number" id="basic_salary" name="basic_salary" class="form-control" value="{{ old('basic_salary',$payroll->basic_salary) }}" min="0" step="0.01" required></div>
        <div class="form-group"><label class="form-label">SSS (₱)</label><input type="number" id="sss_deduction" name="sss_deduction" class="form-control" value="{{ old('sss_deduction',$payroll->sss_deduction) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">PhilHealth (₱)</label><input type="number" id="philhealth_deduction" name="philhealth_deduction" class="form-control" value="{{ old('philhealth_deduction',$payroll->philhealth_deduction) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">Pag-IBIG (₱)</label><input type="number" id="pagibig_deduction" name="pagibig_deduction" class="form-control" value="{{ old('pagibig_deduction',$payroll->pagibig_deduction) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">Tax (₱)</label><input type="number" id="tax_deduction" name="tax_deduction" class="form-control" value="{{ old('tax_deduction',$payroll->tax_deduction) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option value="pending" {{ $payroll->status==='pending'?'selected':'' }}>Pending</option><option value="released" {{ $payroll->status==='released'?'selected':'' }}>Released</option></select></div>
      </div>
      <div style="background:var(--gray-50);border:1px solid var(--gray-200);border-radius:10px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;">
        <div><div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:3px;">Estimated Net Pay</div><div id="net-preview" style="font-size:1.5rem;font-weight:700;color:var(--green);">₱0.00</div></div>
        <div style="text-align:right;font-size:.8rem;color:var(--gray-500);"><div>Basic — All deductions</div><div id="ded-preview" style="color:var(--red);margin-top:2px;">— ₱0.00</div></div>
      </div>
      <div style="display:flex;gap:10px;padding-top:6px;border-top:1px solid var(--gray-100);"><button type="submit" class="btn btn-primary">Update Record</button><a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a></div>
    </form>
  </div>
</div>
@endsection
