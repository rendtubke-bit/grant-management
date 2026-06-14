<?php
// pages/researchers.php
$researchers = [
  ['id'=>1,'nameAr'=>'د. أحمد الزهراني','nameEn'=>'Dr. Ahmed Al-Zahrani','titleAr'=>'أستاذ مشارك','titleEn'=>'Associate Professor','deptAr'=>'علوم الحاسب','deptEn'=>'Computer Science','grants'=>8,'activeGrants'=>3,'funding'=>4200000,'pubCount'=>32,'hIndex'=>14,'email'=>'ahzahrani@kfupm.edu.sa','areasAr'=>['ذكاء اصطناعي','تعلم الآلة'],'areasEn'=>['AI','Machine Learning'],'status'=>'active'],
  ['id'=>2,'nameAr'=>'د. فاطمة العمري','nameEn'=>'Dr. Fatima Al-Omari','titleAr'=>'أستاذ','titleEn'=>'Professor','deptAr'=>'الهندسة الكيميائية','deptEn'=>'Chemical Eng.','grants'=>12,'activeGrants'=>4,'funding'=>7800000,'pubCount'=>54,'hIndex'=>22,'email'=>'falomari@kfupm.edu.sa','areasAr'=>['نانو تكنولوجي','طاقة شمسية'],'areasEn'=>['Nanotechnology','Solar Energy'],'status'=>'active'],
  ['id'=>3,'nameAr'=>'د. عبدالله الحربي','nameEn'=>'Dr. Abdullah Al-Harbi','titleAr'=>'أستاذ','titleEn'=>'Professor','deptAr'=>'هندسة البترول','deptEn'=>'Petroleum Eng.','grants'=>15,'activeGrants'=>5,'funding'=>9400000,'pubCount'=>68,'hIndex'=>28,'email'=>'aharbi@kfupm.edu.sa','areasAr'=>['استرداد النفط','تقنيات الطاقة'],'areasEn'=>['EOR','Energy Tech'],'status'=>'active'],
  ['id'=>4,'nameAr'=>'د. سارة المالكي','nameEn'=>'Dr. Sara Al-Malki','titleAr'=>'أستاذ مساعد','titleEn'=>'Assistant Professor','deptAr'=>'الهندسة الكيميائية','deptEn'=>'Chemical Eng.','grants'=>5,'activeGrants'=>2,'funding'=>1800000,'pubCount'=>18,'hIndex'=>8,'email'=>'smalki@kfupm.edu.sa','areasAr'=>['تكنولوجيا المياه','أغشية تقنية'],'areasEn'=>['Water Tech','Membranes'],'status'=>'active'],
  ['id'=>5,'nameAr'=>'د. خالد الجهني','nameEn'=>'Dr. Khalid Al-Johani','titleAr'=>'أستاذ مشارك','titleEn'=>'Associate Professor','deptAr'=>'الهندسة المدنية','deptEn'=>'Civil Eng.','grants'=>9,'activeGrants'=>2,'funding'=>3600000,'pubCount'=>38,'hIndex'=>16,'email'=>'kjohani@kfupm.edu.sa','areasAr'=>['المواد الإنشائية','الاستدامة'],'areasEn'=>['Structural Materials','Sustainability'],'status'=>'active'],
  ['id'=>6,'nameAr'=>'د. نورة القحطاني','nameEn'=>'Dr. Noura Al-Qahtani','titleAr'=>'أستاذ مشارك','titleEn'=>'Associate Professor','deptAr'=>'هندسة البيئة','deptEn'=>'Env. Eng.','grants'=>7,'activeGrants'=>3,'funding'=>2900000,'pubCount'=>27,'hIndex'=>12,'email'=>'nqahtani@kfupm.edu.sa','areasAr'=>['إدارة المياه','تغير المناخ'],'areasEn'=>['Water Management','Climate'],'status'=>'active'],
  ['id'=>7,'nameAr'=>'د. محمد السبيعي','nameEn'=>'Dr. Mohammed Al-Subai','titleAr'=>'أستاذ مساعد','titleEn'=>'Assistant Professor','deptAr'=>'هندسة الميكانيكا','deptEn'=>'Mechanical Eng.','grants'=>4,'activeGrants'=>1,'funding'=>1400000,'pubCount'=>15,'hIndex'=>6,'email'=>'msubai@kfupm.edu.sa','areasAr'=>['ميكانيكا المواد','تحليل الإجهاد'],'areasEn'=>['Materials Mech.','Stress Anal.'],'status'=>'active'],
  ['id'=>8,'nameAr'=>'د. ريم الشمري','nameEn'=>'Dr. Reem Al-Shammari','titleAr'=>'أستاذ مشارك','titleEn'=>'Associate Professor','deptAr'=>'الفيزياء','deptEn'=>'Physics','grants'=>6,'activeGrants'=>2,'funding'=>2200000,'pubCount'=>24,'hIndex'=>11,'email'=>'rshammari@kfupm.edu.sa','areasAr'=>['فيزياء المواد','الكم'],'areasEn'=>['Materials Physics','Quantum'],'status'=>'active'],
];

