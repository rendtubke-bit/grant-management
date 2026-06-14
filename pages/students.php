<?php
// pages/students.php
$students = [
  ['id'=>1,'nameAr'=>'أحمد بن سعد المطيري','nameEn'=>'Ahmed Bin Saad Al-Mutairi','idNum'=>'201901234','majorAr'=>'علوم الحاسب','majorEn'=>'Computer Science','gpa'=>3.82,'grantTypeAr'=>'منحة التميز','grantTypeEn'=>'Excellence Grant','amount'=>18000,'semestersLeft'=>3,'status'=>'funded','appliedDate'=>'2024-01-15'],
  ['id'=>2,'nameAr'=>'منال محمد الدوسري','nameEn'=>'Manal Mohammed Al-Dosari','idNum'=>'202012567','majorAr'=>'الهندسة الكيميائية','majorEn'=>'Chemical Eng.','gpa'=>3.95,'grantTypeAr'=>'منحة الدراسات العليا','grantTypeEn'=>'Grad Studies Grant','amount'=>24000,'semestersLeft'=>2,'status'=>'funded','appliedDate'=>'2024-01-22'],
  ['id'=>3,'nameAr'=>'سلطان فهد القرني','nameEn'=>'Sultan Fahd Al-Qarni','idNum'=>'202123890','majorAr'=>'هندسة البترول','majorEn'=>'Petroleum Eng.','gpa'=>3.75,'grantTypeAr'=>'منحة بحثية','grantTypeEn'=>'Research Grant','amount'=>15000,'semestersLeft'=>4,'status'=>'review','appliedDate'=>'2024-02-10'],
  ['id'=>4,'nameAr'=>'ريهام عبدالله السلمي','nameEn'=>'Reham Abdullah Al-Salmi','idNum'=>'202234512','majorAr'=>'الهندسة المدنية','majorEn'=>'Civil Eng.','gpa'=>3.60,'grantTypeAr'=>'منحة التميز','grantTypeEn'=>'Excellence Grant','amount'=>18000,'semestersLeft'=>2,'status'=>'pending','appliedDate'=>'2024-02-28'],
  ['id'=>5,'nameAr'=>'يوسف إبراهيم العتيبي','nameEn'=>'Yousef Ibrahim Al-Otaibi','idNum'=>'202301678','majorAr'=>'الفيزياء','majorEn'=>'Physics','gpa'=>3.45,'grantTypeAr'=>'منحة عامة','grantTypeEn'=>'General Grant','amount'=>12000,'semestersLeft'=>5,'status'=>'rejected','appliedDate'=>'2024-03-05'],
  ['id'=>6,'nameAr'=>'دانة سعود الغامدي','nameEn'=>'Dana Saud Al-Ghamdi','idNum'=>'202210345','majorAr'=>'هندسة البيئة','majorEn'=>'Env. Engineering','gpa'=>3.88,'grantTypeAr'=>'منحة الدراسات العليا','grantTypeEn'=>'Grad Studies Grant','amount'=>24000,'semestersLeft'=>3,'status'=>'funded','appliedDate'=>'2024-01-30'],
  ['id'=>7,'nameAr'=>'عمر ناصر الزهراني','nameEn'=>'Omar Nasser Al-Zahrani','idNum'=>'202015678','majorAr'=>'هندسة الميكانيكا','majorEn'=>'Mechanical Eng.','gpa'=>3.70,'grantTypeAr'=>'منحة صناعية','grantTypeEn'=>'Industry Grant','amount'=>20000,'semestersLeft'=>1,'status'=>'funded','appliedDate'=>'2024-02-05'],
  ['id'=>8,'nameAr'=>'نوف عبدالعزيز العنزي','nameEn'=>'Nawf Abdulaziz Al-Anzi','idNum'=>'202334901','majorAr'=>'علوم الحاسب','majorEn'=>'Computer Science','gpa'=>3.55,'grantTypeAr'=>'منحة التميز','grantTypeEn'=>'Excellence Grant','amount'=>18000,'semestersLeft'=>4,'status'=>'review','appliedDate'=>'2024-03-15'],
];

