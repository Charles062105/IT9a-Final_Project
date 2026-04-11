@extends('layouts.app')
@section('title','Apply for Leave')
@section('content')
<div class="breadcrumb"><a href="{{ route('leaves.index') }}">My Leaves</a><span class="bc-sep">/</span><span>Apply</span></div>
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Apply for Leave</h1><p>Submit a leave application for admin approval.</p></div></div>
<div class="card" style="max-width:580px;">
  <div class="card-hdr"><h2>Leave Application Form</h2></div>
  <div class="card-body">
    @if($errors->any())<div class="alert alert-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg><div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div></div>@endif
    <form method="POST" action="{{ route('leaves.store') }}" class="form-grid">
      @csrf
      <div class="form-group"><label class="form-label">Leave type <span class="req">*</span></label><select name="type" class="form-control {{ $errors->has('type')?'err':'' }}" required><option value="">— Select —</option>@foreach(['Vacation','Sick','Emergency','Maternity','Paternity','Bereavement','Other'] as $t)<option value="{{ $t }}" {{ old('type')===$t?'selected':'' }}>{{ $t }}</option>@endforeach</select></div>
      <div class="form-grid fg-2">
        <div class="form-group"><label class="form-label">Start date <span class="req">*</span></label><input type="date" name="start_date" class="form-control {{ $errors->has('start_date')?'err':'' }}" value="{{ old('start_date') }}" required></div>
        <div class="form-group"><label class="form-label">End date <span class="req">*</span></label><input type="date" name="end_date" class="form-control {{ $errors->has('end_date')?'err':'' }}" value="{{ old('end_date') }}" required></div>
      </div>
      <div class="form-group"><label class="form-label">Reason <span class="req">*</span></label><textarea name="reason" class="form-control {{ $errors->has('reason')?'err':'' }}" placeholder="Describe the reason..." required>{{ old('reason') }}</textarea></div>
      <div style="display:flex;gap:10px;padding-top:6px;border-top:1px solid var(--gray-100);">
        <button type="submit" class="btn btn-primary">Submit Application</button>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
