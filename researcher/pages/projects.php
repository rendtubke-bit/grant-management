<?php
$pageTitle = $isRTL ? 'مشاريعي البحثية' : 'My Research Projects';
$myProjects = dbAll('SELECT p.*,g.title_ar as grant_ar,g.title_en as grant_en FROM projects p LEFT JOIN grants g ON p.grant_id=g.id WHERE p.lead_researcher_id=?', [$user['id']]);
if (!$myProjects) {
    $myProjects = [
        ['title_ar'=>'نظام ذكاء اصطناعي لتشخيص أمراض النخيل','title_en'=>'AI Palm Disease Diagnosis','status'=>'active','progress'=>72,'budget_total'=>750000,'budget_spent'=>540000,'start_date'=>'2024-03-01','end_date'=>'2025-03-01','grant_ar'=>'منحة الذكاء الاصطناعي','grant_en'=>'AI Research Grant'],
        ['title_ar'=>'منصة التحول الرقمي للمنشآت الصناعية','title_en'=>'Digital Transformation Platform','status'=>'planning','progress'=>15,'budget_total'=>980000,'budget_spent'=>147000,'start_date'=>'2024-06-01','end_date'=>'2026-06-01','grant_ar'=>'منحة التحول الرقمي','grant_en'=>'Digital Transformation Grant'],
    ];
}
$statusMap = [
    'active'       =>['ar'=>'نشط',         'en'=>'Active',        'class'=>'badge-success'],
    'planning'     =>['ar'=>'تخطيط',        'en'=>'Planning',      'class'=>'badge-secondary'],
    'completed'    =>['ar'=>'مكتمل',        'en'=>'Completed',     'class'=>'badge-info'],
];
?>
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-journal-code" style="color:#6366f1"></i>
      <?= $pageTitle ?>
    </h5>
  </div>
  <div class="card-custom-body">
    <div class="row g-3">
      <?php foreach($myProjects as $p):
        $s = $statusMap[$p['status']] ?? ['ar'=>$p['status'],'en'=>$p['status'],'class'=>'badge-secondary'];
        $pct = (int)$p['progress'];
        $barColor = $pct > 70 ? '#10b981' : ($pct > 40 ? '#6366f1' : '#f59e0b');
      ?>
      <div class="col-md-6">
        <div class="project-progress-card">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div style="font-weight:700;color:var(--text-primary);font-size:.95rem;flex:1">
              <?= htmlspecialchars($isRTL ? $p['title_ar'] : $p['title_en']) ?>
            </div>
            <span class="badge-status <?= $s['class'] ?> ms-2 flex-shrink-0"><?= $isRTL ? $s['ar'] : $s['en'] ?></span>
          </div>
          <div style="font-size:12px;color:var(--text-muted);margin-bottom:12px">
            <i class="bi bi-award-fill me-1" style="color:#f59e0b"></i>
            <?= htmlspecialchars($isRTL ? ($p['grant_ar']??'') : ($p['grant_en']??'')) ?>
          </div>
          <div class="d-flex justify-content-between mb-1" style="font-size:12px">
            <span style="color:var(--text-muted)"><?= $isRTL?'التقدم':'Progress' ?></span>
            <span style="color:<?=$barColor?>;font-weight:700"><?=$pct?>%</span>
          </div>
          <div class="prog-bar-wrap">
            <div class="prog-bar-fill" style="width:<?=$pct?>%;background:linear-gradient(90deg,<?=$barColor?>,<?=$barColor?>dd)"></div>
          </div>
          <div class="row g-2 mt-1">
            <div class="col-6" style="font-size:11px;color:var(--text-muted)">
              <i class="bi bi-cash me-1"></i><?= number_format($p['budget_spent']) ?>/<?= number_format($p['budget_total']) ?> <?= $isRTL?'ريال':'SAR' ?>
            </div>
            <div class="col-6 text-end" style="font-size:11px;color:var(--text-muted)">
              <i class="bi bi-calendar me-1"></i><?= $p['end_date'] ?? '—' ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
