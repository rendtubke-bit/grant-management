<?php
$pageTitle = $isRTL ? 'المنح الممولة' : 'Funded Grants';

$myGrants = dbAll('SELECT * FROM grants WHERE donor_id=(SELECT id FROM donors WHERE user_id=? LIMIT 1) ORDER BY created_at DESC', [$user['id']]);
if (!$myGrants) {
    $myGrants = [
        ['title_ar'=>'منحة أبحاث الذكاء الاصطناعي','title_en'=>'AI Research Grant','amount'=>750000,'status'=>'active','submission_date'=>'2024-01-15','start_date'=>'2024-03-01','end_date'=>'2025-03-01'],
        ['title_ar'=>'منحة التحول الرقمي','title_en'=>'Digital Transformation Grant','amount'=>980000,'status'=>'approved','submission_date'=>'2024-03-10','start_date'=>'2024-06-01','end_date'=>'2026-06-01'],
        ['title_ar'=>'منحة تطوير المختبرات','title_en'=>'Laboratory Development Grant','amount'=>430000,'status'=>'approved','submission_date'=>'2024-03-25','start_date'=>'2024-07-01','end_date'=>'2025-07-01'],
    ];
}

$statusMap = [
    'active'   =>['ar'=>'نشط',        'en'=>'Active',    'class'=>'badge-success'],
    'approved' =>['ar'=>'موافق',       'en'=>'Approved',  'class'=>'badge-info'],
    'pending'  =>['ar'=>'معلق',        'en'=>'Pending',   'class'=>'badge-secondary'],
    'closed'   =>['ar'=>'مغلق',        'en'=>'Closed',    'class'=>'badge-secondary'],
    'rejected' =>['ar'=>'مرفوض',       'en'=>'Rejected',  'class'=>'badge-danger'],
];
?>
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-award-fill" style="color:#f59e0b"></i>
      <?= $pageTitle ?>
    </h5>
  </div>
  <div class="card-custom-body">
    <div class="table-responsive">
      <table class="table-custom">
        <thead>
          <tr>
            <th><?= $isRTL ? 'اسم المنحة' : 'Grant Name' ?></th>
            <th><?= $isRTL ? 'المبلغ' : 'Amount' ?></th>
            <th><?= $isRTL ? 'تاريخ البداية' : 'Start Date' ?></th>
            <th><?= $isRTL ? 'تاريخ النهاية' : 'End Date' ?></th>
            <th><?= $isRTL ? 'الحالة' : 'Status' ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($myGrants as $g): 
             $s = $statusMap[$g['status']] ?? ['ar'=>$g['status'],'en'=>$g['status'],'class'=>'badge-secondary'];
          ?>
          <tr>
            <td style="font-weight:700;color:var(--text-primary)"><?= htmlspecialchars($isRTL ? $g['title_ar'] : $g['title_en']) ?></td>
            <td style="color:#f59e0b;font-weight:700"><?= number_format($g['amount']) ?> <?= $isRTL ? 'ريال' : 'SAR' ?></td>
            <td style="color:var(--text-muted)"><?= $g['start_date'] ?? '—' ?></td>
            <td style="color:var(--text-muted)"><?= $g['end_date'] ?? '—' ?></td>
            <td><span class="badge-status <?= $s['class'] ?>"><?= $isRTL ? $s['ar'] : $s['en'] ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
