<?php
// pages/budget.php
$budgetItems = [
  ['id'=>1,'projectAr'=>'تطوير مواد نانوية لتخزين الطاقة','projectEn'=>'Nano Materials for Energy Storage','categoryAr'=>'رواتب الباحثين','categoryEn'=>'Researcher Salaries','amount'=>480000,'spent'=>336000,'status'=>'active'],
  ['id'=>2,'projectAr'=>'الذكاء الاصطناعي في تشخيص أمراض القلب','projectEn'=>'AI in Cardiac Disease Diagnosis','categoryAr'=>'أجهزة ومعدات','categoryEn'=>'Equipment','amount'=>360000,'spent'=>162000,'status'=>'active'],
  ['id'=>3,'projectAr'=>'تقنيات التقاط ثاني أكسيد الكربون','projectEn'=>'Carbon Capture Technologies','categoryAr'=>'مواد ومستلزمات','categoryEn'=>'Materials & Supplies','amount'=>630000,'spent'=>567000,'status'=>'active'],
  ['id'=>4,'projectAr'=>'تحسين أداء الخرسانة الساحلية','projectEn'=>'Coastal Concrete Performance','categoryAr'=>'سفريات وتنقلات','categoryEn'=>'Travel','amount'=>195000,'spent'=>58500,'status'=>'active'],
  ['id'=>5,'projectAr'=>'ترشيح المياه بالأغشية','projectEn'=>'Membrane Water Filtration','categoryAr'=>'خدمات استشارية','categoryEn'=>'Consulting Services','amount'=>255000,'spent'=>153000,'status'=>'active'],
  ['id'=>6,'projectAr'=>'تحليل شبكات الكهرباء الذكية','projectEn'=>'Smart Grid Analytics','categoryAr'=>'نشر وطباعة','categoryEn'=>'Publications','amount'=>78000,'spent'=>78000,'status'=>'completed'],
];

$expenses = [
  ['id'=>1,'descAr'=>'رواتب فريق البحث – الربع الأول','descEn'=>'Research Team Salaries Q1','projectAr'=>'تطوير مواد نانوية','projectEn'=>'Nano Materials','amount'=>120000,'date'=>'2024-03-31','categoryAr'=>'رواتب','categoryEn'=>'Salaries','status'=>'approved'],
  ['id'=>2,'descAr'=>'شراء جهاز مطياف الكتلة','descEn'=>'Mass Spectrometer Purchase','projectAr'=>'الذكاء الاصطناعي - القلب','projectEn'=>'AI Cardiac','amount'=>95000,'date'=>'2024-04-15','categoryAr'=>'أجهزة','categoryEn'=>'Equipment','status'=>'approved'],
  ['id'=>3,'descAr'=>'مواد كيميائية للتجارب','descEn'=>'Chemical Materials for Experiments','projectAr'=>'التقاط الكربون','projectEn'=>'Carbon Capture','amount'=>47000,'date'=>'2024-05-02','categoryAr'=>'مستلزمات','categoryEn'=>'Supplies','status'=>'pending'],
  ['id'=>4,'descAr'=>'تذاكر مؤتمر ICSE 2024','descEn'=>'ICSE 2024 Conference Tickets','projectAr'=>'ترشيح المياه','projectEn'=>'Water Filtration','amount'=>18500,'date'=>'2024-05-10','categoryAr'=>'سفريات','categoryEn'=>'Travel','status'=>'approved'],
  ['id'=>5,'descAr'=>'رسوم نشر مقالة علمية','descEn'=>'Journal Publication Fees','projectAr'=>'شبكات الكهرباء','projectEn'=>'Smart Grid','amount'=>8500,'date'=>'2024-05-18','categoryAr'=>'نشر','categoryEn'=>'Publications','status'=>'approved'],
];

$totalBudget  = array_sum(array_column($budgetItems,'amount'));
$totalSpent   = array_sum(array_column($budgetItems,'spent'));
$totalRemain  = $totalBudget - $totalSpent;
$spentPct     = round($totalSpent/$totalBudget*100);

$catLabels = $isRTL
  ? ['رواتب الباحثين','أجهزة ومعدات','مواد ومستلزمات','سفريات','خدمات استشارية','نشر وطباعة']
  : ['Researcher Salaries','Equipment','Materials & Supplies','Travel','Consulting','Publications'];
