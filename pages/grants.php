<?php
// pages/grants.php
$grants = [
  ['id'=>1,'titleAr'=>'نظام ذكاء اصطناعي لتشخيص الأمراض المبكرة','titleEn'=>'AI System for Early Disease Diagnosis','applicantAr'=>'د. أحمد الزهراني','applicantEn'=>'Dr. Ahmed Al-Zahrani','deptAr'=>'علوم الحاسب','deptEn'=>'Computer Science','type'=>'research','amount'=>'500,000','status'=>'funded','date'=>'2024-03-15','duration'=>24],
  ['id'=>2,'titleAr'=>'تطوير خلايا طاقة شمسية متقدمة الكفاءة','titleEn'=>'Advanced High-Efficiency Solar Cells','applicantAr'=>'د. فاطمة العمري','applicantEn'=>'Dr. Fatima Al-Omari','deptAr'=>'الهندسة الكيميائية','deptEn'=>'Chemical Eng.','type'=>'external','amount'=>'750,000','status'=>'review','date'=>'2024-04-02','duration'=>36],
  ['id'=>3,'titleAr'=>'تحسين عمليات البتروكيماويات باستخدام نمذجة CFD','titleEn'=>'Petrochemical Process Optimization via CFD','applicantAr'=>'د. عبدالله الحربي','applicantEn'=>'Dr. Abdullah Al-Harbi','deptAr'=>'هندسة البترول','deptEn'=>'Petroleum Eng.','type'=>'industry','amount'=>'1,200,000','status'=>'funded','date'=>'2024-01-20','duration'=>18],
  ['id'=>4,'titleAr'=>'أبحاث النانو تكنولوجي في معالجة المياه','titleEn'=>'Nanotechnology in Water Treatment Research','applicantAr'=>'د. سارة المالكي','applicantEn'=>'Dr. Sara Al-Malki','deptAr'=>'الهندسة الكيميائية','deptEn'=>'Chemical Eng.','type'=>'research','amount'=>'300,000','status'=>'pending','date'=>'2024-05-10','duration'=>12],
  ['id'=>5,'titleAr'=>'تطوير مواد بناء مستدامة منخفضة الكربون','titleEn'=>'Sustainable Low-Carbon Building Materials','applicantAr'=>'د. خالد الجهني','applicantEn'=>'Dr. Khalid Al-Johani','deptAr'=>'الهندسة المدنية','deptEn'=>'Civil Eng.','type'=>'external','amount'=>'450,000','status'=>'rejected','date'=>'2024-02-28','duration'=>24],
  ['id'=>6,'titleAr'=>'نمذجة وتحسين إدارة الموارد المائية','titleEn'=>'Water Resource Management Modeling','applicantAr'=>'د. نورة القحطاني','applicantEn'=>'Dr. Noura Al-Qahtani','deptAr'=>'هندسة البيئة','deptEn'=>'Environmental Eng.','type'=>'research','amount'=>'600,000','status'=>'review','date'=>'2024-04-18','duration'=>30],
  ['id'=>7,'titleAr'=>'دراسة آليات الفشل في الهياكل المعدنية','titleEn'=>'Failure Mechanisms in Metal Structures','applicantAr'=>'د. محمد السبيعي','applicantEn'=>'Dr. Mohammed Al-Subai','deptAr'=>'هندسة الميكانيكا','deptEn'=>'Mechanical Eng.','type'=>'research','amount'=>'380,000','status'=>'funded','date'=>'2024-03-05','duration'=>18],
  ['id'=>8,'titleAr'=>'برنامج التميز الأكاديمي للطلاب الموهوبين','titleEn'=>'Academic Excellence Program for Gifted Students','applicantAr'=>'أ. ريم الشمري','applicantEn'=>'Ms. Reem Al-Shammari','deptAr'=>'شؤون الطلاب','deptEn'=>'Student Affairs','type'=>'scholarship','amount'=>'120,000','status'=>'funded','date'=>'2024-01-10','duration'=>12],
];

