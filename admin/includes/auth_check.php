<?php
// admin/includes/auth_check.php — must be included at top of every admin page
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/db.php';
require_once dirname(__DIR__, 2) . '/includes/lang.php';

requireRole('admin', '/login.php');
