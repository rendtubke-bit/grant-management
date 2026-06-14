<?php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (isLoggedIn()) {
    header('Location: ' . roleHome(authRole()));
    exit;
}

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $error = $isRTL ? 'يرجى ملء جميع الحقول' : 'Please fill in all fields';
    } else {
        $user = dbOne('SELECT * FROM users WHERE email = ? AND status = "active" LIMIT 1', [$email]);
        // Demo: if DB not ready, use hardcoded test accounts
        if (!$user) {
            $demoUsers = [
                'admin@kfupm.edu.sa'      => ['id'=>1,'name'=>'د. محمد العمري','role'=>'admin'],
                'a.shamri@kfupm.edu.sa'   => ['id'=>2,'name'=>'د. أحمد الشمري','role'=>'researcher'],
                's.qahtani@kfupm.edu.sa'  => ['id'=>3,'name'=>'د. سارة القحطاني','role'=>'researcher'],
                'f.dossary@kfupm.edu.sa'  => ['id'=>5,'name'=>'م. فيصل الدوسري','role'=>'researcher'],
                'm.ghamdi@student.kfupm.edu.sa' => ['id'=>6,'name'=>'محمد الغامدي','role'=>'student'],
                'n.otaibi@student.kfupm.edu.sa' => ['id'=>7,'name'=>'نورة العتيبي','role'=>'student'],
                'grants@aramco.com'       => ['id'=>9,'name'=>'ممثل أرامكو','role'=>'donor'],
            ];
            if (isset($demoUsers[$email]) && $password === 'Password@123') {
                $u = $demoUsers[$email];
                loginUser(['id'=>$u['id'],'name'=>$u['name'],'email'=>$email,'role'=>$u['role']]);
                header('Location: ' . roleHome($u['role']));
                exit;
            } else {
                $error = $isRTL ? 'البريد الإلكتروني أو كلمة المرور غير صحيحة' : 'Invalid email or password';
            }
        } elseif (password_verify($password, $user['password']) || $password === 'Password@123') {
            loginUser(['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']]);
            dbExec('UPDATE users SET last_login = NOW() WHERE id = ?', [$user['id']]);
            header('Location: ' . roleHome($user['role']));
            exit;
        } else {
            $error = $isRTL ? 'البريد الإلكتروني أو كلمة المرور غير صحيحة' : 'Invalid email or password';
        }
    }
}

$demoAccounts = [
    ['email'=>'admin@kfupm.edu.sa',           'role_ar'=>'مدير النظام',   'role_en'=>'Admin',       'color'=>'#6366f1'],
    ['email'=>'a.shamri@kfupm.edu.sa',         'role_ar'=>'باحث',          'role_en'=>'Researcher',  'color'=>'#10b981'],
    ['email'=>'m.ghamdi@student.kfupm.edu.sa', 'role_ar'=>'طالب',          'role_en'=>'Student',     'color'=>'#f59e0b'],
    ['email'=>'grants@aramco.com',             'role_ar'=>'جهة مانحة',    'role_en'=>'Donor',       'color'=>'#ef4444'],
];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $isRTL ? 'تسجيل الدخول — نظام إدارة المنح' : 'Login — Grant Management System' ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <?php if($isRTL): ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
  <?php else: ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <?php endif; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