$criteria = [
  ['ar'=>'الحد الأدنى للمعدل التراكمي','en'=>'Minimum GPA Requirement','valAr'=>'3.5 من 4.0','valEn'=>'3.5 out of 4.0','icon'=>'bi-mortarboard-fill','color'=>'#1a56db'],
  ['ar'=>'منحة التميز','en'=>'Excellence Grant','valAr'=>'18,000 ريال/سنة','valEn'=>'SAR 18,000/year','icon'=>'bi-star-fill','color'=>'#f59e0b'],
  ['ar'=>'منحة الدراسات العليا','en'=>'Graduate Studies Grant','valAr'=>'24,000 ريال/سنة','valEn'=>'SAR 24,000/year','icon'=>'bi-book-fill','color'=>'#8b5cf6'],
  ['ar'=>'منحة صناعية','en'=>'Industry-Sponsored Grant','valAr'=>'20,000 ريال/سنة','valEn'=>'SAR 20,000/year','icon'=>'bi-building-fill','color'=>'#22c55e'],
  ['ar'=>'فترة التقديم','en'=>'Application Period','valAr'=>'يناير - مارس','valEn'=>'January - March','icon'=>'bi-calendar3','color'=>'#06b6d4'],
  ['ar'=>'الحد الأقصى للساعات','en'=>'Max Credit Hours','valAr'=>'حتى 18 ساعة','valEn'=>'Up to 18 credits','icon'=>'bi-hourglass-split','color'=>'#ef4444'],
];

$statusConfig = [
  'funded'   =>['ar'=>'ممول',          'en'=>'Funded',       'cls'=>'funded'],
  'pending'  =>['ar'=>'قيد الانتظار',  'en'=>'Pending',      'cls'=>'pending'],
  'review'   =>['ar'=>'تحت المراجعة', 'en'=>'Under Review', 'cls'=>'review'],
  'rejected' =>['ar'=>'مرفوض',        'en'=>'Rejected',     'cls'=>'rejected'],
];

$search       = trim($_GET['q'] ?? '');
$filterStatus = $_GET['fstatus'] ?? 'all';
$filterType   = $_GET['ftype']   ?? 'all';
$showApply    = isset($_GET['apply']);
$applyStep    = (int)($_GET['step'] ?? 1);
if ($applyStep < 1) $applyStep = 1;
if ($applyStep > 3) $applyStep = 3;

$allTypes = array_unique(array_map(fn($s) => $isRTL?$s['grantTypeAr']:$s['grantTypeEn'], $students));

$filtered = array_filter($students, function($s) use ($search,$filterStatus,$filterType,$isRTL) {
  $name = $isRTL?$s['nameAr']:$s['nameEn'];
  $type = $isRTL?$s['grantTypeAr']:$s['grantTypeEn'];
  $matchSearch = !$search || stripos($name,$search)!==false || stripos($s['idNum'],$search)!==false;
  $matchStatus = $filterStatus==='all' || $s['status']===$filterStatus;
  $matchType   = $filterType==='all'   || $type===$filterType;
  return $matchSearch && $matchStatus && $matchType;
});

$funded   = count(array_filter($students,fn($s)=>$s['status']==='funded'));
$totalAmt = array_sum(array_column(array_filter($students,fn($s)=>$s['status']==='funded'),'amount'));
$avgGpa   = round(array_sum(array_column($students,'gpa'))/count($students),2);
?>

