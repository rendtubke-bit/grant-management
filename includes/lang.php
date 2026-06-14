<?php
/**
 * Language handling for Grant Management System
 * Supports Arabic (ar) and English (en)
 */
session_start();

$availableLangs = ['ar', 'en'];
$lang = $_GET['lang'] ?? $_SESSION['lang'] ?? 'ar';
if (!in_array($lang, $availableLangs)) $lang = 'ar';
$_SESSION['lang'] = $lang;
$isRTL = ($lang === 'ar');
$dir = $isRTL ? 'rtl' : 'ltr';

// Language translations
$translations = [
    'ar' => [
        'appName' => 'نظام إدارة المنح',
        'dashboard' => 'لوحة التحكم',
        'grants' => 'المنح',
        'projects' => 'المشاريع',
        'budget' => 'الميزانية',
        'reports' => 'التقارير',
        'donors' => 'الجهات المانحة',
        'researchers' => 'الباحثون',
        'students' => 'الطلاب',
        'notifications' => 'الإشعارات',
        'audit' => 'سجل المراجعة',
        'settings' => 'الإعدادات',
        'users' => 'المستخدمين',
        'login' => 'تسجيل الدخول',
        'logout' => 'تسجيل الخروج',
        'export' => 'تصدير',
        'actions' => 'الإجراءات',
        'search' => 'بحث',
        'noData' => 'لا توجد بيانات',
        'save' => 'حفظ',
        'cancel' => 'إلغاء',
        'delete' => 'حذف',
        'edit' => 'تعديل',
        'add' => 'إضافة',
        'view' => 'عرض',
        'loading' => 'جاري التحميل...',
        'error' => 'حدث خطأ',
        'success' => 'تم بنجاح',
        'welcome' => 'مرحباً',
        'total' => 'المجموع',
        'status' => 'الحالة',
        'date' => 'التاريخ',
        'amount' => 'المبلغ',
        'title' => 'العنوان',
        'description' => 'الوصف',
    ],
    'en' => [
        'appName' => 'Grant Management System',
        'dashboard' => 'Dashboard',
        'grants' => 'Grants',
        'projects' => 'Projects',
        'budget' => 'Budget',
        'reports' => 'Reports',
        'donors' => 'Donors',
        'researchers' => 'Researchers',
        'students' => 'Students',
        'notifications' => 'Notifications',
        'audit' => 'Audit Log',
        'settings' => 'Settings',
        'users' => 'Users',
        'login' => 'Login',
        'logout' => 'Logout',
        'export' => 'Export',
        'actions' => 'Actions',
        'search' => 'Search',
        'noData' => 'No data available',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'add' => 'Add',
        'view' => 'View',
        'loading' => 'Loading...',
        'error' => 'An error occurred',
        'success' => 'Success',
        'welcome' => 'Welcome',
        'total' => 'Total',
        'status' => 'Status',
        'date' => 'Date',
        'amount' => 'Amount',
        'title' => 'Title',
        'description' => 'Description',
    ],
];

function t($key) {
    global $lang, $translations;
    return $translations[$lang][$key] ?? $translations['en'][$key] ?? $key;
}

function pageUrl($page) {
    global $lang;
    return "?page={$page}&lang={$lang}";
}

function langUrl($l) {
    global $currentPage;
    $page = $currentPage ?? 'dashboard';
    return "?page={$page}&lang={$l}";
}

// Set variables for use in all pages
$currentPage = $_GET['page'] ?? 'dashboard';

$docRoot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$projRoot = str_replace('\\', '/', dirname(__DIR__));
$basePath = str_ireplace($docRoot, '', $projRoot);

define('BASE_URL', rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $basePath, '/'));
