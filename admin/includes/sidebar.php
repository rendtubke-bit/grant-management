<?php
// admin/includes/sidebar.php
$user = authUser();
$nav = [
    'main' => [
        ['page'=>'dashboard',     'icon'=>'bi-grid-fill',              'key'=>'dashboard',     'badge'=>''],
        ['page'=>'grants',        'icon'=>'bi-file-earmark-text-fill', 'key'=>'grants',        'badge'=>'8'],
        ['page'=>'projects',      'icon'=>'bi-journal-code',           'key'=>'projects',      'badge'=>''],
        ['page'=>'budget',        'icon'=>'bi-cash-stack',             'key'=>'budget',        'badge'=>''],
        ['page'=>'reports',       'icon'=>'bi-bar-chart-fill',         'key'=>'reports',       'badge'=>''],
        ['page'=>'donors',        'icon'=>'bi-building-fill',          'key'=>'donors',        'badge'=>''],
    ],
    'admin' => [
        ['page'=>'researchers',   'icon'=>'bi-person-badge-fill',      'key'=>'researchers',   'badge'=>''],
        ['page'=>'students',      'icon'=>'bi-mortarboard-fill',       'key'=>'students',      'badge'=>''],
        ['page'=>'notifications', 'icon'=>'bi-bell-fill',              'key'=>'notifications', 'badge'=>'3'],
    ],
    'system' => [
        ['page'=>'audit',         'icon'=>'bi-shield-check',           'key'=>'audit',         'badge'=>''],
        ['page'=>'settings',      'icon'=>'bi-gear-fill',              'key'=>'settings',      'badge'=>''],
        ['page'=>'users',         'icon'=>'bi-people-fill',            'key'=>'users',         'badge'=>''],
    ],
];
function adminPageUrl($page) {
    global $lang;
    $base = defined('BASE_URL') ? BASE_URL : '';
    return $base . '/admin/?page=' . urlencode($page) . '&lang=' . $lang;
}
?>
<nav class="sidebar" id="mainSidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
    <div class="logo-text">
      <h6><?= t('appName') ?></h6>
      <span><?= $isRTL ? 'لوحة الإدارة' : 'Admin Panel' ?></span>
    </div>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= t('mainMenu') ?></span>
    <?php foreach ($nav['main'] as $item): ?>
    <a href="<?= adminPageUrl($item['page']) ?>" class="sidebar-nav-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
      <i class="bi <?= $item['icon'] ?> nav-icon"></i>
      <?= t($item['key']) ?>
      <?php if (!empty($item['badge'])): ?>
      <span class="badge-count"><?= $item['badge'] ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= t('administration') ?></span>
    <?php foreach ($nav['admin'] as $item): ?>
    <a href="<?= adminPageUrl($item['page']) ?>" class="sidebar-nav-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
      <i class="bi <?= $item['icon'] ?> nav-icon"></i>
      <?= t($item['key']) ?>
      <?php if (!empty($item['badge'])): ?>
      <span class="badge-count"><?= $item['badge'] ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= t('system') ?></span>
    <?php foreach ($nav['system'] as $item): ?>
    <a href="<?= adminPageUrl($item['page']) ?>" class="sidebar-nav-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
      <i class="bi <?= $item['icon'] ?> nav-icon"></i>
      <?= t($item['key']) ?>
      <?php if (!empty($item['badge'])): ?>
      <span class="badge-count"><?= $item['badge'] ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;flex-shrink:0;">
        <?= mb_substr($user['name'] ?? 'A', 0, 1, 'UTF-8') ?>
      </div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          <?= htmlspecialchars($user['name'] ?? '') ?>
        </div>
        <div style="font-size:10px;color:var(--sidebar-heading);">
          <?= $isRTL ? 'مدير النظام' : 'System Admin' ?>
        </div>
      </div>
      <a href="<?= BASE_URL ?>/logout.php" title="<?= $isRTL ? 'تسجيل الخروج' : 'Logout' ?>" style="color:var(--sidebar-heading);text-decoration:none;">
        <i class="bi bi-box-arrow-right" style="font-size:14px;"></i>
      </a>
    </div>
  </div>
</nav>