$colors = ['#1a56db','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899','#14b8a6'];

$search     = trim($_GET['q'] ?? '');
$filterDept = $_GET['fdept'] ?? 'all';
$viewId     = (int)($_GET['view'] ?? 0);
$viewR      = null;
foreach ($researchers as $r) { if ($r['id']===$viewId) { $viewR=$r; break; } }

$depts = array_unique(array_map(fn($r) => $isRTL?$r['deptAr']:$r['deptEn'], $researchers));

$filtered = array_filter($researchers, function($r) use ($search,$filterDept,$isRTL) {
  $name = $isRTL?$r['nameAr']:$r['nameEn'];
  $dept = $isRTL?$r['deptAr']:$r['deptEn'];
  $matchSearch = !$search || stripos($name,$search)!==false || stripos($dept,$search)!==false;
  $matchDept   = $filterDept==='all' || $dept===$filterDept;
  return $matchSearch && $matchDept;
});

$totalFunding = array_sum(array_column($researchers,'funding'));
$totalPubs    = array_sum(array_column($researchers,'pubCount'));
$avgHIndex    = round(array_sum(array_column($researchers,'hIndex'))/count($researchers));
$totalActive  = array_sum(array_column($researchers,'activeGrants'));
?>

<!-- Stats -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['ar'=>'إجمالي الباحثين','en'=>'Total Researchers','value'=>count($researchers),'icon'=>'bi-people-fill','color'=>'primary'],
    ['ar'=>'إجمالي التمويل','en'=>'Total Funding','value'=>number_format($totalFunding/1000000,1).'M','icon'=>'bi-cash-stack','color'=>'success'],
    ['ar'=>'المنشورات العلمية','en'=>'Publications','value'=>$totalPubs,'icon'=>'bi-journal-text','color'=>'info'],
    ['ar'=>'متوسط H-Index','en'=>'Avg H-Index','value'=>$avgHIndex,'icon'=>'bi-graph-up','color'=>'warning'],
    ['ar'=>'المنح النشطة','en'=>'Active Grants','value'=>$totalActive,'icon'=>'bi-file-earmark-check','color'=>'primary'],
  ];
  foreach ($stats as $s): ?>
  <div class="col-6 col-md-4 col-xl">
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

<!-- Filters -->
<div class="filter-bar">
  <form method="get" action="">
    <input type="hidden" name="page" value="researchers">
    <input type="hidden" name="lang" value="<?= $lang ?>">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <div class="search-box">
          <input type="text" name="q" class="form-control" placeholder="<?= $isRTL?'ابحث عن باحث أو قسم...':'Search researcher or department...' ?>" value="<?= htmlspecialchars($search) ?>">
          <i class="bi bi-search search-icon"></i>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <select name="fdept" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($depts as $d): ?><option value="<?= htmlspecialchars($d) ?>" <?= $filterDept===$d?'selected':'' ?>><?= htmlspecialchars($d) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-5 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i><?= t('search') ?></button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addResearcherModal"><i class="bi bi-person-plus me-1"></i><?= $isRTL?'إضافة باحث':'Add Researcher' ?></button>
        <button type="button" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i><?= t('export') ?></button>
      </div>
    </div>
  </form>
</div>

