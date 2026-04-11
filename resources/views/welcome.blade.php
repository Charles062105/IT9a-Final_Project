<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>HRMS — Human Resources Management System</title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="landing-body">

{{-- NAVBAR --}}
<nav class="l-nav" id="landingNav">
  <div class="l-brand">
    <div class="l-brand-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
    <span class="l-brand-name">HRMS</span>
  </div>
  <div class="l-nav-links">
    <a href="#features" class="l-nav-link">Features</a>
    <a href="#about" class="l-nav-link">About</a>
    <a href="#stats" class="l-nav-link">Stats</a>
  </div>
  <div class="l-nav-ctas">
    <a href="#" data-auth="login" class="btn btn-secondary btn-sm">Sign In</a>
    <a href="#" data-auth="register" class="btn btn-primary btn-sm">Get Started</a>
  </div>
</nav>

{{-- HERO --}}
<section class="hero">
  <div class="hero-content">
    <div class="hero-badge"><div class="hero-badge-dot"></div>Human Resources Management</div>
    <h1>Manage your<br>people, <span>smarter</span><br>not harder.</h1>
    <p class="hero-desc">A modern, all-in-one HR platform for Philippine organizations. Employee records, attendance, leave management, payroll, and compliance — all in one place.</p>
    <div class="hero-ctas">
      <a href="#" data-auth="register" class="btn btn-white btn-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Get Started Free</a>
      <a href="#features" class="btn btn-outline-w btn-lg"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>Learn More</a>
    </div>
  </div>
  <div class="hero-visual">
    <div class="hero-card">
      <div class="hv-label">Dashboard Overview</div>
      <div class="hv-stats">
        <div class="hv-stat"><div class="hv-val">248</div><div class="hv-name">Employees</div></div>
        <div class="hv-stat"><div class="hv-val">97%</div><div class="hv-name">Attendance Rate</div></div>
        <div class="hv-stat"><div class="hv-val">12</div><div class="hv-name">On Leave</div></div>
        <div class="hv-stat"><div class="hv-val">3</div><div class="hv-name">Pending</div></div>
      </div>
      <div style="font-size:.65rem;color:rgba(255,255,255,.3);margin-bottom:6px;text-transform:uppercase;letter-spacing:.06em;">Weekly Attendance</div>
      <div class="hv-bar">
        <div class="hv-bar-item on"></div><div class="hv-bar-item on"></div>
        <div class="hv-bar-item cy"></div><div class="hv-bar-item on"></div>
        <div class="hv-bar-item on"></div><div class="hv-bar-item"></div><div class="hv-bar-item"></div>
      </div>
    </div>
  </div>
</section>

{{-- FEATURES --}}
<section class="l-section l-section-alt" id="features">
  <div style="max-width:1200px;margin:0 auto;">
    <div class="l-label">Core Features</div>
    <h2 class="l-title">Everything your HR team needs</h2>
    <p class="l-desc">Purpose-built for Philippine organizations with DOLE compliance and real government deduction standards.</p>
    <div class="feat-grid">
      <div class="feat-card"><div class="feat-icon fi-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><h3>Employee Management</h3><p>Complete employee profiles with personal info, job details, government numbers, emergency contacts, and employment history.</p></div>
      <div class="feat-card"><div class="feat-icon fi-cy"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div><h3>Attendance Tracking</h3><p>Real-time time-in/out system with late detection, absence tracking, and automatic progressive discipline enforcement.</p></div>
      <div class="feat-card"><div class="feat-icon fi-gr"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><h3>Leave Management</h3><p>Employees apply online, admins approve instantly. Status notifications keep everyone informed automatically.</p></div>
      <div class="feat-card"><div class="feat-icon fi-am"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h3>Payroll Processing</h3><p>Auto-computed SSS, PhilHealth, and Pag-IBIG deductions with live net pay calculator and payslip records.</p></div>
      <div class="feat-card"><div class="feat-icon fi-rd"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg></div><h3>Progressive Discipline</h3><p>5-level violation system: Verbal Warning → Written Warning → Final Warning → Suspension → Termination. Auto-tracked.</p></div>
      <div class="feat-card"><div class="feat-icon fi-bl"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg></div><h3>Smart Notifications</h3><p>Real-time alerts for approvals, violations, announcements. Badge counts, dropdown previews, and full history page.</p></div>
    </div>
  </div>
