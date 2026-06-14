<?php
require_once dirname(__DIR__) . '/includes/auth_check.php';
$pageTitle = t('settings');

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $k => $v) {
        if ($k === '_action') continue;
        dbExec('UPDATE settings SET value = ? WHERE key_name = ?', [trim($v), $k]);
    }
    $msg = $isRTL ? 'تم حفظ الإعدادات بنجاح' : 'Settings saved successfully';
}

$settingsRows = dbAll('SELECT * FROM settings ORDER BY group_name, id');
$settings = [];
foreach ($settingsRows as $r) { $settings[$r['key_name']] = $r; }

if (!$settings) {
    $default = [
        'app_name_ar'       => ['value'=>'نظام إدارة المنح',         'label_ar'=>'اسم التطبيق (عربي)',    'label_en'=>'App Name (Arabic)',     'group_name'=>'general'],
        'app_name_en'       => ['value'=>'Grant Management System',  'label_ar'=>'اسم التطبيق (إنجليزي)', 'label_en'=>'App Name (English)',    'group_name'=>'general'],
        'university_name'   => ['value'=>'جامعة الملك فهد للبترول',  'label_ar'=>'اسم الجامعة',           'label_en'=>'University Name',       'group_name'=>'general'],
        'default_currency'  => ['value'=>'SAR',                      'label_ar'=>'العملة الافتراضية',     'label_en'=>'Default Currency',      'group_name'=>'financial'],
        'max_grant_amount'  => ['value'=>'5000000',                  'label_ar'=>'الحد الأقصى للمنحة',   'label_en'=>'Max Grant Amount',      'group_name'=>'financial'],
        'allow_registration'=> ['value'=>'1',                        'label_ar'=>'السماح بالتسجيل',       'label_en'=>'Allow Registration',    'group_name'=>'security'],
        'session_timeout'   => ['value'=>'120',                      'label_ar'=>'مهلة الجلسة (دقيقة)',   'label_en'=>'Session Timeout (min)', 'group_name'=>'security'],
        'system_email'      => ['value'=>'system@kfupm.edu.sa',      'label_ar'=>'البريد الرسمي',         'label_en'=>'System Email',          'group_name'=>'notifications'],
        'maintenance_mode'  => ['value'=>'0',                        'label_ar'=>'وضع الصيانة',           'label_en'=>'Maintenance Mode',      'group_name'=>'system'],
        'system_version'    => ['value'=>'2.0.0',                    'label_ar'=>'إصدار النظام',          'label_en'=>'System Version',        'group_name'=>'system'],
    ];
    $settings = $default;
}

$groupIcons = ['general'=>'bi-gear-fill','financial'=>'bi-cash-stack','security'=>'bi-shield-check','notifications'=>'bi-bell-fill','system'=>'bi-cpu'];
$groupLabels_ar = ['general'=>'عام','financial'=>'المالية','security'=>'الأمان','notifications'=>'الإشعارات','system'=>'النظام'];
$groupLabels_en = ['general'=>'General','financial'=>'Financial','security'=>'Security','notifications'=>'Notifications','system'=>'System'];
$grouped = [];
foreach ($settings as $k => $s) { $grouped[$s['group_name']][$k] = $s; }

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
include dirname(__DIR__) . '/includes/app_header.php';
?>

<?php if ($msg): ?>
<div class="alert" style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:.875rem">
  <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<form method="POST">
<div class="row g-4">
  <!-- Left column: settings groups -->
  <div class="col-lg-8">
    <?php foreach($grouped as $group => $items): ?>
    <div class="card-custom mb-4">
      <div class="card-custom-header">
        <h5 class="card-custom-title">
          <i class="bi <?= $groupIcons[$group] ?? 'bi-gear' ?>" style="color:#6366f1"></i>
          <?= $isRTL ? ($groupLabels_ar[$group] ?? $group) : ($groupLabels_en[$group] ?? $group) ?>
        </h5>
      </div>
      <div class="card-custom-body">
        <div class="row g-3">
          <?php foreach($items as $key => $s): ?>
          <div class="col-md-6">
            <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
              <?= htmlspecialchars($isRTL ? $s['label_ar'] : $s['label_en']) ?>
            </label>
            <?php if (in_array($key, ['allow_registration','maintenance_mode'])): ?>
            <select name="<?= htmlspecialchars($key) ?>" class="form-select"
              style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px;font-family:'Cairo',sans-serif">
              <option value="1" <?= ($s['value']==='1') ? 'selected' : '' ?>><?= $isRTL ? 'مفعّل' : 'Enabled' ?></option>
              <option value="0" <?= ($s['value']==='0') ? 'selected' : '' ?>><?= $isRTL ? 'معطّل' : 'Disabled' ?></option>
            </select>
            <?php else: ?>
            <input type="text" name="<?= htmlspecialchars($key) ?>" class="form-control"
              value="<?= htmlspecialchars($s['value'] ?? '') ?>"
              style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px">
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Right column: actions & info -->
  <div class="col-lg-4">
    <div class="card-custom mb-4" style="position:sticky;top:80px">
      <div class="card-custom-header">
        <h5 class="card-custom-title">
          <i class="bi bi-floppy-fill" style="color:#10b981"></i>
          <?= $isRTL ? 'حفظ الإعدادات' : 'Save Settings' ?>
        </h5>
      </div>
      <div class="card-custom-body">
        <p style="color:var(--text-muted);font-size:.875rem;margin-bottom:16px">
          <?= $isRTL ? 'تأكد من مراجعة التغييرات قبل الحفظ. تؤثر بعض الإعدادات على جميع المستخدمين.' : 'Review your changes before saving. Some settings affect all users.' ?>
        </p>
        <button type="submit" class="btn-primary-sm w-100 mb-2" style="padding:12px;font-size:.9rem">
          <i class="bi bi-floppy-fill me-2"></i><?= $isRTL ? 'حفظ جميع الإعدادات' : 'Save All Settings' ?>
        </button>
        <a href="<?= BASE_URL ?>/admin/" class="btn-secondary-sm w-100" style="text-align:center;display:block;padding:10px">
          <?= $isRTL ? 'إلغاء' : 'Cancel' ?>
        </a>
      </div>
    </div>

    <!-- System Info -->
    <div class="card-custom">
      <div class="card-custom-header">
        <h5 class="card-custom-title">
          <i class="bi bi-info-circle-fill" style="color:#f59e0b"></i>
          <?= $isRTL ? 'معلومات النظام' : 'System Info' ?>
        </h5>
      </div>
      <div class="card-custom-body">
        <?php
        $sysInfo = [
          [$isRTL?'إصدار PHP':'PHP Version', PHP_VERSION],
          [$isRTL?'المنطقة الزمنية':'Timezone', date_default_timezone_get()],
          [$isRTL?'التاريخ':'Date', date('Y-m-d')],
          [$isRTL?'الذاكرة المستخدمة':'Memory Used', round(memory_get_usage()/1024/1024,2).' MB'],
        ];
        foreach($sysInfo as $i): ?>
        <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border-color);font-size:12px">
          <span style="color:var(--text-muted)"><?=$i[0]?></span>
          <span style="color:var(--text-secondary);font-weight:600"><?=$i[1]?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
</form>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
