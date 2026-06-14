<?php
/**
 * Admin authentication check
 */
require_once dirname(__DIR__, 2) . '/includes/lang.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';

requireAdmin('/login.php');
$user = authUser();
