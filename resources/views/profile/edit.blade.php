@extends('layouts.app')
@section('title','My Profile')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>My Profile</h1><p>Manage your account info and change your password.</p></div></div>
<div class="g2" style="align-items:start;">
  <div style="display:flex;flex-direction:column;gap:18px;">
    <div class="card">
      <div class="card-hdr"><h2>Account Information</h2></div>
      <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}" class="form-grid">
          @csrf @method('PATCH')
          <div style="display:flex;align-items:center;gap:14px;padding-bottom:16px;border-bottom:1px solid var(--gray-100);">
            <div class="av av-xl">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
            <div>
              <div style="font-size:1.125rem;font-weight:700;">{{ auth()->user()->name }}</div>
              <div style="font-size:.875rem;color:var(--gray-400);margin-top:2px;">{{ auth()->user()->email }}</div>
              <span class="badge {{ auth()->user()->isAdmin()?'b-indigo':'b-blue' }}" style="margin-top:7px;">{{ auth()->user()->isAdmin()?'Administrator':'Employee' }}</span>
            </div>
          </div>
          <div class="form-group"><label class="form-label">Full Name <span class="req">*</span></label><input type="text" name="name" class="form-control {{ $errors->has('name')?'err':'' }}" value="{{ old('name',auth()->user()->name) }}" required>@error('name')<span class="err-msg">{{ $message }}</span>@enderror</div>
          <div class="form-group"><label class="form-label">Email Address <span class="req">*</span></label><input type="email" name="email" class="form-control {{ $errors->has('email')?'err':'' }}" value="{{ old('email',auth()->user()->email) }}" required>@error('email')<span class="err-msg">{{ $message }}</span>@enderror</div>
          <button type="submit" class="btn btn-primary" style="width:fit-content;">Save Changes</button>
        </form>
      </div>
    </div>
    <div class="card">
      <div class="card-hdr"><h2>Change Password</h2></div>
      <div class="card-body">
        <form method="POST" action="{{ route('profile.password') }}" class="form-grid">
          @csrf @method('PATCH')
          <div class="form-group"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" placeholder="Your current password" required></div>
          <div class="form-group"><label class="form-label">New Password</label><input type="password" name="password" class="form-control" placeholder="At least 8 characters" required></div>
          <div class="form-group"><label class="form-label">Confirm New Password</label><input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required></div>
          <button type="submit" class="btn btn-secondary" style="width:fit-content;">Update Password</button>
        </form>
      </div>
    </div>
  </div>
  <div style="display:flex;flex-direction:column;gap:18px;">
    @if($emp)
    <div class="card">
      <div class="card-hdr"><h2>Employee Record</h2><span class="badge {{ $emp->status==='active'?'b-green':'b-gray' }}">{{ ucfirst($emp->status) }}</span></div>
      <div class="card-body">
        <dl style="display:grid;gap:12px;">
          @foreach(['Employee ID'=>$emp->employee_id,'Department'=>$emp->department,'Position'=>$emp->position,'Hire Date'=>$emp->hire_date->format('F d, Y'),'Phone'=>$emp->phone??'—','Civil Status'=>$emp->civil_status??'—'] as $lbl=>$val)
          <div style="display:flex;justify-content:space-between;font-size:.875rem;border-bottom:1px solid var(--gray-100);padding-bottom:10px;"><dt style="color:var(--gray-500);">{{ $lbl }}</dt><dd style="font-weight:500;">{{ $val }}</dd></div>
          @endforeach
          <div style="display:flex;justify-content:space-between;font-size:.875rem;"><dt style="color:var(--gray-500);">Total Violations</dt><dd style="font-weight:600;color:{{ $emp->violations->count()>0?'var(--red)':'var(--green)' }};">{{ $emp->violations->count() }}</dd></div>
        </dl>
      </div>
    </div>
    @if($emp->violations->count()>0)
    <div class="card">
      <div class="card-hdr"><h2>My Violation History</h2></div>
      @foreach($emp->violations as $v)
      <div style="display:flex;gap:12px;padding:12px 18px;border-bottom:1px solid var(--gray-100);">
        <span class="badge" style="background:{{ $v->offense_number===1?'#fef3c7':($v->offense_number===2?'#fee2e2':($v->offense_number<=4?'#ede9fe':'var(--gray-900)')) }};color:{{ $v->offense_number===1?'#92400e':($v->offense_number===2?'#991b1b':($v->offense_number<=4?'#4c1d95':'#f9fafb')) }};">#{{ $v->offense_number }}</span>
        <div><div style="font-size:.8125rem;font-weight:600;">{{ $v->action_taken }}</div><div style="font-size:.75rem;color:var(--gray-400);">{{ $v->type }} · {{ $v->date_issued->format('M d, Y') }}</div></div>
      </div>
      @endforeach
    </div>
    @endif
    @else
    <div class="card"><div class="card-body"><div class="empty-state" style="padding:40px;"><h3>No employee record linked</h3><p>Contact HR to link your account.</p></div></div></div>
    @endif
  </div>
</div>
@endsection
