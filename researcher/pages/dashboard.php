<?php
// Data
$myProjects = dbAll('SELECT p.*,g.title_ar as grant_ar,g.title_en as grant_en FROM projects p LEFT JOIN grants g ON p.grant_id=g.id WHERE p.lead_researcher_id=?', [$user['id']]);
if (!$myProjects) {
    $myProjects = [
        ['title_ar'=>'نظام ذكاء اصطناعي لتشخيص أمراض النخيل','title_en'=>'AI Palm Disease Diagnosis','status'=>'active','progress'=>72,'budget_total'=>750000,'budget_spent'=>540000,'start_date'=>'2024-03-01','end_date'=>'2025-03-01','grant_ar'=>'منحة الذكاء الاصطناعي','grant_en'=>'AI Research Grant'],
        ['title_ar'=>'منصة التحول الرقمي للمنشآت الصناعية','title_en'=>'Digital Transformation Platform','status'=>'planning','progress'=>15,'budget_total'=>980000,'budget_spent'=>147000,'start_date'=>'2024-06-01','end_date'=>'2026-06-01','grant_ar'=>'منحة التحول الرقمي','grant_en'=>'Digital Transformation Grant'],
    ];
}

$myGrants = dbAll('SELECT * FROM grants WHERE applicant_id=? ORDER BY created_at DESC LIMIT 5', [$user['id']]);
if (!$myGrants) {
    $myGrants = [
        ['title_ar'=>'منحة أبحاث الذكاء الاصطناعي','title_en'=>'AI Research Grant','amount'=>750000,'status'=>'active','submission_date'=>'2024-01-15'],
        ['title_ar'=>'منحة النانو تكنولوجي','title_en'=>'Nanotechnology Grant','amount'=>1200000,'status'=>'under_review','submission_date'=>'2024-04-18'],
    ];
}

$resInfo = dbOne('SELECT * FROM researchers WHERE user_id=?', [$user['id']]);
if (!$resInfo) {
    $resInfo = ['specialization_ar'=>'الذكاء الاصطناعي وتعلم الآلة','specialization_en'=>'AI & Machine Learning','rank_ar'=>'أستاذ مشارك','rank_en'=>'Associate Professor','h_index'=>18,'publications'=>42,'projects_count'=>5,'department'=>'هندسة الحاسب والمعلومات'];
}

$statusMap = [
    'active'       =>['ar'=>'نشط',         'en'=>'Active',        'class'=>'badge-success'],
    'approved'     =>['ar'=>'موافق',        'en'=>'Approved',      'class'=>'badge-info'],
    'under_review' =>['ar'=>'قيد المراجعة','en'=>'Under Review',  'class'=>'badge-warning'],
    'pending'      =>['ar'=>'معلق',         'en'=>'Pending',       'class'=>'badge-secondary'],
    'planning'     =>['ar'=>'تخطيط',        'en'=>'Planning',      'class'=>'badge-secondary'],
    'completed'    =>['ar'=>'مكتمل',        'en'=>'Completed',     'class'=>'badge-info'],
    'rejected'     =>['ar'=>'مرفوض',        'en'=>'Rejected',      'class'=>'badge-danger'],
];
?>
<!-- Hero banner -->
<div class="portal-hero mb-4" style="margin:0 -20px 24px;padding:28px 20px">
  <div class="d-flex align-items-center gap-4 flex-wrap">
    <div class="portal-avatar"><?= mb_substr($user['name'],0,1) ?></div>
    <div>
      <div style="font-size:1.5rem;font-weight:800;color:var(--text-primary)"><?= htmlspecialchars($user['name']) ?></div>
      <div style="color:var(--text-muted);font-size:.875rem">
        <?= $isRTL ? $resInfo['rank_ar'] : $resInfo['rank_en'] ?> —
        <?= htmlspecialchars($resInfo['department'] ?? '') ?>
      </div>
      <div style="display:flex;gap:16px;margin-top:10px;flex-wrap:wrap">
        <?php
        $pills = [
          ['bi-graph-up','#6366f1', $resInfo['h_index'], $isRTL?'H-index':'H-index'],
          ['bi-journal-bookmark-fill','#10b981', $resInfo['publications'], $isRTL?'منشور':'Publications'],
          ['bi-folder2-open','#f59e0b', $resInfo['projects_count'], $isRTL?'مشروع':'Projects'],
        ];
        foreach($pills as $pl): ?>
        <div style="display:flex;align-items:center;gap:6px;background:<?=$pl[1]?>22;padding:5px 12px;border-radius:8px">
          <i class="bi <?=$pl[0]?>" style="color:<?=$pl[1]?>"></i>
          <span style="color:<?=$pl[1]?>;font-weight:700"><?=$pl[2]?></span>
          <span style="color:var(--text-muted);font-size:12px"><?=$pl[3]?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- My Projects -->
<div class="mb-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 style="color:var(--text-primary);font-weight:700;margin:0">
      <i class="bi bi-journal-code me-2" style="color:#6366f1"></i>
      <?= $isRTL ? 'مشاريعي البحثية' : 'My Research Projects' ?>
    </h5>
    <span style="background:rgba(99,102,241,.12);color:#818cf8;padding:3px 12px;border-radius:999px;font-size:12px"><?= count($myProjects) ?></span>
  </div>
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

<!-- My Grants -->
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-file-earmark-text-fill" style="color:#f59e0b"></i>
      <?= $isRTL ? 'طلبات المنح' : 'Grant Applications' ?>
    </h5>
  </div>
  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
          <th><?= $isRTL?'العنوان':'Title' ?></th>
          <th><?= $isRTL?'المبلغ':'Amount' ?></th>
          <th><?= $isRTL?'تاريخ التقديم':'Submitted' ?></th>
          <th><?= $isRTL?'الحالة':'Status' ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($myGrants as $g):
          $s = $statusMap[$g['status']] ?? ['ar'=>$g['status'],'en'=>$g['status'],'class'=>'badge-secondary'];
        ?>
        <tr>
          <td style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($isRTL?$g['title_ar']:$g['title_en']) ?></td>
          <td style="color:#f59e0b;font-weight:600"><?= number_format($g['amount']) ?> <?= $isRTL?'ريال':'SAR' ?></td>
          <td style="color:var(--text-muted)"><?= $g['submission_date'] ?></td>
          <td><span class="badge-status <?= $s['class'] ?>"><?= $isRTL?$s['ar']:$s['en'] ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
