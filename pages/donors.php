<?php
// pages/donors.php
$donors = [
  ['id'=>1,'nameAr'=>'أرامكو السعودية','nameEn'=>'Saudi Aramco','typeKey'=>'company','countryAr'=>'المملكة العربية السعودية','countryEn'=>'Saudi Arabia','total'=>12500000,'projects'=>8,'level'=>'gold','email'=>'research@aramco.com','logoColor'=>'#1a3c6e'],
  ['id'=>2,'nameAr'=>'سابك','nameEn'=>'SABIC','typeKey'=>'company','countryAr'=>'المملكة العربية السعودية','countryEn'=>'Saudi Arabia','total'=>9800000,'projects'=>6,'level'=>'gold','email'=>'research@sabic.com','logoColor'=>'#0072bc'],
  ['id'=>3,'nameAr'=>'مؤسسة الملك عبدالعزيز','nameEn'=>'King Abdulaziz Foundation','typeKey'=>'government','countryAr'=>'المملكة العربية السعودية','countryEn'=>'Saudi Arabia','total'=>7200000,'projects'=>12,'level'=>'gold','email'=>'grants@kaf.org.sa','logoColor'=>'#166534'],
  ['id'=>4,'nameAr'=>'مؤسسة بيل وميليندا غيتس','nameEn'=>'Bill & Melinda Gates Foundation','typeKey'=>'ngo','countryAr'=>'الولايات المتحدة','countryEn'=>'United States','total'=>5400000,'projects'=>4,'level'=>'silver','email'=>'info@gatesfoundation.org','logoColor'=>'#7c3aed'],
  ['id'=>5,'nameAr'=>'البنك الدولي','nameEn'=>'World Bank','typeKey'=>'international','countryAr'=>'الولايات المتحدة','countryEn'=>'United States','total'=>4100000,'projects'=>3,'level'=>'silver','email'=>'research@worldbank.org','logoColor'=>'#1a56db'],
  ['id'=>6,'nameAr'=>'وزارة التعليم','nameEn'=>'Ministry of Education','typeKey'=>'government','countryAr'=>'المملكة العربية السعودية','countryEn'=>'Saudi Arabia','total'=>8900000,'projects'=>22,'level'=>'gold','email'=>'research@moe.gov.sa','logoColor'=>'#059669'],
  ['id'=>7,'nameAr'=>'شركة شيفرون','nameEn'=>'Chevron Corporation','typeKey'=>'company','countryAr'=>'الولايات المتحدة','countryEn'=>'United States','total'=>3200000,'projects'=>2,'level'=>'bronze','email'=>'research@chevron.com','logoColor'=>'#b45309'],
  ['id'=>8,'nameAr'=>'الاتحاد الأوروبي – هورايزون','nameEn'=>'EU Horizon Program','typeKey'=>'international','countryAr'=>'بلجيكا','countryEn'=>'Belgium','total'=>6700000,'projects'=>5,'level'=>'silver','email'=>'horizon@ec.europa.eu','logoColor'=>'#1e40af'],
];

$levelConfig = [
  'gold'  =>['ar'=>'ذهبي',  'en'=>'Gold',   'color'=>'#b45309','bg'=>'#fef3c7','icon'=>'bi-star-fill'],
  'silver'=>['ar'=>'فضي',   'en'=>'Silver', 'color'=>'#64748b','bg'=>'#f1f5f9','icon'=>'bi-star-half'],
  'bronze'=>['ar'=>'برونزي','en'=>'Bronze', 'color'=>'#92400e','bg'=>'#fef9e7','icon'=>'bi-star'],
];

$typeConfig = [
  'company'     =>['ar'=>'شركة',  'en'=>'Company',      'color'=>'#1a56db'],
  'government'  =>['ar'=>'حكومية','en'=>'Government',   'color'=>'#059669'],
  'ngo'         =>['ar'=>'منظمة', 'en'=>'NGO',          'color'=>'#7c3aed'],
  'international'=>['ar'=>'دولية','en'=>'International','color'=>'#f59e0b'],
];

