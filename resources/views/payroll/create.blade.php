@extends('layouts.app')
@section('title','Add Payroll Record')
@section('content')
<div class="breadcrumb"><a href="{{ route('payroll.index') }}">Payroll</a><span class="bc-sep">/</span><span>Add Record</span></div>
<div class="card" style="max-width:660px;">
  <div class="card-hdr"><h2>Payroll Details</h2></div>
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
    <form method="POST" action="{{ route('payroll.store') }}" class="form-grid">
      @csrf
      <div class="form-group"><label class="form-label">Employee <span class="req">*</span></label><select id="employee_id" name="employee_id" class="form-control" required><option value="">— Select —</option>@foreach($employees as $e)<option value="{{ $e->id }}" data-salary="{{ $e->salary }}" {{ old('employee_id')==$e->id?'selected':'' }}>{{ $e->full_name }} ({{ $e->employee_id }})</option>@endforeach</select></div>
      <div class="form-grid fg-2">
        <div class="form-group"><label class="form-label">Month <span class="req">*</span></label><select name="period_month" class="form-control" required>@foreach(range(1,12) as $m)<option value="{{ $m }}" {{ old('period_month',now()->month)==$m?'selected':'' }}>{{ date('F',mktime(0,0,0,$m,1)) }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Year <span class="req">*</span></label><input type="number" name="period_year" class="form-control" value="{{ old('period_year',now()->year) }}" min="2000" max="2099" required></div>
      </div>
      <hr class="hr"><div class="form-sec">Salary & Deductions</div>
      <div class="form-grid fg-2">
        <div class="form-group"><label class="form-label">Basic Salary (₱) <span class="req">*</span></label><input type="number" id="basic_salary" name="basic_salary" class="form-control" value="{{ old('basic_salary') }}" min="0" step="0.01" required></div>
        <div class="form-group"><label class="form-label">SSS (₱)</label><input type="number" id="sss_deduction" name="sss_deduction" class="form-control" value="{{ old('sss_deduction',0) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">PhilHealth (₱)</label><input type="number" id="philhealth_deduction" name="philhealth_deduction" class="form-control" value="{{ old('philhealth_deduction',0) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">Pag-IBIG (₱)</label><input type="number" id="pagibig_deduction" name="pagibig_deduction" class="form-control" value="{{ old('pagibig_deduction',100) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">Withholding Tax (₱)</label><input type="number" id="tax_deduction" name="tax_deduction" class="form-control" value="{{ old('tax_deduction',0) }}" min="0" step="0.01"></div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option value="pending">Pending</option><option value="released">Released</option></select></div>
      </div>
      <div style="background:var(--gray-50);border:1px solid var(--gray-200);border-radius:10px;padding:14px 18px;display:flex;justify-content:space-between;align-items:center;">
        <div><div style="font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--gray-400);margin-bottom:3px;">Estimated Net Pay</div><div id="net-preview" style="font-size:1.5rem;font-weight:700;color:var(--green);">₱0.00</div></div>
        <div style="text-align:right;font-size:.8rem;color:var(--gray-500);"><div>Basic — All deductions</div><div id="ded-preview" style="color:var(--red);margin-top:2px;">— ₱0.00</div></div>
      </div>
      <div style="display:flex;gap:10px;padding-top:6px;border-top:1px solid var(--gray-100);"><button type="submit" class="btn btn-primary">Save Record</button><a href="{{ route('payroll.index') }}" class="btn btn-secondary">Cancel</a></div>
    </form>
  </div>
</div>
@endsection
