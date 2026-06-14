<?php
$pageTitle = $isRTL ? 'طلباتي' : 'My Applications';
$myApplications = dbAll('SELECT ga.*,g.title_ar,g.title_en,g.amount FROM grant_applications ga JOIN grants g ON ga.grant_id=g.id WHERE ga.student_id=?', [$user['id']]);
if (!$myApplications) {
    $myApplications = [
        ['title_ar'=>'منحة الدراسات العليا المتميزة','title_en'=>'Distinguished Graduate Studies','amount'=>180000,'status'=>'under_review','submitted_at'=>'2024-05-05'],
    ];
}
$statusMap = [
    'draft'        =>['ar'=>'مسودة',        'en'=>'Draft',         'class'=>'badge-secondary'],
    'submitted'    =>['ar'=>'مقدّم',         'en'=>'Submitted',     'class'=>'badge-info'],
    'under_review' =>['ar'=>'قيد المراجعة', 'en'=>'Under Review',  'class'=>'badge-warning'],
    'approved'     =>['ar'=>'موافق',         'en'=>'Approved',      'class'=>'badge-success'],
    'rejected'     =>['ar'=>'مرفوض',         'en'=>'Rejected',      'class'=>'badge-danger'],
];
?>
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-file-earmark-check" style="color:#10b981"></i>
      <?= $pageTitle ?>
    </h5>
  </div>
  <?php if ($myApplications): ?>
  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
          <th><?= $isRTL?'المنحة':'Grant' ?></th>
          <th><?= $isRTL?'المبلغ':'Amount' ?></th>
          <th><?= $isRTL?'تاريخ التقديم':'Submitted' ?></th>
          <th><?= $isRTL?'الحالة':'Status' ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($myApplications as $a):
          $s = $statusMap[$a['status']] ?? ['ar'=>$a['status'],'en'=>$a['status'],'class'=>'badge-secondary'];
        ?>
        <tr>
          <td style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($isRTL?$a['title_ar']:$a['title_en']) ?></td>
          <td style="color:#f59e0b;font-weight:600"><?= number_format($a['amount']) ?> SAR</td>
          <td style="color:var(--text-muted)"><?= substr($a['submitted_at']??'—',0,10) ?></td>
          <td><span class="badge-status <?= $s['class'] ?>"><?= $isRTL?$s['ar']:$s['en'] ?></span></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php else: ?>
  <div style="text-align:center;padding:40px;color:var(--text-muted)">
    <i class="bi bi-inbox" style="font-size:2.5rem;opacity:.3"></i>
    <p class="mt-2"><?= $isRTL?'لا توجد طلبات بعد':'No applications yet' ?></p>
  </div>
  <?php endif; ?>
</div>
