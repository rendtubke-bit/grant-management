<?php
// pages/audit.php
$logs = [
  ['id'=>1,'actionAr'=>'موافقة على منحة','actionEn'=>'Grant Approved','userAr'=>'د. أحمد الزهراني','userEn'=>'Dr. Ahmed Al-Zahrani','targetAr'=>'منحة الذكاء الاصطناعي #2024-001','targetEn'=>'AI Grant #2024-001','category'=>'grant','ip'=>'10.0.1.45','datetime'=>'2024-05-20 09:15:32'],
  ['id'=>2,'actionAr'=>'رفع تقرير مالي','actionEn'=>'Financial Report Upload','userAr'=>'نورة القحطاني','userEn'=>'Noura Al-Qahtani','targetAr'=>'مشروع الطاقة الشمسية','targetEn'=>'Solar Energy Project','category'=>'finance','ip'=>'10.0.1.52','datetime'=>'2024-05-20 08:42:11'],
  ['id'=>3,'actionAr'=>'تعديل بيانات باحث','actionEn'=>'Researcher Profile Edit','userAr'=>'مدير النظام','userEn'=>'System Admin','targetAr'=>'د. فاطمة العمري','targetEn'=>'Dr. Fatima Al-Omari','category'=>'user','ip'=>'10.0.0.1','datetime'=>'2024-05-19 16:30:05'],
  ['id'=>4,'actionAr'=>'إضافة مصروف','actionEn'=>'Expense Added','userAr'=>'خالد الجهني','userEn'=>'Khalid Al-Johani','targetAr'=>'ميزانية مشروع الخرسانة','targetEn'=>'Concrete Project Budget','category'=>'finance','ip'=>'10.0.1.78','datetime'=>'2024-05-19 14:22:47'],
  ['id'=>5,'actionAr'=>'تسجيل دخول','actionEn'=>'User Login','userAr'=>'سارة المالكي','userEn'=>'Sara Al-Malki','targetAr'=>'لوحة التحكم','targetEn'=>'Dashboard','category'=>'system','ip'=>'10.0.1.90','datetime'=>'2024-05-19 11:08:19'],
  ['id'=>6,'actionAr'=>'حذف طلب منحة','actionEn'=>'Grant Application Deleted','userAr'=>'مدير النظام','userEn'=>'System Admin','targetAr'=>'طلب منحة #2024-008','targetEn'=>'Grant App #2024-008','category'=>'grant','ip'=>'10.0.0.1','datetime'=>'2024-05-18 17:55:03'],
  ['id'=>7,'actionAr'=>'تغيير صلاحيات المستخدم','actionEn'=>'User Permissions Changed','userAr'=>'مدير النظام','userEn'=>'System Admin','targetAr'=>'محمد السبيعي','targetEn'=>'Mohammed Al-Subai','category'=>'user','ip'=>'10.0.0.1','datetime'=>'2024-05-18 15:10:44'],
  ['id'=>8,'actionAr'=>'إرسال إشعار بريدي','actionEn'=>'Email Notification Sent','userAr'=>'النظام التلقائي','userEn'=>'Automated System','targetAr'=>'7 مستخدمين','targetEn'=>'7 Users','category'=>'system','ip'=>'10.0.0.2','datetime'=>'2024-05-18 12:00:00'],
  ['id'=>9,'actionAr'=>'تصدير تقرير','actionEn'=>'Report Exported','userAr'=>'عبدالله الحربي','userEn'=>'Abdullah Al-Harbi','targetAr'=>'تقرير Q1 2024','targetEn'=>'Q1 2024 Report','category'=>'report','ip'=>'10.0.1.33','datetime'=>'2024-05-17 10:25:16'],
  ['id'=>10,'actionAr'=>'رفع ملف مرفق','actionEn'=>'Attachment Uploaded','userAr'=>'ريم الشمري','userEn'=>'Reem Al-Shammari','targetAr'=>'طلب منحة فيزياء المواد','targetEn'=>'Materials Physics App','category'=>'grant','ip'=>'10.0.1.61','datetime'=>'2024-05-17 09:12:58'],
  ['id'=>11,'actionAr'=>'تعديل إعدادات النظام','actionEn'=>'System Settings Changed','userAr'=>'مدير النظام','userEn'=>'System Admin','targetAr'=>'إعدادات البريد الإلكتروني','targetEn'=>'Email Settings','category'=>'system','ip'=>'10.0.0.1','datetime'=>'2024-05-16 16:45:22'],
  ['id'=>12,'actionAr'=>'إنشاء مشروع بحثي','actionEn'=>'Research Project Created','userAr'=>'فاطمة العمري','userEn'=>'Fatima Al-Omari','targetAr'=>'مشروع بحث النانو','targetEn'=>'Nano Research Project','category'=>'project','ip'=>'10.0.1.52','datetime'=>'2024-05-16 14:30:09'],
];

