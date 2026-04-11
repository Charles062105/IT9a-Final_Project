@extends('layouts.app')
@section('title','Notifications')
@section('content')
<div class="pg-hdr"><div class="pg-hdr-l"><h1>Notifications</h1><p>All your alerts, approvals, and system messages.</p></div></div>
<div class="card">
  @forelse($notifications as $n)
  <div style="display:flex;gap:14px;padding:16px 22px;border-bottom:1px solid var(--gray-100);{{ !$n->read?'background:#f8f9ff;':'' }}">
    <div style="width:38px;height:38px;border-radius:9px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:{{ $n->type==='danger'?'var(--red-bg)':($n->type==='warning'?'var(--amber-bg)':($n->type==='success'?'var(--green-bg)':'var(--blue-bg)')) }};color:{{ $n->type==='danger'?'var(--red)':($n->type==='warning'?'var(--amber)':($n->type==='success'?'var(--green)':'var(--blue)')) }};">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
    </div>
    <div style="flex:1;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
        <span style="font-size:.9rem;font-weight:600;color:var(--gray-900);">{{ $n->title }}</span>
        @if(!$n->read)<span class="badge b-blue" style="font-size:.6rem;">New</span>@endif
      </div>
      <div style="font-size:.875rem;color:var(--gray-600);line-height:1.6;">{{ $n->message }}</div>
      <div style="font-size:.72rem;color:var(--gray-400);margin-top:5px;">{{ $n->created_at->format('F d, Y \a\t h:i A') }} · {{ $n->created_at->diffForHumans() }}</div>
    </div>
  </div>
  @empty
  <div class="empty-state" style="padding:60px;"><div class="es-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></div><h3>No notifications</h3><p>You are all caught up!</p></div>
  @endforelse
  <div style="padding:14px 18px;">{{ $notifications->links() }}</div>
</div>
@endsection