</section>

{{-- STATS --}}
<section class="l-section" id="stats" style="background:var(--gray-50);">
  <div style="max-width:1200px;margin:0 auto;">
    <div class="stats-row">
      <div class="stats-item"><div class="stats-num" data-count="500" data-suffix="+">0+</div><div class="stats-lbl">Organizations</div></div>
      <div class="stats-item"><div class="stats-num" data-count="12000" data-suffix="+">0+</div><div class="stats-lbl">Employees Managed</div></div>
      <div class="stats-item"><div class="stats-num" data-count="99" data-suffix="%">0%</div><div class="stats-lbl">Uptime</div></div>
      <div class="stats-item"><div class="stats-num" data-count="24" data-suffix="/7">0/7</div><div class="stats-lbl">Support</div></div>
    </div>
  </div>
</section>

{{-- ABOUT --}}
<section class="l-section l-section-alt" id="about">
  <div style="max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;">
    <div>
      <div class="l-label">About HRMS</div>
      <h2 class="l-title">Built for the Filipino workplace</h2>
      <p style="color:var(--gray-600);line-height:1.8;margin-bottom:18px;font-size:.9375rem;">HRMS handles the unique demands of Philippine HR — from DOLE labor code compliance to SSS/PhilHealth/Pag-IBIG contributions, to the progressive discipline framework required by law.</p>
      <p style="color:var(--gray-600);line-height:1.8;margin-bottom:32px;font-size:.9375rem;">Admin approval for new accounts ensures security. Employees get personal dashboards with their own attendance, leave, and violation history.</p>
      <a href="#" data-auth="register" class="btn btn-primary btn-lg">Start for free <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>
    <div style="display:flex;flex-direction:column;gap:14px;">
      @foreach([['Admin approval for all accounts','No unauthorized access — all registrations require admin review before login is granted.'],['Automatic violation tracking','Absences automatically trigger the correct disciplinary action per DOLE guidelines.'],['Real-time notifications','Instant alerts for approvals, violations, and announcements delivered to the right person.'],['No data deletion','Employee and payroll records are preserved — only status changes allowed for compliance.']] as [$title,$desc])
      <div style="display:flex;gap:14px;padding:18px;background:var(--white);border-radius:12px;border:1px solid var(--gray-200);">
        <div style="width:26px;height:26px;border-radius:50%;background:var(--indigo-light);color:var(--indigo);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0;">✓</div>
        <div><div style="font-weight:600;font-size:.9rem;margin-bottom:3px;">{{ $title }}</div><div style="font-size:.825rem;color:var(--gray-500);line-height:1.6;">{{ $desc }}</div></div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="l-cta">
  <h2>Ready to modernize your HR?</h2>
  <p>Join hundreds of organizations managing their people with HRMS.</p>
  <div class="l-cta-btns">
    <a href="#" data-auth="register" class="btn btn-white btn-lg">Create Free Account</a>
    <a href="#" data-auth="login" class="btn btn-outline-w btn-lg">Sign In</a>
  </div>
</section>

<footer class="l-footer"><p>&copy; {{ date('Y') }} HRMS — Human Resources Management System. All rights reserved.</p></footer>