$history = [
  ['year'=>2022,'donorId'=>1,'amountAr'=>'5,200,000','amountEn'=>'5,200,000','projectAr'=>'أبحاث التقاط الكربون','projectEn'=>'Carbon Capture Research'],
  ['year'=>2023,'donorId'=>1,'amountAr'=>'7,300,000','amountEn'=>'7,300,000','projectAr'=>'طاقة هيدروجينية متقدمة','projectEn'=>'Advanced Hydrogen Energy'],
  ['year'=>2023,'donorId'=>2,'amountAr'=>'4,500,000','amountEn'=>'4,500,000','projectAr'=>'مواد بتروكيماوية مستدامة','projectEn'=>'Sustainable Petrochemicals'],
  ['year'=>2024,'donorId'=>3,'amountAr'=>'3,800,000','amountEn'=>'3,800,000','projectAr'=>'منح الطلاب المتميزين','projectEn'=>'Excellence Student Grants'],
  ['year'=>2024,'donorId'=>4,'amountAr'=>'2,100,000','amountEn'=>'2,100,000','projectAr'=>'أبحاث الصحة الرقمية','projectEn'=>'Digital Health Research'],
];

$filterLevel = $_GET['flevel'] ?? 'all';
$filterType  = $_GET['ftype']  ?? 'all';
$search      = trim($_GET['q'] ?? '');
$viewId      = (int)($_GET['view'] ?? 0);
$viewDonor   = null;
foreach ($donors as $d) { if ($d['id']===$viewId) { $viewDonor=$d; break; } }

$filtered = array_filter($donors, function($d) use ($filterLevel,$filterType,$search,$isRTL) {
  $name = $isRTL?$d['nameAr']:$d['nameEn'];
  $matchSearch = !$search || stripos($name,$search)!==false;
  $matchLevel = $filterLevel==='all' || $d['level']===$filterLevel;
  $matchType  = $filterType==='all'  || $d['typeKey']===$filterType;
  return $matchSearch && $matchLevel && $matchType;
});

$totalContrib = array_sum(array_column($donors,'total'));
$goldCount    = count(array_filter($donors,fn($d)=>$d['level']==='gold'));
$totalProjects= array_sum(array_column($donors,'projects'));
?>

