<?php
$pageTitle = $isRTL ? 'التقارير والإحصائيات' : 'Reports & Analytics';
?>
<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-bar-chart-fill" style="color:#6366f1"></i>
      <?= $pageTitle ?>
    </h5>
    <button class="btn-sm-outline">
      <i class="bi bi-download"></i> <?= $isRTL ? 'تصدير PDF' : 'Export PDF' ?>
    </button>
  </div>
  <div class="card-custom-body" style="min-height: 300px; display: flex; align-items: center; justify-content: center;">
    <div style="text-align: center; color: var(--text-muted);">
      <i class="bi bi-tools" style="font-size: 3rem; margin-bottom: 15px; color: #6366f1;"></i>
      <h4><?= $isRTL ? 'قيد التطوير' : 'Under Development' ?></h4>
      <p><?= $isRTL ? 'هذه الصفحة قيد التطوير وسيتم إضافة ميزات التقارير قريباً.' : 'This page is under development and reporting features will be added soon.' ?></p>
    </div>
  </div>
</div>
