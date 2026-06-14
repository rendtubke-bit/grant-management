<?php
$pageTitle = $isRTL ? 'الإشعارات' : 'Notifications';
$notifications = dbAll('SELECT * FROM notifications WHERE user_id=? ORDER BY created_at DESC LIMIT 10', [$user['id']]);
?>
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-bell-fill" style="color:#f59e0b"></i>
      <?= $pageTitle ?>
    </h5>
    <form method="POST" action="">
      <button type="submit" name="mark_all_read" class="btn-sm-outline">
        <i class="bi bi-check2-all"></i> <?= $isRTL ? 'تحديد الكل كمقروء' : 'Mark all as read' ?>
      </button>
    </form>
  </div>
  <div class="card-custom-body" style="padding: 0;">
    <?php if (!$notifications): ?>
    <div style="padding: 30px; text-align: center; color: var(--text-muted);">
      <i class="bi bi-bell-slash" style="font-size: 2rem; margin-bottom: 10px;"></i>
      <p><?= $isRTL ? 'لا توجد إشعارات جديدة.' : 'No new notifications.' ?></p>
    </div>
    <?php else: ?>
      <?php foreach($notifications as $n): ?>
      <div style="padding: 15px 20px; border-bottom: 1px solid var(--border-color); background: <?= $n['is_read'] ? 'transparent' : 'rgba(99,102,241,.05)' ?>;">
        <div class="d-flex align-items-center gap-3">
          <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(99,102,241,.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 18px;">
            <i class="bi bi-info-circle-fill"></i>
          </div>
          <div>
            <div style="font-weight: 600; color: var(--text-primary);"><?= htmlspecialchars($isRTL ? $n['title_ar'] : $n['title_en']) ?></div>
            <div style="font-size: 13px; color: var(--text-secondary); margin-top: 3px;"><?= htmlspecialchars($isRTL ? $n['message_ar'] : $n['message_en']) ?></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 5px;"><i class="bi bi-clock me-1"></i><?= $n['created_at'] ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