<!-- Stats -->
<div class="row g-3 mb-4">
  <?php
  $stats=[
    ['ar'=>'إجمالي الطلاب المتقدمين','en'=>'Total Applicants','value'=>count($students),'icon'=>'bi-mortarboard-fill','color'=>'primary'],
    ['ar'=>'الطلاب الممولون','en'=>'Funded Students','value'=>$funded,'icon'=>'bi-check-circle-fill','color'=>'success'],
    ['ar'=>'إجمالي المنح المصروفة','en'=>'Total Grants Paid','value'=>number_format($totalAmt/1000,0).'K','icon'=>'bi-cash-coin','color'=>'info'],
    ['ar'=>'متوسط المعدل التراكمي','en'=>'Average GPA','value'=>$avgGpa,'icon'=>'bi-graph-up-arrow','color'=>'warning'],
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

<!-- Eligibility Criteria -->
<div class="custom-card mb-4">
  <div class="card-header-custom">
    <h5><i class="bi bi-info-circle me-2 text-primary"></i><?= $isRTL?'معايير الأهلية وشروط المنح':'Eligibility Criteria & Grant Terms' ?></h5>
  </div>
  <div class="p-3">
    <div class="row g-3">
      <?php foreach ($criteria as $c): ?>
      <div class="col-12 col-md-6 col-xl-4">
        <div class="d-flex align-items-center gap-3 p-3 rounded-3 border" style="background:<?= $c['color'] ?>08;border-color:<?= $c['color'] ?>30!important;">
          <div style="width:40px;height:40px;border-radius:10px;background:<?= $c['color'] ?>18;color:<?= $c['color'] ?>;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
            <i class="bi <?= $c['icon'] ?>"></i>
          </div>
          <div>
            <div style="font-size:12px;color:#64748b;"><?= $isRTL?$c['ar']:$c['en'] ?></div>
            <div style="font-size:14px;font-weight:700;color:<?= $c['color'] ?>;"><?= $isRTL?$c['valAr']:$c['valEn'] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Filters -->
<div class="filter-bar">
  <form method="get" action="">
    <input type="hidden" name="page" value="students">
    <input type="hidden" name="lang" value="<?= $lang ?>">
    <div class="row g-3 align-items-end">
      <div class="col-12 col-md-4">
        <div class="search-box">
          <input type="text" name="q" class="form-control" placeholder="<?= $isRTL?'ابحث باسم الطالب أو رقمه الجامعي...':'Search by name or student ID...' ?>" value="<?= htmlspecialchars($search) ?>">
          <i class="bi bi-search search-icon"></i>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <select name="fstatus" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($statusConfig as $k=>$v): ?><option value="<?= $k ?>" <?= $filterStatus===$k?'selected':'' ?>><?= $isRTL?$v['ar']:$v['en'] ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <select name="ftype" class="form-select" onchange="this.form.submit()">
          <option value="all"><?= t('all') ?></option>
          <?php foreach ($allTypes as $ty): ?><option value="<?= htmlspecialchars($ty) ?>" <?= $filterType===$ty?'selected':'' ?>><?= htmlspecialchars($ty) ?></option><?php endforeach; ?>
        </select>
      </div>
      <div class="col-12 col-md-3 d-flex gap-2 justify-content-end">
        <a href="?page=students&lang=<?= $lang ?>&apply=1&step=1" class="btn btn-primary"><i class="bi bi-person-plus me-1"></i><?= $isRTL?'تقديم طلب':'Apply' ?></a>
        <button type="button" class="btn btn-outline-secondary"><i class="bi bi-download"></i></button>
      </div>
    </div>
  </form>
</div>

<!-- Table -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-table me-2 text-primary"></i><?= $isRTL?'طلبات منح الطلاب':'Student Grant Applications' ?></h5>
    <span class="badge bg-secondary" style="font-size:12px;"><?= count($filtered) ?> <?= $isRTL?'نتيجة':'results' ?></span>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th><?= $isRTL?'الرقم الجامعي':'Student ID' ?></th>
          <th><?= $isRTL?'اسم الطالب':'Student Name' ?></th>
          <th><?= $isRTL?'التخصص':'Major' ?></th>
          <th><?= $isRTL?'المعدل':'GPA' ?></th>
          <th><?= $isRTL?'نوع المنحة':'Grant Type' ?></th>
          <th><?= $isRTL?'المبلغ السنوي':'Annual Amount' ?></th>
          <th><?= $isRTL?'الفصول المتبقية':'Semesters Left' ?></th>
          <th><?= t('status') ?></th>
          <th><?= t('submissionDate') ?></th>
          <th><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($filtered)): ?>
        <tr><td colspan="10" class="text-center py-5 text-muted"><?= t('noData') ?></td></tr>
        <?php else: foreach ($filtered as $s):
          $sc = $statusConfig[$s['status']];
          $gpaColor = $s['gpa']>=3.8?'#22c55e':($s['gpa']>=3.6?'#f59e0b':'#ef4444');
        ?>
        <tr>
          <td><code style="background:#f8fafc;padding:3px 8px;border-radius:4px;font-size:12px;"><?= $s['idNum'] ?></code></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="avatar avatar-sm" style="background:hsl(<?= $s['id']*47 ?>,65%,55%);color:#fff;flex-shrink:0;">
                <?= mb_substr($isRTL?$s['nameAr']:$s['nameEn'],0,1,'UTF-8') ?>
              </div>
              <span style="font-size:13px;font-weight:600;"><?= htmlspecialchars($isRTL?$s['nameAr']:$s['nameEn']) ?></span>
            </div>
          </td>
          <td style="font-size:12px;"><?= htmlspecialchars($isRTL?$s['majorAr']:$s['majorEn']) ?></td>
          <td>
            <span style="font-weight:800;font-size:14px;color:<?= $gpaColor ?>;"><?= $s['gpa'] ?></span>
            <span style="font-size:10px;color:#94a3b8;">/4.0</span>
          </td>
          <td style="font-size:12px;"><?= htmlspecialchars($isRTL?$s['grantTypeAr']:$s['grantTypeEn']) ?></td>
          <td><span class="amount-text" style="font-size:13px;"><?= number_format($s['amount']) ?></span></td>
          <td style="text-align:center;font-weight:700;"><?= $s['semestersLeft'] ?></td>
          <td><span class="status-badge <?= $sc['cls'] ?>"><?= $isRTL?$sc['ar']:$sc['en'] ?></span></td>
          <td style="font-size:12px;color:#64748b;"><?= $s['appliedDate'] ?></td>
          <td>
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-icon btn-outline-primary"><i class="bi bi-eye" style="font-size:12px;"></i></button>
              <button class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-pencil" style="font-size:12px;"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Multi-step Apply Modal -->