$catBudget = [480000,360000,630000,195000,255000,78000];
$catSpent  = [336000,162000,567000,58500,153000,78000];
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['ar'=>'إجمالي الميزانية المعتمدة','en'=>'Total Approved Budget','value'=>number_format($totalBudget),'icon'=>'bi-cash-stack','color'=>'primary'],
    ['ar'=>'المصروف حتى الآن','en'=>'Total Spent','value'=>number_format($totalSpent),'icon'=>'bi-credit-card','color'=>'danger'],
    ['ar'=>'الرصيد المتبقي','en'=>'Remaining Balance','value'=>number_format($totalRemain),'icon'=>'bi-wallet2','color'=>'success'],
    ['ar'=>'نسبة الصرف','en'=>'Spending Rate','value'=>$spentPct.'%','icon'=>'bi-pie-chart-fill','color'=>'warning'],
  ];
  foreach ($stats as $s): ?>
  <div class="col-6 col-xl-3">
    <div class="stat-card <?= $s['color'] ?>">
      <div class="stat-icon <?= $s['color'] ?>"><i class="bi <?= $s['icon'] ?>"></i></div>
      <div class="stat-value" style="font-size:22px;direction:ltr;"><?= $s['value'] ?></div>
      <div class="stat-label"><?= $isRTL?$s['ar']:$s['en'] ?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
  <div class="col-12 col-lg-8">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-bar-chart-fill me-2 text-primary"></i><?= $isRTL?'الميزانية حسب الفئة':'Budget by Category' ?></h5>
      </div>
      <div style="padding:16px;height:260px;">
        <canvas id="budgetBarChart"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4">
    <div class="custom-card">
      <div class="card-header-custom">
        <h5><i class="bi bi-pie-chart me-2 text-primary"></i><?= $isRTL?'توزيع الإنفاق':'Spending Distribution' ?></h5>
      </div>
      <div style="padding:16px;height:260px;display:flex;align-items:center;justify-content:center;">
        <canvas id="spendPie"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Project Budget Table -->
<div class="custom-card mb-4">
  <div class="card-header-custom">
    <h5><i class="bi bi-table me-2 text-primary"></i><?= $isRTL?'ميزانية المشاريع':'Project Budgets' ?></h5>
    <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-download me-1"></i><?= t('export') ?></button>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th><?= $isRTL?'المشروع':'Project' ?></th>
          <th><?= $isRTL?'الفئة':'Category' ?></th>
          <th><?= $isRTL?'المعتمد':'Budget' ?></th>
          <th><?= $isRTL?'المصروف':'Spent' ?></th>
          <th><?= $isRTL?'المتبقي':'Remaining' ?></th>
          <th><?= $isRTL?'نسبة الصرف':'Usage %' ?></th>
          <th><?= t('status') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($budgetItems as $b):
          $remain = $b['amount'] - $b['spent'];
          $pct    = round($b['spent']/$b['amount']*100);
          $pctColor = $pct>=90?'#ef4444':($pct>=70?'#f59e0b':'#22c55e');
        ?>
        <tr>
          <td style="font-weight:600;font-size:13px;"><?= htmlspecialchars($isRTL?$b['projectAr']:$b['projectEn']) ?></td>
          <td style="font-size:12px;color:#64748b;"><?= htmlspecialchars($isRTL?$b['categoryAr']:$b['categoryEn']) ?></td>
          <td><span class="amount-text"><?= number_format($b['amount']) ?></span></td>
          <td><span class="amount-text" style="color:#ef4444;"><?= number_format($b['spent']) ?></span></td>
          <td><span class="amount-text" style="color:#22c55e;"><?= number_format($remain) ?></span></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;min-width:100px;">
              <div class="progress-custom" style="flex:1;">
                <div class="progress-bar-custom" style="width:<?= $pct ?>%;background:<?= $pctColor ?>;height:8px;"></div>
              </div>
              <span style="font-size:12px;font-weight:700;color:<?= $pctColor ?>;min-width:30px;"><?= $pct ?>%</span>
            </div>
          </td>
          <td><span class="status-badge <?= $b['status']==='active'?'active':'completed' ?>"><?= $b['status']==='active'?($isRTL?'نشط':'Active'):($isRTL?'مكتمل':'Completed') ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Expenses Table -->
