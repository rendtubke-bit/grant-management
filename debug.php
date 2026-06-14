<?php
$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projRoot = str_replace('\\', '/', __DIR__);
$basePath = str_ireplace($docRoot, '', $projRoot);
$baseUrl = rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $basePath, '/');

echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "docRoot: " . $docRoot . "\n";
echo "DIR: " . __DIR__ . "\n";
echo "projRoot: " . $projRoot . "\n";
echo "basePath: " . $basePath . "\n";
echo "baseUrl: " . $baseUrl . "\n";
