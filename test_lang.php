<?php
require_once __DIR__ . '/includes/lang.php';
echo "BASE_URL: " . BASE_URL . "\n";
echo "docRoot: " . str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "\n";
echo "projRoot: " . str_replace('\\', '/', dirname(__DIR__)) . "\n";
