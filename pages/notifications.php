<?php
// pages/notifications.php
$notifs = [
  ['id'=>1,'type'=>'approval','icon'=>'bi-check-circle-fill','color'=>'#22c55e','titleAr'=>'تمت الموافقة على المنحة','titleEn'=>'Grant Application Approved','bodyAr'=>'تمت الموافقة على منحة مشروع الذكاء الاصطناعي لتشخيص الأمراض بمبلغ 500,000 ريال.','bodyEn'=>'The AI Disease Diagnosis grant application has been approved for SAR 500,000.','time'=>'2024-05-20 09:15','read'=>false],
  ['id'=>2,'type'=>'reminder','icon'=>'bi-alarm-fill','color'=>'#f59e0b','titleAr'=>'تذكير: موعد تسليم التقرير','titleEn'=>'Reminder: Report Submission Deadline','bodyAr'=>'تذكير: موعد تسليم تقرير الإنجاز لمشروع التقاط الكربون خلال 3 أيام.','bodyEn'=>'Reminder: Progress report for Carbon Capture project due in 3 days.','time'=>'2024-05-19 14:30','read'=>false],
  ['id'=>3,'type'=>'alert','icon'=>'bi-exclamation-triangle-fill','color'=>'#ef4444','titleAr'=>'تحذير: ميزانية المشروع تقترب من الحد','titleEn'=>'Alert: Project Budget Near Limit','bodyAr'=>'اقتربت ميزانية مشروع مواد النانو من حد الإنفاق (85% مصروف).','bodyEn'=>'Nano Materials project budget is approaching the spending limit (85% spent).','time'=>'2024-05-19 11:00','read'=>false],
  ['id'=>4,'type'=>'system','icon'=>'bi-gear-fill','color'=>'#1a56db','titleAr'=>'تحديث النظام','titleEn'=>'System Update','bodyAr'=>'تم تحديث نظام إدارة المنح بنجاح إلى الإصدار 2.4.1.','bodyEn'=>'Grant Management System successfully updated to version 2.4.1.','time'=>'2024-05-18 08:00','read'=>true],
  ['id'=>5,'type'=>'approval','icon'=>'bi-check-circle-fill','color'=>'#22c55e','titleAr'=>'طلب جديد يحتاج مراجعتك','titleEn'=>'New Application Needs Your Review','bodyAr'=>'قدّم د. خالد الجهني طلب منحة جديداً لمشروع المواد الإنشائية المستدامة.','bodyEn'=>'Dr. Khalid Al-Johani submitted a new grant application for Sustainable Structures.','time'=>'2024-05-18 16:45','read'=>true],
  ['id'=>6,'type'=>'reminder','icon'=>'bi-alarm-fill','color'=>'#f59e0b','titleAr'=>'اجتماع لجنة التمويل','titleEn'=>'Funding Committee Meeting','bodyAr'=>'اجتماع لجنة مراجعة المنح غداً الساعة 10:00 صباحاً في قاعة الاجتماعات الرئيسية.','bodyEn'=>'Grant Review Committee meeting tomorrow at 10:00 AM in the Main Conference Room.','time'=>'2024-05-17 12:00','read'=>true],
  ['id'=>7,'type'=>'alert','icon'=>'bi-exclamation-triangle-fill','color'=>'#ef4444','titleAr'=>'رفض طلب المنحة','titleEn'=>'Grant Application Rejected','bodyAr'=>'رُفض طلب منحة مشروع المواد الإنشائية المستدامة. يمكنك الاطلاع على أسباب الرفض.','bodyEn'=>'Sustainable Building Materials grant application was rejected. View the reasons.','time'=>'2024-05-16 15:30','read'=>true],
  ['id'=>8,'type'=>'system','icon'=>'bi-gear-fill','color'=>'#1a56db','titleAr'=>'انتهاء صلاحية الحساب قريباً','titleEn'=>'Account Expiring Soon','bodyAr'=>'ستنتهي صلاحية حساب المستخدم د. نورة القحطاني خلال 30 يوماً. يرجى التجديد.','bodyEn'=>"Dr. Noura Al-Qahtani's account expires in 30 days. Please renew.",'time'=>'2024-05-15 09:00','read'=>true],
  ['id'=>9,'type'=>'approval','icon'=>'bi-check-circle-fill','color'=>'#22c55e','titleAr'=>'إفراج مالي مكتمل','titleEn'=>'Financial Release Completed','bodyAr'=>'تم إفراج الدفعة الثانية (250,000 ريال) لمشروع تطوير خلايا الطاقة الشمسية.','bodyEn'=>'Second installment (SAR 250,000) released for Solar Cell Development project.','time'=>'2024-05-14 10:15','read'=>true],
  ['id'=>10,'type'=>'reminder','icon'=>'bi-alarm-fill','color'=>'#f59e0b','titleAr'=>'انتهاء مدة المشروع قريباً','titleEn'=>'Project Ending Soon','bodyAr'=>'مشروع تحليل شبكات الكهرباء الذكية سينتهي خلال 45 يوماً. تأكد من تسليم التقرير النهائي.','bodyEn'=>'Smart Grid Analytics project ends in 45 days. Ensure final report submission.','time'=>'2024-05-13 08:30','read'=>true],
];

