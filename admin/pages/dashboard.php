<?php
require_once dirname(__DIR__) . '/includes/auth_check.php';

$pageTitle = t('dashboard');

$totalGrants    = (int)(dbVal('SELECT COUNT(*) FROM grants') ?: 10);
$activeProjects = (int)(dbVal('SELECT COUNT(*) FROM projects WHERE status="active"') ?: 4);
$totalFunding   = (float)(dbVal('SELECT SUM(amount) FROM grants WHERE status NOT IN ("rejected","pending")') ?: 5200000);
$pendingCount   = (int)(dbVal('SELECT COUNT(*) FROM grants WHERE status="pending" OR status="under_review"') ?: 4);

$recentGrants = dbAll('SELECT g.*, u.name as applicant_name FROM grants g LEFT JOIN users u ON g.applicant_id=u.id ORDER BY g.created_at DESC LIMIT 6');
if (!$recentGrants) {
    $recentGrants = [
        ['title_ar'=>'منحة أبحاث الذكاء الاصطناعي','title_en'=>'AI Research Grant','applicant_name'=>'د. أحمد الشمري','amount'=>750000,'status'=>'active','submission_date'=>'2024-01-15'],
        ['title_ar'=>'منحة الطاقة المتجددة','title_en'=>'Renewable Energy Grant','applicant_name'=>'د. سارة القحطاني','amount'=>520000,'status'=>'active','submission_date'=>'2024-02-20'],
        ['title_ar'=>'منحة التحول الرقمي','title_en'=>'Digital Transformation','applicant_name'=>'د. خالد الزهراني','amount'=>980000,'status'=>'approved','submission_date'=>'2024-03-10'],
        ['title_ar'=>'منحة النانو تكنولوجي','title_en'=>'Nanotechnology Grant','applicant_name'=>'د. أحمد الشمري','amount'=>1200000,'status'=>'under_review','submission_date'=>'2024-04-18'],
        ['title_ar'=>'منحة ريادة الأعمال','title_en'=>'Tech Entrepreneurship','applicant_name'=>'م. فيصل الدوسري','amount'=>290000,'status'=>'active','submission_date'=>'2024-02-14'],
        ['title_ar'=>'منحة أبحاث المياه','title_en'=>'Water Research Grant','applicant_name'=>'د. أحمد الشمري','amount'=>840000,'status'=>'pending','submission_date'=>'2024-06-10'],
    ];
}

$monthlyData = [3,5,4,7,6,8,5,9,7,11,8,10];
$months_ar   = ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];
$months_en   = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
?>