$typeConfig = [
  'research'   =>['ar'=>'بحثية',  'en'=>'Research',    'color'=>'#1a56db'],
  'external'   =>['ar'=>'خارجية', 'en'=>'External',    'color'=>'#8b5cf6'],
  'industry'   =>['ar'=>'صناعية', 'en'=>'Industry',    'color'=>'#f59e0b'],
  'scholarship'=>['ar'=>'دراسية', 'en'=>'Scholarship', 'color'=>'#06b6d4'],
];

$statusConfig = [
  'funded'   =>['ar'=>'ممول',          'en'=>'Funded',       'cls'=>'funded',   'icon'=>'bi-check-circle-fill'],
  'pending'  =>['ar'=>'قيد الانتظار',  'en'=>'Pending',      'cls'=>'pending',  'icon'=>'bi-clock-fill'],
  'review'   =>['ar'=>'تحت المراجعة', 'en'=>'Under Review', 'cls'=>'review',   'icon'=>'bi-eye-fill'],
  'rejected' =>['ar'=>'مرفوض',        'en'=>'Rejected',     'cls'=>'rejected', 'icon'=>'bi-x-circle-fill'],
];

// Filters
$activeStatus = $_GET['status'] ?? 'all';
$activeType   = $_GET['type']   ?? 'all';
$search       = trim($_GET['q'] ?? '');

$filtered = array_filter($grants, function($g) use ($activeStatus, $activeType, $search, $isRTL) {
  $title = $isRTL ? $g['titleAr'] : $g['titleEn'];
  $applicant = $isRTL ? $g['applicantAr'] : $g['applicantEn'];
  $matchSearch = !$search || stripos($title,$search)!==false || stripos($applicant,$search)!==false;
  $matchStatus = $activeStatus === 'all' || $g['status'] === $activeStatus;
  $matchType   = $activeType   === 'all' || $g['type']   === $activeType;
  return $matchSearch && $matchStatus && $matchType;
});

$statusCounts = ['all'=>count($grants),'funded'=>0,'review'=>0,'pending'=>0,'rejected'=>0];
foreach ($grants as $g) $statusCounts[$g['status']] = ($statusCounts[$g['status']]??0)+1;

$statCards = [
  ['status'=>'all',     'ar'=>'إجمالي الطلبات','en'=>'Total Applications','color'=>'#1a56db','icon'=>'bi-file-earmark-text-fill'],
  ['status'=>'funded',  'ar'=>'طلبات ممولة',   'en'=>'Funded',           'color'=>'#22c55e','icon'=>'bi-check-circle-fill'],
  ['status'=>'review',  'ar'=>'تحت المراجعة',  'en'=>'Under Review',     'color'=>'#3b82f6','icon'=>'bi-eye-fill'],
  ['status'=>'pending', 'ar'=>'قيد الانتظار',  'en'=>'Pending',          'color'=>'#f59e0b','icon'=>'bi-clock-fill'],
  ['status'=>'rejected','ar'=>'مرفوضة',         'en'=>'Rejected',        'color'=>'#ef4444','icon'=>'bi-x-circle-fill'],
];

