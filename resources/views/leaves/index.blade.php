@extends('layouts.app')
@section('title','My Leave Requests')
@section('content')
<div class="pg-hdr">
  <div class="pg-hdr-l"><h1>My Leave Requests</h1><p>Your personal leave application history.</p></div>
  <a href="{{ route('leaves.create') }}" class="btn btn-primary"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Apply for Leave</a>
</div>
<div class="tbl-wrap">
  <table>
    <thead><tr><th>Type</th><th>Start</th><th>End</th><th>Days</th><th>Reason</th><th>Status</th><th>Filed</th><th></th></tr></thead>
    <tbody>
      @forelse($leaves as $lv)
      <tr>
        <td><span class="badge b-gray">{{ $lv->type }}</span></td>
        <td class="td-mono">{{ $lv->start_date->format('M d, Y') }}</td>
        <td class="td-mono">{{ $lv->end_date->format('M d, Y') }}</td>
        <td style="font-weight:600;">{{ $lv->start_date->diffInDays($lv->end_date)+1 }}</td>
        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.875rem;">{{ $lv->reason }}</td>
        <td>@if($lv->status==='approved')<span class="badge b-green">Approved</span>@elseif($lv->status==='pending')<span class="badge b-amber">Pending</span>@else<span class="badge b-red">Rejected</span>@endif</td>
        <td class="td-sm">{{ $lv->created_at->format('M d, Y') }}</td>
        <td>@if($lv->status==='pending')<form method="POST" action="{{ route('leaves.destroy',$lv) }}" onsubmit="return confirm('Cancel this request?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">Cancel</button></form>@endif</td>
      </tr>
      @empty<tr><td colspan="8"><div class="empty-state"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><h3>No leave requests</h3><p>You have not applied for leave yet.</p></div></td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div style="margin-top:14px;">{{ $leaves->links() }}</div>
@endsection
