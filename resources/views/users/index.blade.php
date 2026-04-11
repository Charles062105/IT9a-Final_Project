@extends('layouts.app')
@section('title','User Accounts')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>User Accounts</h1><p>Active system accounts and role management.</p></div></div>
<div class="tbl-wrap">
  <table>
    <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Employee Record</th><th>Joined</th><th>Actions</th></tr></thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <td>
          <div class="flex-c gap-2">
            <div class="av av-sm" style="{{ $user->isAdmin()?'background:var(--indigo-light);color:var(--indigo);':'' }}">{{ strtoupper(substr($user->name,0,1)) }}</div>
            <span class="td-name">{{ $user->name }}</span>
          </div>
        </td>
        <td class="td-mono">{{ $user->email }}</td>
        <td><span class="badge {{ $user->isAdmin()?'b-indigo':'b-blue' }}">{{ ucfirst($user->role) }}</span></td>
        <td>
          @if($user->employee)
            <a href="{{ route('employees.show',$user->employee) }}" style="font-size:.875rem;">{{ $user->employee->full_name }}</a>
          @else
            <span class="text-muted" style="font-size:.875rem;">— not linked —</span>
          @endif
        </td>
        <td class="td-sm">{{ $user->created_at->format('M d, Y') }}</td>
        <td>
          @if($user->id !== auth()->id())
            <form method="POST" action="{{ route('users.toggle-role',$user) }}" onsubmit="return confirm('Change {{ addslashes($user->name) }} to {{ $user->isAdmin() ? "Employee" : "Admin" }}?')">

              @csrf @method('PATCH')
              <button type="submit" class="btn btn-secondary btn-sm">{{ $user->isAdmin()?'Make Employee':'Make Admin' }}</button>
            </form>
          @else
            <button class="btn btn-secondary btn-sm" disabled style="opacity:.4;">You</button>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $users->links() }}</div>
@endsection
