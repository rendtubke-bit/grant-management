<?php
require_once dirname(__DIR__) . '/includes/auth_check.php';

$pageTitle = t('users');

// Handle add user
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['_action'] ?? '') === 'add_user') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role  = $_POST['role'] ?? 'student';
    $dept  = trim($_POST['department'] ?? '');
    $pass  = $_POST['password'] ?? 'Password@123';
    if ($name && $email) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $r = dbExec('INSERT INTO users (name,email,password,role,department) VALUES (?,?,?,?,?)', [$name,$email,$hash,$role,$dept]);
        $msg = $r ? ($isRTL ? 'تم إضافة المستخدم بنجاح' : 'User added successfully') : ($isRTL ? 'خطأ في الإضافة' : 'Error adding user');
    }
}

$users = dbAll('SELECT * FROM users ORDER BY created_at DESC');
if (!$users) {
    $users = [
        ['id'=>1,'name'=>'د. محمد العمري',  'email'=>'admin@kfupm.edu.sa',               'role'=>'admin',      'department'=>'إدارة النظام',              'status'=>'active', 'created_at'=>'2024-01-01'],
        ['id'=>2,'name'=>'د. أحمد الشمري',  'email'=>'a.shamri@kfupm.edu.sa',            'role'=>'researcher', 'department'=>'هندسة الحاسب',              'status'=>'active', 'created_at'=>'2024-01-05'],
        ['id'=>3,'name'=>'د. سارة القحطاني','email'=>'s.qahtani@kfupm.edu.sa',           'role'=>'researcher', 'department'=>'هندسة البترول',             'status'=>'active', 'created_at'=>'2024-01-10'],
        ['id'=>4,'name'=>'د. خالد الزهراني','email'=>'k.zahrani@kfupm.edu.sa',           'role'=>'researcher', 'department'=>'الهندسة الكيميائية',       'status'=>'active', 'created_at'=>'2024-01-15'],
        ['id'=>5,'name'=>'م. فيصل الدوسري','email'=>'f.dossary@kfupm.edu.sa',           'role'=>'researcher', 'department'=>'الهندسة الميكانيكية',       'status'=>'active', 'created_at'=>'2024-01-20'],
        ['id'=>6,'name'=>'محمد الغامدي',    'email'=>'m.ghamdi@student.kfupm.edu.sa',   'role'=>'student',    'department'=>'هندسة الحاسب',              'status'=>'active', 'created_at'=>'2024-02-01'],
        ['id'=>7,'name'=>'نورة العتيبي',    'email'=>'n.otaibi@student.kfupm.edu.sa',   'role'=>'student',    'department'=>'هندسة البترول',             'status'=>'active', 'created_at'=>'2024-02-05'],
        ['id'=>8,'name'=>'عبدالله السبيعي', 'email'=>'a.subaie@student.kfupm.edu.sa',   'role'=>'student',    'department'=>'الرياضيات والإحصاء',        'status'=>'active', 'created_at'=>'2024-02-10'],
        ['id'=>9,'name'=>'ممثل أرامكو',     'email'=>'grants@aramco.com',               'role'=>'donor',      'department'=>'أرامكو السعودية',           'status'=>'active', 'created_at'=>'2024-01-02'],
        ['id'=>10,'name'=>'ممثل صندوق الابتكار','email'=>'grants@innovation.gov.sa',    'role'=>'donor',      'department'=>'صندوق الابتكار الوطني',     'status'=>'active', 'created_at'=>'2024-01-03'],
    ];
}
$roleColors = ['admin'=>'#ef4444','researcher'=>'#6366f1','student'=>'#10b981','donor'=>'#f59e0b'];
$roleLabels_ar = ['admin'=>'مدير','researcher'=>'باحث','student'=>'طالب','donor'=>'جهة مانحة'];
$roleLabels_en = ['admin'=>'Admin','researcher'=>'Researcher','student'=>'Student','donor'=>'Donor'];
$summary = array_count_values(array_column($users,'role'));

include dirname(__DIR__) . '/includes/header.php';
include dirname(__DIR__) . '/includes/sidebar.php';
include dirname(__DIR__) . '/includes/app_header.php';
?>

<?php if ($msg): ?>
<div class="alert" style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.3);color:#6ee7b7;border-radius:12px;padding:12px 16px;margin-bottom:20px;font-size:.875rem">
  <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($msg) ?>
</div>
<?php endif; ?>

