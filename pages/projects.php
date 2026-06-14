<?php
// pages/projects.php
$projects = [
  ['id'=>1,'titleAr'=>'تطوير مواد نانوية لتخزين الطاقة','titleEn'=>'Nano Materials for Energy Storage','piAr'=>'د. أحمد الزهراني','piEn'=>'Dr. Ahmed Al-Zahrani','dept'=>'Chemical Eng.','budget'=>1200000,'spent'=>840000,'progress'=>70,'start'=>'2023-01-15','end'=>'2025-01-14','status'=>'active','pubCount'=>4,'patentCount'=>1,'milestonesTotal'=>8,'milestoneDone'=>6],
  ['id'=>2,'titleAr'=>'الذكاء الاصطناعي في تشخيص أمراض القلب','titleEn'=>'AI in Cardiac Disease Diagnosis','piAr'=>'د. فاطمة العمري','piEn'=>'Dr. Fatima Al-Omari','dept'=>'Computer Science','budget'=>900000,'spent'=>405000,'progress'=>45,'start'=>'2024-02-01','end'=>'2026-01-31','status'=>'active','pubCount'=>2,'patentCount'=>0,'milestonesTotal'=>10,'milestoneDone'=>4],
  ['id'=>3,'titleAr'=>'تقنيات التقاط وتخزين ثاني أكسيد الكربون','titleEn'=>'Carbon Capture & Storage Technologies','piAr'=>'د. عبدالله الحربي','piEn'=>'Dr. Abdullah Al-Harbi','dept'=>'Petroleum Eng.','budget'=>2100000,'spent'=>1890000,'progress'=>90,'start'=>'2022-06-01','end'=>'2024-12-31','status'=>'active','pubCount'=>7,'patentCount'=>3,'milestonesTotal'=>12,'milestoneDone'=>11],
  ['id'=>4,'titleAr'=>'تحسين أداء الخرسانة في البيئات الساحلية','titleEn'=>'Concrete Performance in Coastal Environments','piAr'=>'د. سارة المالكي','piEn'=>'Dr. Sara Al-Malki','dept'=>'Civil Eng.','budget'=>650000,'spent'=>195000,'progress'=>30,'start'=>'2024-04-01','end'=>'2026-03-31','status'=>'active','pubCount'=>1,'patentCount'=>0,'milestonesTotal'=>8,'milestoneDone'=>2],
  ['id'=>5,'titleAr'=>'طرق ترشيح المياه باستخدام الأغشية التقنية','titleEn'=>'Advanced Membrane Water Filtration','piAr'=>'د. خالد الجهني','piEn'=>'Dr. Khalid Al-Johani','dept'=>'Env. Eng.','budget'=>850000,'spent'=>510000,'progress'=>60,'start'=>'2023-08-01','end'=>'2025-07-31','status'=>'active','pubCount'=>3,'patentCount'=>1,'milestonesTotal'=>9,'milestoneDone'=>5],
  ['id'=>6,'titleAr'=>'تحليل بيانات الشبكات الذكية الكهربائية','titleEn'=>'Smart Electrical Grid Data Analytics','piAr'=>'د. نورة القحطاني','piEn'=>'Dr. Noura Al-Qahtani','dept'=>'Electrical Eng.','budget'=>780000,'spent'=>780000,'progress'=>100,'start'=>'2022-01-01','end'=>'2024-06-30','status'=>'completed','pubCount'=>5,'patentCount'=>2,'milestonesTotal'=>10,'milestoneDone'=>10],
];

$statusConfig = [
  'active'    =>['ar'=>'نشط',   'en'=>'Active',    'cls'=>'active',   'color'=>'#22c55e'],
  'completed' =>['ar'=>'مكتمل', 'en'=>'Completed', 'cls'=>'completed','color'=>'#64748b'],
  'suspended' =>['ar'=>'موقوف', 'en'=>'Suspended', 'cls'=>'suspended','color'=>'#f97316'],
];