$catConfig = [
  'grant'  =>['ar'=>'منح',    'en'=>'Grant',    'color'=>'#1a56db','icon'=>'bi-file-earmark-text-fill'],
  'finance'=>['ar'=>'مالي',   'en'=>'Finance',  'color'=>'#22c55e','icon'=>'bi-cash-stack'],
  'user'   =>['ar'=>'مستخدم', 'en'=>'User',     'color'=>'#8b5cf6','icon'=>'bi-person-fill'],
  'system' =>['ar'=>'نظام',   'en'=>'System',   'color'=>'#f59e0b','icon'=>'bi-gear-fill'],
  'report' =>['ar'=>'تقرير',  'en'=>'Report',   'color'=>'#06b6d4','icon'=>'bi-bar-chart-fill'],
  'project'=>['ar'=>'مشروع',  'en'=>'Project',  'color'=>'#ec4899','icon'=>'bi-journal-code'],
];

$filterCat = $_GET['fcat'] ?? 'all';
$search    = trim($_GET['q'] ?? '');
$view      = $_GET['vw'] ?? 'table'; // table | timeline

$catCounts = ['all'=>count($logs)];
foreach ($logs as $l) $catCounts[$l['category']] = ($catCounts[$l['category']]??0)+1;

$filtered = array_filter($logs, function($l) use ($filterCat,$search,$isRTL) {
  $action = $isRTL?$l['actionAr']:$l['actionEn'];
  $user   = $isRTL?$l['userAr']:$l['userEn'];
  $matchCat    = $filterCat==='all' || $l['category']===$filterCat;
  $matchSearch = !$search || stripos($action,$search)!==false || stripos($user,$search)!==false || stripos($l['ip'],$search)!==false;
  return $matchCat && $matchSearch;
});
?>

<!-- Stat Cards (clickable) -->
<div class="row g-3 mb-4">
  <?php foreach ($catConfig as $k=>$v): ?>
  <div class="col-6 col-md-4 col-xl-2">
    <a href="?page=audit&lang=<?= $lang ?>&fcat=<?= ($filterCat===$k?'all':$k) ?>&q=<?= urlencode($search) ?>&vw=<?= $view ?>" style="text-decoration:none;">
      <div class="stat-card" style="padding:14px;cursor:pointer;border:<?= $filterCat===$k?'2px solid '.$v['color']:'1px solid #e9edf5' ?>;">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-value" style="font-size:24px;"><?= $catCounts[$k]??0 ?></div>
            <div class="stat-label" style="font-size:11px;"><?= $isRTL?$v['ar']:$v['en'] ?></div>
          </div>
          <i class="bi <?= $v['icon'] ?>" style="font-size:26px;color:<?= $v['color'] ?>;opacity:0.7;"></i>
        </div>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
  <form method="get" action="">
    <input type="hidden" name="page" value="audit">
    <input type="hidden" name="lang" value="<?= $lang ?>">
    <input type="hidden" name="fcat" value="<?= htmlspecialchars($filterCat) ?>">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <div class="search-box">
          <input type="text" name="q" class="form-control" placeholder="<?= $isRTL?'ابحث في السجلات...':'Search logs...' ?>" value="<?= htmlspecialchars($search) ?>">
          <i class="bi bi-search search-icon"></i>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <input type="date" name="dfrom" class="form-control" value="2024-05-01">
      </div>
      <div class="col-6 col-md-2">
        <input type="date" name="dto" class="form-control" value="2024-05-20">
      </div>
      <div class="col-12 col-md-4 d-flex gap-2 justify-content-end">
        <div class="d-flex border rounded-2 overflow-hidden">
          <a href="?page=audit&lang=<?= $lang ?>&fcat=<?= $filterCat ?>&q=<?= urlencode($search) ?>&vw=table" class="btn btn-sm <?= $view==='table'?'btn-primary':'btn-outline-secondary' ?>" style="border-radius:0;">
            <i class="bi bi-table me-1"></i><?= $isRTL?'جدول':'Table' ?>
          </a>
          <a href="?page=audit&lang=<?= $lang ?>&fcat=<?= $filterCat ?>&q=<?= urlencode($search) ?>&vw=timeline" class="btn btn-sm <?= $view==='timeline'?'btn-primary':'btn-outline-secondary' ?>" style="border-radius:0;">
            <i class="bi bi-clock-history me-1"></i><?= $isRTL?'خط زمني':'Timeline' ?>
          </a>
        </div>
        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-file-earmark-pdf me-1"></i><?= t('export') ?></button>
      </div>
    </div>
  </form>