<!-- Researcher Cards -->
<div class="row g-3">
  <?php if (empty($filtered)): ?>
  <div class="col-12 text-center py-5 text-muted"><?= t('noData') ?></div>
  <?php else: foreach ($filtered as $r):
    $color = $colors[($r['id']-1) % count($colors)];
  ?>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="researcher-card">
      <!-- Avatar -->
      <div class="avatar" style="background:<?= $color ?>;color:#fff;">
        <?= mb_substr($isRTL?$r['nameAr']:$r['nameEn'],3,1,'UTF-8') ?>
      </div>
      <h6 style="font-size:14px;font-weight:700;margin:0 0 4px;"><?= htmlspecialchars($isRTL?$r['nameAr']:$r['nameEn']) ?></h6>
      <div style="font-size:12px;color:#64748b;margin-bottom:2px;"><?= htmlspecialchars($isRTL?$r['titleAr']:$r['titleEn']) ?></div>
      <div style="font-size:11px;color:#94a3b8;margin-bottom:12px;"><?= htmlspecialchars($isRTL?$r['deptAr']:$r['deptEn']) ?></div>

      <!-- Research Areas -->
      <div class="d-flex flex-wrap gap-1 justify-content-center mb-3">
        <?php $areas = $isRTL?$r['areasAr']:$r['areasEn']; foreach ($areas as $area): ?>
        <span style="background:<?= $color ?>18;color:<?= $color ?>;border-radius:20px;font-size:10px;padding:2px 8px;"><?= htmlspecialchars($area) ?></span>
        <?php endforeach; ?>
      </div>

      <div class="divider"></div>

      <!-- Metrics -->
      <div class="row g-2 mt-2 mb-3" style="text-align:center;">
        <?php
        $metrics = [
          ['v'=>$r['grants'],'ar'=>'المنح','en'=>'Grants'],
          ['v'=>$r['pubCount'],'ar'=>'منشور','en'=>'Pubs'],
          ['v'=>'H'.$r['hIndex'],'ar'=>'H-Index','en'=>'H-Index'],
        ];
        foreach ($metrics as $m): ?>
        <div class="col-4">
          <div style="font-size:16px;font-weight:800;color:#1e293b;"><?= $m['v'] ?></div>
          <div style="font-size:10px;color:#94a3b8;"><?= $isRTL?$m['ar']:$m['en'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>

      <div style="font-size:11px;color:#64748b;margin-bottom:12px;"><?= $r['email'] ?></div>

      <div class="d-flex gap-2">
        <a href="?page=researchers&lang=<?= $lang ?>&view=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary flex-fill"><?= $isRTL?'عرض':'View' ?></a>
        <button class="btn btn-sm btn-outline-secondary flex-fill"><?= t('edit') ?></button>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<!-- View Researcher Modal -->
<?php if ($viewR): $color = $colors[($viewR['id']-1) % count($colors)]; ?>
<div class="custom-modal-overlay" onclick="if(event.target===this) window.location='?page=researchers&lang=<?= $lang ?>'">
  <div class="custom-modal" style="max-width:580px;">
    <div class="custom-modal-header">
      <div class="d-flex align-items-center gap-3">
        <div class="avatar avatar-lg" style="background:<?= $color ?>;color:#fff;"><?= mb_substr($isRTL?$viewR['nameAr']:$viewR['nameEn'],3,1,'UTF-8') ?></div>
        <div>
          <h5 style="margin:0;font-size:16px;font-weight:700;"><?= htmlspecialchars($isRTL?$viewR['nameAr']:$viewR['nameEn']) ?></h5>
          <div style="font-size:13px;color:#64748b;"><?= htmlspecialchars($isRTL?$viewR['titleAr']:$viewR['titleEn']) ?></div>
        </div>
      </div>
      <a href="?page=researchers&lang=<?= $lang ?>" class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </div>
    <div class="custom-modal-body">
      <div class="row g-3">
        <div class="col-6"><div style="font-size:12px;color:#64748b;"><?= t('department') ?></div><div style="font-weight:600;font-size:13px;"><?= htmlspecialchars($isRTL?$viewR['deptAr']:$viewR['deptEn']) ?></div></div>
        <div class="col-6"><div style="font-size:12px;color:#64748b;"><?= t('email') ?></div><div style="font-size:12px;"><?= $viewR['email'] ?></div></div>
        <div class="col-3"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'المنح':'Grants' ?></div><div style="font-weight:700;font-size:18px;"><?= $viewR['grants'] ?></div></div>
        <div class="col-3"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'المنشورات':'Pubs' ?></div><div style="font-weight:700;font-size:18px;"><?= $viewR['pubCount'] ?></div></div>
        <div class="col-3"><div style="font-size:12px;color:#64748b;">H-Index</div><div style="font-weight:700;font-size:18px;"><?= $viewR['hIndex'] ?></div></div>
        <div class="col-3"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'التمويل (M)':'Funding (M)' ?></div><div style="font-weight:700;font-size:18px;"><?= number_format($viewR['funding']/1000000,1) ?></div></div>
        <div class="col-12">
          <div style="font-size:12px;color:#64748b;margin-bottom:6px;"><?= $isRTL?'مجالات البحث':'Research Areas' ?></div>
          <div class="d-flex flex-wrap gap-1">
            <?php $areas = $isRTL?$viewR['areasAr']:$viewR['areasEn']; foreach ($areas as $area): ?>
            <span style="background:<?= $color ?>18;color:<?= $color ?>;border-radius:20px;font-size:12px;padding:4px 12px;"><?= htmlspecialchars($area) ?></span>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <div class="custom-modal-footer">
      <a href="?page=researchers&lang=<?= $lang ?>" class="btn btn-outline-secondary"><?= t('close') ?></a>
      <button class="btn btn-primary"><i class="bi bi-pencil me-1"></i><?= t('edit') ?></button>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Add Researcher Modal -->
<div class="modal fade" id="addResearcherModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header"><h5 class="modal-title"><?= $isRTL?'إضافة باحث جديد':'Add New Researcher' ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <?php foreach ([['ar'=>'الاسم (عربي)','en'=>'Name (Arabic)','t'=>'text'],['ar'=>'الاسم (إنجليزي)','en'=>'Name (English)','t'=>'text'],['ar'=>'اللقب العلمي','en'=>'Academic Title','t'=>'select'],['ar'=>'القسم','en'=>'Department','t'=>'select'],['ar'=>'البريد الإلكتروني','en'=>'Email','t'=>'email'],['ar'=>'H-Index','en'=>'H-Index','t'=>'number']] as $f): ?>
          <div class="col-12 col-sm-6">
            <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
            <?php if($f['t']==='select'): ?><select class="form-select"><option>--</option></select><?php else: ?><input type="<?= $f['t'] ?>" class="form-control"><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('cancel') ?></button><button class="btn btn-primary"><?= t('save') ?></button></div>
    </div>
  </div>
</div>