$milestones = [
  ['ar'=>'مراجعة الأدبيات','en'=>'Literature Review','done'=>true],
  ['ar'=>'تصميم التجارب','en'=>'Experiment Design','done'=>true],
  ['ar'=>'إجراء التجارب','en'=>'Conducting Experiments','done'=>true],
  ['ar'=>'تحليل النتائج','en'=>'Results Analysis','done'=>true],
  ['ar'=>'تقرير منتصف المشروع','en'=>'Mid-Project Report','done'=>true],
  ['ar'=>'التحقق من النتائج','en'=>'Results Validation','done'=>true],
  ['ar'=>'كتابة الأوراق البحثية','en'=>'Paper Writing','done'=>false],
  ['ar'=>'التقرير النهائي','en'=>'Final Report','done'=>false],
];

$filterStatus = $_GET['fstatus'] ?? 'all';
$search = trim($_GET['q'] ?? '');
$viewId = (int)($_GET['view'] ?? 0);
$viewProject = null;
foreach ($projects as $p) { if ($p['id'] === $viewId) { $viewProject = $p; break; } }

$filtered = array_filter($projects, function($p) use ($filterStatus, $search, $isRTL) {
  $title = $isRTL ? $p['titleAr'] : $p['titleEn'];
  $pi    = $isRTL ? $p['piAr']   : $p['piEn'];
  $matchSearch = !$search || stripos($title,$search)!==false || stripos($pi,$search)!==false;
  $matchStatus = $filterStatus==='all' || $p['status']===$filterStatus;
  return $matchSearch && $matchStatus;
});

$totalBudget = array_sum(array_column($projects,'budget'));
$totalSpent  = array_sum(array_column($projects,'spent'));
$activeCount = count(array_filter($projects,fn($p)=>$p['status']==='active'));
$completedCount = count(array_filter($projects,fn($p)=>$p['status']==='completed'));
$activeProjs = array_filter($projects,fn($p)=>$p['status']==='active');
$avgProgress = $activeCount ? round(array_sum(array_column(array_values($activeProjs),'progress'))/$activeCount) : 0;
$totalPubs = array_sum(array_column($projects,'pubCount'));
$totalPatents = array_sum(array_column($projects,'patentCount'));

function progressColor($v) { return $v>=80?'#22c55e':($v>=50?'#1a56db':($v>=30?'#f59e0b':'#ef4444')); }
?>

