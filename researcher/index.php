<?php
require_once dirname(__DIR__) . '/includes/lang.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';

requireRole('researcher', '/login.php');
$user = authUser();

$pageTitle = $isRTL ? 'بوابة الباحث' : 'Researcher Portal';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $pageTitle ?> — <?= t('appName') ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <?php if($isRTL): ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
  <?php else: ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?php endif; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/custom.css">
<style>
.portal-hero{
  background:linear-gradient(135deg,rgba(99,102,241,.15),rgba(16,185,129,.1));
  border-bottom:1px solid var(--border-color);
  padding:32px 0;
}
.portal-avatar{
  width:64px;height:64px;border-radius:18px;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);
  display:flex;align-items:center;justify-content:center;
  font-size:26px;font-weight:700;color:#fff;
}
.project-progress-card{
  background:var(--card-bg);border:1px solid var(--border-color);border-radius:16px;
  padding:20px;transition:.3s;
}
.project-progress-card:hover{transform:translateY(-3px);border-color:rgba(99,102,241,.4)}
.prog-bar-wrap{height:8px;background:rgba(255,255,255,.08);border-radius:4px;overflow:hidden;margin:10px 0}
.prog-bar-fill{height:100%;border-radius:4px;background:linear-gradient(90deg,#6366f1,#8b5cf6);transition:width 1s ease}
</style>
</head>
<body>
<div class="app-layout">

<!-- Minimal sidebar for researcher -->
<nav class="sidebar" id="mainSidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-person-badge-fill"></i></div>
    <div class="logo-text">
      <h6><?= t('appName') ?></h6>
      <span><?= $isRTL ? 'بوابة الباحث' : 'Researcher Portal' ?></span>
    </div>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= $isRTL ? 'القائمة' : 'Menu' ?></span>
    <?php
    $items = [
      ['p'=>'dashboard','i'=>'bi-grid-fill',     'ar'=>'لوحة التحكم',    'en'=>'Dashboard'],
      ['p'=>'projects', 'i'=>'bi-journal-code',  'ar'=>'مشاريعي',        'en'=>'My Projects'],
      ['p'=>'grants',   'i'=>'bi-file-earmark-text-fill','ar'=>'طلبات المنح','en'=>'Grant Requests'],
      ['p'=>'reports',  'i'=>'bi-bar-chart-fill', 'ar'=>'التقارير',       'en'=>'Reports'],
      ['p'=>'notifications','i'=>'bi-bell-fill',  'ar'=>'الإشعارات',      'en'=>'Notifications'],
    ];
    $pg = $_GET['tab'] ?? 'dashboard';
    foreach($items as $it): ?>
    <a href="?tab=<?=$it['p']?>&lang=<?=$lang?>" class="sidebar-nav-item <?= $pg===$it['p']?'active':'' ?>">
      <i class="bi <?=$it['i']?> nav-icon"></i>
      <?= $isRTL ? $it['ar'] : $it['en'] ?>
    </a>
    <?php endforeach; ?>
  </div>
  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff"><?= mb_substr($user['name'],0,1) ?></div>
      <div style="flex:1;min-width:0">
        <div style="font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($user['name']) ?></div>
        <div style="font-size:10px;color:var(--sidebar-heading)"><?= $isRTL?'باحث':'Researcher' ?></div>
      </div>
      <a href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right" style="color:var(--sidebar-heading)"></i></a>
    </div>
  </div>
</nav>

<div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:auto">
  <!-- Top bar -->
  <header class="app-header">
    <div class="d-flex align-items-center gap-3">
      <button class="btn-icon d-lg-none" onclick="document.getElementById('mainSidebar').classList.toggle('open')"><i class="bi bi-list" style="font-size:20px"></i></button>
      <h1 class="page-title"><?= $pageTitle ?></h1>
    </div>
    <div class="d-flex gap-2">
      <a href="?lang=<?= $isRTL?'en':'ar' ?>" class="btn-icon"><span style="font-size:12px;font-weight:700;color:var(--text-secondary)"><?= $isRTL?'EN':'عر' ?></span></a>
      <a href="<?= BASE_URL ?>/logout.php" class="btn-icon" title="<?= $isRTL?'خروج':'Logout' ?>"><i class="bi bi-box-arrow-right" style="font-size:18px;color:var(--text-secondary)"></i></a>
    </div>
  </header>

  <div class="main-content">
    <?php
      $pageFile = __DIR__ . '/pages/' . $pg . '.php';
      if (file_exists($pageFile)) {
          include $pageFile;
      } else {
          echo "<div class='alert alert-warning'>Page not found: " . htmlspecialchars($pg) . "</div>";
      }
    ?>
  </div>
</div>

</div><!-- app-layout -->

<div class="sidebar-overlay" id="sidebarOverlay" onclick="document.getElementById('mainSidebar').classList.remove('open');this.classList.remove('active')"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