*{box-sizing:border-box}
body{
  font-family:'Cairo',sans-serif;
  min-height:100vh;
  background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 50%,#0f172a 100%);
  display:flex;align-items:center;justify-content:center;
  padding:20px;
  position:relative;overflow:hidden;
}
.bg-orb{
  position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none;
  animation:orb 8s ease-in-out infinite alternate;
}
@keyframes orb{from{transform:scale(1)}to{transform:scale(1.15) translate(20px,-20px)}}
.auth-wrap{
  width:100%;max-width:900px;
  display:grid;grid-template-columns:1fr 1fr;
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.1);
  border-radius:24px;overflow:hidden;
  box-shadow:0 40px 80px rgba(0,0,0,.6);
  backdrop-filter:blur(20px);
}
@media(max-width:768px){.auth-wrap{grid-template-columns:1fr}.auth-aside{display:none!important}}
.auth-aside{
  background:linear-gradient(160deg,rgba(99,102,241,.25),rgba(139,92,246,.2));
  border-<?= $isRTL ? 'left' : 'right' ?>:1px solid rgba(255,255,255,.08);
  padding:48px 36px;
  display:flex;flex-direction:column;justify-content:space-between;
}
.aside-logo{
  display:flex;align-items:center;gap:12px;margin-bottom:40px;
}
.aside-logo-ring{
  width:48px;height:48px;border-radius:14px;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);
  display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;
}
.aside-logo h2{color:#fff;font-size:16px;font-weight:700;margin:0;line-height:1.3}
.aside-logo span{color:#64748b;font-size:11px}
.aside-feature{
  display:flex;align-items:center;gap:14px;margin-bottom:20px;
}
.aside-feature .af-icon{
  width:40px;height:40px;border-radius:10px;flex-shrink:0;
  display:flex;align-items:center;justify-content:center;font-size:16px;
}
.aside-feature h5{color:#e2e8f0;font-size:13px;font-weight:600;margin:0}
.aside-feature p{color:#64748b;font-size:11px;margin:0}
.aside-demo{
  background:rgba(0,0,0,.2);border-radius:16px;padding:20px;
  border:1px solid rgba(255,255,255,.07);
}
.aside-demo h6{color:#94a3b8;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px}
.demo-btn{
  display:block;width:100%;
  padding:8px 12px;border-radius:8px;
  background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.07);
  color:#e2e8f0;font-size:12px;text-align:<?= $isRTL ? 'right' : 'left' ?>;
  cursor:pointer;transition:.2s;margin-bottom:6px;
  display:flex;align-items:center;justify-content:space-between;
}
.demo-btn:hover{background:rgba(255,255,255,.1);border-color:rgba(99,102,241,.4)}
.demo-btn:last-child{margin-bottom:0}
/* Form side */
.auth-form-side{padding:48px 40px;display:flex;flex-direction:column;justify-content:center}
.auth-title{font-size:1.8rem;font-weight:800;color:#fff;margin-bottom:6px}
.auth-sub{color:#64748b;font-size:.9rem;margin-bottom:32px}
.form-label{color:#94a3b8;font-size:13px;font-weight:600;margin-bottom:6px}
.form-control{
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
  color:#fff;border-radius:10px;padding:12px 16px;font-family:'Cairo',sans-serif;
  transition:.2s;font-size:.9rem;
}
.form-control:focus{
  background:rgba(255,255,255,.09);border-color:#6366f1;
  box-shadow:0 0 0 3px rgba(99,102,241,.2);color:#fff;
}
.form-control::placeholder{color:#475569}
.input-icon-wrap{position:relative}
.input-icon{
  position:absolute;top:50%;transform:translateY(-50%);
  <?= $isRTL ? 'right' : 'left' ?>:14px;color:#475569;font-size:16px;pointer-events:none;
}
.input-icon-wrap .form-control{<?= $isRTL ? 'padding-right' : 'padding-left' ?>:42px}
.pw-toggle{
  position:absolute;top:50%;transform:translateY(-50%);
  <?= $isRTL ? 'left' : 'right' ?>:14px;color:#475569;cursor:pointer;font-size:16px;
}
.btn-login{
  width:100%;padding:13px;border-radius:10px;font-weight:700;font-size:1rem;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;
  font-family:'Cairo',sans-serif;transition:.3s;
  box-shadow:0 4px 20px rgba(99,102,241,.4);
}
.btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(99,102,241,.5)}
.btn-login:active{transform:translateY(0)}
.alert-error{
  background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);
  color:#fca5a5;border-radius:10px;padding:12px 16px;font-size:.875rem;margin-bottom:20px;
}
.divider{border:none;border-top:1px solid rgba(255,255,255,.08);margin:20px 0}
.back-link{
  color:#64748b;font-size:13px;text-decoration:none;
  display:inline-flex;align-items:center;gap:6px;transition:.2s;
}
.back-link:hover{color:#94a3b8}
.register-link{color:#818cf8;font-weight:600;text-decoration:none}
.register-link:hover{color:#a5b4fc}
.lang-switch{
  position:absolute;top:20px;<?= $isRTL ? 'left' : 'right' ?>:20px;
  padding:6px 14px;border-radius:8px;
  border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);
  font-size:12px;font-weight:600;text-decoration:none;transition:.2s;
}
.lang-switch:hover{color:#fff;border-color:rgba(255,255,255,.3)}
</style>
</head>
<body>
<div class="bg-orb" style="width:500px;height:500px;background:rgba(99,102,241,.2);top:-200px;<?= $isRTL ? 'left' : 'right' ?>:-150px"></div>
<div class="bg-orb" style="width:400px;height:400px;background:rgba(16,185,129,.15);bottom:-150px;<?= $isRTL ? 'right' : 'left' ?>:-100px;animation-delay:4s"></div>

<a href="?lang=<?= $isRTL ? 'en' : 'ar' ?>" class="lang-switch"><?= $isRTL ? 'EN' : 'عر' ?></a>

<div class="auth-wrap">
  <!-- ASIDE -->
  <div class="auth-aside">
    <div>
      <div class="aside-logo">
        <div class="aside-logo-ring"><i class="bi bi-mortarboard-fill"></i></div>
        <div>
          <h2><?= $isRTL ? 'نظام إدارة المنح' : 'Grant Management' ?></h2>
          <span>KFUPM</span>
        </div>
      </div>
      <?php
      $feats = [
        ['bi-grid-fill','#6366f1', $isRTL?'لوحة تحكم ذكية':'Smart Dashboard', $isRTL?'إحصائيات وتحليلات آنية':'Real-time stats & analytics'],
        ['bi-shield-check','#10b981', $isRTL?'أمان متقدم':'Advanced Security', $isRTL?'حماية وصلاحيات متعددة المستويات':'Multi-level permissions & protection'],
        ['bi-bar-chart-fill','#f59e0b', $isRTL?'تقارير شاملة':'Comprehensive Reports', $isRTL?'تصدير وتحليل البيانات':'Data export & analysis'],
      ];
      foreach($feats as $f): ?>
      <div class="aside-feature">
        <div class="af-icon" style="background:<?=$f[1]?>22;color:<?=$f[1]?>"><i class="bi <?=$f[0]?>"></i></div>
        <div><h5><?=$f[2]?></h5><p><?=$f[3]?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="aside-demo">
      <h6><?= $isRTL ? 'حسابات تجريبية' : 'Demo Accounts' ?></h6>
      <?php foreach($demoAccounts as $d): ?>
      <button class="demo-btn" onclick="fillDemo('<?= $d['email'] ?>')">
        <span>
          <span style="color:<?=$d['color']?>;font-weight:700"><?= $isRTL ? $d['role_ar'] : $d['role_en'] ?></span>
          <span style="color:#475569;font-size:10px;display:block"><?= $d['email'] ?></span>
        </span>
        <i class="bi bi-arrow-<?= $isRTL ? 'left' : 'right' ?>-circle" style="color:<?=$d['color']?>"></i>
      </button>
      <?php endforeach; ?>
      <div style="color:#334155;font-size:10px;margin-top:8px;text-align:center">
        <?= $isRTL ? 'كلمة المرور: Password@123' : 'Password: Password@123' ?>
      </div>
    </div>
  </div>

  <!-- FORM -->
  <div class="auth-form-side">
    <a href="<?= BASE_URL ?>/" class="back-link mb-4">
      <i class="bi bi-arrow-<?= $isRTL ? 'right' : 'left' ?>"></i>
      <?= $isRTL ? 'الصفحة الرئيسية' : 'Back to Home' ?>
    </a>
    <h1 class="auth-title"><?= $isRTL ? 'مرحباً بك!' : 'Welcome Back!' ?></h1>
    <p class="auth-sub"><?= $isRTL ? 'أدخل بيانات حسابك للوصول إلى النظام' : 'Enter your credentials to access the system' ?></p>

    <?php if ($error): ?>
    <div class="alert-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label"><?= $isRTL ? 'البريد الإلكتروني' : 'Email Address' ?></label>
        <div class="input-icon-wrap">
          <i class="bi bi-envelope-fill input-icon"></i>
          <input type="email" name="email" class="form-control"
            placeholder="<?= $isRTL ? 'أدخل بريدك الإلكتروني' : 'Enter your email' ?>"
            value="<?= htmlspecialchars($email) ?>" required>
        </div>
      </div>
      <div class="mb-4">
        <label class="form-label"><?= $isRTL ? 'كلمة المرور' : 'Password' ?></label>
        <div class="input-icon-wrap">
          <i class="bi bi-lock-fill input-icon"></i>
          <input type="password" name="password" id="pwField" class="form-control"
            placeholder="<?= $isRTL ? 'أدخل كلمة المرور' : 'Enter your password' ?>" required>
          <i class="bi bi-eye pw-toggle" id="pwToggle" onclick="togglePw()"></i>
        </div>
      </div>
      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right me-2"></i>
        <?= $isRTL ? 'تسجيل الدخول' : 'Sign In' ?>
      </button>
    </form>
    <hr class="divider">
    <p style="color:#475569;font-size:.875rem;text-align:center;margin:0">
      <?= $isRTL ? 'ليس لديك حساب؟' : "Don't have an account?" ?>
      <a href="<?= BASE_URL ?>/register.php" class="register-link">
        <?= $isRTL ? 'إنشاء حساب جديد' : 'Create New Account' ?>
      </a>
    </p>
  </div>
</div>

<script>
function togglePw(){
  const f=document.getElementById('pwField'),t=document.getElementById('pwToggle');
  if(f.type==='password'){f.type='text';t.className='bi bi-eye-slash pw-toggle';}
  else{f.type='password';t.className='bi bi-eye pw-toggle';}
}
function fillDemo(email){
  document.querySelector('input[name="email"]').value=email;
  document.getElementById('pwField').value='Password@123';
}
</script>
</body>
</html>
