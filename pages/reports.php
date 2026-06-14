<?php
// pages/reports.php
$activeTab = $_GET['rtab'] ?? 'overview';

$quarterlyData = [
  'labels' => ['Q1 2023','Q2 2023','Q3 2023','Q4 2023','Q1 2024','Q2 2024'],
  'applications' => [22,28,35,30,38,45],
  'funded' => [14,18,22,19,25,30],
  'budget' => [8.2,10.5,13.1,11.8,14.3,16.7],
];

$deptData = [
  'labels'  => $isRTL
    ? ['علوم الحاسب','الهندسة الكيميائية','هندسة البترول','الهندسة المدنية','الفيزياء','هندسة البيئة']
    : ['Computer Science','Chemical Eng.','Petroleum Eng.','Civil Eng.','Physics','Env. Eng.'],
  'values' => [18,24,15,12,9,14],
  'funding' => [9.4,14.2,18.8,7.6,5.1,8.3],
];

$tabs = [
  ['id'=>'overview','ar'=>'نظرة عامة','en'=>'Overview','icon'=>'bi-grid'],
  ['id'=>'dept','ar'=>'حسب القسم','en'=>'By Department','icon'=>'bi-building'],
  ['id'=>'trends','ar'=>'الاتجاهات','en'=>'Trends','icon'=>'bi-graph-up'],
  ['id'=>'custom','ar'=>'تقرير مخصص','en'=>'Custom Report','icon'=>'bi-file-earmark-bar-graph'],
];

$summaryStats = [
  ['ar'=>'إجمالي الطلبات','en'=>'Total Applications','value'=>'207','change'=>'+18%','up'=>true,'icon'=>'bi-file-earmark-text','color'=>'primary'],
  ['ar'=>'نسبة الموافقة','en'=>'Approval Rate','value'=>'64%','change'=>'+5%','up'=>true,'icon'=>'bi-check-circle','color'=>'success'],
  ['ar'=>'متوسط قيمة المنحة','en'=>'Avg. Grant Value','value'=>'620K','change'=>'+12%','up'=>true,'icon'=>'bi-cash','color'=>'info'],
  ['ar'=>'إجمالي التمويل','en'=>'Total Funding','value'=>'45.2M','change'=>'+18%','up'=>true,'icon'=>'bi-bank','color'=>'warning'],
];
?>

<!-- Tabs -->
<ul class="nav-tabs-custom mb-4">
  <?php foreach ($tabs as $tab): ?>
  <li>
    <a href="?page=reports&lang=<?= $lang ?>&rtab=<?= $tab['id'] ?>" class="nav-link <?= $activeTab===$tab['id']?'active':'' ?>">
      <i class="bi <?= $tab['icon'] ?> me-1"></i><?= $isRTL?$tab['ar']:$tab['en'] ?>
    </a>
  </li>
  <?php endforeach; ?>
</ul>