<!-- Summary cards -->
<div class="row g-3 mb-4">
  <?php foreach(['admin','researcher','student','donor'] as $r): $c=$roleColors[$r]; $cnt=$summary[$r]??0; ?>
  <div class="col-sm-6 col-xl-3">
    <div class="stat-card">
      <div class="stat-icon" style="background:<?=$c?>22;color:<?=$c?>">
        <i class="bi <?= $r==='admin'?'bi-shield-fill':($r==='researcher'?'bi-person-badge-fill':($r==='student'?'bi-mortarboard-fill':'bi-bank2')) ?>"></i>
      </div>
      <div class="stat-info">
        <div class="stat-value"><?=$cnt?></div>
        <div class="stat-label"><?= $isRTL ? $roleLabels_ar[$r] : $roleLabels_en[$r] ?></div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="card-custom">
  <div class="card-custom-header">
    <h5 class="card-custom-title">
      <i class="bi bi-people-fill" style="color:#6366f1"></i>
      <?= $isRTL ? 'جميع المستخدمين' : 'All Users' ?>
      <span class="badge ms-2" style="background:rgba(99,102,241,.15);color:#818cf8;font-size:11px"><?= count($users) ?></span>
    </h5>
    <div class="d-flex gap-2">
      <input type="text" id="userSearch" class="form-control form-control-sm" style="width:200px;background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:8px"
        placeholder="<?= $isRTL ? 'بحث...' : 'Search...' ?>" oninput="filterUsers(this.value)">
      <button class="btn-primary-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-plus-lg me-1"></i><?= $isRTL ? 'إضافة مستخدم' : 'Add User' ?>
      </button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table-custom" id="usersTable">
      <thead>
        <tr>
          <th>#</th>
          <th><?= $isRTL ? 'الاسم' : 'Name' ?></th>
          <th><?= $isRTL ? 'البريد الإلكتروني' : 'Email' ?></th>
          <th><?= $isRTL ? 'الدور' : 'Role' ?></th>
          <th><?= $isRTL ? 'القسم' : 'Department' ?></th>
          <th><?= $isRTL ? 'الحالة' : 'Status' ?></th>
          <th><?= $isRTL ? 'تاريخ التسجيل' : 'Joined' ?></th>
          <th><?= $isRTL ? 'إجراءات' : 'Actions' ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($users as $u):
          $rc = $roleColors[$u['role']] ?? '#6366f1';
          $rl = $isRTL ? ($roleLabels_ar[$u['role']] ?? $u['role']) : ($roleLabels_en[$u['role']] ?? $u['role']);
        ?>
        <tr>
          <td style="color:var(--text-muted)"><?=$u['id']?></td>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div style="width:36px;height:36px;border-radius:50%;background:<?=$rc?>22;color:<?=$rc?>;display:flex;align-items:center;justify-content:center;font-weight:700;flex-shrink:0">
                <?= mb_substr($u['name'],0,1) ?>
              </div>
              <span style="font-weight:600;color:var(--text-primary)"><?= htmlspecialchars($u['name']) ?></span>
            </div>
          </td>
          <td style="color:var(--text-secondary)"><?= htmlspecialchars($u['email']) ?></td>
          <td><span style="background:<?=$rc?>22;color:<?=$rc?>;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:700"><?=$rl?></span></td>
          <td style="color:var(--text-muted)"><?= htmlspecialchars($u['department'] ?? '—') ?></td>
          <td>
            <span class="badge-status <?= ($u['status']==='active') ? 'badge-success' : 'badge-secondary' ?>">
              <?= $isRTL ? ($u['status']==='active'?'نشط':'غير نشط') : ucfirst($u['status']) ?>
            </span>
          </td>
          <td style="color:var(--text-muted)"><?= substr($u['created_at'],0,10) ?></td>
          <td>
            <div style="display:flex;gap:4px">
              <button class="action-btn" title="<?= $isRTL ? 'تعديل' : 'Edit' ?>"><i class="bi bi-pencil"></i></button>
              <?php if ($u['id'] != authUser()['id']): ?>
              <button class="action-btn danger" title="<?= $isRTL ? 'حذف' : 'Delete' ?>"><i class="bi bi-trash"></i></button>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background:var(--bg-secondary);border:1px solid var(--border-color);border-radius:20px">
      <div class="modal-header" style="border-bottom:1px solid var(--border-color);padding:20px 24px">
        <h5 class="modal-title" style="color:var(--text-primary);font-weight:700">
          <i class="bi bi-person-plus-fill me-2" style="color:#6366f1"></i>
          <?= $isRTL ? 'إضافة مستخدم جديد' : 'Add New User' ?>
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="_action" value="add_user">
        <div class="modal-body" style="padding:24px">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
                <?= $isRTL ? 'الاسم الكامل *' : 'Full Name *' ?>
              </label>
              <input type="text" name="name" class="form-control" required
                style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px">
            </div>
            <div class="col-12">
              <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
                <?= $isRTL ? 'البريد الإلكتروني *' : 'Email *' ?>
              </label>
              <input type="email" name="email" class="form-control" required
                style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px">
            </div>
            <div class="col-md-6">
              <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
                <?= $isRTL ? 'الدور' : 'Role' ?>
              </label>
              <select name="role" class="form-select" style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px">
                <option value="student"><?= $isRTL ? 'طالب' : 'Student' ?></option>
                <option value="researcher"><?= $isRTL ? 'باحث' : 'Researcher' ?></option>
                <option value="donor"><?= $isRTL ? 'جهة مانحة' : 'Donor' ?></option>
                <option value="admin"><?= $isRTL ? 'مدير' : 'Admin' ?></option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
                <?= $isRTL ? 'كلمة المرور' : 'Password' ?>
              </label>
              <input type="password" name="password" class="form-control" value="Password@123"
                style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px">
            </div>
            <div class="col-12">
              <label class="form-label" style="color:var(--text-secondary);font-size:13px;font-weight:600">
                <?= $isRTL ? 'القسم' : 'Department' ?>
              </label>
              <input type="text" name="department" class="form-control"
                style="background:var(--bg-tertiary);border:1px solid var(--border-color);color:var(--text-primary);border-radius:10px">
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid var(--border-color);padding:16px 24px">
          <button type="button" class="btn-secondary-sm" data-bs-dismiss="modal"><?= $isRTL?'إلغاء':'Cancel' ?></button>
          <button type="submit" class="btn-primary-sm">
            <i class="bi bi-plus-lg me-1"></i><?= $isRTL ? 'إضافة' : 'Add User' ?>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php
$pageScript = "
function filterUsers(q) {
  q = q.toLowerCase();
  document.querySelectorAll('#usersTable tbody tr').forEach(function(r) {
    r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
";
include dirname(__DIR__) . '/includes/footer.php';
