/* ==========================================
   HRMS — Main JavaScript
   ========================================== */
document.addEventListener('DOMContentLoaded', () => {

  /* ── Landing Navbar Scroll ── */
  const nav = document.getElementById('landingNav');
  if (nav) {
    window.addEventListener('scroll', () => {
      nav.classList.toggle('scrolled', window.scrollY > 30);
    }, { passive: true });
  }

  /* ── Auth Modal ── */
  const overlay   = document.getElementById('authModal');
  const btnClose  = document.getElementById('authClose');
  const tabLogin  = document.getElementById('tabLogin');
  const tabReg    = document.getElementById('tabRegister');
  const panelL    = document.getElementById('panelLogin');
  const panelR    = document.getElementById('panelRegister');

  function openModal(tab = 'login') {
    if (!overlay) return;
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
    switchTab(tab);
  }
  function closeModal() {
    if (!overlay) return;
    overlay.classList.remove('open');
    document.body.style.overflow = '';
  }
  function switchTab(t) {
    if (!tabLogin) return;
    const isL = t === 'login';
    tabLogin.classList.toggle('active', isL);
    tabReg.classList.toggle('active', !isL);
    panelL.classList.toggle('active', isL);
    panelR.classList.toggle('active', !isL);
  }

  document.querySelectorAll('[data-auth="login"]').forEach(el =>
    el.addEventListener('click', e => { e.preventDefault(); openModal('login'); }));
  document.querySelectorAll('[data-auth="register"]').forEach(el =>
    el.addEventListener('click', e => { e.preventDefault(); openModal('register'); }));
  if (btnClose) btnClose.addEventListener('click', closeModal);
  if (overlay)  overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
  if (tabLogin) tabLogin.addEventListener('click', () => switchTab('login'));
  if (tabReg)   tabReg.addEventListener('click',   () => switchTab('register'));
  document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

  /* Auto-open modal on validation error */
  if (document.getElementById('authModal') && document.querySelector('.modal-err')) {
    const isReg = document.getElementById('panelRegister')?.querySelector('.modal-err');
    openModal(isReg ? 'register' : 'login');
  }

  /* ── Live Clock ── */
  function tick() {
    const el = document.getElementById('liveClock');
    const de = document.getElementById('liveDate');
    if (!el) return;
    const now = new Date();
    el.textContent = now.toLocaleTimeString('en-PH', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:true });
    if (de) de.textContent = now.toLocaleDateString('en-PH', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
  }
  tick();
  setInterval(tick, 1000);

  /* ── Header Dropdowns ── */
  const userCard = document.getElementById('headerUser');
  const userDd   = document.getElementById('userDropdown');
  const notifBtn = document.getElementById('notifBtn');
  const notifPnl = document.getElementById('notifPanel');

  function closeAll() {
    userDd?.classList.remove('open');
    notifPnl?.classList.remove('open');
  }

  userCard?.addEventListener('click', e => {
    e.stopPropagation();
    notifPnl?.classList.remove('open');
    userDd?.classList.toggle('open');
  });
  notifBtn?.addEventListener('click', e => {
    e.stopPropagation();
    userDd?.classList.remove('open');
    notifPnl?.classList.toggle('open');
  });
  document.addEventListener('click', closeAll);

  /* ── Mobile Sidebar ── */
  const sbToggle = document.getElementById('sbToggle');
  const sidebar  = document.getElementById('appSidebar');
  sbToggle?.addEventListener('click', () => sidebar?.classList.toggle('open'));

  /* ── Time In / Out Confirmation ── */
  document.getElementById('timeInBtn')?.addEventListener('click', () => {
    if (confirm('Confirm Time In now?')) document.getElementById('timeInForm')?.submit();
  });
  document.getElementById('timeOutBtn')?.addEventListener('click', () => {
    if (confirm('Confirm Time Out now?')) document.getElementById('timeOutForm')?.submit();
  });

  /* ── Payroll Net Pay Calculator ── */
  function calcNet() {
    const g  = id => parseFloat(document.getElementById(id)?.value || 0) || 0;
    const basic = g('basic_salary');
    const deductions = g('sss_deduction') + g('philhealth_deduction') + g('pagibig_deduction') + g('tax_deduction');
    const net = basic - deductions;
    const netEl = document.getElementById('net-preview');
    const dedEl = document.getElementById('ded-preview');
    if (netEl) netEl.textContent = '₱' + net.toLocaleString('en-PH', { minimumFractionDigits: 2 });
    if (dedEl) dedEl.textContent = '— ₱' + deductions.toLocaleString('en-PH', { minimumFractionDigits: 2 });
  }
  ['basic_salary','sss_deduction','philhealth_deduction','pagibig_deduction','tax_deduction']
    .forEach(id => document.getElementById(id)?.addEventListener('input', calcNet));
  calcNet();

  /* Auto-fill salary from employee dropdown */
  document.getElementById('employee_id')?.addEventListener('change', function() {
    const sal = this.options[this.selectedIndex]?.dataset.salary;
    if (sal) { const el = document.getElementById('basic_salary'); if(el){el.value=parseFloat(sal).toFixed(2);calcNet();} }
  });

  /* ── Flash Auto Dismiss ── */
  document.querySelectorAll('[data-dismiss]').forEach(el => {
    setTimeout(() => { el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(()=>el.remove(),400); }, 4000);
  });

  /* ── Landing Counter Animation ── */
  const counters = document.querySelectorAll('[data-count]');
  if (counters.length) {
    const io = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target = parseInt(el.dataset.count);
        const suffix = el.dataset.suffix || '';
        let cur = 0;
        const step = target / 55;
        const t = setInterval(() => {
          cur += step;
          if (cur >= target) { cur = target; clearInterval(t); }
          el.textContent = Math.floor(cur).toLocaleString() + suffix;
        }, 14);
        io.unobserve(el);
      });
    }, { threshold: 0.4 });
    counters.forEach(c => io.observe(c));
  }

  /* ── Smooth scroll ── */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const t = document.querySelector(a.getAttribute('href'));
      if (t) { e.preventDefault(); t.scrollIntoView({ behavior:'smooth', block:'start' }); }
    });
  });
});
