<?php
$donorInfo = dbOne('SELECT * FROM donors WHERE user_id=?', [$user['id']]);
if (!$donorInfo) {
    $donorInfo = ['name_ar'=>'أرامكو السعودية','name_en'=>'Saudi Aramco','type'=>'industrial','total_donated'=>18500000,'active_grants'=>4,'country'=>'السعودية'];
}

$myGrants = dbAll('SELECT * FROM grants WHERE donor_id=(SELECT id FROM donors WHERE user_id=? LIMIT 1) ORDER BY created_at DESC', [$user['id']]);
if (!$myGrants) {
    $myGrants = [
        ['title_ar'=>'منحة أبحاث الذكاء الاصطناعي','title_en'=>'AI Research Grant','amount'=>750000,'status'=>'active','submission_date'=>'2024-01-15','start_date'=>'2024-03-01','end_date'=>'2025-03-01'],
        ['title_ar'=>'منحة التحول الرقمي','title_en'=>'Digital Transformation Grant','amount'=>980000,'status'=>'approved','submission_date'=>'2024-03-10','start_date'=>'2024-06-01','end_date'=>'2026-06-01'],
        ['title_ar'=>'منحة تطوير المختبرات','title_en'=>'Laboratory Development Grant','amount'=>430000,'status'=>'approved','submission_date'=>'2024-03-25','start_date'=>'2024-07-01','end_date'=>'2025-07-01'],
        ['title_ar'=>'منحة ريادة الأعمال','title_en'=>'Tech Entrepreneurship Grant','amount'=>290000,'status'=>'active','submission_date'=>'2024-02-14','start_date'=>'2024-04-15','end_date'=>'2024-10-15'],
    ];
}

$statusMap = [
    'active'   =>['ar'=>'نشط',        'en'=>'Active',    'class'=>'badge-success'],
    'approved' =>['ar'=>'موافق',       'en'=>'Approved',  'class'=>'badge-info'],
    'pending'  =>['ar'=>'معلق',        'en'=>'Pending',   'class'=>'badge-secondary'],
    'closed'   =>['ar'=>'مغلق',        'en'=>'Closed',    'class'=>'badge-secondary'],
    'rejected' =>['ar'=>'مرفوض',       'en'=>'Rejected',  'class'=>'badge-danger'],
];

$totalDonated  = array_sum(array_column($myGrants,'amount'));
$activeCount   = count(array_filter($myGrants, fn($g)=>$g['status']==='active'));
?>
<!-- Donor hero -->
<div class="donor-hero">
  <div class="d-flex align-items-center gap-4 flex-wrap">
    <div style="width:64px;height:64px;border-radius:18px;background:linear-gradient(135deg,#f59e0b,#ea580c);display:flex;align-items:center;justify-content:center;font-size:28px;color:#fff">
      <i class="bi bi-bank2"></i>
    </div>
    <div>
      <div style="font-size:1.4rem;font-weight:800;color:var(--text-primary)"><?= htmlspecialchars($isRTL?$donorInfo['name_ar']:$donorInfo['name_en']) ?></div>
      <div style="color:var(--text-muted);font-size:.875rem">
        <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($donorInfo['country']??'') ?>
      </div>
    </div>
  </div>
</div>

<!-- Impact stats -->
<div class="row g-3 mb-4">
  <?php
  $stats = [
    ['#f59e0b','bi-cash-stack',  number_format($totalDonated).' '.($isRTL?'ريال':'SAR'), $isRTL?'إجمالي التمويل':'Total Funding'],
    ['#10b981','bi-award-fill',  count($myGrants),                                        $isRTL?'إجمالي المنح':'Total Grants'],
    ['#6366f1','bi-folder2-open',$activeCount,                                             $isRTL?'منح نشطة':'Active Grants'],
    ['#ef4444','bi-people-fill', count($myGrants)*3,                                       $isRTL?'مستفيد':'Beneficiaries'],
  ];
  foreach($stats as $s): ?>
  <div class="col-sm-6 col-xl-3">
    <div class="impact-card">
      <div style="width:48px;height:48px;border-radius:14px;background:<?=$s[0]?>22;color:<?=$s[0]?>;display:flex;align-items:center;justify-content:center;font-size:20px;margin:0 auto 12px">
        <i class="bi <?=$s[1]?>"></i>
      </div>
      <div style="font-size:1.3rem;font-weight:800;color:var(--text-primary)"><?=$s[2]?></div>
      <div style="font-size:12px;color:var(--text-muted);margin-top:4px"><?=$s[3]?></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- My Grants detail -->
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-award-fill" style="color:#f59e0b"></i>
      <?= $isRTL?'المنح الممولة':'Funded Grants' ?>
    </h5>
  </div>
  <div class="card-custom-body">
    <?php foreach($myGrants as $g):
      $s = $statusMap[$g['status']] ?? ['ar'=>$g['status'],'en'=>$g['status'],'class'=>'badge-secondary'];
      $start = strtotime($g['start_date']??date('Y-m-d'));
      $end   = strtotime($g['end_date']??date('Y-m-d',time()+86400*365));
      $now   = time();
      $pct   = ($end > $start) ? min(100,max(0,round(($now-$start)/($end-$start)*100))) : 50;
    ?>
    <div class="grant-row">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <div style="font-weight:700;color:var(--text-primary)"><?= htmlspecialchars($isRTL?$g['title_ar']:$g['title_en']) ?></div>
          <div style="font-size:12px;color:var(--text-muted);margin-top:3px">
            <i class="bi bi-calendar me-1"></i><?= $g['start_date']??'—' ?> → <?= $g['end_date']??'—' ?>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2">
          <span style="font-weight:700;color:#f59e0b"><?= number_format($g['amount']) ?> <?= $isRTL?'ريال':'SAR' ?></span>
          <span class="badge-status <?= $s['class'] ?>"><?= $isRTL?$s['ar']:$s['en'] ?></span>
        </div>
      </div>
      <?php if ($g['start_date']): ?>
      <div class="d-flex align-items-center gap-2 mt-2">
        <div class="prog flex-1" style="flex:1">
          <div class="prog-fill" style="width:<?=$pct?>%"></div>
        </div>
        <span style="font-size:11px;color:var(--text-muted);white-space:nowrap"><?=$pct?>% <?= $isRTL?'مكتمل':'complete' ?></span>
      </div>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Chart -->
<div class="card-custom mt-4">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-bar-chart-line-fill" style="color:#6366f1"></i>
      <?= $isRTL?'توزيع التمويل السنوي':'Annual Funding Distribution' ?>
    </h5>
  </div>
  <div class="card-custom-body" style="padding:16px">
    <canvas id="donorChart" height="200"></canvas>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if(typeof Chart === 'undefined') return;
    Chart.defaults.color='#64748b';
    Chart.defaults.borderColor='rgba(255,255,255,0.06)';
    Chart.defaults.font.family="'Cairo',sans-serif";
    new Chart(document.getElementById('donorChart'),{
      type:'bar',
      data:{
        labels:['2020','2021','2022','2023','2024'],
        datasets:[{
          label:'<?= $isRTL?"التمويل (مليون ريال)":"Funding (M SAR)" ?>',
          data:[2.5,4.1,5.8,7.2,8.5],
          backgroundColor:'rgba(245,158,11,0.7)',borderColor:'#f59e0b',borderWidth:1,borderRadius:6
        }]
      },
      options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'rgba(255,255,255,0.05)'}}}}
    });
});
</script>
