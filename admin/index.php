<?php
require_once __DIR__ . '/includes/auth_check.php';

$currentPage = $_GET['page'] ?? 'dashboard';
$allowedPages = ['dashboard','grants','projects','budget','reports','donors','researchers','students','notifications','audit','settings','users'];
if (!in_array($currentPage, $allowedPages)) $currentPage = 'dashboard';

$pageFile = __DIR__ . '/pages/' . $currentPage . '.php';

// If specific page file doesn't exist, fall back to legacy pages folder
$isLegacy = false;
if (!file_exists($pageFile)) {
    $legacyFile = dirname(__DIR__) . '/pages/' . $currentPage . '.php';
    if (file_exists($legacyFile)) {
        $pageFile = $legacyFile;
        $isLegacy = true;
    } else {
        $currentPage = 'dashboard';
        $pageFile = __DIR__ . '/pages/dashboard.php';
    }
}

if ($isLegacy) {
    $pageTitleKey = $currentPage;
    // Buffer output so any PHP logic in legacy pages runs before header outputs,
    // though usually legacy pages just output HTML directly.
    // Actually, including header first is safer to wrap the HTML.
    include __DIR__ . '/includes/header.php';
    include __DIR__ . '/includes/sidebar.php';
    include __DIR__ . '/includes/app_header.php';
    
    include $pageFile;
    
    include __DIR__ . '/includes/footer.php';
} else {
    include $pageFile;
}
