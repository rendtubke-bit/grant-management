<?php
require_once __DIR__ . '/includes/auth.php';
logout();
// Use relative redirect to work regardless of BASE_URL/virtual host path
header('Location: ./login.php');
exit;