$typeFilters = [
  ['key'=>'all',     'ar'=>'الكل',    'en'=>'All',      'icon'=>'bi-bell-fill',                  'color'=>'#1a56db'],
  ['key'=>'approval','ar'=>'موافقات', 'en'=>'Approvals','icon'=>'bi-check-circle-fill',           'color'=>'#22c55e'],
  ['key'=>'reminder','ar'=>'تذكيرات', 'en'=>'Reminders','icon'=>'bi-alarm-fill',                  'color'=>'#f59e0b'],
  ['key'=>'alert',   'ar'=>'تنبيهات', 'en'=>'Alerts',   'icon'=>'bi-exclamation-triangle-fill',   'color'=>'#ef4444'],
  ['key'=>'system',  'ar'=>'نظام',    'en'=>'System',   'icon'=>'bi-gear-fill',                    'color'=>'#1a56db'],
];

$filterType = $_GET['ftype'] ?? 'all';
$filterRead = $_GET['fread'] ?? 'all';
$markAllRead = isset($_GET['markall']);
$markId     = (int)($_GET['markid'] ?? 0);
$deleteId   = (int)($_GET['deleteid'] ?? 0);

// Apply mark all read
if ($markAllRead) { foreach ($notifs as &$n) $n['read'] = true; unset($n); }

// Apply mark single read
if ($markId) { foreach ($notifs as &$n) { if ($n['id']===$markId) $n['read']=true; } unset($n); }

// Apply delete
if ($deleteId) { $notifs = array_values(array_filter($notifs,fn($n)=>$n['id']!==$deleteId)); }

$unreadCount = count(array_filter($notifs,fn($n)=>!$n['read']));

$typeCounts = ['all'=>count($notifs),'approval'=>0,'reminder'=>0,'alert'=>0,'system'=>0];
foreach ($notifs as $n) $typeCounts[$n['type']] = ($typeCounts[$n['type']]??0)+1;

$filtered = array_filter($notifs, function($n) use ($filterType,$filterRead) {
  $matchType = $filterType==='all' || $n['type']===$filterType;
  $matchRead = $filterRead==='all' || ($filterRead==='unread'?!$n['read']:$n['read']);
  return $matchType && $matchRead;
});

$typeMap = [];
foreach ($typeFilters as $tf) $typeMap[$tf['key']] = $tf;
?>

