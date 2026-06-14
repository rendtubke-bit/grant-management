<?php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/auth.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: ' . roleHome(authRole()));
    exit;
}

$stats = [
    ['value'=>'150+', 'label_ar'=>'منحة بحثية ممولة',   'label_en'=>'Funded Research Grants',    'icon'=>'bi-award-fill',        'color'=>'#6366f1'],
    ['value'=>'48',   'label_ar'=>'مشروع نشط',           'label_en'=>'Active Research Projects',  'icon'=>'bi-journal-code',      'color'=>'#10b981'],
    ['value'=>'73M',  'label_ar'=>'ريال تمويل إجمالي',   'label_en'=>'SAR Total Funding',         'icon'=>'bi-cash-stack',        'color'=>'#f59e0b'],
    ['value'=>'320+', 'label_ar'=>'باحث ومتخصص',         'label_en'=>'Researchers & Specialists', 'icon'=>'bi-people-fill',       'color'=>'#ef4444'],
];
$features = [
    ['icon'=>'bi-grid-fill',         'color'=>'#6366f1', 'ar'=>'لوحة تحكم ذكية',        'en'=>'Smart Dashboard',        'desc_ar'=>'إحصائيات وتحليلات لحظية بتصورات بيانية متقدمة', 'desc_en'=>'Real-time stats & advanced data visualizations'],
    ['icon'=>'bi-shield-check',       'color'=>'#10b981', 'ar'=>'أمان وصلاحيات متقدمة', 'en'=>'Advanced Security',       'desc_ar'=>'نظام صلاحيات متعدد المستويات مع سجل مراجعة كامل', 'desc_en'=>'Multi-level permissions with full audit trail'],
    ['icon'=>'bi-cash-stack',         'color'=>'#f59e0b', 'ar'=>'إدارة الميزانية',       'en'=>'Budget Management',      'desc_ar'=>'تتبع المصروفات والميزانيات بدقة عالية في الوقت الفعلي', 'desc_en'=>'Track expenses & budgets in real-time with precision'],
    ['icon'=>'bi-bar-chart-fill',     'color'=>'#ef4444', 'ar'=>'تقارير وتحليلات',       'en'=>'Reports & Analytics',    'desc_ar'=>'تقارير قابلة للتخصيص وتصدير بصيغ متعددة', 'desc_en'=>'Customizable reports exportable in multiple formats'],
    ['icon'=>'bi-building-fill',      'color'=>'#8b5cf6', 'ar'=>'بوابة الجهات المانحة', 'en'=>'Donor Portal',           'desc_ar'=>'إدارة علاقات الجهات المانحة بكفاءة عالية', 'desc_en'=>'Efficiently manage donor relationships'],
    ['icon'=>'bi-bell-fill',          'color'=>'#06b6d4', 'ar'=>'نظام إشعارات ذكي',     'en'=>'Smart Notifications',    'desc_ar'=>'تنبيهات فورية لجميع أحداث المنح والمشاريع', 'desc_en'=>'Instant alerts for all grant & project events'],
];
$roles = [
    ['icon'=>'bi-shield-fill',       'color'=>'from-violet-600 to-purple-700', 'ar'=>'مدير النظام',   'en'=>'Admin',      'desc_ar'=>'صلاحيات كاملة على النظام والمستخدمين',   'desc_en'=>'Full system & user management access'],
    ['icon'=>'bi-person-badge-fill', 'color'=>'from-blue-600 to-cyan-600',     'ar'=>'الباحث',        'en'=>'Researcher', 'desc_ar'=>'إدارة المشاريع وطلبات التمويل',            'desc_en'=>'Manage projects & funding applications'],
    ['icon'=>'bi-mortarboard-fill',  'color'=>'from-emerald-600 to-teal-600',  'ar'=>'الطالب',        'en'=>'Student',    'desc_ar'=>'تقديم طلبات المنح ومتابعة الحالة',        'desc_en'=>'Submit grant applications & track status'],
    ['icon'=>'bi-bank2',             'color'=>'from-amber-500 to-orange-600',  'ar'=>'الجهة المانحة', 'en'=>'Donor',      'desc_ar'=>'متابعة المشاريع الممولة والتقارير',       'desc_en'=>'Track funded projects & reports'],
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $isRTL ? 'نظام إدارة المنح والتمويل البحثي — KFUPM' : 'Grant Management System — KFUPM' ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php if ($isRTL): ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
  <?php else: ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?php endif; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{
  --primary:#6366f1;--primary-dark:#4f46e5;--secondary:#10b981;
  --accent:#f59e0b;--dark:#0f172a;--card-bg:rgba(255,255,255,.07);
  --glass:rgba(255,255,255,.05);--border:rgba(255,255,255,.12);
}
html{scroll-behavior:smooth}
body{
  font-family:'Cairo',sans-serif;
  background:var(--dark);
  color:#e2e8f0;
  overflow-x:hidden;
}

/* ===== NAVBAR ===== */
.lp-nav{
  position:fixed;top:0;left:0;right:0;z-index:100;
  padding:16px 0;
  background:rgba(15,23,42,.7);
  backdrop-filter:blur(20px);
  border-bottom:1px solid var(--border);
  transition:all .3s;
}
.lp-nav.scrolled{padding:10px 0;background:rgba(15,23,42,.95)}
.nav-logo{font-size:20px;font-weight:800;color:#fff;text-decoration:none;display:flex;align-items:center;gap:10px}
.nav-logo .logo-ring{
  width:38px;height:38px;border-radius:10px;
  background:linear-gradient(135deg,var(--primary),#8b5cf6);
  display:flex;align-items:center;justify-content:center;font-size:18px;
}
.nav-links a{color:rgba(255,255,255,.75);text-decoration:none;font-size:14px;font-weight:500;padding:6px 14px;border-radius:8px;transition:.2s}
.nav-links a:hover{color:#fff;background:var(--glass)}
.btn-nav-login{
  padding:8px 22px;border-radius:10px;font-weight:600;font-size:14px;
  border:1px solid var(--border);color:#fff;background:transparent;
  text-decoration:none;transition:.25s;
}
.btn-nav-login:hover{background:var(--glass);color:#fff}
.btn-nav-register{
  padding:8px 22px;border-radius:10px;font-weight:600;font-size:14px;
  background:linear-gradient(135deg,var(--primary),#8b5cf6);
  color:#fff;text-decoration:none;border:none;transition:.25s;box-shadow:0 0 20px rgba(99,102,241,.4);
}
.btn-nav-register:hover{transform:translateY(-1px);box-shadow:0 0 30px rgba(99,102,241,.6);color:#fff}

/* ===== HERO ===== */
.hero{
  min-height:100vh;
  display:flex;align-items:center;
  position:relative;
  overflow:hidden;
  padding:120px 0 80px;
}
.hero-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse 80% 60% at 50% -10%,rgba(99,102,241,.35) 0%,transparent 70%),
             radial-gradient(ellipse 40% 40% at 80% 60%,rgba(16,185,129,.2) 0%,transparent 60%),
             radial-gradient(ellipse 50% 50% at 20% 80%,rgba(139,92,246,.2) 0%,transparent 60%),
             linear-gradient(180deg,#0f172a 0%,#1e1b4b 100%);
}
.hero-grid{
  position:absolute;inset:0;
  background-image:linear-gradient(rgba(99,102,241,.07) 1px,transparent 1px),
                   linear-gradient(90deg,rgba(99,102,241,.07) 1px,transparent 1px);
  background-size:60px 60px;
  mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black 0%,transparent 80%);
}
.hero-orb{
  position:absolute;border-radius:50%;filter:blur(80px);animation:orb-float 8s ease-in-out infinite;
}
.orb1{width:500px;height:500px;background:rgba(99,102,241,.25);top:-200px;left:-100px;animation-delay:0s}
.orb2{width:400px;height:400px;background:rgba(16,185,129,.18);bottom:-100px;right:-100px;animation-delay:3s}
.orb3{width:300px;height:300px;background:rgba(245,158,11,.15);top:30%;right:10%;animation-delay:6s}
@keyframes orb-float{0%,100%{transform:translate(0,0) scale(1)}50%{transform:translate(30px,-40px) scale(1.1)}}

.hero-badge{
  display:inline-flex;align-items:center;gap:8px;
  padding:6px 16px;border-radius:999px;
  background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.3);
  color:#a5b4fc;font-size:13px;font-weight:600;margin-bottom:24px;
  animation:fade-up .8s ease both;
}
.hero-badge .dot{width:8px;height:8px;border-radius:50%;background:#6366f1;animation:pulse-dot 2s infinite}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}

.hero-title{
  font-size:clamp(2.5rem,6vw,4.5rem);font-weight:900;line-height:1.1;
  margin-bottom:20px;color:#fff;
  animation:fade-up .8s .1s ease both;
}
.hero-title .gradient-text{
  background:linear-gradient(135deg,#818cf8,#a78bfa,#34d399);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-clip:text;
}
.hero-desc{
  font-size:1.1rem;color:#94a3b8;line-height:1.8;max-width:580px;
  margin-bottom:36px;animation:fade-up .8s .2s ease both;
}
.hero-actions{
  display:flex;flex-wrap:wrap;gap:14px;margin-bottom:60px;
  animation:fade-up .8s .3s ease both;
}
.btn-hero-primary{
  padding:14px 32px;border-radius:12px;font-weight:700;font-size:1rem;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;
  text-decoration:none;border:none;display:inline-flex;align-items:center;gap:8px;
  box-shadow:0 8px 32px rgba(99,102,241,.45);transition:.3s;
}
.btn-hero-primary:hover{transform:translateY(-3px);box-shadow:0 12px 40px rgba(99,102,241,.6);color:#fff}
.btn-hero-secondary{
  padding:14px 32px;border-radius:12px;font-weight:700;font-size:1rem;
  background:rgba(255,255,255,.07);color:#e2e8f0;
  text-decoration:none;border:1px solid var(--border);
  display:inline-flex;align-items:center;gap:8px;transition:.3s;
}
.btn-hero-secondary:hover{background:rgba(255,255,255,.12);transform:translateY(-2px);color:#fff}

/* Hero Stats */
.hero-stats{
  display:grid;grid-template-columns:repeat(4,1fr);gap:16px;
  animation:fade-up .8s .4s ease both;
}
.hero-stat{
  background:var(--card-bg);border:1px solid var(--border);border-radius:16px;
  padding:20px;text-align:center;backdrop-filter:blur(10px);
  transition:.3s;
}
.hero-stat:hover{transform:translateY(-4px);border-color:var(--primary)}
.hero-stat .stat-icon{
  width:44px;height:44px;border-radius:12px;
  display:inline-flex;align-items:center;justify-content:center;
  font-size:20px;margin-bottom:10px;
}
.hero-stat .stat-value{font-size:1.6rem;font-weight:800;color:#fff;line-height:1}
.hero-stat .stat-label{font-size:11px;color:#64748b;margin-top:4px}

/* Hero visual */
.hero-visual{
  position:relative;animation:fade-up .8s .2s ease both;
}
.dashboard-preview{
  background:rgba(255,255,255,.05);border:1px solid var(--border);
  border-radius:20px;overflow:hidden;backdrop-filter:blur(20px);
  box-shadow:0 40px 80px rgba(0,0,0,.5);
}
.preview-bar{
  background:rgba(255,255,255,.06);padding:12px 16px;
  border-bottom:1px solid var(--border);
  display:flex;align-items:center;gap:8px;
}
.preview-dot{width:10px;height:10px;border-radius:50%}
.preview-body{padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:12px}
.mini-card{
  background:rgba(255,255,255,.06);border-radius:12px;padding:14px;
  border:1px solid var(--border);
}
.mini-card-label{font-size:10px;color:#64748b;margin-bottom:6px}
.mini-card-val{font-size:1.2rem;font-weight:700;color:#fff}
.mini-progress{height:6px;border-radius:3px;background:rgba(255,255,255,.1);margin-top:8px;overflow:hidden}
.mini-progress-fill{height:100%;border-radius:3px;animation:prog-fill 2s 1s ease both}
@keyframes prog-fill{from{width:0}to{width:var(--w)}}
.mini-chart{grid-column:span 2;height:70px;display:flex;align-items:flex-end;gap:4px;padding:8px}
.mini-bar{flex:1;border-radius:4px 4px 0 0;opacity:.85;animation:bar-rise 1s ease both}
@keyframes bar-rise{from{height:0}to{height:var(--h)}}

/* ===== SECTION STYLES ===== */
section{padding:80px 0}
.section-label{
  display:inline-block;padding:5px 16px;border-radius:999px;
  font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
  background:rgba(99,102,241,.15);color:#a5b4fc;border:1px solid rgba(99,102,241,.2);
  margin-bottom:14px;
}
.section-title{font-size:clamp(1.8rem,3.5vw,2.6rem);font-weight:800;color:#fff;line-height:1.2;margin-bottom:14px}
.section-desc{font-size:1rem;color:#64748b;max-width:540px;line-height:1.8}

/* ===== FEATURE CARDS ===== */
.feature-card{
  background:var(--card-bg);border:1px solid var(--border);border-radius:20px;
  padding:28px;transition:.35s;cursor:default;position:relative;overflow:hidden;
}
.feature-card::before{
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at var(--mx,50%) var(--my,50%),rgba(99,102,241,.12) 0%,transparent 60%);
  opacity:0;transition:.35s;
}
.feature-card:hover{transform:translateY(-6px);border-color:rgba(99,102,241,.4)}
.feature-card:hover::before{opacity:1}
.fc-icon{
  width:52px;height:52px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;font-size:22px;
  margin-bottom:16px;
}
.fc-title{font-size:1rem;font-weight:700;color:#fff;margin-bottom:8px}
.fc-desc{font-size:.85rem;color:#64748b;line-height:1.7}

/* ===== ROLES CARDS ===== */
.role-card{
  background:var(--card-bg);border:1px solid var(--border);border-radius:24px;
  padding:36px 28px;text-align:center;transition:.35s;
  position:relative;overflow:hidden;
}
.role-card::after{
  content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg,var(--rc1),var(--rc2));
  transform:scaleX(0);transform-origin:center;transition:.35s;
}
.role-card:hover{transform:translateY(-8px);border-color:rgba(255,255,255,.2)}
.role-card:hover::after{transform:scaleX(1)}
.role-icon{
  width:72px;height:72px;border-radius:20px;
  display:flex;align-items:center;justify-content:center;font-size:30px;color:#fff;
  margin:0 auto 20px;
}
.role-title{font-size:1.15rem;font-weight:800;color:#fff;margin-bottom:8px}
.role-desc{font-size:.875rem;color:#64748b;line-height:1.7;margin-bottom:24px}
.btn-role{
  display:inline-flex;align-items:center;gap:6px;
  padding:10px 24px;border-radius:10px;font-weight:600;font-size:.875rem;
  text-decoration:none;transition:.25s;color:#fff;
  border:1px solid rgba(255,255,255,.15);background:rgba(255,255,255,.07);
}
.btn-role:hover{background:rgba(255,255,255,.14);color:#fff}

/* ===== CTA BAND ===== */
.cta-band{
  background:linear-gradient(135deg,rgba(99,102,241,.2),rgba(139,92,246,.2));
  border-top:1px solid var(--border);border-bottom:1px solid var(--border);
  padding:80px 0;text-align:center;
}
.cta-band h2{font-size:2rem;font-weight:800;color:#fff;margin-bottom:16px}
.cta-band p{color:#94a3b8;font-size:1rem;margin-bottom:32px}

/* ===== FOOTER ===== */
.lp-footer{
  background:rgba(0,0,0,.3);border-top:1px solid var(--border);
  padding:40px 0 24px;text-align:center;
}
.lp-footer .footer-logo{font-size:18px;font-weight:800;color:#fff;margin-bottom:8px}
.lp-footer p{color:#475569;font-size:13px}

/* Animations */
@keyframes fade-up{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
.anim-delay-1{animation-delay:.1s!important}
.anim-delay-2{animation-delay:.2s!important}
.anim-delay-3{animation-delay:.3s!important}

/* Floating particles */
.particle{
  position:absolute;border-radius:50%;pointer-events:none;
  animation:particle-float linear infinite;opacity:.4;
}
@keyframes particle-float{
  0%{transform:translateY(100vh) rotate(0);opacity:0}
  10%{opacity:.4}
  90%{opacity:.4}
  100%{transform:translateY(-100px) rotate(720deg);opacity:0}
}

/* Lang button */
.lang-btn{
  padding:6px 14px;border-radius:8px;font-weight:600;font-size:13px;
  border:1px solid var(--border);color:rgba(255,255,255,.7);background:transparent;
  cursor:pointer;text-decoration:none;transition:.2s;
}
.lang-btn:hover{color:#fff;background:var(--glass);border-color:rgba(255,255,255,.3)}

@media(max-width:768px){
  .hero-stats{grid-template-columns:repeat(2,1fr)}
  .hero-visual{display:none}
  .hero-actions{flex-direction:column}
  .btn-hero-primary,.btn-hero-secondary{justify-content:center}
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="lp-nav" id="lpNav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">
      <a href="<?= BASE_URL ?>/" class="nav-logo">
        <div class="logo-ring"><i class="bi bi-mortarboard-fill" style="color:#fff"></i></div>
        <span><?= $isRTL ? 'نظام المنح — KFUPM' : 'Grant System — KFUPM' ?></span>
      </a>
      <div class="nav-links d-none d-md-flex align-items-center gap-1">
        <a href="#features"><?= $isRTL ? 'المميزات' : 'Features' ?></a>
        <a href="#roles"><?= $isRTL ? 'المستخدمون' : 'Users' ?></a>
        <a href="#cta"><?= $isRTL ? 'ابدأ الآن' : 'Get Started' ?></a>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="?lang=<?= $isRTL ? 'en' : 'ar' ?>" class="lang-btn"><?= $isRTL ? 'EN' : 'عر' ?></a>
        <a href="<?= BASE_URL ?>/login.php" class="btn-nav-login"><?= $isRTL ? 'تسجيل الدخول' : 'Login' ?></a>
        <a href="<?= BASE_URL ?>/register.php" class="btn-nav-register"><?= $isRTL ? 'إنشاء حساب' : 'Register' ?></a>
      </div>
    </div>
  </div>
</nav>

<!-- FLOATING PARTICLES -->
<div id="particles" aria-hidden="true"></div>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-orb orb1"></div>
  <div class="hero-orb orb2"></div>
  <div class="hero-orb orb3"></div>

  <div class="container position-relative">
    <div class="row align-items-center g-5">
      <!-- Text -->
      <div class="col-lg-6">
        <div class="hero-badge">
          <span class="dot"></span>
          <?= $isRTL ? 'الإصدار 2.0 — متاح الآن' : 'Version 2.0 — Now Available' ?>
        </div>
        <h1 class="hero-title">
          <?php if($isRTL): ?>
            نظام <span class="gradient-text">إدارة المنح</span><br>والتمويل البحثي
          <?php else: ?>
            Research <span class="gradient-text">Grant</span><br>Management System
          <?php endif; ?>
        </h1>
        <p class="hero-desc">
          <?= $isRTL
            ? 'منصة متكاملة لإدارة المنح البحثية، تتبع المشاريع، والميزانيات بكفاءة عالية. مصممة خصيصاً لجامعة الملك فهد للبترول والمعادن.'
            : 'A comprehensive platform for managing research grants, tracking projects and budgets with high efficiency. Designed exclusively for KFUPM.' ?>
        </p>
        <div class="hero-actions">
          <a href="<?= BASE_URL ?>/register.php" class="btn-hero-primary">
            <i class="bi bi-rocket-takeoff-fill"></i>
            <?= $isRTL ? 'ابدأ الآن مجاناً' : 'Get Started Free' ?>
          </a>
          <a href="<?= BASE_URL ?>/login.php" class="btn-hero-secondary">
            <i class="bi bi-box-arrow-in-right"></i>
            <?= $isRTL ? 'تسجيل الدخول' : 'Sign In' ?>
          </a>
        </div>

        <!-- Stats -->
        <div class="hero-stats">
          <?php foreach($stats as $i => $s): ?>
          <div class="hero-stat" style="animation:fade-up .8s <?= .4 + $i*.1 ?>s ease both">
            <div class="stat-icon" style="background:<?= $s['color'] ?>22;color:<?= $s['color'] ?>">
              <i class="bi <?= $s['icon'] ?>"></i>
            </div>
            <div class="stat-value counter" data-target="<?= $s['value'] ?>"><?= $s['value'] ?></div>
            <div class="stat-label"><?= $isRTL ? $s['label_ar'] : $s['label_en'] ?></div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Visual -->
      <div class="col-lg-6">
        <div class="hero-visual">
          <div class="dashboard-preview">
            <div class="preview-bar">
              <div class="preview-dot" style="background:#ef4444"></div>
              <div class="preview-dot" style="background:#f59e0b"></div>
              <div class="preview-dot" style="background:#10b981"></div>
              <span style="color:#475569;font-size:11px;margin-<?= $isRTL ? 'right' : 'left' ?>:auto">
                <?= $isRTL ? 'نظام إدارة المنح' : 'Grant Management System' ?>
              </span>
            </div>
            <div class="preview-body">
              <div class="mini-card">
                <div class="mini-card-label"><?= $isRTL ? 'إجمالي المنح' : 'Total Grants' ?></div>
                <div class="mini-card-val" style="color:#818cf8">150</div>
                <div class="mini-progress"><div class="mini-progress-fill" style="--w:75%;background:linear-gradient(90deg,#6366f1,#8b5cf6)"></div></div>
              </div>
              <div class="mini-card">
                <div class="mini-card-label"><?= $isRTL ? 'المشاريع النشطة' : 'Active Projects' ?></div>
                <div class="mini-card-val" style="color:#34d399">48</div>
                <div class="mini-progress"><div class="mini-progress-fill" style="--w:60%;background:linear-gradient(90deg,#10b981,#34d399)"></div></div>
              </div>
              <div class="mini-card">
                <div class="mini-card-label"><?= $isRTL ? 'التمويل (م.ريال)' : 'Funding (M SAR)' ?></div>
                <div class="mini-card-val" style="color:#fbbf24">73</div>
                <div class="mini-progress"><div class="mini-progress-fill" style="--w:82%;background:linear-gradient(90deg,#f59e0b,#fbbf24)"></div></div>
              </div>
              <div class="mini-card">
                <div class="mini-card-label"><?= $isRTL ? 'طلبات جديدة' : 'New Requests' ?></div>
                <div class="mini-card-val" style="color:#f87171">12</div>
                <div class="mini-progress"><div class="mini-progress-fill" style="--w:40%;background:linear-gradient(90deg,#ef4444,#f87171)"></div></div>
              </div>
              <!-- Mini chart -->
              <div style="grid-column:span 2;background:rgba(255,255,255,.04);border-radius:12px;padding:12px;border:1px solid var(--border)">
                <div style="font-size:10px;color:#475569;margin-bottom:8px"><?= $isRTL ? 'التمويل الشهري' : 'Monthly Funding' ?></div>
                <div style="display:flex;align-items:flex-end;gap:5px;height:55px">
                  <?php $bars=[30,55,40,70,45,85,60,75,50,90,65,80]; $bc=['#6366f1','#8b5cf6','#6366f1','#8b5cf6','#10b981','#34d399','#f59e0b','#fbbf24','#6366f1','#8b5cf6','#10b981','#34d399'];
                  foreach($bars as $i=>$b): ?>
                  <div style="flex:1;height:<?=$b?>%;background:<?=$bc[$i]?>;border-radius:3px 3px 0 0;opacity:.8;animation:bar-rise 1s <?=$i*.05?>s ease both;--h:<?=$b?>%"></div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>
          </div>
          <!-- Floating badges -->
          <div style="position:absolute;top:-16px;<?= $isRTL ? 'left' : 'right' ?>:-16px;background:linear-gradient(135deg,#10b981,#34d399);border-radius:14px;padding:10px 16px;box-shadow:0 8px 24px rgba(16,185,129,.4)">
            <div style="color:#fff;font-size:11px;font-weight:700">✓ <?= $isRTL ? 'تمت الموافقة' : 'Grant Approved' ?></div>
            <div style="color:rgba(255,255,255,.7);font-size:10px">750,000 SAR</div>
          </div>
          <div style="position:absolute;bottom:-16px;<?= $isRTL ? 'right' : 'left' ?>:-16px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:14px;padding:10px 16px;box-shadow:0 8px 24px rgba(99,102,241,.4)">
            <div style="color:#fff;font-size:11px;font-weight:700">🔔 <?= $isRTL ? 'إشعار جديد' : 'New Notification' ?></div>
            <div style="color:rgba(255,255,255,.7);font-size:10px"><?= $isRTL ? 'طلب قيد المراجعة' : 'Application Under Review' ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section id="features" style="background:rgba(0,0,0,.2)">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-label"><?= $isRTL ? 'المميزات' : 'FEATURES' ?></div>
      <h2 class="section-title"><?= $isRTL ? 'كل ما تحتاجه في منصة واحدة' : 'Everything You Need in One Platform' ?></h2>
      <p class="section-desc mx-auto"><?= $isRTL ? 'نظام متكامل يغطي كل جوانب إدارة المنح والتمويل البحثي' : 'A complete system covering all aspects of grant and research funding management' ?></p>
    </div>
    <div class="row g-4">
      <?php foreach($features as $i => $f): ?>
      <div class="col-md-6 col-lg-4" style="animation:fade-up .8s <?= .1+$i*.1 ?>s ease both">
        <div class="feature-card h-100">
          <div class="fc-icon" style="background:<?= $f['color'] ?>22;color:<?= $f['color'] ?>">
            <i class="bi <?= $f['icon'] ?>"></i>
          </div>
          <div class="fc-title"><?= $isRTL ? $f['ar'] : $f['en'] ?></div>
          <div class="fc-desc"><?= $isRTL ? $f['desc_ar'] : $f['desc_en'] ?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ROLES -->
<section id="roles">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-label"><?= $isRTL ? 'المستخدمون' : 'USER ROLES' ?></div>
      <h2 class="section-title"><?= $isRTL ? 'بوابة لكل مستخدم' : 'A Portal for Every User' ?></h2>
      <p class="section-desc mx-auto"><?= $isRTL ? 'كل دور يمتلك بوابته الخاصة مع صلاحيات مخصصة' : 'Each role has its own portal with tailored permissions' ?></p>
    </div>
    <div class="row g-4 justify-content-center">
      <?php
      $roleColors=[['#7c3aed','#6366f1'],['#2563eb','#0891b2'],['#059669','#0d9488'],['#d97706','#ea580c']];
      foreach($roles as $i=>$r): ?>
      <div class="col-sm-6 col-lg-3" style="animation:fade-up .8s <?= .1+$i*.1 ?>s ease both">
        <div class="role-card" style="--rc1:<?=$roleColors[$i][0]?>;--rc2:<?=$roleColors[$i][1]?>">
          <div class="role-icon" style="background:linear-gradient(135deg,<?=$roleColors[$i][0]?>,<?=$roleColors[$i][1]?>)">
            <i class="bi <?= $r['icon'] ?>"></i>
          </div>
          <div class="role-title"><?= $isRTL ? $r['ar'] : $r['en'] ?></div>
          <div class="role-desc"><?= $isRTL ? $r['desc_ar'] : $r['desc_en'] ?></div>
          <a href="<?= BASE_URL ?>/login.php" class="btn-role">
            <?= $isRTL ? 'تسجيل الدخول' : 'Sign In' ?>
            <i class="bi bi-arrow-<?= $isRTL ? 'left' : 'right' ?>"></i>
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section id="cta" class="cta-band">
  <div class="container">
    <h2><?= $isRTL ? 'جاهز للبدء؟' : 'Ready to Get Started?' ?></h2>
    <p><?= $isRTL ? 'انضم اليوم وابدأ في إدارة منحك البحثية باحترافية' : 'Join today and start managing your research grants professionally' ?></p>
    <div class="d-flex flex-wrap gap-3 justify-content-center">
      <a href="<?= BASE_URL ?>/register.php" class="btn-hero-primary">
        <i class="bi bi-person-plus-fill"></i>
        <?= $isRTL ? 'إنشاء حساب جديد' : 'Create New Account' ?>
      </a>
      <a href="<?= BASE_URL ?>/login.php" class="btn-hero-secondary">
        <i class="bi bi-box-arrow-in-right"></i>
        <?= $isRTL ? 'لدي حساب بالفعل' : 'I Already Have an Account' ?>
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="lp-footer">
  <div class="container">
    <div class="footer-logo">
      <i class="bi bi-mortarboard-fill" style="color:#6366f1"></i>
      <?= $isRTL ? 'نظام إدارة المنح — KFUPM' : 'Grant Management System — KFUPM' ?>
    </div>
    <p>© <?= date('Y') ?> <?= $isRTL ? 'جامعة الملك فهد للبترول والمعادن. جميع الحقوق محفوظة.' : 'King Fahd University of Petroleum & Minerals. All rights reserved.' ?></p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Navbar scroll effect
window.addEventListener('scroll',()=>{
  document.getElementById('lpNav').classList.toggle('scrolled',window.scrollY>60);
});

// Particles
(function(){
  const container=document.getElementById('particles');
  const colors=['#6366f1','#8b5cf6','#10b981','#f59e0b','#ef4444','#06b6d4'];
  for(let i=0;i<25;i++){
    const p=document.createElement('div');
    p.className='particle';
    const size=Math.random()*6+2;
    const left=Math.random()*100;
    const dur=Math.random()*15+10;
    const delay=-Math.random()*20;
    const color=colors[Math.floor(Math.random()*colors.length)];
    Object.assign(p.style,{
      width:size+'px',height:size+'px',
      left:left+'%',bottom:'0',
      background:color,
      animationDuration:dur+'s',
      animationDelay:delay+'s',
      position:'fixed',zIndex:0,
    });
    container.appendChild(p);
  }
})();

// Feature card mouse glow
document.querySelectorAll('.feature-card').forEach(card=>{
  card.addEventListener('mousemove',e=>{
    const r=card.getBoundingClientRect();
    const x=((e.clientX-r.left)/r.width*100).toFixed(1);
    const y=((e.clientY-r.top)/r.height*100).toFixed(1);
    card.style.setProperty('--mx',x+'%');
    card.style.setProperty('--my',y+'%');
  });
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a=>{
  a.addEventListener('click',e=>{
    const t=document.querySelector(a.getAttribute('href'));
    if(t){e.preventDefault();t.scrollIntoView({behavior:'smooth',block:'start'});}
  });
});
</script>
</body>
</html>