<div class="custom-card">
  <div class="card-header-custom">
    <h5><i class="bi bi-receipt me-2 text-primary"></i><?= $isRTL?'سجل المصروفات':'Expense Records' ?></h5>
    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addExpenseModal"><i class="bi bi-plus me-1"></i><?= $isRTL?'إضافة مصروف':'Add Expense' ?></button>
  </div>
  <div class="table-responsive">
    <table class="custom-table">
      <thead>
        <tr>
          <th>#</th>
          <th><?= $isRTL?'الوصف':'Description' ?></th>
          <th><?= $isRTL?'المشروع':'Project' ?></th>
          <th><?= $isRTL?'الفئة':'Category' ?></th>
          <th><?= t('amount') ?></th>
          <th><?= $isRTL?'التاريخ':'Date' ?></th>
          <th><?= t('status') ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($expenses as $e): ?>
        <tr>
          <td style="font-size:11px;color:#94a3b8;">#<?= $e['id'] ?></td>
          <td style="font-size:13px;font-weight:600;"><?= htmlspecialchars($isRTL?$e['descAr']:$e['descEn']) ?></td>
          <td style="font-size:12px;color:#64748b;"><?= htmlspecialchars($isRTL?$e['projectAr']:$e['projectEn']) ?></td>
          <td style="font-size:12px;"><?= htmlspecialchars($isRTL?$e['categoryAr']:$e['categoryEn']) ?></td>
          <td><span class="amount-text"><?= number_format($e['amount']) ?></span></td>
          <td style="font-size:12px;color:#64748b;"><?= $e['date'] ?></td>
          <td><span class="status-badge <?= $e['status']==='approved'?'funded':'pending' ?>"><?= $e['status']==='approved'?($isRTL?'معتمد':'Approved'):($isRTL?'قيد المراجعة':'Pending') ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add Expense Modal -->
<div class="modal fade" id="addExpenseModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header"><h5 class="modal-title"><?= $isRTL?'إضافة مصروف جديد':'Add New Expense' ?></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <?php foreach ([['ar'=>'وصف المصروف','en'=>'Expense Description','type'=>'text','full'=>true],['ar'=>'المشروع','en'=>'Project','type'=>'select','full'=>false],['ar'=>'الفئة','en'=>'Category','type'=>'select','full'=>false],['ar'=>'المبلغ (ريال)','en'=>'Amount (SAR)','type'=>'number','full'=>false],['ar'=>'التاريخ','en'=>'Date','type'=>'date','full'=>false]] as $f): ?>
          <div class="col-<?= $f['full']?'12':'6' ?>">
            <label class="form-label"><?= $isRTL?$f['ar']:$f['en'] ?></label>
            <?php if($f['type']==='select'): ?><select class="form-select"><option>--</option></select><?php else: ?><input type="<?= $f['type'] ?>" class="form-control"><?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-outline-secondary" data-bs-dismiss="modal"><?= t('cancel') ?></button><button class="btn btn-primary"><?= t('save') ?></button></div>
    </div>
  </div>
</div>

<?php
$pageScript = "
new Chart(document.getElementById('budgetBarChart'),{
  type:'bar',
  data:{
    labels:".json_encode($catLabels).",
    datasets:[
      {label:'".($isRTL?'المعتمد':'Budget')."',data:".json_encode($catBudget).",backgroundColor:'#1a56db',borderRadius:4},
      {label:'".($isRTL?'المصروف':'Spent')."',data:".json_encode($catSpent).",backgroundColor:'#ef4444',borderRadius:4}
    ]
  },
  options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{labels:{font:{family:'Cairo'}}}},scales:{x:{ticks:{font:{size:10}}},y:{ticks:{font:{size:11}}}}}
});
new Chart(document.getElementById('spendPie'),{
  type:'doughnut',
  data:{
    labels:".json_encode($catLabels).",
    datasets:[{data:".json_encode($catSpent).",backgroundColor:['#1a56db','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4'],borderWidth:2}]
  },
  options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{position:'bottom',labels:{font:{family:'Cairo',size:10}}}}}
});
";
?>
