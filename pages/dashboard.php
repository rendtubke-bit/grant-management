<?php
// pages/dashboard.php
$monthlyLabels = ['يناير/Jan','فبراير/Feb','مارس/Mar','أبريل/Apr','مايو/May','يونيو/Jun','يوليو/Jul','أغسطس/Aug','سبتمبر/Sep','أكتوبر/Oct','نوفمبر/Nov','ديسمبر/Dec'];
$monthlyApps   = [8,12,15,10,18,14,22,16,20,25,19,28];
$monthlyFunded = [5,7,9,6,11,8,14,10,13,16,12,18];
$fundYears     = ['2019','2020','2021','2022','2023','2024'];
$fundAmounts   = [18.5,22.1,28.7,35.4,40.8,45.2];
$grantDistLabels = ['بحثية / Research','دراسية / Scholarship','صناعية / Industry','دولية / International'];
$grantDistValues = [42,28,18,12];

$recentActivity = [
  ['id'=>1,'actionAr'=>'موافقة على منحة','actionEn'=>'Grant Approved','projectAr'=>'تطوير خلايا طاقة شمسية','projectEn'=>'Solar Cell Development','userAr'=>'د. أحمد الزهراني','userEn'=>'Dr. Ahmed Al-Zahrani','status'=>'funded','amount'=>'750,000','time'=>$isRTL?'منذ ساعتين':'2h ago'],
  ['id'=>2,'actionAr'=>'طلب تقديم جديد','actionEn'=>'New Application','projectAr'=>'نمذجة الموارد المائية','projectEn'=>'Water Resource Modeling','userAr'=>'د. نورة القحطاني','userEn'=>'Dr. Noura Al-Qahtani','status'=>'pending','amount'=>'600,000','time'=>$isRTL?'منذ 5 ساعات':'5h ago'],
  ['id'=>3,'actionAr'=>'مراجعة الميزانية','actionEn'=>'Budget Review','projectAr'=>'بحث الذكاء الاصطناعي','projectEn'=>'AI Research','userAr'=>'د. فاطمة العمري','userEn'=>'Dr. Fatima Al-Omari','status'=>'review','amount'=>'1,200,000','time'=>$isRTL?'أمس':'Yesterday'],
  ['id'=>4,'actionAr'=>'رفض الطلب','actionEn'=>'Application Rejected','projectAr'=>'مواد بناء مستدامة','projectEn'=>'Sustainable Materials','userAr'=>'د. خالد الجهني','userEn'=>'Dr. Khalid Al-Johani','status'=>'rejected','amount'=>'450,000','time'=>$isRTL?'أمس':'Yesterday'],
  ['id'=>5,'actionAr'=>'تقرير إنجاز مقدم','actionEn'=>'Progress Report','projectAr'=>'هندسة المواد المتقدمة','projectEn'=>'Advanced Materials Eng.','userAr'=>'د. سارة المالكي','userEn'=>'Dr. Sara Al-Malki','status'=>'active','amount'=>'900,000','time'=>$isRTL?'منذ يومين':'2d ago'],
];

$statusConfig = [
  'funded'   => ['ar'=>'ممول',          'en'=>'Funded',       'cls'=>'funded',   'icon'=>'bi-check-circle-fill'],
  'pending'  => ['ar'=>'قيد الانتظار',  'en'=>'Pending',      'cls'=>'pending',  'icon'=>'bi-clock-fill'],
  'review'   => ['ar'=>'تحت المراجعة', 'en'=>'Under Review', 'cls'=>'review',   'icon'=>'bi-eye-fill'],
  'rejected' => ['ar'=>'مرفوض',        'en'=>'Rejected',     'cls'=>'rejected', 'icon'=>'bi-x-circle-fill'],
  'active'   => ['ar'=>'نشط',          'en'=>'Active',       'cls'=>'active',   'icon'=>'bi-play-circle-fill'],
];