{{-- AUTH MODAL --}}
<div class="modal-overlay" id="authModal">
  <div class="modal-box" onclick="event.stopPropagation()">
    <button class="modal-close" id="authClose"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></button>
    <div class="modal-brand">
      <div class="modal-brand-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
      <div><div class="modal-brand-name">HRMS</div><div class="modal-brand-sub">Human Resources Management</div></div>
    </div>
    <div class="modal-tabs">
      <button class="modal-tab active" id="tabLogin">Sign In</button>
      <button class="modal-tab" id="tabRegister">Create Account</button>
    </div>

    {{-- Login panel --}}
    <div class="modal-panel active" id="panelLogin">
      <div class="modal-title">Welcome back</div>
      <div class="modal-sub">Sign in to your HRMS account to continue.</div>
      @if(session('pending_msg'))
        <div class="alert alert-warning modal-err" style="font-size:.8rem;padding:10px 12px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/></svg>{{ session('pending_msg') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger modal-err" style="font-size:.8rem;padding:10px 12px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/></svg>{{ session('error') }}</div>
      @endif
      @if($errors->has('email'))
        <div class="alert alert-danger modal-err" style="font-size:.8rem;padding:10px 12px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>{{ $errors->first('email') }}</div>
      @endif
      <form method="POST" action="{{ route('login') }}" class="form-grid">
        @csrf
        <div class="form-group"><label class="form-label">Email address</label><input type="email" name="email" class="form-control {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}" placeholder="you@company.com" required autofocus></div>
        <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password"></div>
        <div style="display:flex;align-items:center;gap:8px;"><input type="checkbox" name="remember" id="rem" style="width:15px;height:15px;accent-color:var(--indigo);cursor:pointer;"><label for="rem" style="font-size:.8rem;color:var(--gray-500);cursor:pointer;">Remember me</label></div>
        <button type="submit" class="btn btn-primary w-full" style="padding:11px;">Sign In</button>
      </form>
      <p style="text-align:center;font-size:.8125rem;color:var(--gray-500);margin-top:16px;">Don't have an account? <a href="#" data-auth="register">Create one</a></p>
    </div>

    {{-- Register panel --}}
    <div class="modal-panel" id="panelRegister">
      <div class="modal-title">Create account</div>
      <div class="modal-sub">After registering, your account will be reviewed by an administrator before you can log in.</div>
      @if(session('pending_msg'))
        <div class="alert alert-success modal-err" style="font-size:.8rem;padding:10px 12px;"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>{{ session('pending_msg') }}</div>
      @endif
      <form method="POST" action="{{ route('register') }}" class="form-grid">
        @csrf
        <div class="form-group"><label class="form-label">Full name</label><input type="text" name="name" class="form-control {{ $errors->has('name') ? 'err' : '' }}" value="{{ old('name') }}" placeholder="Juan dela Cruz" required></div>
        <div class="form-group"><label class="form-label">Email address</label><input type="email" name="email" class="form-control {{ $errors->has('email') ? 'err' : '' }}" value="{{ old('email') }}" placeholder="you@company.com" required></div>
        <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-control {{ $errors->has('password') ? 'err' : '' }}" placeholder="At least 8 characters" required autocomplete="new-password"></div>
        <div class="form-group"><label class="form-label">Confirm password</label><input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required></div>
        <button type="submit" class="btn btn-primary w-full" style="padding:11px;">Create Account</button>
        <p style="font-size:.75rem;color:var(--gray-400);text-align:center;line-height:1.5;">Admin approval required before first login.</p>
      </form>
      <p style="text-align:center;font-size:.8125rem;color:var(--gray-500);margin-top:12px;">Already have an account? <a href="#" data-auth="login">Sign in</a></p>
    </div>
  </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
@if(session('pending_msg') || session('error') || $errors->any())
<script>
document.addEventListener('DOMContentLoaded',()=>{
  const m=document.getElementById('authModal');
  if(m){m.classList.add('open');document.body.style.overflow='hidden';
    @if($errors->has('name') || $errors->has('password'))
    document.getElementById('tabRegister')?.click();
    @endif
  }
});
</script>
@endif
</body>
</html>