<!-- Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
  <div class="d-flex align-items-center gap-3">
    <div style="background:rgba(26,86,219,0.1);border-radius:10px;width:46px;height:46px;display:flex;align-items:center;justify-content:center;color:var(--primary);font-size:22px;">
      <i class="bi bi-bell-fill"></i>
    </div>
    <div>
      <h5 style="margin:0;font-weight:700;"><?= $isRTL?'مركز الإشعارات':'Notification Center' ?></h5>
      <div style="font-size:13px;color:#64748b;">
        <?php if ($unreadCount > 0): ?>
        <?= $isRTL?"$unreadCount إشعار غير مقروء":"$unreadCount unread notifications" ?>
        <?php else: ?>
        <?= $isRTL?'جميع الإشعارات مقروءة':'All notifications read' ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="d-flex gap-2">
    <?php if ($unreadCount > 0): ?>
    <a href="?page=notifications&lang=<?= $lang ?>&markall=1&ftype=<?= $filterType ?>&fread=<?= $filterRead ?>" class="btn btn-sm btn-outline-primary">
      <i class="bi bi-check2-all me-1"></i><?= t('markAllRead') ?>
    </a>
    <?php endif; ?>
    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-gear me-1"></i><?= $isRTL?'الإعدادات':'Settings' ?></button>
  </div>
</div>

<!-- Type Filter Pills -->
<div class="d-flex flex-wrap gap-2 mb-4 align-items-center">
  <?php foreach ($typeFilters as $tf): ?>
  <a href="?page=notifications&lang=<?= $lang ?>&ftype=<?= $tf['key'] ?>&fread=<?= $filterRead ?>" style="text-decoration:none;">
    <button class="btn btn-sm <?= $filterType===$tf['key']?'btn-primary':'btn-outline-secondary' ?>" style="border-radius:20px;">
      <i class="bi <?= $tf['icon'] ?> me-1" style="font-size:12px;"></i>
      <?= $isRTL?$tf['ar']:$tf['en'] ?>
      <span class="badge rounded-pill ms-2" style="background:<?= $filterType===$tf['key']?'rgba(255,255,255,0.3)':'#f1f5f9' ?>;color:<?= $filterType===$tf['key']?'#fff':'#64748b' ?>;font-size:10px;">
        <?= $typeCounts[$tf['key']] ?? 0 ?>
      </span>
    </button>
  </a>
  <?php endforeach; ?>
  <div class="ms-auto">
    <form method="get" style="display:inline;">
      <input type="hidden" name="page" value="notifications">
      <input type="hidden" name="lang" value="<?= $lang ?>">
      <input type="hidden" name="ftype" value="<?= $filterType ?>">
      <select name="fread" class="form-select form-select-sm" style="border-radius:20px;" onchange="this.form.submit()">
        <option value="all" <?= $filterRead==='all'?'selected':'' ?>><?= $isRTL?'الكل':'All' ?></option>
        <option value="unread" <?= $filterRead==='unread'?'selected':'' ?>><?= $isRTL?'غير مقروء':'Unread' ?></option>
        <option value="read" <?= $filterRead==='read'?'selected':'' ?>><?= $isRTL?'مقروء':'Read' ?></option>
      </select>
    </form>
  </div>
</div>