$quickActions = [
  ['icon'=>'bi-plus-circle-fill','ar'=>'تقديم طلب منحة جديد','en'=>'New Grant Application','color'=>'#1a56db','page'=>'grants'],
  ['icon'=>'bi-journal-plus',    'ar'=>'إنشاء مشروع بحثي',   'en'=>'Create Research Project','color'=>'#22c55e','page'=>'projects'],
  ['icon'=>'bi-cash-coin',       'ar'=>'إضافة مصروف ميزانية','en'=>'Add Budget Expense',    'color'=>'#f59e0b','page'=>'budget'],
  ['icon'=>'bi-file-earmark-bar-graph','ar'=>'إنشاء تقرير تحليلي','en'=>'Generate Report','color'=>'#8b5cf6','page'=>'reports'],
  ['icon'=>'bi-person-plus-fill','ar'=>'إضافة باحث جديد',    'en'=>'Add Researcher',        'color'=>'#06b6d4','page'=>'researchers'],
];
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['key'=>'totalGrants',     'value'=>'127', 'change'=>'+12%', 'up'=>true,  'icon'=>'bi-file-earmark-check-fill','color'=>'primary'],
    ['key'=>'activeProjects',  'value'=>'34',  'change'=>'+8%',  'up'=>true,  'icon'=>'bi-journal-code',           'color'=>'success'],
    ['key'=>'totalFunding',    'value'=>'45.2M','change'=>'+18%','up'=>true,  'icon'=>'bi-cash-stack',             'color'=>'info'],
    ['key'=>'pendingApprovals','value'=>'12',  'change'=>'-3',   'up'=>false, 'icon'=>'bi-hourglass-split',        'color'=>'warning'],
  ];
  foreach ($stats as $s): ?>
  <div class="col-6 col-xl-3">
    <div class="stat-card <?= $s['color'] ?>">
      <div class="stat-icon <?= $s['color'] ?>"><i class="bi <?= $s['icon'] ?>"></i></div>
      <div class="stat-value"><?= $s['value'] ?></div>
      <div class="stat-label"><?= t($s['key']) ?></div>
      <div class="stat-change <?= $s['up']?'up':'down' ?>">
        <i class="bi <?= $s['up']?'bi-arrow-up-right':'bi-arrow-down-right' ?>"></i> <?= $s['change'] ?>
        <span style="color:#94a3b8;margin-<?= $isRTL?'right':'left' ?>:4px;"><?= $isRTL?'عن الفترة السابقة':'vs last period' ?></span>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts Row 1 -->
<div class="row g-3 mb-4">
  <!-- Monthly Bar Chart -->
  <div class="col-12 col-lg-8">
    <div class="custom-card h-100">
      <div class="card-header-custom">
        <h5><i class="bi bi-bar-chart me-2 text-primary"></i><?= t('monthlyApplications') ?></h5>
        <div class="d-flex gap-2">
          <span class="badge" style="background:#1a56db22;color:#1a56db;border-radius:6px;padding:4px 10px;font-size:12px;">2024</span>
          <button class="btn btn-sm btn-outline-secondary"><?= t('export') ?></button>
        </div>
      </div>
      <div style="padding:16px;height:280px;">
        <canvas id="barChart"></canvas>
      </div>
    </div>
  </div>
  <!-- Pie Chart -->
  <div class="col-12 col-lg-4">
    <div class="custom-card h-100">
      <div class="card-header-custom">
        <h5><i class="bi bi-pie-chart me-2 text-primary"></i><?= t('grantDistribution') ?></h5>
      </div>
      <div style="padding:12px;height:280px;display:flex;align-items:center;justify-content:center;">
        <canvas id="pieChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Funding Trend + Quick Actions -->
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-graph-up-arrow me-2 text-success"></i><?= t('fundingTrend') ?> (<?= $isRTL?'مليون ريال':'Million SAR' ?>)</h5>
        <span class="badge" style="background:#22c55e22;color:#16a34a;border-radius:6px;padding:4px 10px;font-size:12px;">2019-2024</span>
      </div>
      <div style="padding:16px;height:220px;">
        <canvas id="areaChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="custom-card h-100">
      <div class="card-header-custom">
        <h5><i class="bi bi-lightning-fill me-2 text-warning"></i><?= t('quickActions') ?></h5>
      </div>
      <div class="p-3 d-flex flex-column gap-2">
        <?php foreach ($quickActions as $qa): ?>
        <a href="<?= pageUrl($qa['page']) ?>" style="text-decoration:none;">
          <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:<?= $qa['color'] ?>12;cursor:pointer;transition:background .2s;" onmouseover="this.style.background='<?= $qa['color'] ?>22'" onmouseout="this.style.background='<?= $qa['color'] ?>12'">
            <div style="width:36px;height:36px;border-radius:10px;background:<?= $qa['color'] ?>22;display:flex;align-items:center;justify-content:center;color:<?= $qa['color'] ?>;font-size:17px;flex-shrink:0;">
              <i class="bi <?= $qa['icon'] ?>"></i>
            </div>
            <span style="font-size:13px;font-weight:600;color:#1e293b;"><?= loc($qa['ar'],$qa['en']) ?></span>
            <i class="bi bi-chevron-<?= $isRTL?'left':'right' ?> ms-auto" style="color:#94a3b8;font-size:12px;"></i>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-clock-history me-2 text-primary"></i><?= t('recentActivity') ?></h5>
    <a href="<?= pageUrl('grants') ?>" class="btn btn-sm btn-outline-primary"><?= t('viewAllGrants') ?> <i class="bi bi-arrow-<?= $isRTL?'left':'right' ?> ms-1"></i></a>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= $isRTL?'الإجراء / المشروع':'Action / Project' ?></th>
          <th><?= t('applicant') ?></th>
          <th><?= t('amount') ?> (<?= t('sar') ?>)</th>
          <th><?= t('status') ?></th>
          <th><?= $isRTL?'الوقت':'Time' ?></th>
          <th><?= t('actions') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentActivity as $row):
          $sc = $statusConfig[$row['status']]; ?>
        <tr>
          <td><span class="text-muted" style="font-size:12px;">#<?= $row['id'] ?></span></td>
          <td>
            <div style="font-weight:600;font-size:13px;"><?= $isRTL?$row['actionAr']:$row['actionEn'] ?></div>
            <div style="font-size:12px;color:#64748b;"><?= $isRTL?$row['projectAr']:$row['projectEn'] ?></div>
          </td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="avatar avatar-sm" style="background:hsl(<?= $row['id']*50 ?>,70%,50%);color:#fff;flex-shrink:0;">
                <?= mb_substr($isRTL?$row['userAr']:$row['userEn'], 3, 1, 'UTF-8') ?>
              </div>
              <span style="font-size:13px;"><?= $isRTL?$row['userAr']:$row['userEn'] ?></span>
            </div>
          </td>
          <td><span class="amount-text" style="font-size:13px;"><?= $row['amount'] ?></span></td>
          <td><span class="status-badge <?= $sc['cls'] ?>"><i class="bi <?= $sc['icon'] ?>" style="font-size:11px;"></i><?= $isRTL?$sc['ar']:$sc['en'] ?></span></td>
          <td style="font-size:12px;color:#94a3b8;"><?= $row['time'] ?></td>
          <td>
            <div class="d-flex gap-1">
              <button class="btn btn-sm btn-icon btn-outline-primary"><i class="bi bi-eye" style="font-size:13px;"></i></button>
              <button class="btn btn-sm btn-icon btn-outline-secondary"><i class="bi bi-pencil" style="font-size:13px;"></i></button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$pageScript = "
