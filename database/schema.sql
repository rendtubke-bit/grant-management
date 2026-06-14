-- ============================================================
-- نظام إدارة المنح والتمويل البحثي — KFUPM
-- MySQL Database Schema + Seed Data
-- ============================================================

CREATE DATABASE IF NOT EXISTS grant_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE grant_management;

-- ============================================================
-- USERS
-- ============================================================
CREATE TABLE IF NOT EXISTS users (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(120) NOT NULL,
  email       VARCHAR(180) NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,
  role        ENUM('admin','researcher','student','donor') NOT NULL DEFAULT 'student',
  department  VARCHAR(120),
  avatar_url  VARCHAR(255),
  status      ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  last_login  DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- DONORS
-- ============================================================
CREATE TABLE IF NOT EXISTS donors (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED,
  name_ar        VARCHAR(150) NOT NULL,
  name_en        VARCHAR(150) NOT NULL,
  type           ENUM('government','private','ngo','international') NOT NULL DEFAULT 'private',
  contact_name   VARCHAR(120),
  email          VARCHAR(180),
  phone          VARCHAR(30),
  country        VARCHAR(80),
  total_donated  DECIMAL(14,2) NOT NULL DEFAULT 0,
  active_grants  SMALLINT NOT NULL DEFAULT 0,
  logo_url       VARCHAR(255),
  website        VARCHAR(255),
  notes          TEXT,
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- GRANTS
-- ============================================================
CREATE TABLE IF NOT EXISTS grants (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title_ar        VARCHAR(255) NOT NULL,
  title_en        VARCHAR(255) NOT NULL,
  grant_type      ENUM('research','scholarship','equipment','international','industrial') NOT NULL,
  donor_id        INT UNSIGNED,
  amount          DECIMAL(14,2) NOT NULL,
  currency        VARCHAR(10) NOT NULL DEFAULT 'SAR',
  status          ENUM('pending','under_review','approved','rejected','active','closed') NOT NULL DEFAULT 'pending',
  applicant_id    INT UNSIGNED,
  description_ar  TEXT,
  description_en  TEXT,
  submission_date DATE NOT NULL,
  start_date      DATE,
  end_date        DATE,
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (donor_id)    REFERENCES donors(id) ON DELETE SET NULL,
  FOREIGN KEY (applicant_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- PROJECTS
-- ============================================================
CREATE TABLE IF NOT EXISTS projects (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title_ar          VARCHAR(255) NOT NULL,
  title_en          VARCHAR(255) NOT NULL,
  grant_id          INT UNSIGNED,
  lead_researcher_id INT UNSIGNED,
  status            ENUM('planning','active','paused','completed','cancelled') NOT NULL DEFAULT 'planning',
  progress          TINYINT NOT NULL DEFAULT 0,
  budget_total      DECIMAL(14,2) NOT NULL DEFAULT 0,
  budget_spent      DECIMAL(14,2) NOT NULL DEFAULT 0,
  start_date        DATE,
  end_date          DATE,
  description_ar    TEXT,
  description_en    TEXT,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (grant_id)           REFERENCES grants(id) ON DELETE SET NULL,
  FOREIGN KEY (lead_researcher_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- BUDGET CATEGORIES
-- ============================================================
CREATE TABLE IF NOT EXISTS budget_categories (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  project_id  INT UNSIGNED NOT NULL,
  category_ar VARCHAR(100) NOT NULL,
  category_en VARCHAR(100) NOT NULL,
  allocated   DECIMAL(14,2) NOT NULL DEFAULT 0,
  spent       DECIMAL(14,2) NOT NULL DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- RESEARCHERS
-- ============================================================
CREATE TABLE IF NOT EXISTS researchers (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         INT UNSIGNED NOT NULL UNIQUE,
  employee_id     VARCHAR(30),
  specialization_ar VARCHAR(150),
  specialization_en VARCHAR(150),
  department      VARCHAR(120),
  rank_ar         VARCHAR(80),
  rank_en         VARCHAR(80),
  h_index         SMALLINT DEFAULT 0,
  publications    SMALLINT DEFAULT 0,
  projects_count  SMALLINT DEFAULT 0,
  phone           VARCHAR(30),
  office          VARCHAR(80),
  created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- STUDENTS
-- ============================================================
CREATE TABLE IF NOT EXISTS students (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        INT UNSIGNED NOT NULL UNIQUE,
  student_id     VARCHAR(20),
  degree         ENUM('bachelor','master','phd') NOT NULL DEFAULT 'master',
  department     VARCHAR(120),
  supervisor_id  INT UNSIGNED,
  gpa            DECIMAL(4,2),
  enrollment_year YEAR,
  phone          VARCHAR(30),
  created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)      REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- GRANT APPLICATIONS (student-level)
-- ============================================================
CREATE TABLE IF NOT EXISTS grant_applications (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id   INT UNSIGNED NOT NULL,
  grant_id     INT UNSIGNED NOT NULL,
  status       ENUM('draft','submitted','under_review','approved','rejected') NOT NULL DEFAULT 'draft',
  message      TEXT,
  submitted_at DATETIME,
  reviewed_by  INT UNSIGNED,
  reviewed_at  DATETIME,
  notes        TEXT,
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id)  REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (grant_id)    REFERENCES grants(id) ON DELETE CASCADE,
  FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- NOTIFICATIONS
-- ============================================================
CREATE TABLE IF NOT EXISTS notifications (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED,
  title_ar   VARCHAR(255) NOT NULL,
  title_en   VARCHAR(255) NOT NULL,
  message_ar TEXT,
  message_en TEXT,
  type       ENUM('info','success','warning','danger') NOT NULL DEFAULT 'info',
  is_read    TINYINT(1) NOT NULL DEFAULT 0,
  link       VARCHAR(255),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- AUDIT LOG
-- ============================================================
CREATE TABLE IF NOT EXISTS audit_log (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED,
  action      VARCHAR(100) NOT NULL,
  entity_type VARCHAR(60),
  entity_id   INT UNSIGNED,
  details     TEXT,
  ip_address  VARCHAR(45),
  user_agent  VARCHAR(255),
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SETTINGS
-- ============================================================
CREATE TABLE IF NOT EXISTS settings (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  key_name   VARCHAR(80) NOT NULL UNIQUE,
  value      TEXT,
  label_ar   VARCHAR(150),
  label_en   VARCHAR(150),
  group_name VARCHAR(60) NOT NULL DEFAULT 'general',
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Passwords are all: Password@123  (hashed with password_hash)
INSERT INTO users (id, name, email, password, role, department, status) VALUES
(1,  'د. محمد العمري',       'admin@kfupm.edu.sa',      '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'admin',       'إدارة النظام',                 'active'),
(2,  'د. أحمد الشمري',       'a.shamri@kfupm.edu.sa',   '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'researcher',  'هندسة الحاسب والمعلومات',      'active'),
(3,  'د. سارة القحطاني',     's.qahtani@kfupm.edu.sa',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'researcher',  'علوم وهندسة البترول',          'active'),
(4,  'د. خالد الزهراني',     'k.zahrani@kfupm.edu.sa',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'researcher',  'الهندسة الكيميائية',           'active'),
(5,  'م. فيصل الدوسري',      'f.dossary@kfupm.edu.sa',  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'researcher',  'الهندسة الميكانيكية',          'active'),
(6,  'محمد الغامدي',         'm.ghamdi@student.kfupm.edu.sa', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'student', 'هندسة الحاسب والمعلومات',  'active'),
(7,  'نورة العتيبي',         'n.otaibi@student.kfupm.edu.sa', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'student', 'علوم وهندسة البترول',      'active'),
(8,  'عبدالله السبيعي',      'a.subaie@student.kfupm.edu.sa', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'student', 'الرياضيات والإحصاء',        'active'),
(9,  'ممثل أرامكو السعودية', 'grants@aramco.com',        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'donor',   'أرامكو السعودية',              'active'),
(10, 'ممثل صندوق الابتكار',  'grants@innovation.gov.sa', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uHxL5GU', 'donor',   'صندوق الابتكار الوطني',        'active');

INSERT INTO donors (id, user_id, name_ar, name_en, type, contact_name, email, phone, country, total_donated, active_grants) VALUES
(1, 9,    'أرامكو السعودية',               'Saudi Aramco',             'industrial',    'خالد السلطان',    'grants@aramco.com',         '+966112345678', 'السعودية', 18500000, 4),
(2, 10,   'صندوق الابتكار الوطني',         'National Innovation Fund',  'government',    'عبدالرحمن الحمد', 'grants@innovation.gov.sa',  '+966112345679', 'السعودية', 12200000, 3),
(3, NULL, 'وزارة التعليم',                 'Ministry of Education',     'government',    'سلمى العثمان',    'research@moe.gov.sa',       '+966112345670', 'السعودية', 25000000, 6),
(4, NULL, 'مؤسسة الملك عبدالعزيز والرجال', 'King Abdulaziz Foundation', 'ngo',           'طارق الحمدان',    'info@kaf.org.sa',            '+966112345671', 'السعودية',  8750000, 2),
(5, NULL, 'البنك الإسلامي للتنمية',        'Islamic Development Bank',  'international', 'محمد الإدريسي',   'grants@isdb.org',           '+966126361400', 'السعودية',  9300000, 2);

INSERT INTO grants (id, title_ar, title_en, grant_type, donor_id, amount, status, applicant_id, submission_date, start_date, end_date) VALUES
(1,  'منحة أبحاث الذكاء الاصطناعي',       'AI Research Grant',               'research',      1, 750000,  'active',       2, '2024-01-15', '2024-03-01', '2025-03-01'),
(2,  'منحة هندسة الطاقة المتجددة',         'Renewable Energy Engineering',    'research',      2, 520000,  'active',       3, '2024-02-20', '2024-04-01', '2025-04-01'),
(3,  'منحة التحول الرقمي',                 'Digital Transformation Grant',    'industrial',    1, 980000,  'approved',     4, '2024-03-10', '2024-06-01', '2026-06-01'),
(4,  'منحة تطوير الكوادر البشرية',          'Human Capital Development',       'scholarship',   3, 350000,  'active',       5, '2024-01-05', '2024-02-01', '2025-08-01'),
(5,  'منحة أبحاث النانو تكنولوجي',         'Nanotechnology Research Grant',   'equipment',     4, 1200000, 'under_review', 2, '2024-04-18', NULL,         NULL),
(6,  'منحة الأبحاث المشتركة الدولية',      'International Joint Research',    'international', 5, 680000,  'pending',      3, '2024-05-02', NULL,         NULL),
(7,  'منحة تطوير المختبرات',               'Laboratory Development Grant',    'equipment',     1, 430000,  'approved',     4, '2024-03-25', '2024-07-01', '2025-07-01'),
(8,  'منحة ريادة الأعمال التقنية',          'Tech Entrepreneurship Grant',     'industrial',    2, 290000,  'active',       5, '2024-02-14', '2024-04-15', '2024-10-15'),
(9,  'منحة الدراسات العليا المتميزة',       'Distinguished Graduate Studies',  'scholarship',   3, 180000,  'active',       NULL, '2024-05-01', '2024-09-01', '2027-09-01'),
(10, 'منحة أبحاث المياه والبيئة',           'Water & Environment Research',    'research',      5, 840000,  'pending',      2, '2024-06-10', NULL,         NULL);

INSERT INTO projects (id, title_ar, title_en, grant_id, lead_researcher_id, status, progress, budget_total, budget_spent, start_date, end_date) VALUES
(1, 'نظام ذكاء اصطناعي لتشخيص أمراض النخيل',    'AI Palm Disease Diagnosis System',        1, 2, 'active',    72, 750000,  540000,  '2024-03-01', '2025-03-01'),
(2, 'توليد الطاقة الشمسية في المناطق النائية',   'Solar Energy in Remote Areas',            2, 3, 'active',    45, 520000,  234000,  '2024-04-01', '2025-04-01'),
(3, 'منصة التحول الرقمي للمنشآت الصناعية',       'Digital Transformation Platform',        3, 4, 'planning',  15, 980000,  147000,  '2024-06-01', '2026-06-01'),
(4, 'برنامج تطوير القيادات الجامعية',             'University Leadership Development',       4, 5, 'active',    60, 350000,  210000,  '2024-02-01', '2025-08-01'),
(5, 'تطبيق النانو تكنولوجي في تحلية المياه',     'Nanotechnology in Water Desalination',    5, 2, 'planning',   5, 1200000, 60000,   NULL,         NULL),
(6, 'تطوير مختبر الحوسبة الكمومية',              'Quantum Computing Lab Development',       7, 4, 'active',    30, 430000,  129000,  '2024-07-01', '2025-07-01'),
(7, 'منصة ريادة الأعمال التقنية الجامعية',       'University Tech Entrepreneurship',        8, 5, 'completed', 100,290000,  290000,  '2024-04-15', '2024-10-15'),
(8, 'بحث جودة مياه الخليج العربي',               'Arabian Gulf Water Quality Research',     10,2, 'planning',   0, 840000,  0,       NULL,         NULL);

INSERT INTO budget_categories (project_id, category_ar, category_en, allocated, spent) VALUES
(1, 'رواتب الباحثين',    'Researcher Salaries',  300000, 216000),
(1, 'معدات ومختبرات',    'Equipment & Labs',     200000, 180000),
(1, 'مستلزمات البحث',   'Research Supplies',    100000,  72000),
(1, 'نشر وتوثيق',        'Publication & Docs',    80000,  52000),
(1, 'رحلات ومؤتمرات',   'Travel & Conferences',  70000,  20000),
(2, 'رواتب الباحثين',    'Researcher Salaries',  200000, 100000),
(2, 'معدات ومختبرات',    'Equipment & Labs',     180000,  88000),
(2, 'مستلزمات البحث',   'Research Supplies',     80000,  34000),
(2, 'نشر وتوثيق',        'Publication & Docs',    60000,  12000),
(3, 'رواتب الباحثين',    'Researcher Salaries',  400000,  60000),
(3, 'معدات ومختبرات',    'Equipment & Labs',     300000,  42000),
(3, 'برمجيات وتراخيص',  'Software & Licenses',  180000,  35000),
(4, 'تدريب وورش عمل',   'Training & Workshops', 150000,  90000),
(4, 'مواد تعليمية',      'Educational Materials', 80000,  50000),
(4, 'أتعاب خبراء',       'Expert Fees',          120000,  70000);

INSERT INTO researchers (user_id, employee_id, specialization_ar, specialization_en, department, rank_ar, rank_en, h_index, publications, projects_count) VALUES
(2, 'EMP-2021-0042', 'الذكاء الاصطناعي وتعلم الآلة', 'AI & Machine Learning',    'هندسة الحاسب', 'أستاذ مشارك', 'Associate Professor', 18, 42, 5),
(3, 'EMP-2019-0018', 'علوم البترول والطاقة المتجددة',  'Petroleum & Renewable Energy', 'هندسة البترول', 'أستاذ',  'Professor',  24, 67, 8),
(4, 'EMP-2020-0031', 'الهندسة الكيميائية والبيئية',   'Chemical & Environmental Eng', 'الهندسة الكيميائية', 'أستاذ مساعد', 'Assistant Professor', 12, 28, 4),
(5, 'EMP-2022-0057', 'الهندسة الميكانيكية والمواد',   'Mechanical Eng & Materials',   'الهندسة الميكانيكية', 'أستاذ مساعد', 'Assistant Professor', 9, 19, 3);

INSERT INTO students (user_id, student_id, degree, department, supervisor_id, gpa, enrollment_year) VALUES
(6, 'S202110234', 'phd',    'هندسة الحاسب والمعلومات', 2, 3.85, 2021),
(7, 'S202210087', 'master', 'علوم وهندسة البترول',     3, 3.92, 2022),
(8, 'S202310156', 'master', 'الرياضيات والإحصاء',     4, 3.70, 2023);

INSERT INTO grant_applications (student_id, grant_id, status, message, submitted_at) VALUES
(6, 9, 'under_review', 'أرغب في التقدم لهذه المنحة لدعم أبحاثي في مجال الذكاء الاصطناعي.', '2024-05-05 10:30:00'),
(7, 9, 'approved',     'أسعى لإتمام رسالة الماجستير في مجال الطاقة المتجددة.',             '2024-05-03 09:15:00'),
(8, 9, 'submitted',    'أقدم طلبي لدعم دراستي في الرياضيات التطبيقية.',                    '2024-05-10 14:20:00');

INSERT INTO notifications (user_id, title_ar, title_en, message_ar, message_en, type, is_read) VALUES
(1, 'طلب منحة جديد',            'New Grant Application',      'تم استلام طلب منحة جديد من الباحث أحمد الشمري', 'New grant application received from researcher Ahmad Al-Shamri', 'info',    0),
(1, 'موافقة على مشروع',          'Project Approved',           'تمت الموافقة على مشروع التحول الرقمي',           'Digital transformation project has been approved',               'success', 0),
(1, 'تحذير: ميزانية قاربت النهاية', 'Budget Alert',            'ميزانية مشروع رقم 1 تجاوزت 70%',                'Project #1 budget has exceeded 70%',                             'warning', 0),
(1, 'تقرير شهري جاهز',           'Monthly Report Ready',       'التقرير الشهري لشهر مايو جاهز للمراجعة',          'May monthly report is ready for review',                        'info',    1),
(2, 'موافقة على طلبك',           'Your Application Approved',  'تمت الموافقة على طلب منحة الذكاء الاصطناعي',     'Your AI research grant application has been approved',           'success', 0),
(6, 'طلبك قيد المراجعة',         'Application Under Review',   'طلب المنحة الخاص بك قيد المراجعة الآن',          'Your grant application is currently under review',               'info',    0),
(7, 'تهانينا! تمت الموافقة',     'Congratulations! Approved',  'تمت الموافقة على طلب منحتك بنجاح',               'Your grant application has been successfully approved',          'success', 0);

INSERT INTO audit_log (user_id, action, entity_type, entity_id, details, ip_address) VALUES
(1, 'login',           'user',    1, 'تسجيل دخول ناجح',                     '192.168.1.10'),
(1, 'create',          'grant',   10,'إنشاء طلب منحة جديد',                 '192.168.1.10'),
(1, 'update',          'project', 1, 'تحديث نسبة التقدم إلى 72%',           '192.168.1.10'),
(2, 'login',           'user',    2, 'تسجيل دخول ناجح',                     '192.168.1.22'),
(2, 'submit',          'grant',   5, 'تقديم طلب منحة النانو تكنولوجي',      '192.168.1.22'),
(1, 'approve',         'grant',   3, 'الموافقة على منحة التحول الرقمي',     '192.168.1.10'),
(3, 'login',           'user',    3, 'تسجيل دخول ناجح',                     '192.168.1.35'),
(1, 'delete',          'notification', 2, 'حذف إشعار قديم',                 '192.168.1.10'),
(4, 'update',          'project', 3, 'تعديل خطة المشروع',                   '192.168.1.40'),
(1, 'export',          'report',  NULL,'تصدير التقرير السنوي',               '192.168.1.10');

INSERT INTO settings (key_name, value, label_ar, label_en, group_name) VALUES
('app_name_ar',       'نظام إدارة المنح',         'اسم التطبيق (عربي)',    'App Name (Arabic)',      'general'),
('app_name_en',       'Grant Management System',  'اسم التطبيق (إنجليزي)', 'App Name (English)',     'general'),
('university_name',   'جامعة الملك فهد للبترول والمعادن', 'اسم الجامعة',   'University Name',        'general'),
('default_currency',  'SAR',                      'العملة الافتراضية',     'Default Currency',       'financial'),
('fiscal_year_start', '01-01',                    'بداية السنة المالية',   'Fiscal Year Start',      'financial'),
('max_grant_amount',  '5000000',                  'الحد الأقصى للمنحة',   'Max Grant Amount',       'financial'),
('allow_registration','1',                        'السماح بالتسجيل',       'Allow Registration',     'security'),
('session_timeout',   '120',                      'مهلة الجلسة (دقيقة)',   'Session Timeout (min)',  'security'),
('email_notifications','1',                       'إشعارات البريد',        'Email Notifications',    'notifications'),
('system_email',      'system@kfupm.edu.sa',      'البريد الرسمي للنظام',  'System Email',           'notifications'),
('maintenance_mode',  '0',                        'وضع الصيانة',           'Maintenance Mode',       'system'),
('system_version',    '2.0.0',                    'إصدار النظام',          'System Version',         'system');
