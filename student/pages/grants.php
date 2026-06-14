<?php
$pageTitle = $isRTL ? 'المنح المتاحة للتقديم' : 'Available Grants';
$availableGrants = dbAll('SELECT * FROM grants WHERE grant_type="scholarship" AND status IN ("active","approved") LIMIT 5');
if (!$availableGrants) {
    $availableGrants = [
        ['id'=>9,'title_ar'=>'منحة الدراسات العليا المتميزة','title_en'=>'Distinguished Graduate Studies','amount'=>180000,'grant_type'=>'scholarship','end_date'=>'2027-09-01'],
        ['id'=>4,'title_ar'=>'منحة تطوير الكوادر البشرية','title_en'=>'Human Capital Development','amount'=>350000,'grant_type'=>'scholarship','end_date'=>'2025-08-01'],
    ];
}
$applyMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'apply') {
    $gid = (int)($_POST['grant_id'] ?? 0);
    $msg = trim($_POST['message'] ?? '');
    if ($gid) {
        $already = dbOne('SELECT id FROM grant_applications WHERE student_id=? AND grant_id=?', [$user['id'], $gid]);
        if ($already) {
            $applyMsg = ['type'=>'warning','text'=> $isRTL ? 'لقد تقدمت على هذه المنحة مسبقاً' : 'You already applied for this grant'];
        } else {
            dbExec('INSERT INTO grant_applications (student_id,grant_id,status,message,submitted_at) VALUES (?,?,?,?,NOW())', [$user['id'],$gid,'submitted',$msg]);
            $applyMsg = ['type'=>'success','text'=> $isRTL ? 'تم تقديم طلبك بنجاح!' : 'Application submitted successfully!'];
        }
    }
}
?>

<?php if ($applyMsg): ?>
<div class="alert mb-4" style="background:rgba(<?= $applyMsg['type']==='success'?'16,185,129':'245,158,11' ?>,.12);border:1px solid rgba(<?= $applyMsg['type']==='success'?'16,185,129':'245,158,11' ?>,.3);color:<?= $applyMsg['type']==='success'?'#6ee7b7':'#fcd34d' ?>;border-radius:12px;padding:12px 16px;font-size:.875rem">
  <i class="bi bi-<?= $applyMsg['type']==='success'?'check-circle-fill':'exclamation-circle-fill' ?> me-2"></i>
  <?= htmlspecialchars($applyMsg['text']) ?>
</div>
<?php endif; ?>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h5 style="color:var(--text-primary);font-weight:700;margin:0">
    <i class="bi bi-award-fill me-2" style="color:#f59e0b"></i>
    <?= $pageTitle ?>
  </h5>
</div>
<div class="row g-3">
  <?php foreach($availableGrants as $g): ?>
  <div class="col-md-6">
    <div class="grant-card" style="background:var(--card-bg);border:1px solid var(--border-color);border-radius:16px;padding:20px;transition:.3s;">
      <div style="font-weight:700;color:var(--text-primary);margin-bottom:8px"><?= htmlspecialchars($isRTL?$g['title_ar']:$g['title_en']) ?></div>
      <div style="color:#f59e0b;font-weight:700;font-size:1.1rem;margin-bottom:12px"><?= number_format($g['amount']) ?> <?= $isRTL?'ريال':'SAR' ?></div>
      <div style="display:flex;gap:8px;align-items:center;font-size:12px;color:var(--text-muted);margin-bottom:16px">
        <i class="bi bi-calendar2"></i>
        <?= $isRTL?'آخر موعد:':'Deadline:' ?> <?= $g['end_date']??'—' ?>
      </div>
      <button class="btn-primary-sm w-100" data-bs-toggle="modal" data-bs-target="#applyModal"
        onclick="document.getElementById('applyGrantId').value='<?= $g['id'] ?>';document.getElementById('applyGrantTitle').textContent='<?= addslashes($isRTL?$g['title_ar']:$g['title_en']) ?>'">
        <i class="bi bi-send-fill me-1"></i>
        <?= $isRTL?'تقديم طلب':'Apply Now' ?>
      </button>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<!-- Apply Modal -->
<div class="modal fade" id="applyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--bg-secondary);border:1px solid var(--border-color);border-radius:20px">
      <div class="modal-header" style="border-bottom:1px solid var(--border-color);padding:20px 24px">
        <h5 class="modal-title" style="color:var(--text-primary);font-weight:700">
          <i class="bi bi-send-fill me-2" style="color:#10b981"></i>
          <?= $isRTL?'تقديم طلب منحة':'Apply for Grant' ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="_action" value="apply">
        <input type="hidden" name="grant_id" id="applyGrantId" value="">
        <div class="modal-body" style="padding:24px">
          <p style="color:var(--text-muted);font-size:.875rem;margin-bottom:16px">
            <?= $isRTL?'تتقدم على منحة: ':'Applying for: ' ?><strong id="applyGrantTitle" style="color:var(--text-primary)"></strong>
          </p>
          <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
            <?= $isRTL?'رسالة الطلب':'Application Message' ?>
          </label>
          <textarea name="message" class="form-control" rows="4"
            style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px;font-family:'Cairo',sans-serif"
            placeholder="<?= $isRTL?'اشرح سبب تقدمك لهذه المنحة وكيف ستستفيد منها...':'Explain why you are applying for this grant...' ?>"></textarea>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--border-color);padding:16px 24px">
          <button type="button" class="btn-secondary-sm" data-bs-dismiss="modal"><?= $isRTL?'إلغاء':'Cancel' ?></button>
          <button type="submit" class="btn-primary-sm" style="background:linear-gradient(135deg,#10b981,#34d399)">
            <i class="bi bi-send-fill me-1"></i><?= $isRTL?'إرسال الطلب':'Submit Application' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
