@extends('layouts.app')
@section('title','Leave Requests')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Leave Requests</h1><p>Approve or reject employee leave applications.</p></div></div>
<div class="tab-pills">
  @foreach(['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $v=>$l)
  <a href="{{ route('admin.leaves.index',['status'=>$v]) }}" class="tab-pill {{ (request('status',$v)===$v||(!request('status')&&$v==='all'))?'active':'' }}">{{ $l }}</a>
  @endforeach
</div>
<div class="tbl-wrap">
  <table>
    <thead><tr><th>Employee</th><th>Type</th><th>Start</th><th>End</th><th>Days</th><th>Reason</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
      @forelse($leaves as $lv)
      <tr>
        <td><div class="td-name">{{ $lv->employee->full_name }}</div><div style="font-size:.72rem;color:var(--gray-400);">{{ $lv->employee->department }}</div></td>
        <td><span class="badge b-gray">{{ $lv->type }}</span></td>
        <td class="td-mono">{{ $lv->start_date->format('M d, Y') }}</td>
        <td class="td-mono">{{ $lv->end_date->format('M d, Y') }}</td>
        <td style="font-weight:600;">{{ $lv->start_date->diffInDays($lv->end_date)+1 }}</td>
        <td style="max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.875rem;">{{ $lv->reason }}</td>
        <td>@if($lv->status==='approved')<span class="badge b-green">Approved</span>@elseif($lv->status==='pending')<span class="badge b-amber">Pending</span>@else<span class="badge b-red">Rejected</span>@endif</td>
        <td>
          <div class="act-grp">
            @if($lv->status==='pending')
            <form method="POST" action="{{ route('leaves.approve',$lv) }}">@csrf @method('PATCH')<button class="btn btn-success btn-sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Approve</button></form>
            <form method="POST" action="{{ route('leaves.reject',$lv) }}">@csrf @method('PATCH')<button class="btn btn-danger btn-sm"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>Reject</button></form>
            @endif
          </div>
        </td>
      </tr>
      @empty<tr><td colspan="8"><div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/></svg></div><h3>No leave requests</h3></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $leaves->links() }}</div>
@endsection
