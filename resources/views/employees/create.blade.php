@extends('layouts.app')
@section('title','Add Employee')
@section('content')
<div class="breadcrumb"><a href="{{ route('employees.index') }}">Employees</a><span class="bc-sep">/</span><span>Add</span></div>
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Add New Employee</h1><p>Fill in all required fields to create an employee record.</p></div></div>
<div class="card">
  <div class="card-hdr"><h2>Employee Information</h2></div>
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg><div><strong>Please fix:</strong><ul style="margin-top:4px;padding-left:16px;">@foreach($errors->all() as $e)<li style="font-size:.8rem;">{{ $e }}</li>@endforeach</ul></div></div>@endif
    <form method="POST" action="{{ route('employees.store') }}" class="form-grid">
      @csrf
      <div class="form-sec">Personal Details</div>
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">First name <span class="req">*</span></label><input type="text" name="first_name" class="form-control {{ $errors->has('first_name')?'err':'' }}" value="{{ old('first_name') }}" placeholder="Juan" required></div>
        <div class="form-group"><label class="form-label">Last name <span class="req">*</span></label><input type="text" name="last_name" class="form-control {{ $errors->has('last_name')?'err':'' }}" value="{{ old('last_name') }}" placeholder="dela Cruz" required></div>
        <div class="form-group"><label class="form-label">Gender</label><select name="gender" class="form-control"><option value="">— Select —</option>@foreach(['Male','Female','Other'] as $g)<option value="{{ $g }}" {{ old('gender')===$g?'selected':'' }}>{{ $g }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Birth date</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date') }}"></div>
        <div class="form-group"><label class="form-label">Civil status</label><select name="civil_status" class="form-control"><option value="">— Select —</option>@foreach(['Single','Married','Widowed','Separated'] as $cs)<option value="{{ $cs }}" {{ old('civil_status')===$cs?'selected':'' }}>{{ $cs }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="09XX-XXX-XXXX"></div>
        <div class="form-group" style="grid-column:span 2;"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Street, City, Province"></div>
        <div class="form-group"><label class="form-label">Emergency contact</label><input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}"></div>
        <div class="form-group"><label class="form-label">Emergency phone</label><input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone') }}"></div>
      </div>
      <hr class="hr">
      <div class="form-sec">Job Details</div>
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">Employee ID <span class="req">*</span></label><input type="text" name="employee_id" class="form-control {{ $errors->has('employee_id')?'err':'' }}" value="{{ old('employee_id') }}" placeholder="EMP-0001" required></div>
        <div class="form-group"><label class="form-label">Department <span class="req">*</span></label><select name="department" class="form-control {{ $errors->has('department')?'err':'' }}" required><option value="">— Select —</option>@foreach(['Engineering','Finance','Human Resources','IT','Marketing','Operations','Sales','Admin'] as $d)<option value="{{ $d }}" {{ old('department')===$d?'selected':'' }}>{{ $d }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Position <span class="req">*</span></label><input type="text" name="position" class="form-control {{ $errors->has('position')?'err':'' }}" value="{{ old('position') }}" placeholder="Software Engineer" required></div>
        <div class="form-group"><label class="form-label">Hire date <span class="req">*</span></label><input type="date" name="hire_date" class="form-control {{ $errors->has('hire_date')?'err':'' }}" value="{{ old('hire_date') }}" required></div>
        <div class="form-group"><label class="form-label">Basic salary (₱) <span class="req">*</span></label><input type="number" name="salary" class="form-control {{ $errors->has('salary')?'err':'' }}" value="{{ old('salary') }}" placeholder="25000" min="0" step="0.01" required></div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option value="active" {{ old('status','active')==='active'?'selected':'' }}>Active</option><option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option></select></div>
      </div>
      <hr class="hr">
      <div class="form-sec">Government Numbers</div>
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">SSS Number</label><input type="text" name="sss_number" class="form-control" value="{{ old('sss_number') }}" placeholder="XX-XXXXXXX-X"></div>
        <div class="form-group"><label class="form-label">PhilHealth</label><input type="text" name="philhealth_number" class="form-control" value="{{ old('philhealth_number') }}"></div>
        <div class="form-group"><label class="form-label">Pag-IBIG</label><input type="text" name="pagibig_number" class="form-control" value="{{ old('pagibig_number') }}"></div>
      </div>
      <hr class="hr">
      <div class="form-sec">System Account</div>
      <div class="form-group" style="max-width:420px;">
        <label class="form-label">Link to user account <span class="req">*</span></label>
        <select name="user_id" class="form-control {{ $errors->has('user_id')?'err':'' }}" required><option value="">— Select approved user —</option>@foreach($users as $u)<option value="{{ $u->id }}" {{ old('user_id')==$u->id?'selected':'' }}>{{ $u->name }} ({{ $u->email }})</option>@endforeach</select>
        <span class="form-hint">Only active users without an employee record are shown.</span>
      </div>
      <div style="display:flex;gap:10px;padding-top:6px;border-top:1px solid var(--gray-100);margin-top:4px;">
        <button type="submit" class="btn btn-primary">Save Employee</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
