<?php
$pageTitle = $isRTL ? 'طلبات المنح' : 'Grant Applications';
$myGrants = dbAll('SELECT * FROM grants WHERE applicant_id=? ORDER BY created_at DESC', [$user['id']]);
if (!$myGrants) {
    $myGrants = [
        ['title_ar'=>'منحة أبحاث الذكاء الاصطناعي','title_en'=>'AI Research Grant','amount'=>750000,'status'=>'active','submission_date'=>'2024-01-15'],
        ['title_ar'=>'منحة النانو تكنولوجي','title_en'=>'Nanotechnology Grant','amount'=>1200000,'status'=>'under_review','submission_date'=>'2024-04-18'],
    ];
}
$statusMap = [
    'active'       =>['ar'=>'نشط',         'en'=>'Active',        'class'=>'badge-success'],
    'approved'     =>['ar'=>'موافق',        'en'=>'Approved',      'class'=>'badge-info'],
    'under_review' =>['ar'=>'قيد المراجعة','en'=>'Under Review',  'class'=>'badge-warning'],
    'pending'      =>['ar'=>'معلق',         'en'=>'Pending',       'class'=>'badge-secondary'],
    'rejected'     =>['ar'=>'مرفوض',        'en'=>'Rejected',      'class'=>'badge-danger'],
];
?>
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-file-earmark-text-fill" style="color:#f59e0b"></i>
      <?= $pageTitle ?>
    </h5>
    <button class="btn-sm-outline">
      <i class="bi bi-plus"></i> <?= $isRTL ? 'طلب منحة جديد' : 'New Grant Request' ?>
    </button>
  </div>
  <div class="card-custom-body">
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
</div>