</div>

<?php if ($view === 'table'): ?>
<!-- Table View -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-shield-check me-2 text-primary"></i><?= $isRTL?'سجل عمليات النظام':'System Operations Log' ?></h5>
    <span class="badge bg-secondary" style="font-size:12px;"><?= count($filtered) ?> <?= $isRTL?'سجل':'records' ?></span>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= $isRTL?'التاريخ والوقت':'Date & Time' ?></th>
          <th><?= $isRTL?'الإجراء':'Action' ?></th>
          <th><?= $isRTL?'المستخدم':'User' ?></th>
          <th><?= $isRTL?'الهدف':'Target' ?></th>
          <th><?= $isRTL?'الفئة':'Category' ?></th>
          <th>IP</th>
          <th><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($filtered as $l):
          $cc = $catConfig[$l['category']];
          $parts = explode(' ',$l['datetime']);
        ?>
        <tr>
          <td><span class="text-muted" style="font-size:11px;">#<?= $l['id'] ?></span></td>
          <td>
            <div style="font-size:12px;"><?= $parts[0] ?></div>
            <div style="font-size:11px;color:#94a3b8;"><?= $parts[1]??'' ?></div>
          </td>
          <td style="font-weight:600;font-size:13px;"><?= htmlspecialchars($isRTL?$l['actionAr']:$l['actionEn']) ?></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="avatar avatar-sm" style="background:hsl(<?= $l['id']*43 ?>,60%,55%);color:#fff;flex-shrink:0;">
                <?= mb_substr($isRTL?$l['userAr']:$l['userEn'],0,1,'UTF-8') ?>
              </div>
              <span style="font-size:12px;"><?= htmlspecialchars($isRTL?$l['userAr']:$l['userEn']) ?></span>
            </div>
          </td>
          <td style="font-size:12px;color:#475569;"><?= htmlspecialchars($isRTL?$l['targetAr']:$l['targetEn']) ?></td>
          <td>
            <span class="badge rounded-pill" style="background:<?= $cc['color'] ?>18;color:<?= $cc['color'] ?>;font-size:11px;">
              <i class="bi <?= $cc['icon'] ?> me-1" style="font-size:9px;"></i><?= $isRTL?$cc['ar']:$cc['en'] ?>
            </span>
          </td>
          <td><code style="font-size:11px;background:#f8fafc;padding:2px 6px;border-radius:4px;"><?= $l['ip'] ?></code></td>
          <td><button class="btn btn-sm btn-icon btn-outline-primary"><i class="bi bi-eye" style="font-size:12px;"></i></button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php else: ?>
<!-- Timeline View -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-clock-history me-2 text-primary"></i><?= $isRTL?'الخط الزمني للأحداث':'Events Timeline' ?></h5>
  </div>
  <div style="padding:20px;">
    <ul class="timeline">
      <?php foreach ($filtered as $l):
        $cc = $catConfig[$l['category']];
      ?>
      <li class="timeline-item">
        <div class="timeline-dot" style="background:<?= $cc['color'] ?>;color:#fff;">
          <i class="bi <?= $cc['icon'] ?>"></i>
        </div>
        <div style="background:#f8fafc;border-radius:10px;padding:14px 16px;border:1px solid #e2e8f0;">
          <div class="d-flex align-items-start justify-content-between gap-2">
            <div>
              <span style="font-weight:700;font-size:14px;color:#1e293b;"><?= htmlspecialchars($isRTL?$l['actionAr']:$l['actionEn']) ?></span>
              <span class="badge rounded-pill ms-2" style="background:<?= $cc['color'] ?>18;color:<?= $cc['color'] ?>;font-size:10px;"><?= $isRTL?$cc['ar']:$cc['en'] ?></span>
            </div>
            <span style="font-size:11px;color:#94a3b8;white-space:nowrap;"><?= $l['datetime'] ?></span>
          </div>
          <div style="font-size:13px;color:#64748b;margin-top:4px;">
            <i class="bi bi-person me-1"></i><?= htmlspecialchars($isRTL?$l['userAr']:$l['userEn']) ?>
            <span class="mx-2">·</span>
            <i class="bi bi-bullseye me-1"></i><?= htmlspecialchars($isRTL?$l['targetAr']:$l['targetEn']) ?>
            <span class="mx-2">·</span>
            <code style="font-size:11px;"><?= $l['ip'] ?></code>
          </div>
        </div>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
<?php endif; ?>