const isRTL = " . ($isRTL ? 'true' : 'false') . ";

// Bar Chart
new Chart(document.getElementById('barChart'), {
  type: 'bar',
  data: {
    labels: " . json_encode($monthlyLabels) . ",
    datasets: [
      { label: isRTL ? 'الطلبات' : 'Applications', data: " . json_encode($monthlyApps) . ", backgroundColor: '#1a56db', borderRadius: 4 },
      { label: isRTL ? 'الممولة' : 'Funded',       data: " . json_encode($monthlyFunded) . ", backgroundColor: '#22c55e', borderRadius: 4 }
    ]
  },
  options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { font: { family: 'Cairo' } } } }, scales: { x: { ticks: { font: { size: 10 } } }, y: { ticks: { font: { size: 11 } } } } }
});

// Pie Chart
new Chart(document.getElementById('pieChart'), {
  type: 'doughnut',
  data: {
    labels: " . json_encode($grantDistLabels) . ",
    datasets: [{ data: " . json_encode($grantDistValues) . ", backgroundColor: ['#1a56db','#22c55e','#f59e0b','#ef4444'], borderWidth: 2 }]
  },
  options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { family: 'Cairo', size: 11 } } } } }
});

// Area Chart
new Chart(document.getElementById('areaChart'), {
  type: 'line',
  data: {
    labels: " . json_encode($fundYears) . ",
    datasets: [{
      label: isRTL ? 'التمويل (مليون)' : 'Funding (M)',
      data: " . json_encode($fundAmounts) . ",
      borderColor: '#1a56db', backgroundColor: 'rgba(26,86,219,0.1)', fill: true, tension: 0.4, borderWidth: 2.5, pointRadius: 4
    }]
  },
  options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { labels: { font: { family: 'Cairo' } } } }, scales: { x: { ticks: { font: { size: 12 } } }, y: { ticks: { font: { size: 11 } } } } }
});
";
?>
