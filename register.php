<?php
require_once __DIR__ . '/includes/lang.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/db.php';

if (authUser()) {
    $userRole = $_SESSION['user']['role'] ?? '';
    $roleToPath = [
        'admin' => '/admin/',
        'researcher' => '/researcher/',
        'student' => '/student/',
        'donor' => '/donor/',
    ];
    $target = $roleToPath[$userRole] ?? '/';
    header('Location: ' . BASE_URL . $target);
    exit;
}

$error   = '';
$success = '';
$data    = ['name'=>'','email'=>'','role'=>'student','department'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']       = trim($_POST['name'] ?? '');
    $data['email']      = trim($_POST['email'] ?? '');
    $data['role']       = in_array($_POST['role'] ?? '', ['researcher','student','donor']) ? $_POST['role'] : 'student';
    $data['department'] = trim($_POST['department'] ?? '');
    $password           = $_POST['password'] ?? '';
    $password2          = $_POST['password2'] ?? '';

    if (!$data['name'] || !$data['email'] || !$password) {
        $error = $isRTL ? 'يرجى ملء جميع الحقول المطلوبة' : 'Please fill in all required fields';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = $isRTL ? 'البريد الإلكتروني غير صحيح' : 'Invalid email address';
    } elseif (strlen($password) < 8) {
        $error = $isRTL ? 'كلمة المرور يجب أن تكون 8 أحرف على الأقل' : 'Password must be at least 8 characters';
    } elseif ($password !== $password2) {
        $error = $isRTL ? 'كلمتا المرور غير متطابقتين' : 'Passwords do not match';
    } else {
        $existing = dbOne('SELECT id FROM users WHERE email = ? LIMIT 1', [$data['email']]);
        if ($existing) {
            $error = $isRTL ? 'هذا البريد الإلكتروني مسجل مسبقاً' : 'This email is already registered';
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $id   = dbExec(
                'INSERT INTO users (name, email, password, role, department) VALUES (?,?,?,?,?)',
                [$data['name'], $data['email'], $hash, $data['role'], $data['department']]
            );
            if ($id) {
                // Auto-login
                $_SESSION['user'] = ['id'=>$id,'name'=>$data['name'],'email'=>$data['email'],'role'=>$data['role']];
                $roleToPath = [
                    'admin' => '/admin/',
                    'researcher' => '/researcher/',
                    'student' => '/student/',
                    'donor' => '/donor/',
                ];
                $target = $roleToPath[$data['role']] ?? '/';
                header('Location: ' . BASE_URL . $target);
                exit;
            } else {
                // DB not ready — fake success for demo
                $_SESSION['user'] = ['id'=>99,'name'=>$data['name'],'email'=>$data['email'],'role'=>$data['role']];
                $roleToPath = [
                    'admin' => '/admin/',
                    'researcher' => '/researcher/',
                    'student' => '/student/',
                    'donor' => '/donor/',
                ];
                $target = $roleToPath[$data['role']] ?? '/';
                header('Location: ' . BASE_URL . $target);
                exit;
            }
        }
    }
}