// View item
$viewId = (int)($_GET['view'] ?? 0);
$viewItem = null;
foreach ($grants as $g) { if ($g['id'] === $viewId) { $viewItem = $g; break; } }
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <?php foreach ($statCards as $s): ?>
  <div class="col-6 col-md-4 col-xl">
    <a href="?page=grants&lang=<?= $lang ?>&status=<?= $s['status'] ?>&type=<?= $activeType ?>&q=<?= urlencode($search) ?>" style="text-decoration:none;">
      <div class="stat-card" style="cursor:pointer;border:<?= $activeStatus===$s['status']?'2px solid '.$s['color']:'1px solid #e9edf5' ?>;">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <div class="stat-value" style="font-size:26px;"><?= $statusCounts[$s['status']] ?></div>
            <div class="stat-label"><?= $isRTL?$s['ar']:$s['en'] ?></div>
          </div>
          <i class="bi <?= $s['icon'] ?>" style="font-size:28px;color:<?= $s['color'] ?>;opacity:0.7;"></i>
        </div>
      </div>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
  <form method="get" action="">
    <input type="hidden" name="page" value="grants">
    <input type="hidden" name="lang" value="<?= $lang ?>">
    <input type="hidden" name="status" value="<?= htmlspecialchars($activeStatus) ?>">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <div class="search-box">
          <input type="text" name="q" class="form-control" placeholder="<?= $isRTL?'ابحث عن طلب...':'Search applications...' ?>" value="<?= htmlspecialchars($search) ?>">
          <i class="bi bi-search search-icon"></i>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <select name="type" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($typeConfig as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $activeType===$k?'selected':'' ?>><?= $isRTL?$v['ar']:$v['en'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-5 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i><?= t('search') ?></button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newGrantModal"><i class="bi bi-plus me-1"></i><?= $isRTL?'طلب جديد':'New Application' ?></button>
        <button type="button" class="btn btn-outline-secondary"><i class="bi bi-download"></i></button>
      </div>
    </div>
  </form>
</div>

<!-- Table -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-table me-2 text-primary"></i><?= $isRTL?'قائمة طلبات المنح':'Grant Applications List' ?></h5>
    <span class="badge bg-secondary" style="font-size:12px;"><?= count($filtered) ?> <?= $isRTL?'نتيجة':'results' ?></span>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= $isRTL?'عنوان المشروع':'Project Title' ?></th>
          <th><?= t('applicant') ?></th>
          <th><?= $isRTL?'النوع':'Type' ?></th>
          <th><?= t('amount') ?> (<?= t('sar') ?>)</th>
          <th><?= $isRTL?'المدة (شهر)':'Duration (mo)' ?></th>
          <th><?= t('status') ?></th>
          <th><?= t('submissionDate') ?></th>
          <th><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($filtered)): ?>
        <tr><td colspan="9" class="text-center py-5 text-muted"><?= t('noData') ?></td></tr>
        <?php else: foreach ($filtered as $g):
          $sc = $statusConfig[$g['status']];
          $tc = $typeConfig[$g['type']]; ?>
        <tr>
          <td><span class="text-muted" style="font-size:11px;">#<?= $g['id'] ?></span></td>
          <td>
            <div style="font-weight:600;font-size:13px;max-width:280px;"><?= htmlspecialchars($isRTL?$g['titleAr']:$g['titleEn']) ?></div>
            <div style="font-size:11px;color:#64748b;"><?= htmlspecialchars($isRTL?$g['deptAr']:$g['deptEn']) ?></div>
          </td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="avatar avatar-sm" style="background:hsl(<?= $g['id']*47 ?>,65%,55%);color:#fff;flex-shrink:0;">
                <?= mb_substr($isRTL?$g['applicantAr']:$g['applicantEn'],3,1,'UTF-8') ?>
              </div>
              <span style="font-size:12px;"><?= htmlspecialchars($isRTL?$g['applicantAr']:$g['applicantEn']) ?></span>
            </div>
          </td>
          <td>
            <span class="badge rounded-pill" style="background:<?= $tc['color'] ?>18;color:<?= $tc['color'] ?>;font-size:11px;">
              <?= $isRTL?$tc['ar']:$tc['en'] ?>
            </span>
          </td>
          <td><span class="amount-text" style="font-size:13px;"><?= $g['amount'] ?></span></td>
          <td style="text-align:center;font-weight:700;"><?= $g['duration'] ?></td>
          <td><span class="status-badge <?= $sc['cls'] ?>"><i class="bi <?= $sc['icon'] ?>" style="font-size:11px;"></i><?= $isRTL?$sc['ar']:$sc['en'] ?></span></td>
          <td style="font-size:12px;color:#64748b;"><?= $g['date'] ?></td>
          <td>
            <div class="d-flex gap-1">
              <a href="?page=grants&lang=<?= $lang ?>&view=<?= $g['id'] ?>" class="btn btn-sm btn-icon btn-outline-primary"><i class="bi bi-eye" style="font-size:12px;"></i></a>
              <button class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-pencil" style="font-size:12px;"></i></button>
              <button class="btn btn-sm btn-icon btn-outline-danger"><i class="bi bi-trash" style="font-size:12px;"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- View Modal -->
<?php if ($viewItem): $sc = $statusConfig[$viewItem['status']]; $tc = $typeConfig[$viewItem['type']]; ?>
<div class="custom-modal-overlay" onclick="if(event.target===this) window.location='?page=grants&lang=<?= $lang ?>'">
  <div class="custom-modal" style="max-width:640px;">
    <div class="custom-modal-header">
      <div>
        <h5 style="margin:0;font-size:16px;font-weight:700;"><?= htmlspecialchars($isRTL?$viewItem['titleAr']:$viewItem['titleEn']) ?></h5>
        <span class="status-badge <?= $sc['cls'] ?> mt-1"><?= $isRTL?$sc['ar']:$sc['en'] ?></span>
      </div>
      <a href="?page=grants&lang=<?= $lang ?>" class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </div>
    <div class="custom-modal-body">
      <div class="row g-3">
        <div class="col-6">
          <div style="font-size:12px;color:#64748b;"><?= $isRTL?'مقدم الطلب':'Applicant' ?></div>
          <div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($isRTL?$viewItem['applicantAr']:$viewItem['applicantEn']) ?></div>
        </div>
        <div class="col-6">
          <div style="font-size:12px;color:#64748b;"><?= $isRTL?'القسم':'Department' ?></div>
          <div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($isRTL?$viewItem['deptAr']:$viewItem['deptEn']) ?></div>
        </div>
        <div class="col-6">
          <div style="font-size:12px;color:#64748b;"><?= $isRTL?'النوع':'Type' ?></div>
          <span class="badge rounded-pill" style="background:<?= $tc['color'] ?>18;color:<?= $tc['color'] ?>"><?= $isRTL?$tc['ar']:$tc['en'] ?></span>
        </div>
        <div class="col-6">
          <div style="font-size:12px;color:#64748b;"><?= $isRTL?'المبلغ (ريال)':'Amount (SAR)' ?></div>
          <div class="amount-text" style="font-weight:700;"><?= $viewItem['amount'] ?></div>
        </div>
        <div class="col-6">
          <div style="font-size:12px;color:#64748b;"><?= $isRTL?'المدة':'Duration' ?></div>
          <div style="font-weight:600;"><?= $viewItem['duration'] ?> <?= $isRTL?'شهراً':'months' ?></div>
        </div>
        <div class="col-6">
          <div style="font-size:12px;color:#64748b;"><?= t('submissionDate') ?></div>
          <div style="font-weight:600;"><?= $viewItem['date'] ?></div>
        </div>
      </div>
    </div>
    <div class="custom-modal-footer">
      <a href="?page=grants&lang=<?= $lang ?>" class="btn btn-outline-secondary"><?= t('close') ?></a>
      <button class="btn btn-primary"><i class="bi bi-pencil me-1"></i><?= t('edit') ?></button>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- New Grant Modal -->
<div class="modal fade" id="newGrantModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header" style="border-bottom:1px solid #f1f5f9;">
        <h5 class="modal-title" style="font-weight:700;"><?= $isRTL?'تقديم طلب منحة جديد':'New Grant Application' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <?php
          $fields = [
            ['ar'=>'عنوان المشروع (عربي)','en'=>'Project Title (Arabic)','type'=>'text'],
            ['ar'=>'عنوان المشروع (إنجليزي)','en'=>'Project Title (English)','type'=>'text'],
            ['ar'=>'المبلغ المطلوب (ريال)','en'=>'Requested Amount (SAR)','type'=>'number'],
            ['ar'=>'مدة المشروع (أشهر)','en'=>'Duration (months)','type'=>'number'],
            ['ar'=>'ملخص المشروع','en'=>'Project Summary','type'=>'textarea'],
            ['ar'=>'الأهداف والمخرجات','en'=>'Objectives & Deliverables','type'=>'textarea'],
          ];
          foreach ($fields as $f): ?>
          <div class="col-<?= $f['type']==='textarea'?'12':'6' ?>">
            <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
            <?php if ($f['type']==='textarea'): ?>
            <textarea class="form-control" rows="2"></textarea>
            <?php else: ?>
            <input type="<?= $f['type'] ?>" class="form-control">
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer" style="border-top:1px solid #f1f5f9;">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('cancel') ?></button>
        <button type="button" class="btn btn-primary"><i class="bi bi-send me-1"></i><?= t('submit') ?></button>
      </div>
    </div>
  </div>
</div>
