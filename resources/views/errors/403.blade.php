<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Access Denied — HRMS</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="err-page">
  <div style="text-align:center;max-width:420px;padding:40px 24px;">
    <div style="width:72px;height:72px;border-radius:50%;background:var(--red-bg);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--red)" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h1 style="font-size:1.5rem;font-weight:700;letter-spacing:-.02em;margin-bottom:8px;color:var(--gray-900);">Access Denied</h1>
    <p style="color:var(--gray-500);font-size:.9375rem;margin-bottom:28px;line-height:1.7;">You don't have permission to access this page. This area is restricted to administrators only.</p>
    <div style="display:flex;gap:10px;justify-content:center;">
      <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
      <a href="javascript:history.back()" class="btn btn-secondary">Go Back</a>
    </div>
    @auth
    <p style="margin-top:22px;font-size:.8rem;color:var(--gray-400);">Logged in as <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})</p>
    @endauth
  </div>
</div>
</body>
</html>
