<nav class="sidebar" id="mainSidebar">
  <div class="sidebar-logo">
    <div class="logo-icon"><i class="bi bi-shield-fill-check"></i></div>
    <div class="logo-text">
      <h6><?= t('appName') ?></h6>
      <span><?= $isRTL ? 'لوحة الإدارة' : 'Admin Panel' ?></span>
    </div>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= $isRTL ? 'القائمة الرئيسية' : 'Main Menu' ?></span>
    <?php
    $menuItems = [
      ['p'=>'dashboard',     'i'=>'bi-grid-fill',                  'ar'=>'لوحة التحكم',      'en'=>'Dashboard'],
      ['p'=>'grants',        'i'=>'bi-file-earmark-text-fill',      'ar'=>'المنح',            'en'=>'Grants'],
      ['p'=>'projects',      'i'=>'bi-journal-code',                'ar'=>'المشاريع',         'en'=>'Projects'],
      ['p'=>'budget',        'i'=>'bi-wallet2',                    'ar'=>'الميزانية',         'en'=>'Budget'],
      ['p'=>'donors',        'i'=>'bi-bank2',                      'ar'=>'الجهات المانحة',   'en'=>'Donors'],
      ['p'=>'researchers',   'i'=>'bi-person-badge',               'ar'=>'الباحثون',         'en'=>'Researchers'],
      ['p'=>'students',      'i'=>'bi-mortarboard-fill',           'ar'=>'الطلاب',           'en'=>'Students'],
      ['p'=>'notifications', 'i'=>'bi-bell-fill',                  'ar'=>'الإشعارات',        'en'=>'Notifications'],
      ['p'=>'reports',       'i'=>'bi-bar-chart-fill',              'ar'=>'التقارير',         'en'=>'Reports'],
      ['p'=>'audit',         'i'=>'bi-shield-check',               'ar'=>'سجل المراجعة',     'en'=>'Audit Log'],
    ];
    $pg = $currentPage ?? 'dashboard';
    foreach($menuItems as $it):
      $isActive = ($pg === $it['p']);
    ?>
    <a href="?page=<?=$it['p']?>&lang=<?=$lang?>" class="sidebar-nav-item <?= $isActive ? 'active' : '' ?>">
      <i class="bi <?=$it['i']?> nav-icon"></i>
      <?= $isRTL ? $it['ar'] : $it['en'] ?>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="sidebar-section">
    <span class="sidebar-section-title"><?= $isRTL ? 'الإعدادات' : 'Settings' ?></span>
    <a href="?page=settings&lang=<?=$lang?>" class="sidebar-nav-item <?= $pg==='settings'?'active':'' ?>">
      <i class="bi bi-gear-fill nav-icon"></i><?= $isRTL ? 'الإعدادات' : 'Settings' ?>
    </a>
    <a href="?page=users&lang=<?=$lang?>" class="sidebar-nav-item <?= $pg==='users'?'active':'' ?>">
      <i class="bi bi-people-fill nav-icon"></i><?= $isRTL ? 'المستخدمين' : 'Users' ?>
    </a>
  </div>

  <div class="sidebar-footer">
    <div class="d-flex align-items-center gap-2">
      <div class="avatar avatar-sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff">
        <?= mb_substr($user['name'] ?? 'A', 0, 1) ?>
      </div>
      <div style="flex:1;min-width:0">
        <div style="font-size:12px;font-weight:700;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
          <?= htmlspecialchars($user['name'] ?? 'Admin') ?>
        </div>
        <div style="font-size:10px;color:var(--sidebar-heading)"><?= $isRTL ? 'مدير النظام' : 'Administrator' ?></div>
      </div>
      <a href="<?= BASE_URL ?>/logout.php" title="<?= $isRTL?'تسجيل الخروج':'Logout' ?>">
        <i class="bi bi-box-arrow-right" style="color:var(--sidebar-heading);font-size:16px;"></i>
      </a>
    </div>
  </div>
</nav>

<div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:auto">
