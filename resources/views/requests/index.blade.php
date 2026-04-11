@extends('layouts.app')
@section('title','Account Requests')
@section('content')
<div class="pg-hdr">
  <div class="pg-hdr-l"><h1>Account Requests</h1><p>Review and approve or reject employee registration requests.</p></div>
</div>

@if($pending->count()>0)
<div class="alert alert-info" style="display:flex;align-items:center;justify-content:space-between;">
  <span>{{ $pending->count() }} pending registration {{ $pending->count()==1?'request':'requests' }} awaiting your review.</span>
</div>
@endif

<div class="card" style="margin-bottom:20px;">
  <div class="card-hdr">
    <h2>Pending Approvals</h2>
    <span class="badge b-amber">{{ $pending->total() }} pending</span>
  </div>
  <div class="tbl-wrap" style="border:none;border-radius:0;box-shadow:none;">
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Registered</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($pending as $user)
        <tr>
          <td>
            <div class="flex-c gap-2">
              <div class="av av-sm" style="background:var(--amber-bg);color:var(--amber);">{{ strtoupper(substr($user->name,0,1)) }}</div>
              <span class="td-name">{{ $user->name }}</span>
            </div>
          </td>
          <td class="td-mono">{{ $user->email }}</td>
          <td class="td-sm">{{ $user->created_at->format('M d, Y h:i A') }}</td>
          <td>
            <div class="act-grp">
              <form method="POST" action="{{ route('requests.approve',$user) }}" onsubmit="return confirm('Approve {{ addslashes($user->name) }}\'s account?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-success btn-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Approve
                </button>
              </form>
              <form method="POST" action="{{ route('requests.reject',$user) }}" onsubmit="return confirm('Reject {{ addslashes($user->name) }}\'s request?')">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-danger btn-sm">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Reject
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4">
            <div class="empty-state" style="padding:48px;">
              <div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg></div>
              <h3>No pending requests</h3>
              <p>All registrations have been processed.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div style="padding:14px 18px;">{{ $pending->links() }}</div>
</div>

@if($rejected->count()>0)
<div class="card">
  <div class="card-hdr"><h2>Recently Rejected</h2></div>
  <div class="tbl-wrap" style="border:none;border-radius:0;box-shadow:none;">
    <table>
      <thead><tr><th>Name</th><th>Email</th><th>Rejected</th></tr></thead>
      <tbody>
        @foreach($rejected as $user)
        <tr>
          <td><div class="flex-c gap-2"><div class="av av-sm" style="background:var(--red-bg);color:var(--red);">{{ strtoupper(substr($user->name,0,1)) }}</div><span>{{ $user->name }}</span></div></td>
          <td class="td-mono">{{ $user->email }}</td>
          <td class="td-sm">{{ $user->updated_at->format('M d, Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection
