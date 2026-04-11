@extends('layouts.app')
@section('title','Employees')
@section('content')
<div class="pg-hdr">
  <div class="pg-hdr-l"><h1>Employees</h1><p>All staff records — create and edit only, no deletion.</p></div>
  <a href="{{ route('employees.create') }}" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Employee</a>
</div>
<div class="filter-bar">
  <form method="GET" class="filter-row">
    <div class="form-group" style="flex:2;min-width:160px;margin:0;"><label class="form-label">Search</label><input type="text" name="search" class="form-control" placeholder="Name, ID, dept..." value="{{ request('search') }}"></div>
    <div class="form-group" style="flex:1;min-width:130px;margin:0;"><label class="form-label">Department</label><select name="department" class="form-control"><option value="">All</option>@foreach($departments as $d)<option value="{{ $d }}" {{ request('department')===$d?'selected':'' }}>{{ $d }}</option>@endforeach</select></div>
    <div class="form-group" style="min-width:110px;margin:0;"><label class="form-label">Status</label><select name="status" class="form-control"><option value="">All</option><option value="active" {{ request('status')==='active'?'selected':'' }}>Active</option><option value="inactive" {{ request('status')==='inactive'?'selected':'' }}>Inactive</option></select></div>
    <div style="display:flex;gap:6px;padding-bottom:1px;"><button type="submit" class="btn btn-primary btn-sm">Filter</button><a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">Reset</a></div>
  </form>
</div>
<div class="tbl-wrap">
  <table>
    <thead><tr><th>#</th><th>Employee</th><th>ID</th><th>Department</th><th>Position</th><th>Hired</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($employees as $e)
      <tr>
        <td class="td-sm">{{ $loop->iteration }}</td>
        <td><div class="flex-c gap-2"><div class="av av-sm">{{ strtoupper(substr($e->first_name,0,1).substr($e->last_name,0,1)) }}</div><div><div class="td-name">{{ $e->full_name }}</div><div style="font-size:.72rem;color:var(--gray-400);">{{ $e->user->email }}</div></div></div></td>
        <td class="td-mono">{{ $e->employee_id }}</td>
        <td>{{ $e->department }}</td>
        <td>{{ $e->position }}</td>
        <td class="td-sm">{{ $e->hire_date->format('M d, Y') }}</td>
        <td><span class="badge {{ $e->status==='active'?'b-green':'b-gray' }}">{{ ucfirst($e->status) }}</span></td>
        <td><div class="act-grp">
          <a href="{{ route('employees.show',$e) }}" class="btn btn-secondary btn-sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></a>
          <a href="{{ route('employees.edit',$e) }}" class="btn btn-secondary btn-sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></a>
        </div></td>
      </tr>
      @empty<tr><td colspan="8"><div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><h3>No employees found</h3><p>Add your first employee to get started.</p></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $employees->links() }}</div>
@endsection