<!-- STATS -->
<div class="row g-4 mb-4">
  <?php
  $stats = [
    ['icon'=>'bi-file-earmark-text-fill','color'=>'#6366f1','value'=>$totalGrants,           'ar'=>'إجمالي المنح',         'en'=>'Total Grants',       'change'=>'+12%'],
    ['icon'=>'bi-journal-code',           'color'=>'#10b981','value'=>$activeProjects,        'ar'=>'المشاريع النشطة',       'en'=>'Active Projects',    'change'=>'+3'],
    ['icon'=>'bi-cash-stack',             'color'=>'#f59e0b','value'=>number_format($totalFunding/1000000,1).'M', 'ar'=>'إجمالي التمويل (ريال)','en'=>'Total Funding (SAR)', 'change'=>'+8.4%'],
    ['icon'=>'bi-hourglass-split',        'color'=>'#ef4444','value'=>$pendingCount,           'ar'=>'بانتظار الموافقة',     'en'=>'Pending Approvals',  'change'=>'-2'],
  ];
  foreach($stats as $s): ?>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:<?=$s['color']?>22;color:<?=$s['color']?>;border-color:<?=$s['color']?>">
        <i class="bi <?=$s['icon']?>"></i>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?= $s['value'] ?></div>
        <div class="stat-label"><?= $isRTL ? $s['ar'] : $s['en'] ?></div>
      </div>
      <div class="stat-change" style="color:<?= str_starts_with($s['change'],'+') ? '#10b981' : '#ef4444' ?>">
        <i class="bi bi-arrow-<?= str_starts_with($s['change'],'+') ? 'up' : 'down' ?>-right-circle-fill"></i>
        <?= $s['change'] ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="card-custom">
      <div class="card-custom-header">
        <h5 class="card-custom-title">
          <i class="bi bi-bar-chart-fill" style="color:#6366f1"></i>
          <?= $isRTL ? 'الطلبات الشهرية' : 'Monthly Applications' ?>
        </h5>
      </div>
      <div class="card-custom-body">
        <canvas id="monthlyChart" height="240"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card-custom h-100">
      <div class="card-custom-header">
        <h5 class="card-custom-title">
          <i class="bi bi-pie-chart-fill" style="color:#f59e0b"></i>
          <?= $isRTL ? 'توزيع أنواع المنح' : 'Grant Distribution' ?>
        </h5>
      </div>
      <div class="card-custom-body">
        <canvas id="donutChart" height="210"></canvas>
        <div class="row g-2 mt-3">
          <?php
          $types = [
            ['ar'=>'بحثية','en'=>'Research','pct'=>40,'color'=>'#6366f1'],
            ['ar'=>'منح دراسية','en'=>'Scholarships','pct'=>25,'color'=>'#10b981'],
            ['ar'=>'معدات','en'=>'Equipment','pct'=>20,'color'=>'#f59e0b'],
            ['ar'=>'دولية','en'=>'International','pct'=>15,'color'=>'#ef4444'],
          ];
          foreach($types as $t): ?>
          <div class="col-6">
            <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--text-secondary);">
              <span style="width:12px;height:12px;border-radius:50%;background:<?=$t['color']?>;flex-shrink:0;border:2px solid #000;"></span>
              <?= $isRTL ? $t['ar'] : $t['en'] ?> (<?=$t['pct']?>%)
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- QUICK ACTIONS -->
<div class="row g-3 mb-4">
  <?php
  $actions = [
    ['icon'=>'bi-plus-circle-fill','color'=>'#6366f1','ar'=>'طلب منحة جديد',     'en'=>'New Grant Request',      'href'=>'?page=grants'],
    ['icon'=>'bi-journal-plus',    'color'=>'#10b981','ar'=>'إضافة مشروع',        'en'=>'Add Project',            'href'=>'?page=projects'],
    ['icon'=>'bi-people-fill',     'color'=>'#f59e0b','ar'=>'إدارة المستخدمين',  'en'=>'Manage Users',           'href'=>'?page=users'],
    ['icon'=>'bi-download',        'color'=>'#8b5cf6','ar'=>'تصدير التقارير',     'en'=>'Export Reports',         'href'=>'?page=reports'],
  ];
  foreach($actions as $a): ?>
  <div class="col-sm-6 col-xl-3">
    <a href="<?=$a['href']?>" class="quick-action-btn" style="--qa-color:<?=$a['color']?>">
      <i class="bi <?=$a['icon']?>" style="font-size:24px;color:<?=$a['color']?>"></i>
      <span><?= $isRTL ? $a['ar'] : $a['en'] ?></span>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<!-- RECENT GRANTS TABLE -->
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-clock-history" style="color:#f59e0b"></i>
      <?= $isRTL ? 'المنح الأخيرة' : 'Recent Grants' ?>
    </h5>
    <a href="?page=grants" class="btn-sm-outline">
      <?= $isRTL ? 'عرض الكل' : 'View All' ?>
      <i class="bi bi-arrow-<?= $isRTL ? 'left' : 'right' ?>"></i>
    </a>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th data-label="<?= $isRTL ? 'العنوان' : 'Title' ?>"><?= $isRTL ? 'العنوان' : 'Title' ?></th>
          <th data-label="<?= $isRTL ? 'مقدم الطلب' : 'Applicant' ?>"><?= $isRTL ? 'مقدم الطلب' : 'Applicant' ?></th>
          <th data-label="<?= $isRTL ? 'المبلغ' : 'Amount' ?>"><?= $isRTL ? 'المبلغ' : 'Amount' ?></th>
          <th data-label="<?= $isRTL ? 'تاريخ التقديم' : 'Date' ?>"><?= $isRTL ? 'تاريخ التقديم' : 'Date' ?></th>
          <th data-label="<?= $isRTL ? 'الحالة' : 'Status' ?>"><?= $isRTL ? 'الحالة' : 'Status' ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($recentGrants as $g):
          $statusMap = [
            'active'       =>['ar'=>'نشط',          'en'=>'Active',        'class'=>'badge-success'],
            'approved'     =>['ar'=>'موافق عليه',    'en'=>'Approved',      'class'=>'badge-info'],
            'under_review' =>['ar'=>'قيد المراجعة',  'en'=>'Under Review',  'class'=>'badge-warning'],
            'pending'      =>['ar'=>'معلق',          'en'=>'Pending',       'class'=>'badge-secondary'],
            'rejected'     =>['ar'=>'مرفوض',         'en'=>'Rejected',      'class'=>'badge-danger'],
            'closed'       =>['ar'=>'مغلق',          'en'=>'Closed',        'class'=>'badge-secondary'],
          ];
          $s = $statusMap[$g['status']] ?? ['ar'=>$g['status'],'en'=>$g['status'],'class'=>'badge-secondary'];
        ?>
        <tr>
          <td data-label="<?= $isRTL ? 'العنوان' : 'Title' ?>" style="font-weight:700;color:var(--text-primary)">
            <?= htmlspecialchars($isRTL ? $g['title_ar'] : $g['title_en']) ?>
          </td>
          <td data-label="<?= $isRTL ? 'مقدم الطلب' : 'Applicant' ?>" style="color:var(--text-secondary)"><?= htmlspecialchars($g['applicant_name'] ?? '—') ?></td>
          <td data-label="<?= $isRTL ? 'المبلغ' : 'Amount' ?>" style="font-weight:700;color:var(--text-primary)"><?= number_format($g['amount']) ?> <?= $isRTL?'ريال':'SAR' ?></td>
          <td data-label="<?= $isRTL ? 'تاريخ التقديم' : 'Date' ?>" style="color:var(--text-muted)"><?= $g['submission_date'] ?></td>
          <td data-label="<?= $isRTL ? 'الحالة' : 'Status' ?>"><span class="badge-status <?= $s['class'] ?>"><?= $isRTL ? $s['ar'] : $s['en'] ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$pageScript = <<<'JS'
