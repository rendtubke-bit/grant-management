<?php
// includes/app_header.php — top application header bar
$pageTitles = [
    'ar' => [
        'dashboard'     => 'لوحة التحكم',
        'grants'        => 'طلبات المنح والتمويل',
        'projects'      => 'المشاريع البحثية',
        'budget'        => 'إدارة الميزانية',
        'reports'       => 'التقارير والتحليلات',
        'donors'        => 'بوابة الجهات المانحة',
        'researchers'   => 'الباحثون والمستفيدون',
        'students'      => 'طلبات منح الطلاب',
        'notifications' => 'مركز الإشعارات',
        'audit'         => 'سجل المراجعة والتدقيق',
        'settings'      => 'إعدادات النظام',
        'users'         => 'إدارة المستخدمين والصلاحيات',
    ],
    'en' => [
        'dashboard'     => 'Dashboard',
        'grants'        => 'Grant Applications',
        'projects'      => 'Research Projects',
        'budget'        => 'Budget Management',
        'reports'       => 'Reports & Analytics',
        'donors'        => 'Donors Portal',
        'researchers'   => 'Researchers',
        'students'      => 'Student Grant Applications',
        'notifications' => 'Notification Center',
        'audit'         => 'Audit Log',
        'settings'      => 'System Settings',
        'users'         => 'User Management',
    ],
];
$title = $pageTitles[$lang][$currentPage] ?? t($currentPage);
?>
<header class="app-header">
  <div class="header-left">
    <button class="sidebar-toggle-btn" onclick="toggleSidebar()">
      <i class="bi bi-list"></i>
    </button>
    <div>
      <h1 class="page-title"><?= htmlspecialchars($title) ?></h1>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="<?= pageUrl('dashboard') ?>" style="color:#1a56db;text-decoration:none;">
              <?= $isRTL ? 'الرئيسية' : 'Home' ?>
            </a>
          </li>
          <?php if ($currentPage !== 'dashboard'): ?>
          <li class="breadcrumb-item active"><?= htmlspecialchars($title) ?></li>
          <?php endif; ?>
        </ol>
      </nav>
    </div>
  </div>

  <div class="header-right">
    <!-- Lang Toggle -->
    <div class="lang-toggle">
      <a href="<?= langUrl('ar') ?>" style="text-decoration:none;">
        <button class="<?= $lang === 'ar' ? 'active' : '' ?>">ع</button>
      </a>
      <a href="<?= langUrl('en') ?>" style="text-decoration:none;">
        <button class="<?= $lang === 'en' ? 'active' : '' ?>">EN</button>
      </a>
    </div>

    <!-- Notifications Bell -->
    <a href="<?= pageUrl('notifications') ?>" style="text-decoration:none;position:relative;">
      <button class="btn btn-sm btn-outline-secondary btn-icon">
        <i class="bi bi-bell"></i>
        <span style="position:absolute;top:-4px;<?= $isRTL?'left':'right' ?>:-4px;width:16px;height:16px;background:#ef4444;border-radius:50%;font-size:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">3</span>
      </button>
    </a>

    <!-- User Avatar -->
    <div class="avatar" style="background:linear-gradient(135deg,#1a56db,#7c3aed);color:#fff;cursor:pointer;" title="<?= $isRTL ? 'د. محمد العمري' : 'Dr. M. Al-Omari' ?>">م</div>
  </div>
</header>