<!-- Stats -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['icon'=>'bi-journal-code','color'=>'primary','ar'=>'المشاريع النشطة','en'=>'Active Projects','value'=>$activeCount],
    ['icon'=>'bi-check2-circle','color'=>'success','ar'=>'المشاريع المكتملة','en'=>'Completed','value'=>$completedCount],
    ['icon'=>'bi-cash-stack','color'=>'info','ar'=>'إجمالي الميزانية','en'=>'Total Budget','value'=>number_format($totalBudget/1000000,1).'M'],
    ['icon'=>'bi-graph-up','color'=>'warning','ar'=>'متوسط الإنجاز','en'=>'Avg. Progress','value'=>$avgProgress.'%'],
    ['icon'=>'bi-file-earmark-medical','color'=>'primary','ar'=>'المنشورات','en'=>'Publications','value'=>$totalPubs],
    ['icon'=>'bi-lightbulb-fill','color'=>'warning','ar'=>'براءات الاختراع','en'=>'Patents','value'=>$totalPatents],
  ];
  foreach ($stats as $s): ?>
  <div class="col-6 col-md-4 col-xl-2">
    <div class="stat-card <?= $s['color'] ?>" style="padding:16px;">
      <div class="stat-icon <?= $s['color'] ?>" style="width:40px;height:40px;font-size:18px;margin-bottom:10px;">
        <i class="bi <?= $s['icon'] ?>"></i>
      </div>
      <div class="stat-value" style="font-size:22px;"><?= $s['value'] ?></div>
      <div class="stat-label"><?= $isRTL?$s['ar']:$s['en'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Filter -->
<div class="filter-bar">
  <form method="get" action="">
    <input type="hidden" name="page" value="projects">
    <input type="hidden" name="lang" value="<?= $lang ?>">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-5">
        <div class="search-box">
          <input type="text" name="q" class="form-control" placeholder="<?= $isRTL?'ابحث عن مشروع أو باحث...':'Search project or PI...' ?>" value="<?= htmlspecialchars($search) ?>">
          <i class="bi bi-search search-icon"></i>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <select name="fstatus" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($statusConfig as $k=>$v): ?>
          <option value="<?= $k ?>" <?= $filterStatus===$k?'selected':'' ?>><?= $isRTL?$v['ar']:$v['en'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-4 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i><?= t('search') ?></button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newProjectModal"><i class="bi bi-plus me-1"></i><?= $isRTL?'مشروع جديد':'New Project' ?></button>
      </div>
    </div>
  </form>
</div>

<!-- Project Cards -->
<div class="row g-3">
  <?php if (empty($filtered)): ?>
  <div class="col-12 text-center py-5 text-muted"><?= t('noData') ?></div>
  <?php else: foreach ($filtered as $p):
    $sc = $statusConfig[$p['status']];
    $pct = $p['spent']/$p['budget']*100;
    $remaining = $p['budget'] - $p['spent'];
  ?>
  <div class="col-12 col-lg-6">
    <div class="custom-card h-100">
      <div style="padding:20px 20px 14px;">
        <!-- Header -->
        <div class="d-flex align-items-start justify-content-between gap-2 mb-3">
          <div style="flex:1;">
            <h6 style="font-weight:700;font-size:14px;margin:0 0 6px;"><?= htmlspecialchars($isRTL?$p['titleAr']:$p['titleEn']) ?></h6>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="status-badge <?= $sc['cls'] ?>"><?= $isRTL?$sc['ar']:$sc['en'] ?></span>
              <span style="font-size:12px;color:#64748b;"><?= $p['dept'] ?></span>
            </div>
          </div>
          <div class="d-flex gap-1">
            <a href="?page=projects&lang=<?= $lang ?>&view=<?= $p['id'] ?>" class="btn btn-sm btn-icon btn-outline-primary"><i class="bi bi-eye" style="font-size:12px;"></i></a>
            <button class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-pencil" style="font-size:12px;"></i></button>
          </div>
        </div>

        <!-- PI -->
        <div class="d-flex align-items-center gap-2 mb-3">
          <div class="avatar avatar-sm" style="background:hsl(<?= $p['id']*47 ?>,65%,55%);color:#fff;"><?= mb_substr($isRTL?$p['piAr']:$p['piEn'],3,1,'UTF-8') ?></div>
          <span style="font-size:12px;color:#475569;"><?= htmlspecialchars($isRTL?$p['piAr']:$p['piEn']) ?></span>
          <span style="font-size:11px;color:#94a3b8;margin-<?= $isRTL?'right':'left' ?>:auto;"><?= $p['start'] ?> — <?= $p['end'] ?></span>
        </div>

        <!-- Progress -->
        <div class="mb-3">
          <div class="d-flex justify-content-between mb-1">
            <span style="font-size:12px;font-weight:600;color:#475569;"><?= $isRTL?'الإنجاز':'Progress' ?></span>
            <span style="font-size:12px;font-weight:700;color:<?= progressColor($p['progress']) ?>;"><?= $p['progress'] ?>%</span>
          </div>
          <div class="progress-custom">
            <div class="progress-bar-custom" style="width:<?= $p['progress'] ?>%;background:<?= progressColor($p['progress']) ?>;height:8px;border-radius:4px;"></div>
          </div>
        </div>

        <!-- Budget -->
        <div class="row g-2 mb-3">
          <div class="col-4">
            <div style="font-size:10px;color:#94a3b8;"><?= $isRTL?'الميزانية':'Budget' ?></div>
            <div style="font-size:12px;font-weight:700;"><?= number_format($p['budget']/1000) ?>K</div>
          </div>
          <div class="col-4">
            <div style="font-size:10px;color:#94a3b8;"><?= $isRTL?'المصروف':'Spent' ?></div>
            <div style="font-size:12px;font-weight:700;color:#ef4444;"><?= number_format($p['spent']/1000) ?>K</div>
          </div>
          <div class="col-4">
            <div style="font-size:10px;color:#94a3b8;"><?= $isRTL?'المتبقي':'Remaining' ?></div>
            <div style="font-size:12px;font-weight:700;color:#22c55e;"><?= number_format($remaining/1000) ?>K</div>
          </div>
        </div>

        <!-- Stats row -->
        <div class="d-flex gap-3" style="border-top:1px solid #f1f5f9;padding-top:12px;">
          <div style="font-size:12px;color:#64748b;"><i class="bi bi-file-earmark-medical me-1 text-primary"></i><?= $p['pubCount'] ?> <?= $isRTL?'منشور':'pubs' ?></div>
          <div style="font-size:12px;color:#64748b;"><i class="bi bi-lightbulb me-1 text-warning"></i><?= $p['patentCount'] ?> <?= $isRTL?'براءة':'patent' ?></div>
          <div style="font-size:12px;color:#64748b;"><i class="bi bi-list-check me-1 text-success"></i><?= $p['milestoneDone'] ?>/<?= $p['milestonesTotal'] ?> <?= $isRTL?'معلم':'milestones' ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<!-- View Modal -->
<?php if ($viewProject): $sc = $statusConfig[$viewProject['status']]; ?>
<div class="custom-modal-overlay" onclick="if(event.target===this) window.location='?page=projects&lang=<?= $lang ?>'">
  <div class="custom-modal" style="max-width:600px;">
    <div class="custom-modal-header">
      <div>
        <h5 style="margin:0;font-size:15px;font-weight:700;"><?= htmlspecialchars($isRTL?$viewProject['titleAr']:$viewProject['titleEn']) ?></h5>
        <span class="status-badge <?= $sc['cls'] ?>"><?= $isRTL?$sc['ar']:$sc['en'] ?></span>
      </div>
      <a href="?page=projects&lang=<?= $lang ?>" class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </div>
    <div class="custom-modal-body">
      <h6 style="font-weight:700;margin-bottom:12px;"><?= $isRTL?'المعالم':'Milestones' ?></h6>
      <?php foreach ($milestones as $m): ?>
      <div class="d-flex align-items-center gap-2 mb-2">
        <i class="bi <?= $m['done']?'bi-check-circle-fill text-success':'bi-circle text-muted' ?>"></i>
        <span style="font-size:13px;<?= $m['done']?'':'color:#94a3b8;' ?>"><?= $isRTL?$m['ar']:$m['en'] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="custom-modal-footer">
      <a href="?page=projects&lang=<?= $lang ?>" class="btn btn-outline-secondary"><?= t('close') ?></a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- New Project Modal -->
<div class="modal fade" id="newProjectModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header">
        <h5 class="modal-title"><?= $isRTL?'إنشاء مشروع بحثي جديد':'Create New Research Project' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <?php
          $fields=[['ar'=>'عنوان المشروع (عربي)','en'=>'Title (Arabic)','type'=>'text'],['ar'=>'عنوان المشروع (إنجليزي)','en'=>'Title (English)','type'=>'text'],['ar'=>'الباحث الرئيسي','en'=>'Principal Investigator','type'=>'text'],['ar'=>'ميزانية المشروع','en'=>'Project Budget','type'=>'number'],['ar'=>'تاريخ البداية','en'=>'Start Date','type'=>'date'],['ar'=>'تاريخ الانتهاء','en'=>'End Date','type'=>'date']];
          foreach ($fields as $f): ?>
          <div class="col-12 col-sm-6">
            <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
            <input type="<?= $f['type'] ?>" class="form-control">
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('cancel') ?></button>
        <button type="button" class="btn btn-primary"><?= t('save') ?></button>
      </div>
    </div>
  </div>
</div>