const months = document.documentElement.lang === 'ar'
  ? ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر']
  : ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

new Chart(document.getElementById('monthlyChart'), {
  type: 'bar',
  data: {
    labels: months,
    datasets: [{
      label: document.documentElement.lang === 'ar' ? 'عدد الطلبات' : 'Applications',
      data: [3,5,4,7,6,8,5,9,7,11,8,10],
      backgroundColor: 'rgba(99,102,241,0.7)',
      borderColor: '#6366f1',
      borderWidth: 1,
      borderRadius: 6,
    },{
      label: document.documentElement.lang === 'ar' ? 'موافق عليها' : 'Approved',
      data: [2,3,3,5,4,6,4,7,5,8,6,8],
      backgroundColor: 'rgba(16,185,129,0.7)',
      borderColor: '#10b981',
      borderWidth: 1,
      borderRadius: 6,
    }]
  },
  options: {responsive:true,plugins:{legend:{position:'top'}},scales:{y:{beginAtZero:true,grid:{color:'rgba(255,255,255,0.05)'}}}}
});

new Chart(document.getElementById('donutChart'), {
  type: 'doughnut',
  data: {
    labels: document.documentElement.lang === 'ar'
      ? ['بحثية','منح دراسية','معدات','دولية']
      : ['Research','Scholarships','Equipment','International'],
    datasets: [{
      data: [40,25,20,15],
      backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444'],
      borderWidth: 0,
      hoverOffset: 6,
    }]
  },
  options: {responsive: true, cutout:'70%', plugins: {legend:{display:false}}}
});
JS;
?>