<?php if ($activeTab === 'overview'): ?>
<!-- Summary Stats -->
<div class="row g-3 mb-4">
  <?php foreach ($summaryStats as $s): ?>
  <div class="col-6 col-xl-3">
    <div class="stat-card <?= $s['color'] ?>">
      <div class="stat-icon <?= $s['color'] ?>"><i class="bi <?= $s['icon'] ?>"></i></div>
      <div class="stat-value"><?= $s['value'] ?></div>
      <div class="stat-label"><?= $isRTL?$s['ar']:$s['en'] ?></div>
      <div class="stat-change <?= $s['up']?'up':'down' ?>"><i class="bi bi-arrow-up-right"></i> <?= $s['change'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-bar-chart me-2 text-primary"></i><?= $isRTL?'الطلبات الفصلية':'Quarterly Applications' ?></h5>
        <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i><?= t('export') ?></button>
      </div>
      <div style="padding:16px;height:260px;"><canvas id="quarterlyChart"></canvas></div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-pie-chart me-2 text-primary"></i><?= $isRTL?'توزيع الطلبات':'Applications Distribution' ?></h5>
      </div>
      <div style="padding:16px;height:260px;display:flex;align-items:center;justify-content:center;"><canvas id="distPie"></canvas></div>
    </div>
  </div>
</div>

<!-- Budget Trend -->
<div class="custom-card mb-4">
  <div class="card-header-custom">
    <h5><i class="bi bi-graph-up-arrow me-2 text-success"></i><?= $isRTL?'اتجاه التمويل الفصلي (مليون ريال)':'Quarterly Funding Trend (Million SAR)' ?></h5>
  </div>
  <div style="padding:16px;height:220px;"><canvas id="fundTrendChart"></canvas></div>
</div>

<?php elseif ($activeTab === 'dept'): ?>
<!-- By Department -->
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-7">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-bar-chart-fill me-2 text-primary"></i><?= $isRTL?'الطلبات حسب القسم':'Applications by Department' ?></h5>
      </div>
      <div style="padding:16px;height:280px;"><canvas id="deptBarChart"></canvas></div>
    </div>
  </div>
  <div class="col-12 col-lg-5">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-pie-chart me-2 text-primary"></i><?= $isRTL?'التمويل حسب القسم':'Funding by Department' ?></h5>
      </div>
      <div style="padding:16px;height:280px;display:flex;align-items:center;justify-content:center;"><canvas id="deptPie"></canvas></div>
    </div>
  </div>
</div>

<!-- Dept Table -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-table me-2 text-primary"></i><?= $isRTL?'تفاصيل الأقسام':'Department Details' ?></h5>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead><tr>
        <th><?= $isRTL?'القسم':'Department' ?></th>
        <th><?= $isRTL?'الطلبات':'Applications' ?></th>
        <th><?= $isRTL?'التمويل (مليون)':'Funding (M)' ?></th>
        <th><?= $isRTL?'نسبة الطلبات':'Share %' ?></th>
      </tr></thead>
      <tbody>
        <?php
        $total = array_sum($deptData['values']);
        foreach ($deptData['labels'] as $i => $dept): ?>
        <tr>
          <td style="font-weight:600;font-size:13px;"><?= htmlspecialchars($dept) ?></td>
          <td style="font-weight:700;"><?= $deptData['values'][$i] ?></td>
          <td><span class="amount-text"><?= $deptData['funding'][$i] ?>M</span></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="progress-custom" style="flex:1;">
                <div class="progress-bar-custom" style="width:<?= round($deptData['values'][$i]/$total*100) ?>%;background:#1a56db;height:8px;"></div>
              </div>
              <span style="font-size:12px;font-weight:700;color:#1a56db;"><?= round($deptData['values'][$i]/$total*100) ?>%</span>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php elseif ($activeTab === 'trends'): ?>
<div class="custom-card mb-4">
  <div class="card-header-custom">
    <h5><i class="bi bi-graph-up me-2 text-primary"></i><?= $isRTL?'اتجاهات التمويل والطلبات':'Funding & Application Trends' ?></h5>
  </div>
  <div style="padding:16px;height:320px;"><canvas id="trendsChart"></canvas></div>
</div>

<?php elseif ($activeTab === 'custom'): ?>
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i><?= $isRTL?'إنشاء تقرير مخصص':'Generate Custom Report' ?></h5>
  </div>
  <div class="p-4">
    <div class="row g-3">
      <?php
      $cfields = [
        ['ar'=>'نوع التقرير','en'=>'Report Type','type'=>'select'],
        ['ar'=>'الفترة الزمنية','en'=>'Time Period','type'=>'select'],
        ['ar'=>'القسم','en'=>'Department','type'=>'select'],
        ['ar'=>'تاريخ البداية','en'=>'From Date','type'=>'date'],
        ['ar'=>'تاريخ النهاية','en'=>'To Date','type'=>'date'],
        ['ar'=>'صيغة التصدير','en'=>'Export Format','type'=>'select'],
      ];
      foreach ($cfields as $f): ?>
      <div class="col-12 col-sm-6">
        <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
        <?php if($f['type']==='select'): ?><select class="form-select"><option>--</option></select><?php else: ?><input type="<?= $f['type'] ?>" class="form-control"><?php endif; ?>
      </div>
      <?php endforeach; ?>
      <div class="col-12">
        <button class="btn btn-primary"><i class="bi bi-file-earmark-bar-graph me-2"></i><?= $isRTL?'إنشاء التقرير':'Generate Report' ?></button>
        <button class="btn btn-outline-success ms-2"><i class="bi bi-file-earmark-excel me-1"></i>Excel</button>
        <button class="btn btn-outline-danger ms-2"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php
$pageScript = "
const qLabels = ".json_encode($quarterlyData['labels']).";
const qApps   = ".json_encode($quarterlyData['applications']).";
const qFunded = ".json_encode($quarterlyData['funded']).";
const qBudget = ".json_encode($quarterlyData['budget']).";
const dLabels = ".json_encode($deptData['labels']).";
const dValues = ".json_encode($deptData['values']).";
const dFund   = ".json_encode($deptData['funding']).";

if(document.getElementById('quarterlyChart')){
  new Chart(document.getElementById('quarterlyChart'),{type:'bar',data:{labels:qLabels,datasets:[{label:'".($isRTL?'الطلبات':'Applications')."',data:qApps,backgroundColor:'#1a56db',borderRadius:4},{label:'".($isRTL?'الممولة':'Funded')."',data:qFunded,backgroundColor:'#22c55e',borderRadius:4}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{family:'Cairo'}}}},scales:{x:{ticks:{font:{size:10}}},y:{ticks:{font:{size:11}}}}}});
}
if(document.getElementById('distPie')){
  new Chart(document.getElementById('distPie'),{type:'pie',data:{labels:['".($isRTL?'ممولة':'Funded')."','".($isRTL?'تحت المراجعة':'Review')."','".($isRTL?'قيد الانتظار':'Pending')."','".($isRTL?'مرفوضة':'Rejected')."'],datasets:[{data:[63,14,12,11],backgroundColor:['#22c55e','#1a56db','#f59e0b','#ef4444'],borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{family:'Cairo',size:11}}}}}});
}
if(document.getElementById('fundTrendChart')){
  new Chart(document.getElementById('fundTrendChart'),{type:'line',data:{labels:qLabels,datasets:[{label:'".($isRTL?'التمويل (مليون)':'Funding (M)')."',data:qBudget,borderColor:'#22c55e',backgroundColor:'rgba(34,197,94,0.1)',fill:true,tension:0.4,borderWidth:2.5,pointRadius:4}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{family:'Cairo'}}}},scales:{x:{ticks:{font:{size:10}}},y:{ticks:{font:{size:11}}}}}});
}
if(document.getElementById('deptBarChart')){
  new Chart(document.getElementById('deptBarChart'),{type:'bar',data:{labels:dLabels,datasets:[{label:'".($isRTL?'عدد الطلبات':'Applications')."',data:dValues,backgroundColor:'#1a56db',borderRadius:4}]},options:{responsive:true,maintainAspectRatio:false,indexAxis:'y',plugins:{legend:{labels:{font:{family:'Cairo'}}}},scales:{x:{ticks:{font:{size:11}}},y:{ticks:{font:{size:10}}}}}});
}
if(document.getElementById('deptPie')){
  new Chart(document.getElementById('deptPie'),{type:'doughnut',data:{labels:dLabels,datasets:[{data:dFund,backgroundColor:['#1a56db','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4'],borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{family:'Cairo',size:10}}}}}});
}
if(document.getElementById('trendsChart')){
  new Chart(document.getElementById('trendsChart'),{type:'line',data:{labels:qLabels,datasets:[{label:'".($isRTL?'الطلبات':'Applications')."',data:qApps,borderColor:'#1a56db',fill:false,tension:0.4,borderWidth:2,pointRadius:4},{label:'".($isRTL?'التمويل (مليون)':'Funding (M)')."',data:qBudget,borderColor:'#f59e0b',fill:false,tension:0.4,borderWidth:2,pointRadius:4,yAxisID:'y2'}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{family:'Cairo'}}}},scales:{x:{ticks:{font:{size:10}}},y:{ticks:{font:{size:11}}},y2:{position:'right',ticks:{font:{size:11}}}}}});
}
";
?>