<?php if ($showApply): ?>
<div class="custom-modal-overlay" id="applyOverlay">
  <div class="custom-modal" style="max-width:640px;">
    <div class="custom-modal-header">
      <div>
        <h5 style="margin:0;font-size:16px;font-weight:700;"><?= $isRTL?'نموذج طلب المنحة الدراسية':'Scholarship Application Form' ?></h5>
        <div class="d-flex align-items-center gap-2 mt-2">
          <?php for($i=1;$i<=3;$i++): ?>
          <div style="width:28px;height:4px;border-radius:4px;background:<?= $applyStep>=$i?'var(--primary)':'#e2e8f0' ?>;"></div>
          <?php endfor; ?>
          <span style="font-size:11px;color:#64748b;"><?= $isRTL?"خطوة $applyStep من 3":"Step $applyStep of 3" ?></span>
        </div>
      </div>
      <a href="?page=students&lang=<?= $lang ?>" class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
    </div>
    <div class="custom-modal-body">
      <?php if ($applyStep === 1): ?>
      <h6 style="font-weight:700;margin-bottom:16px;"><?= $isRTL?'البيانات الشخصية':'Personal Information' ?></h6>
      <div class="row g-3">
        <?php foreach ([['ar'=>'الاسم الكامل','en'=>'Full Name','type'=>'text'],['ar'=>'الرقم الجامعي','en'=>'Student ID','type'=>'text'],['ar'=>'البريد الجامعي','en'=>'University Email','type'=>'email'],['ar'=>'رقم الجوال','en'=>'Mobile','type'=>'tel'],['ar'=>'التخصص','en'=>'Major','type'=>'select'],['ar'=>'السنة الدراسية','en'=>'Academic Year','type'=>'select']] as $f): ?>
        <div class="col-12 col-sm-6">
          <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
          <?php if($f['type']==='select'): ?><select class="form-select"><option>-- <?= $isRTL?'اختر':'Select' ?> --</option></select><?php else: ?><input type="<?= $f['type'] ?>" class="form-control"><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php elseif ($applyStep === 2): ?>
      <h6 style="font-weight:700;margin-bottom:16px;"><?= $isRTL?'المعلومات الأكاديمية':'Academic Information' ?></h6>
      <div class="row g-3">
        <?php foreach ([['ar'=>'المعدل التراكمي','en'=>'Cumulative GPA','type'=>'number','full'=>false],['ar'=>'الساعات المكتسبة','en'=>'Earned Credit Hours','type'=>'number','full'=>false],['ar'=>'نوع المنحة المطلوبة','en'=>'Grant Type','type'=>'select','full'=>false],['ar'=>'سبب التقديم','en'=>'Application Reason','type'=>'textarea','full'=>true],['ar'=>'الإنجازات الأكاديمية','en'=>'Academic Achievements','type'=>'textarea','full'=>true]] as $f): ?>
        <div class="col-<?= $f['full']?'12':'6' ?>">
          <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
          <?php if($f['type']==='select'): ?><select class="form-select"><option>--</option></select><?php elseif($f['type']==='textarea'): ?><textarea class="form-control" rows="2"></textarea><?php else: ?><input type="<?= $f['type'] ?>" class="form-control"><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <h6 style="font-weight:700;margin-bottom:16px;"><?= $isRTL?'المرفقات والتأكيد':'Attachments & Confirmation' ?></h6>
      <div class="row g-3">
        <?php foreach ([['ar'=>'كشف الدرجات الرسمي','en'=>'Official Transcript'],['ar'=>'صورة الهوية الوطنية','en'=>'National ID Copy'],['ar'=>'توصية أكاديمية','en'=>'Academic Recommendation']] as $doc): ?>
        <div class="col-12">
          <label class="form-label"><?= $isRTL?$doc['ar']:$doc['en'] ?></label>
          <div class="border rounded-3 p-3 d-flex align-items-center gap-3" style="background:#f8fafc;cursor:pointer;">
            <i class="bi bi-cloud-upload text-primary" style="font-size:22px;"></i>
            <span style="font-size:13px;color:#64748b;"><?= $isRTL?'انقر لرفع الملف (PDF, JPG)':'Click to upload (PDF, JPG)' ?></span>
          </div>
        </div>
        <?php endforeach; ?>
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="agree">
            <label class="form-check-label" for="agree" style="font-size:13px;">
              <?= $isRTL?'أقر بأن جميع المعلومات المقدمة صحيحة ودقيقة':'I confirm that all provided information is accurate and truthful' ?>
            </label>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <div class="custom-modal-footer">
      <?php if ($applyStep > 1): ?>
      <a href="?page=students&lang=<?= $lang ?>&apply=1&step=<?= $applyStep-1 ?>" class="btn btn-outline-secondary">
        <i class="bi bi-chevron-<?= $isRTL?'right':'left' ?> me-1"></i><?= t('previous') ?>
      </a>
      <?php endif; ?>
      <a href="?page=students&lang=<?= $lang ?>" class="btn btn-outline-secondary"><?= t('cancel') ?></a>
      <?php if ($applyStep < 3): ?>
      <a href="?page=students&lang=<?= $lang ?>&apply=1&step=<?= $applyStep+1 ?>" class="btn btn-primary">
        <?= t('next') ?> <i class="bi bi-chevron-<?= $isRTL?'left':'right' ?> ms-1"></i>
      </a>
      <?php else: ?>
      <button class="btn btn-success"><i class="bi bi-send me-1"></i><?= t('submit') ?></button>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>
