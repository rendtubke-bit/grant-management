</div><!-- main-content -->
</div><!-- app-layout flex -->

<div class="sidebar-overlay" id="sidebarOverlay" onclick="document.getElementById('mainSidebar').classList.remove('open');this.classList.remove('active')"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
// Sidebar toggle for mobile
function toggleSidebar() {
  document.getElementById('mainSidebar').classList.toggle('open');
  document.getElementById('sidebarOverlay').classList.toggle('active');
}
function openSidebar() {
  document.getElementById('mainSidebar').classList.add('open');
  document.getElementById('sidebarOverlay').classList.add('active');
}
function closeSidebar() {
  document.getElementById('mainSidebar').classList.remove('open');
  document.getElementById('sidebarOverlay').classList.remove('active');
}

// Close sidebar on escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closeSidebar();
});

<?php if (isset($pageScript)): ?>
<?= $pageScript ?>
<?php endif; ?>
</script>
</body>
</html>
