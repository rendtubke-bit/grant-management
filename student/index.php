<?php
require_once dirname(__DIR__) . '/includes/lang.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/includes/db.php';

requireRole('student', '/login.php');
$user = authUser();

$stuInfo = dbOne('SELECT * FROM students WHERE user_id=?', [$user['id']]);
if (!$stuInfo) $stuInfo = ['student_id'=>'S202110234','degree'=>'phd','department'=>'هندسة الحاسب والمعلومات','gpa'=>3.85,'enrollment_year'=>2021];

$degreeMap = ['bachelor'=>$isRTL?'بكالوريوس':'Bachelor','master'=>$isRTL?'ماجستير':'Master','phd'=>$isRTL?'دكتوراه':'PhD'];
$pageTitle = $isRTL ? 'بوابة الطالب' : 'Student Portal';
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
.grant-card{
  background:var(--card-bg);border:1px solid var(--border-color);border-radius:16px;
  padding:20px;transition:.3s;
}
.grant-card:hover{transform:translateY(-3px);border-color:rgba(16,185,129,.4)}
.gpa-ring{
  width:80px;height:80px;border-radius:50%;
  background:conic-gradient(#10b981 calc(<?= min(($stuInfo['gpa']??0)/4*100,100) ?>%),rgba(255,255,255,.08) 0);
  display:flex;align-items:center;justify-content:center;position:relative;
}
.gpa-ring-inner{
  width:60px;height:60px;border-radius:50%;
  background:var(--bg-secondary);
  display:flex;align-items:center;justify-content:center;
  font-weight:800;font-size:1.1rem;color:#10b981;
}
</style>
</head>
<body>
<div class="app-layout">
<nav class="sidebar" id="mainSidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
    <div class="logo-text">
      <h6><?= t('appName') ?></h6>
      <span><?= $isRTL ? 'بوابة الطالب' : 'Student Portal' ?></span>
    </div>
  </div>
  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= $isRTL?'القائمة':'Menu' ?></span>
    <?php
    $items = [
      ['p'=>'dashboard',    'ar'=>'لوحة التحكم',   'en'=>'Dashboard',      'i'=>'bi-grid-fill'],
      ['p'=>'applications', 'ar'=>'طلباتي',        'en'=>'My Applications','i'=>'bi-file-earmark-check'],
      ['p'=>'grants',       'ar'=>'المنح المتاحة', 'en'=>'Available Grants','i'=>'bi-award-fill'],
      ['p'=>'notifications','ar'=>'الإشعارات',     'en'=>'Notifications',  'i'=>'bi-bell-fill'],
    ];
    $pg = $_GET['page'] ?? 'dashboard';
    foreach($items as $it): ?>
    <a href="?page=<?=$it['p']?>&lang=<?=$lang?>" class="sidebar-nav-item <?= $pg===$it['p']?'active':'' ?>">
      <i class="bi <?=$it['i']?> nav-icon"></i>
      <?= $isRTL?$it['ar']:$it['en'] ?>
    </a>
    <?php endforeach; ?>
  </div>
  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#10b981,#34d399);color:#fff"><?= mb_substr($user['name'],0,1) ?></div>
      <div style="flex:1;min-width:0">
        <div style="font-size:12px;font-weight:700;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($user['name']) ?></div>
        <div style="font-size:10px;color:var(--sidebar-heading)"><?= $degreeMap[$stuInfo['degree']??'master'] ?></div>
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
</body>
</html>