<!-- Notification List -->
<div class="custom-card">
  <?php if (empty($filtered)): ?>
  <div class="text-center py-5">
    <i class="bi bi-bell-slash text-muted" style="font-size:48px;display:block;"></i>
    <div style="color:#94a3b8;font-size:14px;margin-top:12px;"><?= $isRTL?'لا توجد إشعارات':'No notifications' ?></div>
  </div>
  <?php else: foreach ($filtered as $n):
    $typeInfo = $typeMap[$n['type']] ?? $typeFilters[0];
  ?>
  <div class="notification-item <?= !$n['read']?'unread':'' ?>">
    <!-- Icon -->
    <div style="width:42px;height:42px;border-radius:12px;background:<?= $n['color'] ?>18;display:flex;align-items:center;justify-content:center;color:<?= $n['color'] ?>;font-size:18px;flex-shrink:0;">
      <i class="bi <?= $n['icon'] ?>"></i>
    </div>

    <!-- Content -->
    <div style="flex:1;min-width:0;">
      <div class="d-flex align-items-start justify-content-between gap-2">
        <div style="font-weight:<?= !$n['read']?700:500 ?>;font-size:14px;color:#1e293b;">
          <?= htmlspecialchars($isRTL?$n['titleAr']:$n['titleEn']) ?>
        </div>
        <div class="d-flex align-items-center gap-2 flex-shrink-0">
          <?php if (!$n['read']): ?>
          <span style="width:8px;height:8px;border-radius:50%;background:#1a56db;display:inline-block;"></span>
          <?php endif; ?>
          <span style="font-size:11px;color:#94a3b8;white-space:nowrap;"><?= explode(' ',$n['time'])[0] ?></span>
          <a href="?page=notifications&lang=<?= $lang ?>&deleteid=<?= $n['id'] ?>&ftype=<?= $filterType ?>&fread=<?= $filterRead ?>" class="btn btn-sm btn-icon btn-outline-secondary" style="width:26px;height:26px;font-size:11px;">
            <i class="bi bi-x"></i>
          </a>
        </div>
      </div>
      <div style="font-size:13px;color:#64748b;margin-top:3px;line-height:1.5;">
        <?= htmlspecialchars($isRTL?$n['bodyAr']:$n['bodyEn']) ?>
      </div>
      <div class="d-flex align-items-center gap-3 mt-2">
        <span class="badge rounded-pill" style="background:<?= $n['color'] ?>18;color:<?= $n['color'] ?>;font-size:10px;padding:3px 8px;">
          <i class="bi <?= $n['icon'] ?> me-1" style="font-size:8px;"></i>
          <?= $isRTL?$typeInfo['ar']:$typeInfo['en'] ?>
        </span>
        <span style="font-size:11px;color:#94a3b8;"><?= $n['time'] ?></span>
        <?php if (!$n['read']): ?>
        <a href="?page=notifications&lang=<?= $lang ?>&markid=<?= $n['id'] ?>&ftype=<?= $filterType ?>&fread=<?= $filterRead ?>" style="font-size:12px;color:#1a56db;text-decoration:none;">
          <?= $isRTL?'تحديد كمقروء':'Mark as read' ?>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<!-- Notification Settings -->
<div class="custom-card mt-4">
  <div class="card-header-custom">
    <h5><i class="bi bi-sliders me-2 text-primary"></i><?= $isRTL?'إعدادات الإشعارات':'Notification Preferences' ?></h5>
  </div>
  <div class="p-3">
    <div class="row g-3">
      <?php
      $prefs = [
        ['ar'=>'إشعارات الموافقة والرفض','en'=>'Approval & Rejection Notifications','checked'=>true],
        ['ar'=>'تذكيرات المواعيد النهائية','en'=>'Deadline Reminders','checked'=>true],
        ['ar'=>'تنبيهات الميزانية','en'=>'Budget Alerts','checked'=>true],
        ['ar'=>'إشعارات النظام','en'=>'System Notifications','checked'=>false],
        ['ar'=>'إشعارات البريد الإلكتروني','en'=>'Email Notifications','checked'=>true],
        ['ar'=>'إشعارات الرسائل القصيرة','en'=>'SMS Notifications','checked'=>false],
      ];
      foreach ($prefs as $pref): ?>
      <div class="col-12 col-md-6">
        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border" style="background:#f8fafc;">
          <span style="font-size:13px;font-weight:500;"><?= $isRTL?$pref['ar']:$pref['en'] ?></span>
          <div class="form-check form-switch mb-0">
            <input class="form-check-input" type="checkbox" <?= $pref['checked']?'checked':'' ?> style="cursor:pointer;">
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
