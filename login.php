<?php
/**
 * Login page for Grant Management System
 */
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

$error = '';
$success = '';

// If already logged in, redirect
$user = authUser();
if ($user) {
    switch ($user['role']) {
        case 'admin': header('Location: ' . BASE_URL . '/admin/'); exit;
        case 'donor': header('Location: ' . BASE_URL . '/donor/'); exit;
        case 'researcher': header('Location: ' . BASE_URL . '/researcher/'); exit;
        case 'student': header('Location: ' . BASE_URL . '/student/'); exit;
        default: header('Location: ' . BASE_URL . '/admin/'); exit;
    }
}

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = $isRTL ? 'يرجى إدخال البريد الإلكتروني وكلمة المرور' : 'Please enter email and password';
    } else {
        if (login($email, $password)) {
            $user = authUser();
            switch ($user['role']) {
                case 'admin': header('Location: ' . BASE_URL . '/admin/'); exit;
                case 'donor': header('Location: ' . BASE_URL . '/donor/'); exit;
                case 'researcher': header('Location: ' . BASE_URL . '/researcher/'); exit;
                case 'student': header('Location: ' . BASE_URL . '/student/'); exit;
                default: header('Location: ' . BASE_URL . '/admin/'); exit;
            }
        } else {
            $error = $isRTL ? 'بريد إلكتروني أو كلمة مرور غير صحيحة' : 'Invalid email or password';
        }
    }
}

$pageTitle = $isRTL ? 'تسجيل الدخول' : 'Login';
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $pageTitle ?> — <?= t('appName') ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <?php if($isRTL): ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
  <?php else: ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?php endif; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    * { font-family: 'Cairo', sans-serif !important; box-sizing: border-box; }
    body {
      background: #f0f2f5;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      margin: 0;
    }
    .login-container {
      width: 100%;
      max-width: 420px;
    }
    .login-card {
      background: #fff;
      border: 3px solid #000;
      border-radius: 20px;
      padding: 36px 32px 32px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.15), 6px 6px 0 #000;
    }
    .login-logo {
      text-align: center;
      margin-bottom: 28px;
    }
    .login-logo .logo-icon {
      width: 64px;
      height: 64px;
      background: linear-gradient(135deg, #6366f1, #8b5cf6);
      border-radius: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      color: #fff;
      box-shadow: 0 4px 12px rgba(99,102,241,0.4);
      border: 3px solid #000;
    }
    .login-logo h2 {
      font-size: 20px;
      font-weight: 900;
      color: #1e293b;
      margin-top: 16px;
      margin-bottom: 4px;
    }
    .login-logo p {
      font-size: 13px;
      color: #64748b;
      margin: 0;
    }
    .form-control {
      border-radius: 10px;
      border: 2px solid #000;
      font-size: 14px;
      padding: 11px 14px;
      transition: all 0.2s;
    }
    .form-control:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
      outline: none;
    }
    .form-label {
      font-size: 13px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
    }
    .btn-login {
      width: 100%;
      padding: 12px;
      background: #6366f1;
      color: #fff;
      border: 3px solid #000;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.2s;
      box-shadow: 0 4px 0 #000;
    }
    .btn-login:hover {
      background: #4f46e5;
      transform: translateY(-2px);
      box-shadow: 0 6px 0 #000;
    }
    .btn-login:active {
      transform: translateY(2px);
      box-shadow: 0 1px 0 #000;
    }
    .error-msg {
      background: #fef2f2;
      border: 2px solid #ef4444;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 13px;
      font-weight: 600;
      color: #dc2626;
      margin-bottom: 16px;
      text-align: center;
    }
    .demo-info {
      margin-top: 20px;
      padding: 14px;
      background: #f8fafc;
      border: 2px solid #000;
      border-radius: 12px;
      font-size: 12px;
      color: #475569;
    }
    .demo-info strong {
      color: #1e293b;
    }
    .demo-info code {
      background: #e2e8f0;
      padding: 1px 6px;
      border-radius: 4px;
      font-size: 11px;
    }
    .lang-switch {
      text-align: center;
      margin-top: 16px;
    }
    .lang-switch a {
      font-size: 13px;
      font-weight: 700;
      color: #6366f1;
      text-decoration: none;
    }
    .lang-switch a:hover { text-decoration: underline; }
    .input-group-icon {
      position: relative;
    }
    .input-group-icon i {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      <?= $isRTL ? 'right' : 'left' ?>: 14px;
      color: #94a3b8;
      font-size: 16px;
    }
    .input-group-icon input {
      <?= $isRTL ? 'padding-right: 42px;' : 'padding-left: 42px;' ?>
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-logo">
        <div class="logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <h2><?= t('appName') ?></h2>
        <p><?= $isRTL ? 'جامعة الملك فهد للبترول والمعادن' : 'King Fahd University of Petroleum & Minerals' ?></p>
      </div>

      <?php if ($error): ?>
        <div class="error-msg">
          <i class="bi bi-exclamation-triangle-fill me-1"></i><?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label"><?= $isRTL ? 'البريد الإلكتروني' : 'Email Address' ?></label>
          <div class="input-group-icon">
            <i class="bi bi-envelope-fill"></i>
            <input type="email" name="email" class="form-control" placeholder="<?= $isRTL ? 'admin@kfupm.edu.sa' : 'admin@kfupm.edu.sa' ?>" required autocomplete="email">
          </div>
        </div>
        <div class="mb-4">
          <label class="form-label"><?= $isRTL ? 'كلمة المرور' : 'Password' ?></label>
          <div class="input-group-icon">
            <i class="bi bi-lock-fill"></i>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required autocomplete="current-password">
          </div>
        </div>
        <button type="submit" class="btn-login">
          <i class="bi bi-box-arrow-in-<?= $isRTL ? 'left' : 'right' ?> me-2"></i>
          <?= $isRTL ? 'تسجيل الدخول' : 'Sign In' ?>
        </button>
      </form>

      <div class="demo-info">
        <strong><?= $isRTL ? 'بيانات تجريبية' : 'Demo Credentials' ?></strong><br>
        <code>admin@kfupm.edu.sa</code> — <?= $isRTL ? 'مدير النظام' : 'Admin' ?><br>
        <code>a.shamri@kfupm.edu.sa</code> — <?= $isRTL ? 'باحث' : 'Researcher' ?><br>
        <code>m.ghamdi@student.kfupm.edu.sa</code> — <?= $isRTL ? 'طالب' : 'Student' ?><br>
        <code>grants@aramco.com</code> — <?= $isRTL ? 'جهة مانحة' : 'Donor' ?><br>
        <small><?= $isRTL ? 'كلمة المرور للجميع: Password@123' : 'Password for all: Password@123' ?></small>
      </div>

      <div class="lang-switch">
        <a href="?lang=<?= $isRTL ? 'en' : 'ar' ?>">
          <i class="bi bi-globe me-1"></i><?= $isRTL ? 'English' : 'العربية' ?>
        </a>
      </div>
    </div>
  </div>
</body>
</html>