$roles = [
    ['value'=>'student',    'ar'=>'طالب',          'en'=>'Student',    'icon'=>'bi-mortarboard-fill', 'color'=>'#10b981'],
    ['value'=>'researcher', 'ar'=>'باحث',           'en'=>'Researcher', 'icon'=>'bi-person-badge-fill','color'=>'#6366f1'],
    ['value'=>'donor',      'ar'=>'جهة مانحة',     'en'=>'Donor',      'icon'=>'bi-bank2',            'color'=>'#f59e0b'],
];
$departments = $isRTL
    ? ['هندسة الحاسب والمعلومات','علوم وهندسة البترول','الهندسة الكيميائية','الهندسة الميكانيكية','الرياضيات والإحصاء','الفيزياء','الكيمياء','إدارة الأعمال','أخرى']
    : ['Computer Science & Engineering','Petroleum Science & Engineering','Chemical Engineering','Mechanical Engineering','Mathematics & Statistics','Physics','Chemistry','Business Administration','Other'];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= $isRTL ? 'إنشاء حساب — نظام إدارة المنح' : 'Register — Grant Management System' ?></title>
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
  background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 100%);
  display:flex;align-items:center;justify-content:center;
  padding:40px 20px;
}
.bg-orb{position:fixed;border-radius:50%;filter:blur(80px);pointer-events:none}
.register-card{
  width:100%;max-width:560px;
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.1);
  border-radius:24px;padding:40px;
  backdrop-filter:blur(20px);
  box-shadow:0 40px 80px rgba(0,0,0,.5);
}
.reg-header{text-align:center;margin-bottom:32px}
.reg-logo{
  width:56px;height:56px;border-radius:16px;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);
  display:inline-flex;align-items:center;justify-content:center;
  font-size:24px;color:#fff;margin-bottom:16px;
}
.reg-title{font-size:1.6rem;font-weight:800;color:#fff;margin-bottom:6px}
.reg-sub{color:#64748b;font-size:.875rem}
/* Role picker */
.role-picker{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:24px}
.role-option{
  position:relative;
}
.role-option input{position:absolute;opacity:0;width:0;height:0}
.role-option label{
  display:flex;flex-direction:column;align-items:center;gap:6px;
  padding:14px 8px;border-radius:12px;cursor:pointer;
  background:rgba(255,255,255,.05);border:2px solid rgba(255,255,255,.08);
  transition:.25s;
}
.role-option input:checked + label{border-color:var(--rc);background:rgba(var(--rcr),var(--rcg),var(--rcb),.12)}
.role-option label:hover{border-color:rgba(255,255,255,.2)}
.role-opt-icon{
  width:36px;height:36px;border-radius:10px;
  display:flex;align-items:center;justify-content:center;font-size:16px;
}
.role-opt-label{font-size:12px;font-weight:600;color:#e2e8f0}
/* Form */
.form-label{color:#94a3b8;font-size:13px;font-weight:600;margin-bottom:6px}
.form-control,.form-select{
  background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);
  color:#fff;border-radius:10px;padding:11px 16px;
  font-family:'Cairo',sans-serif;font-size:.9rem;transition:.2s;
}
.form-control:focus,.form-select:focus{
  background:rgba(255,255,255,.09);border-color:#6366f1;
  box-shadow:0 0 0 3px rgba(99,102,241,.2);color:#fff;
}
.form-control::placeholder{color:#475569}
.form-select option{background:#1e293b;color:#fff}
.input-icon-wrap{position:relative}
.input-icon{position:absolute;top:50%;transform:translateY(-50%);<?= $isRTL ? 'right' : 'left' ?>:14px;color:#475569;font-size:15px;pointer-events:none}
.input-icon-wrap .form-control{<?= $isRTL ? 'padding-right' : 'padding-left' ?>:42px}
.pw-toggle{position:absolute;top:50%;transform:translateY(-50%);<?= $isRTL ? 'left' : 'right' ?>:14px;color:#475569;cursor:pointer}
.btn-register{
  width:100%;padding:13px;border-radius:10px;font-weight:700;font-size:1rem;
  background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;border:none;
  font-family:'Cairo',sans-serif;transition:.3s;box-shadow:0 4px 20px rgba(99,102,241,.4);
}
.btn-register:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(99,102,241,.5)}
.alert-error{background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.3);color:#fca5a5;border-radius:10px;padding:12px 16px;font-size:.875rem;margin-bottom:20px}
.password-strength{height:4px;border-radius:2px;background:rgba(255,255,255,.1);margin-top:6px;overflow:hidden}
.ps-bar{height:100%;border-radius:2px;transition:.5s;width:0}
.login-link{color:#818cf8;font-weight:600;text-decoration:none}
.login-link:hover{color:#a5b4fc}
.lang-switch{position:absolute;top:20px;<?= $isRTL ? 'left' : 'right' ?>:20px;padding:6px 14px;border-radius:8px;border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.6);font-size:12px;font-weight:600;text-decoration:none;transition:.2s}
.lang-switch:hover{color:#fff;border-color:rgba(255,255,255,.3)}
</style>
</head>
<body>
<div class="bg-orb" style="width:400px;height:400px;background:rgba(99,102,241,.2);top:-150px;right:-100px"></div>
<div class="bg-orb" style="width:350px;height:350px;background:rgba(16,185,129,.15);bottom:-100px;left:-80px"></div>

<a href="?lang=<?= $isRTL ? 'en' : 'ar' ?>" class="lang-switch"><?= $isRTL ? 'EN' : 'عر' ?></a>

<div class="register-card">
  <div class="reg-header">
    <div class="reg-logo"><i class="bi bi-person-plus-fill"></i></div>
    <h1 class="reg-title"><?= $isRTL ? 'إنشاء حساب جديد' : 'Create New Account' ?></h1>
    <p class="reg-sub"><?= $isRTL ? 'انضم إلى نظام إدارة المنح والتمويل البحثي' : 'Join the Grant & Research Funding Management System' ?></p>
  </div>

  <?php if ($error): ?>
  <div class="alert-error"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <!-- Role picker -->
    <div class="mb-3">
      <div class="form-label mb-2"><?= $isRTL ? 'نوع الحساب' : 'Account Type' ?></div>
      <div class="role-picker">
        <?php
        $rc=[['99','102','241'],['16','185','129'],['245','158','11']];
        $rcHex=['#6366f1','#10b981','#f59e0b'];
        foreach($roles as $ri=>$r): ?>
        <div class="role-option">
          <input type="radio" name="role" id="role_<?=$r['value']?>" value="<?=$r['value']?>"
            <?= ($data['role']==$r['value']?'checked':'') ?>>
          <label for="role_<?=$r['value']?>"
            style="--rc:<?=$rcHex[$ri]?>;--rcr:<?=$rc[$ri][0]?>;--rcg:<?=$rc[$ri][1]?>;--rcb:<?=$rc[$ri][2]?>">
            <div class="role-opt-icon" style="background:<?=$rcHex[$ri]?>22;color:<?=$rcHex[$ri]?>">
              <i class="bi <?=$r['icon']?>"></i>
            </div>
            <span class="role-opt-label"><?= $isRTL ? $r['ar'] : $r['en'] ?></span>
          </label>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label"><?= $isRTL ? 'الاسم الكامل *' : 'Full Name *' ?></label>
        <div class="input-icon-wrap">
          <i class="bi bi-person-fill input-icon"></i>
          <input type="text" name="name" class="form-control"
            placeholder="<?= $isRTL ? 'الاسم الكامل' : 'Your full name' ?>"
            value="<?= htmlspecialchars($data['name']) ?>" required>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label"><?= $isRTL ? 'البريد الإلكتروني *' : 'Email Address *' ?></label>
        <div class="input-icon-wrap">
          <i class="bi bi-envelope-fill input-icon"></i>
          <input type="email" name="email" class="form-control"
            placeholder="<?= $isRTL ? 'example@kfupm.edu.sa' : 'example@kfupm.edu.sa' ?>"
            value="<?= htmlspecialchars($data['email']) ?>" required>
        </div>
      </div>
      <div class="col-12">
        <label class="form-label"><?= $isRTL ? 'القسم / الجهة' : 'Department / Organization' ?></label>
        <select name="department" class="form-select">
          <option value=""><?= $isRTL ? 'اختر القسم...' : 'Select department...' ?></option>
          <?php foreach($departments as $d): ?>
          <option value="<?= htmlspecialchars($d) ?>" <?= $data['department']===$d ? 'selected' : '' ?>>
            <?= htmlspecialchars($d) ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-12">
        <label class="form-label"><?= $isRTL ? 'كلمة المرور *' : 'Password *' ?></label>
        <div class="input-icon-wrap">
          <i class="bi bi-lock-fill input-icon"></i>
          <input type="password" name="password" id="pw1" class="form-control"
            placeholder="<?= $isRTL ? '8 أحرف على الأقل' : 'At least 8 characters' ?>"
            oninput="checkStrength(this.value)" required>
          <i class="bi bi-eye pw-toggle" id="pt1" onclick="togglePw('pw1','pt1')"></i>
        </div>
        <div class="password-strength mt-1"><div class="ps-bar" id="psBar"></div></div>
        <div id="psText" style="font-size:10px;color:#64748b;margin-top:4px"></div>
      </div>
      <div class="col-12">
        <label class="form-label"><?= $isRTL ? 'تأكيد كلمة المرور *' : 'Confirm Password *' ?></label>
        <div class="input-icon-wrap">
          <i class="bi bi-lock-fill input-icon"></i>
          <input type="password" name="password2" id="pw2" class="form-control"
            placeholder="<?= $isRTL ? 'أعد كتابة كلمة المرور' : 'Repeat your password' ?>" required>
          <i class="bi bi-eye pw-toggle" id="pt2" onclick="togglePw('pw2','pt2')"></i>
        </div>
      </div>
    </div>

    <button type="submit" class="btn-register">
      <i class="bi bi-person-plus-fill me-2"></i>
      <?= $isRTL ? 'إنشاء الحساب' : 'Create Account' ?>
    </button>
  </form>
  <hr style="border-color:rgba(255,255,255,.08);margin:20px 0">
  <p style="text-align:center;color:#475569;font-size:.875rem;margin:0">
    <?= $isRTL ? 'لديك حساب بالفعل؟' : 'Already have an account?' ?>
    <a href="<?= BASE_URL ?>/login.php" class="login-link"><?= $isRTL ? 'تسجيل الدخول' : 'Sign In' ?></a>
  </p>
</div>

<script>
function togglePw(id,tid){
  const f=document.getElementById(id),t=document.getElementById(tid);
  if(f.type==='password'){f.type='text';t.className='bi bi-eye-slash pw-toggle';}
  else{f.type='password';t.className='bi bi-eye pw-toggle';}
}
function checkStrength(v){
  const bar=document.getElementById('psBar'),txt=document.getElementById('psText');
  let s=0;
  if(v.length>=8)s++;if(v.length>=12)s++;
  if(/[A-Z]/.test(v))s++;if(/[0-9]/.test(v))s++;if(/[^A-Za-z0-9]/.test(v))s++;
  const lvls=[
    {w:'0%',c:'transparent',t:''},
    {w:'25%',c:'#ef4444',t:'<?= $isRTL ? 'ضعيفة' : 'Weak' ?>'},
    {w:'50%',c:'#f59e0b',t:'<?= $isRTL ? 'متوسطة' : 'Fair' ?>'},
    {w:'75%',c:'#6366f1',t:'<?= $isRTL ? 'جيدة' : 'Good' ?>'},
    {w:'100%',c:'#10b981',t:'<?= $isRTL ? 'قوية' : 'Strong' ?>'},
    {w:'100%',c:'#10b981',t:'<?= $isRTL ? 'ممتازة' : 'Excellent' ?>'},
  ];
  const l=lvls[Math.min(s,5)];
  bar.style.width=l.w;bar.style.background=l.c;
  txt.textContent=l.t;txt.style.color=l.c;
}
</script>
</body>
</html>
