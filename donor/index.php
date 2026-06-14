<?php
require_once dirname(__DIR__) . '/includes/lang.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';

requireRole('donor', '/login.php');
$user = authUser();

$pageTitle = $isRTL ? 'بوابة الجهة المانحة' : 'Donor Portal';
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
.donor-hero{
  background:linear-gradient(135deg,rgba(245,158,11,.12),rgba(234,88,12,.08));
  border-bottom:1px solid var(--border-color);
  padding:32px 0;margin:0 -20px 24px;padding-<?= $isRTL?'right':'left' ?>:20px;padding-<?= $isRTL?'left':'right' ?>:20px;
}
.impact-card{
  background:var(--card-bg);border:1px solid var(--border-color);border-radius:16px;
  padding:24px;text-align:center;transition:.3s;
}
.impact-card:hover{transform:translateY(-4px);border-color:rgba(245,158,11,.4)}
.grant-row{
  padding:16px 0;border-bottom:1px solid var(--border-color);
}
.grant-row:last-child{border-bottom:none}
.prog{height:6px;background:rgba(255,255,255,.08);border-radius:3px;overflow:hidden}
.prog-fill{height:100%;border-radius:3px;background:linear-gradient(90deg,#f59e0b,#fbbf24)}
</style>
</head>
<body>
<div class="app-layout">
<nav class="sidebar" id="mainSidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-bank2"></i></div>
    <div class="logo-text">
      <h6><?= t('appName') ?></h6>
      <span><?= $isRTL?'بوابة المانح':'Donor Portal' ?></span>
    </div>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= $isRTL?'القائمة':'Menu' ?></span>
    <?php
    $items = [
      ['p'=>'dashboard',    'ar'=>'لوحة التحكم','en'=>'Dashboard',   'i'=>'bi-grid-fill'],
      ['p'=>'grants',       'ar'=>'منحي',       'en'=>'My Grants',    'i'=>'bi-award-fill'],
      ['p'=>'reports',      'ar'=>'التقارير',   'en'=>'Reports',      'i'=>'bi-bar-chart-fill'],
      ['p'=>'notifications','ar'=>'الإشعارات', 'en'=>'Notifications', 'i'=>'bi-bell-fill'],
    ];
    $pg = $_GET['page'] ?? 'dashboard';
    foreach($items as $it): ?>
    <a href="?page=<?=$it['p']?>&lang=<?=$lang?>" class="sidebar-nav-item <?= $pg===$it['p']?'active':'' ?>">
      <i class="bi <?=$it['i']?> nav-icon"></i><?= $isRTL?$it['ar']:$it['en'] ?>
    </a>
    <?php endforeach; ?>
  </div>
  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#f59e0b,#ea580c);color:#fff"><?= mb_substr($user['name'],0,1) ?></div>
      <div style="flex:1;min-width:0">
        <div style="font-size:12px;font-weight:700;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($user['name']) ?></div>
        <div style="font-size:10px;color:var(--sidebar-heading)"><?= $isRTL?'جهة مانحة':'Donor' ?></div>
      </div>
      <a href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right" style="color:var(--sidebar-heading)"></i></a>
    </div>
  </div>
</nav>

<div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:auto">
  <header class="app-header">
    <div class="d-flex align-items-center gap-3">
      <button class="btn-icon d-lg-none" onclick="document.getElementById('mainSidebar').classList.toggle('open')"><i class="bi bi-list" style="font-size:20px"></i></button>
      <h1 class="page-title"><?= $pageTitle ?></h1>
    </div>
    <div class="d-flex gap-2">
      <a href="?lang=<?= $isRTL?'en':'ar' ?>" class="btn-icon"><span style="font-size:12px;font-weight:700;color:var(--text-secondary)"><?= $isRTL?'EN':'عر' ?></span></a>
      <a href="<?= BASE_URL ?>/logout.php" class="btn-icon"><i class="bi bi-box-arrow-right" style="font-size:18px;color:var(--text-secondary)"></i></a>
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
</div>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="document.getElementById('mainSidebar').classList.remove('open');this.classList.remove('active')"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
</body>
</html>