<!-- Stats -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['ar'=>'إجمالي المساهمات','en'=>'Total Contributions','value'=>number_format($totalContrib/1000000,1).'M','icon'=>'bi-cash-stack','color'=>'primary'],
    ['ar'=>'عدد الجهات المانحة','en'=>'Total Donors','value'=>count($donors),'icon'=>'bi-building-fill','color'=>'info'],
    ['ar'=>'شركاء ذهبيون','en'=>'Gold Partners','value'=>$goldCount,'icon'=>'bi-star-fill','color'=>'warning'],
    ['ar'=>'مشاريع مموّلة','en'=>'Funded Projects','value'=>$totalProjects,'icon'=>'bi-journal-check','color'=>'success'],
  ];
  foreach ($stats as $s): ?>
  <div class="col-6 col-xl-3">
    <div class="stat-card <?= $s['color'] ?>">
      <div class="stat-icon <?= $s['color'] ?>"><i class="bi <?= $s['icon'] ?>"></i></div>
      <div class="stat-value"><?= $s['value'] ?></div>
      <div class="stat-label"><?= $isRTL?$s['ar']:$s['en'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Partnership Levels -->
<div class="row g-3 mb-4">
  <?php foreach ($levelConfig as $k => $v):
    $cnt = count(array_filter($donors,fn($d)=>$d['level']===$k));
    $tot = array_sum(array_map(fn($d)=>$d['total'],array_filter($donors,fn($d)=>$d['level']===$k)));
  ?>
  <div class="col-12 col-md-4">
    <div class="p-4 rounded-3 border text-center" style="background:<?= $v['bg'] ?>;border-color:<?= $v['color'] ?>40;">
      <i class="bi <?= $v['icon'] ?>" style="font-size:32px;color:<?= $v['color'] ?>;margin-bottom:8px;display:block;"></i>
      <div style="font-size:18px;font-weight:800;color:<?= $v['color'] ?>"><?= $isRTL?$v['ar']:$v['en'] ?></div>
      <div style="font-size:13px;color:#64748b;margin-top:4px;"><?= $cnt ?> <?= $isRTL?'جهة مانحة':'donors' ?></div>
      <div style="font-size:14px;font-weight:700;color:<?= $v['color'] ?>;margin-top:8px;"><?= number_format($tot/1000000,1) ?>M <?= $isRTL?'ريال':'SAR' ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="filter-bar">
  <form method="get" action="">
    <input type="hidden" name="page" value="donors">
    <input type="hidden" name="lang" value="<?= $lang ?>">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <div class="search-box">
          <input type="text" name="q" class="form-control" placeholder="<?= $isRTL?'ابحث عن جهة مانحة...':'Search donors...' ?>" value="<?= htmlspecialchars($search) ?>">
          <i class="bi bi-search search-icon"></i>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <select name="flevel" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($levelConfig as $k=>$v): ?><option value="<?= $k ?>" <?= $filterLevel===$k?'selected':'' ?>><?= $isRTL?$v['ar']:$v['en'] ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-2">
        <select name="ftype" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($typeConfig as $k=>$v): ?><option value="<?= $k ?>" <?= $filterType===$k?'selected':'' ?>><?= $isRTL?$v['ar']:$v['en'] ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-4 d-flex gap-2 justify-content-end">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i><?= t('search') ?></button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addDonorModal"><i class="bi bi-plus me-1"></i><?= $isRTL?'إضافة جهة':'Add Donor' ?></button>
      </div>
    </div>
  </form>
</div>

<!-- Donor Cards -->
<div class="row g-3 mb-4">
  <?php if (empty($filtered)): ?>
  <div class="col-12 text-center py-5 text-muted"><?= t('noData') ?></div>
  <?php else: foreach ($filtered as $d):
    $lc = $levelConfig[$d['level']];
    $tc = $typeConfig[$d['typeKey']]; ?>
  <div class="col-12 col-md-6 col-xl-4">
    <div class="donor-card">
      <div class="d-flex align-items-start justify-content-between mb-3">
        <div class="d-flex align-items-center gap-3">
          <div style="width:48px;height:48px;border-radius:12px;background:<?= $d['logoColor'] ?>18;color:<?= $d['logoColor'] ?>;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:800;flex-shrink:0;">
            <?= mb_strtoupper(mb_substr($isRTL?$d['nameAr']:$d['nameEn'],0,1,'UTF-8'),'UTF-8') ?>
          </div>
          <div>
            <div style="font-size:14px;font-weight:700;"><?= htmlspecialchars($isRTL?$d['nameAr']:$d['nameEn']) ?></div>
            <div style="font-size:11px;color:#64748b;"><?= $isRTL?$d['countryAr']:$d['countryEn'] ?></div>
          </div>
        </div>
        <span style="background:<?= $lc['bg'] ?>;color:<?= $lc['color'] ?>;border-radius:20px;font-size:11px;font-weight:700;padding:3px 10px;">
          <i class="bi <?= $lc['icon'] ?> me-1"></i><?= $isRTL?$lc['ar']:$lc['en'] ?>
        </span>
      </div>
      <div class="divider"></div>
      <div class="row g-2 mt-1 mb-3">
        <div class="col-6">
          <div style="font-size:10px;color:#94a3b8;"><?= $isRTL?'إجمالي المساهمة':'Total Contribution' ?></div>
          <div style="font-size:13px;font-weight:700;"><?= number_format($d['total']/1000000,1) ?>M</div>
        </div>
        <div class="col-6">
          <div style="font-size:10px;color:#94a3b8;"><?= $isRTL?'المشاريع الممولة':'Funded Projects' ?></div>
          <div style="font-size:13px;font-weight:700;"><?= $d['projects'] ?></div>
        </div>
      </div>
      <div class="d-flex align-items-center justify-content-between">
        <span class="badge rounded-pill" style="background:<?= $tc['color'] ?>18;color:<?= $tc['color'] ?>;font-size:11px;"><?= $isRTL?$tc['ar']:$tc['en'] ?></span>
        <a href="?page=donors&lang=<?= $lang ?>&view=<?= $d['id'] ?>" class="btn btn-sm btn-outline-primary"><?= $isRTL?'عرض':'View' ?></a>
      </div>
    </div>
  </div>
  <?php endforeach; endif; ?>
</div>

<!-- Contribution History -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-clock-history me-2 text-primary"></i><?= $isRTL?'سجل المساهمات':'Contribution History' ?></h5>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead><tr>
        <th><?= $isRTL?'السنة':'Year' ?></th>
        <th><?= $isRTL?'الجهة المانحة':'Donor' ?></th>
        <th><?= $isRTL?'المبلغ (ريال)':'Amount (SAR)' ?></th>
        <th><?= $isRTL?'المشروع':'Project' ?></th>
      </tr></thead>
      <tbody>
        <?php foreach ($history as $h):
          $donorObj = null;
          foreach ($donors as $d) { if ($d['id']===$h['donorId']) { $donorObj=$d; break; } }
        ?>
        <tr>
          <td style="font-weight:700;"><?= $h['year'] ?></td>
          <td style="font-size:13px;"><?= $donorObj?htmlspecialchars($isRTL?$donorObj['nameAr']:$donorObj['nameEn']):'-' ?></td>
          <td><span class="amount-text"><?= $isRTL?$h['amountAr']:$h['amountEn'] ?></span></td>
          <td style="font-size:12px;color:#64748b;"><?= htmlspecialchars($isRTL?$h['projectAr']:$h['projectEn']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- View Donor Modal -->
<?php if ($viewDonor): $lc = $levelConfig[$viewDonor['level']]; $tc = $typeConfig[$viewDonor['typeKey']]; ?>
<div class="custom-modal-overlay" onclick="if(event.target===this) window.location='?page=donors&lang=<?= $lang ?>'">
  <div class="custom-modal" style="max-width:560px;">
    <div class="custom-modal-header">
      <div>
        <h5 style="margin:0;font-size:16px;font-weight:700;"><?= htmlspecialchars($isRTL?$viewDonor['nameAr']:$viewDonor['nameEn']) ?></h5>
        <span style="font-size:12px;color:#64748b;"><?= $isRTL?$viewDonor['countryAr']:$viewDonor['countryEn'] ?></span>
      </div>
      <a href="?page=donors&lang=<?= $lang ?>" class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </div>
    <div class="custom-modal-body">
      <div class="row g-3">
        <div class="col-6"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'المستوى':'Level' ?></div><span style="background:<?= $lc['bg'] ?>;color:<?= $lc['color'] ?>;padding:3px 10px;border-radius:20px;font-size:12px;"><i class="bi <?= $lc['icon'] ?> me-1"></i><?= $isRTL?$lc['ar']:$lc['en'] ?></span></div>
        <div class="col-6"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'النوع':'Type' ?></div><span class="badge rounded-pill" style="background:<?= $tc['color'] ?>18;color:<?= $tc['color'] ?>;font-size:12px;"><?= $isRTL?$tc['ar']:$tc['en'] ?></span></div>
        <div class="col-6"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'إجمالي المساهمة':'Total' ?></div><div class="amount-text"><?= number_format($viewDonor['total']/1000000,1) ?>M SAR</div></div>
        <div class="col-6"><div style="font-size:12px;color:#64748b;"><?= $isRTL?'المشاريع':'Projects' ?></div><div style="font-weight:700;"><?= $viewDonor['projects'] ?></div></div>
        <div class="col-12"><div style="font-size:12px;color:#64748b;"><?= t('email') ?></div><div style="font-size:13px;"><?= $viewDonor['email'] ?></div></div>
      </div>
    </div>
    <div class="custom-modal-footer">
      <a href="?page=donors&lang=<?= $lang ?>" class="btn btn-outline-secondary"><?= t('close') ?></a>
      <button class="btn btn-primary"><i class="bi bi-envelope me-1"></i><?= $isRTL?'تواصل':'Contact' ?></button>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Add Donor Modal -->
<div class="modal fade" id="addDonorModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header"><h5 class="modal-title"><?= $isRTL?'إضافة جهة مانحة':'Add Donor' ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <?php foreach ([['ar'=>'الاسم (عربي)','en'=>'Name (Arabic)','t'=>'text'],['ar'=>'الاسم (إنجليزي)','en'=>'Name (English)','t'=>'text'],['ar'=>'البريد الإلكتروني','en'=>'Email','t'=>'email'],['ar'=>'النوع','en'=>'Type','t'=>'select'],['ar'=>'المستوى','en'=>'Level','t'=>'select'],['ar'=>'الدولة','en'=>'Country','t'=>'text']] as $f): ?>
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
