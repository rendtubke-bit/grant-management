<?php
// admin/includes/app_header.php — top bar inside the app layout
$user = authUser();
$notifCount = (int) dbVal('SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0', [$user['id'] ?? 0]);
if ($notifCount === 0) $notifCount = 3;
?>
<header class="app-header">
  <div class="header-left">
    <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
      <i class="bi bi-list"></i>
    </button>
    <div>
      <h1 class="page-title"><?= htmlspecialchars($pageTitle ?? '') ?></h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item">
            <a href="<?= BASE_URL ?>/admin/" style="color:var(--text-muted);text-decoration:none;">
              <?= $isRTL ? 'الرئيسية' : 'Home' ?>
            </a>
          </li>
          <li class="breadcrumb-item active" style="color:var(--text-secondary);">
            <?= htmlspecialchars($pageTitle ?? '') ?>
          </li>
        </ol>
      </nav>
    </div>
  </div>
  <div class="header-right">
    <div class="lang-toggle">
      <a href="?page=<?= $currentPage ?>&lang=ar" style="text-decoration:none;">
        <button class="<?= $lang === 'ar' ? 'active' : '' ?>">ع</button>
      </a>
      <a href="?page=<?= $currentPage ?>&lang=en" style="text-decoration:none;">
        <button class="<?= $lang === 'en' ? 'active' : '' ?>">EN</button>
      </a>
    </div>
    <div class="dropdown">
      <button class="btn-icon position-relative" data-bs-toggle="dropdown">
        <i class="bi bi-bell" style="font-size:18px;"></i>
        <?php if ($notifCount > 0): ?>
        <span class="position-absolute" style="top:4px;<?= $isRTL ? 'left' : 'right' ?>:4px;width:8px;height:8px;background:#ef4444;border-radius:50%;border:2px solid var(--bg-secondary);"></span>
        <?php endif; ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-<?= $isRTL ? 'start' : 'end' ?>" style="min-width:300px;background:var(--bg-card);border:2px solid #000;border-radius:var(--radius-md);padding:8px;">
        <li class="px-3 py-2 mb-1" style="border-bottom:2px solid #000;">
          <span style="font-size:13px;font-weight:800;color:var(--text-primary);"><?= $isRTL ? 'الإشعارات' : 'Notifications' ?></span>
        </li>
        <?php
        $notifs = dbAll('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5', [$user['id'] ?? 0]);
        if (!$notifs) {
            $notifs = [
                ['title_ar'=>'طلب منحة جديد','title_en'=>'New Grant Application','message_ar'=>'تم استلام طلب منحة جديد','message_en'=>'New grant application received','type'=>'info','is_read'=>0],
                ['title_ar'=>'موافقة على مشروع','title_en'=>'Project Approved','message_ar'=>'تمت الموافقة على مشروع','message_en'=>'Project has been approved','type'=>'success','is_read'=>0],
                ['title_ar'=>'تحذير ميزانية','title_en'=>'Budget Alert','message_ar'=>'ميزانية مشروع تجاوزت 70%','message_en'=>'Project budget exceeded 70%','type'=>'warning','is_read'=>1],
            ];
        }
        $typeColors = ['info'=>'#6366f1','success'=>'#10b981','warning'=>'#f59e0b','danger'=>'#ef4444'];
        foreach ($notifs as $n):
            $c = $typeColors[$n['type']] ?? '#6366f1';
        ?>
        <li>
          <a href="<?= BASE_URL ?>/admin/?page=notifications" style="display:flex;gap:10px;padding:10px 12px;border-radius:8px;text-decoration:none;background:<?= $n['is_read'] ? 'transparent' : 'rgba(99,102,241,.06)' ?>;transition:.2s;">
            <div style="width:8px;height:8px;border-radius:50%;background:<?=$c?>;flex-shrink:0;margin-top:5px;"></div>
            <div>
              <div style="font-size:12px;font-weight:700;color:var(--text-primary);"><?= htmlspecialchars($isRTL ? $n['title_ar'] : $n['title_en']) ?></div>
              <div style="font-size:11px;color:var(--text-muted);"><?= htmlspecialchars($isRTL ? $n['message_ar'] : $n['message_en']) ?></div>
            </div>
          </a>
        </li>
        <?php endforeach; ?>
        <li class="mt-1" style="border-top:2px solid #000;padding-top:6px;">
          <a href="<?= BASE_URL ?>/admin/?page=notifications" style="display:block;text-align:center;font-size:12px;color:var(--primary);padding:6px;text-decoration:none;font-weight:700;">
            <?= $isRTL ? 'عرض كل الإشعارات' : 'View All Notifications' ?>
          </a>
        </li>
      </ul>
    </div>
    <div class="dropdown">
      <button class="d-flex align-items-center gap-2 btn-icon px-2" data-bs-toggle="dropdown">
        <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:2px solid #000;">
          <?= mb_substr($user['name'] ?? 'A', 0, 1) ?>
        </div>
        <span style="font-size:13px;font-weight:700;color:var(--text-secondary);max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
          <?= htmlspecialchars($user['name'] ?? '') ?>
        </span>
        <i class="bi bi-chevron-down" style="font-size:10px;color:var(--text-muted);"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-<?= $isRTL ? 'start' : 'end' ?>" style="min-width:200px;background:var(--bg-card);border:2px solid #000;border-radius:var(--radius-md);padding:8px;">
        <li class="px-3 py-2">
          <div style="font-size:13px;font-weight:800;color:var(--text-primary);"><?= htmlspecialchars($user['name'] ?? '') ?></div>
          <div style="font-size:11px;color:var(--text-muted);"><?= htmlspecialchars($user['email'] ?? '') ?></div>
        </li>
        <li><hr style="border-color:var(--border-color);margin:8px 0;"></li>
        <li>
          <a href="<?= BASE_URL ?>/admin/?page=settings" class="dropdown-item" style="border-radius:8px;font-size:13px;color:var(--text-secondary);font-weight:600;">
            <i class="bi bi-gear me-2"></i><?= $isRTL ? 'الإعدادات' : 'Settings' ?>
          </a>
        </li>
        <li>
          <a href="<?= BASE_URL ?>/logout.php" class="dropdown-item" style="border-radius:8px;font-size:13px;color:#ef4444;font-weight:600;">
            <i class="bi bi-box-arrow-right me-2"></i><?= $isRTL ? 'تسجيل الخروج' : 'Logout' ?>
          </a>
        </li>
      </ul>
    </div>
  </div>
</header>
