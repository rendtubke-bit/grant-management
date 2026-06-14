<?php
require_once __DIR__ . '/includes/auth_check.php';

$currentPage = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard','grants','projects','budget','reports','donors','researchers','students','notifications','audit','settings','users'];
if (!in_array($currentPage, $allowedPages)) $currentPage = 'dashboard';

$pageFile = __DIR__ . '/pages/' . $currentPage . '.php';

if (!file_exists($pageFile)) {
    $currentPage = 'dashboard';
    $pageFile = __DIR__ . '/pages/dashboard.php';
}

$pageTitle = t($currentPage);
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/sidebar.php';
include __DIR__ . '/includes/app_header.php';
?>
<div class="page-content">
<?php include $pageFile; ?>
</div>
<?php
include __DIR__ . '/includes/footer.php';
