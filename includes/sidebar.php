<?php
// includes/sidebar.php
$nav = [
    'main' => [
        ['page' => 'dashboard',   'icon' => 'bi-grid-fill',              'key' => 'dashboard',  'badge' => ''],
        ['page' => 'grants',      'icon' => 'bi-file-earmark-text-fill', 'key' => 'grants',     'badge' => '8'],
        ['page' => 'projects',    'icon' => 'bi-journal-code',           'key' => 'projects',   'badge' => ''],
        ['page' => 'budget',      'icon' => 'bi-cash-stack',             'key' => 'budget',     'badge' => ''],
        ['page' => 'reports',     'icon' => 'bi-bar-chart-fill',         'key' => 'reports',    'badge' => ''],
        ['page' => 'donors',      'icon' => 'bi-building-fill',          'key' => 'donors',     'badge' => ''],
    ],
    'admin' => [
        ['page' => 'researchers', 'icon' => 'bi-person-badge-fill',      'key' => 'researchers','badge' => ''],
        ['page' => 'students',    'icon' => 'bi-mortarboard-fill',       'key' => 'students',   'badge' => ''],
        ['page' => 'notifications','icon'=> 'bi-bell-fill',              'key' => 'notifications','badge'=> '3'],
    ],
    'system' => [
        ['page' => 'audit',       'icon' => 'bi-shield-check',           'key' => 'audit',      'badge' => ''],
        ['page' => 'settings',    'icon' => 'bi-gear-fill',              'key' => 'settings',   'badge' => ''],
        ['page' => 'users',       'icon' => 'bi-people-fill',            'key' => 'users',      'badge' => ''],
    ],
];
?>
<nav class="sidebar" id="mainSidebar">
  <!-- Logo -->
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
    <div class="logo-text">
      <h6><?= t('appName') ?></h6>
      <span><?= $isRTL ? 'KFUPM' : 'KFUPM' ?></span>
    </div>
  </div>

  <!-- Main Menu -->
  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= t('mainMenu') ?></span>
    <?php foreach ($nav['main'] as $item): ?>
    <a href="<?= pageUrl($item['page']) ?>" class="sidebar-nav-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
      <i class="bi <?= $item['icon'] ?> nav-icon"></i>
      <?= t($item['key']) ?>
      <?php if ($item['badge']): ?>
      <span class="badge-count"><?= $item['badge'] ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Administration -->
  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= t('administration') ?></span>
    <?php foreach ($nav['admin'] as $item): ?>
    <a href="<?= pageUrl($item['page']) ?>" class="sidebar-nav-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
      <i class="bi <?= $item['icon'] ?> nav-icon"></i>
      <?= t($item['key']) ?>
      <?php if ($item['badge']): ?>
      <span class="badge-count"><?= $item['badge'] ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- System -->
  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= t('system') ?></span>
    <?php foreach ($nav['system'] as $item): ?>
    <a href="<?= pageUrl($item['page']) ?>" class="sidebar-nav-item <?= ($currentPage === $item['page']) ? 'active' : '' ?>">
      <i class="bi <?= $item['icon'] ?> nav-icon"></i>
      <?= t($item['key']) ?>
      <?php if ($item['badge']): ?>
      <span class="badge-count"><?= $item['badge'] ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Footer -->
  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;flex-shrink:0;">م</div>
      <div style="flex:1;min-width:0;">
        <div style="font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
          <?= $isRTL ? 'د. محمد العمري' : 'Dr. M. Al-Omari' ?>
        </div>
        <div style="font-size:10px;color:var(--sidebar-heading);">
          <?= $isRTL ? 'مدير النظام' : 'System Admin' ?>
        </div>
      </div>
      <i class="bi bi-box-arrow-right" style="color:var(--sidebar-heading);font-size:14px;"></i>
    </div>
  </div>
</nav>
