    </div><!-- end page-content -->
  </div><!-- end main-content -->
</div><!-- end app-layout -->

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<!-- Mobile sidebar toggle -->
<script>
function toggleSidebar() {
  const s = document.getElementById('mainSidebar');
  const o = document.getElementById('sidebarOverlay');
  s.classList.toggle('mobile-open');
  o.classList.toggle('show');
}
function closeSidebar() {
  document.getElementById('mainSidebar').classList.remove('mobile-open');
  document.getElementById('sidebarOverlay').classList.remove('show');
}
</script>

<?php if (isset($pageScript)): ?>
<script><?= $pageScript ?></script>
<?php endif; ?>

</body>
</html>
