@extends('layouts.app')
@section('title','Edit Employee')
@section('content')
<div class="breadcrumb"><a href="{{ route('employees.index') }}">Employees</a><span class="bc-sep">/</span><a href="{{ route('employees.show',$employee) }}">{{ $employee->full_name }}</a><span class="bc-sep">/</span><span>Edit</span></div>
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Edit Employee</h1><p>Update the record for {{ $employee->full_name }}.</p></div></div>
<div class="card">
  <div class="card-hdr"><h2>Employee Information</h2><span class="badge b-gray td-mono">{{ $employee->employee_id }}</span></div>
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
    <form method="POST" action="{{ route('employees.update',$employee) }}" class="form-grid">
      @csrf @method('PUT')
      <div class="form-sec">Personal Details</div>
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">First name <span class="req">*</span></label><input type="text" name="first_name" class="form-control {{ $errors->has('first_name')?'err':'' }}" value="{{ old('first_name',$employee->first_name) }}" required></div>
        <div class="form-group"><label class="form-label">Last name <span class="req">*</span></label><input type="text" name="last_name" class="form-control {{ $errors->has('last_name')?'err':'' }}" value="{{ old('last_name',$employee->last_name) }}" required></div>
        <div class="form-group"><label class="form-label">Gender</label><select name="gender" class="form-control"><option value="">— Select —</option>@foreach(['Male','Female','Other'] as $g)<option value="{{ $g }}" {{ old('gender',$employee->gender)===$g?'selected':'' }}>{{ $g }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Birth date</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date',optional($employee->birth_date)->format('Y-m-d')) }}"></div>
        <div class="form-group"><label class="form-label">Civil status</label><select name="civil_status" class="form-control">@foreach(['Single','Married','Widowed','Separated'] as $cs)<option value="{{ $cs }}" {{ old('civil_status',$employee->civil_status)===$cs?'selected':'' }}>{{ $cs }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$employee->phone) }}"></div>
        <div class="form-group" style="grid-column:span 2;"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ old('address',$employee->address) }}"></div>
        <div class="form-group"><label class="form-label">Emergency contact</label><input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact',$employee->emergency_contact) }}"></div>
        <div class="form-group"><label class="form-label">Emergency phone</label><input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone',$employee->emergency_phone) }}"></div>
      </div>
      <hr class="hr">
      <div class="form-sec">Job Details</div>
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">Employee ID <span class="req">*</span></label><input type="text" name="employee_id" class="form-control" value="{{ old('employee_id',$employee->employee_id) }}" required></div>
        <div class="form-group"><label class="form-label">Department <span class="req">*</span></label><select name="department" class="form-control" required>@foreach(['Engineering','Finance','Human Resources','IT','Marketing','Operations','Sales','Admin'] as $d)<option value="{{ $d }}" {{ old('department',$employee->department)===$d?'selected':'' }}>{{ $d }}</option>@endforeach</select></div>
        <div class="form-group"><label class="form-label">Position <span class="req">*</span></label><input type="text" name="position" class="form-control" value="{{ old('position',$employee->position) }}" required></div>
        <div class="form-group"><label class="form-label">Hire date <span class="req">*</span></label><input type="date" name="hire_date" class="form-control" value="{{ old('hire_date',$employee->hire_date->format('Y-m-d')) }}" required></div>
        <div class="form-group"><label class="form-label">Basic salary (₱) <span class="req">*</span></label><input type="number" name="salary" class="form-control" value="{{ old('salary',$employee->salary) }}" min="0" step="0.01" required></div>
        <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control"><option value="active" {{ old('status',$employee->status)==='active'?'selected':'' }}>Active</option><option value="inactive" {{ old('status',$employee->status)==='inactive'?'selected':'' }}>Inactive</option></select></div>
      </div>
      <hr class="hr">
      <div class="form-sec">Government Numbers</div>
      <div class="form-grid fg-3">
        <div class="form-group"><label class="form-label">SSS Number</label><input type="text" name="sss_number" class="form-control" value="{{ old('sss_number',$employee->sss_number) }}"></div>
        <div class="form-group"><label class="form-label">PhilHealth</label><input type="text" name="philhealth_number" class="form-control" value="{{ old('philhealth_number',$employee->philhealth_number) }}"></div>
        <div class="form-group"><label class="form-label">Pag-IBIG</label><input type="text" name="pagibig_number" class="form-control" value="{{ old('pagibig_number',$employee->pagibig_number) }}"></div>
      </div>
      <div style="display:flex;gap:10px;padding-top:6px;border-top:1px solid var(--gray-100);margin-top:4px;">
        <button type="submit" class="btn btn-primary">Update Employee</button>
        <a href="{{ route('employees.show',$employee) }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
