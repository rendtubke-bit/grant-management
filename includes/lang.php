<?php
// Language & i18n helper

$baseDir = str_replace('\\', '/', dirname(__DIR__));
$docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
$baseUrl = str_ireplace($docRoot, '', $baseDir);
if (!defined('BASE_URL')) {
    define('BASE_URL', rtrim($baseUrl, '/'));
}

// Determine language from GET param or cookie
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'])) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + 86400 * 365, '/');
} elseif (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], ['ar', 'en'])) {
    $lang = $_COOKIE['lang'];
} else {
    $lang = 'ar'; // default Arabic
}

$isRTL = ($lang === 'ar');
$dir   = $isRTL ? 'rtl' : 'ltr';

function t($key) {
    global $lang;
    $translations = [
        'ar' => [
            'appName'            => 'نظام إدارة المنح',
            'appSubtitle'        => 'جامعة الملك فهد للبترول والمعادن',
            'dashboard'          => 'لوحة التحكم',
            'grants'             => 'طلبات المنح',
            'projects'           => 'المشاريع البحثية',
            'budget'             => 'إدارة الميزانية',
            'reports'            => 'التقارير والتحليلات',
            'donors'             => 'بوابة الجهات المانحة',
            'researchers'        => 'الباحثون',
            'students'           => 'الطلاب',
            'notifications'      => 'الإشعارات',
            'audit'              => 'سجل المراجعة',
            'settings'           => 'الإعدادات',
            'users'              => 'إدارة المستخدمين',
            'totalGrants'        => 'إجمالي المنح',
            'activeProjects'     => 'المشاريع النشطة',
            'totalFunding'       => 'إجمالي التمويل',
            'pendingApprovals'   => 'في انتظار الموافقة',
            'monthlyApplications'=> 'الطلبات الشهرية',
            'grantDistribution'  => 'توزيع أنواع المنح',
            'fundingTrend'       => 'اتجاه التمويل',
            'quickActions'       => 'الإجراءات السريعة',
            'recentActivity'     => 'النشاط الأخير',
            'viewAllGrants'      => 'عرض جميع المنح',
            'applicant'          => 'مقدم الطلب',
            'amount'             => 'المبلغ',
            'sar'                => 'ريال',
            'status'             => 'الحالة',
            'actions'            => 'الإجراءات',
            'all'                => 'الكل',
            'search'             => 'بحث',
            'export'             => 'تصدير',
            'save'               => 'حفظ',
            'cancel'             => 'إلغاء',
            'submit'             => 'إرسال',
            'edit'               => 'تعديل',
            'delete'             => 'حذف',
            'view'               => 'عرض',
            'add'                => 'إضافة',
            'next'               => 'التالي',
            'previous'           => 'السابق',
            'close'              => 'إغلاق',
            'noData'             => 'لا توجد بيانات',
            'submissionDate'     => 'تاريخ التقديم',
            'markAllRead'        => 'تحديد الكل كمقروء',
            'email'              => 'البريد الإلكتروني',
            'role'               => 'الدور',
            'department'         => 'القسم',
            'addUser'            => 'إضافة مستخدم',
            'newApplication'     => 'طلب جديد',
            'mainMenu'           => 'القائمة الرئيسية',
            'administration'     => 'الإدارة',
            'system'             => 'النظام',
        ],
        'en' => [
            'appName'            => 'Grant Management',
            'appSubtitle'        => 'King Fahd University of Petroleum & Minerals',
            'dashboard'          => 'Dashboard',
            'grants'             => 'Grant Applications',
            'projects'           => 'Research Projects',
            'budget'             => 'Budget Management',
            'reports'            => 'Reports & Analytics',
            'donors'             => 'Donors Portal',
            'researchers'        => 'Researchers',
            'students'           => 'Students',
            'notifications'      => 'Notifications',
            'audit'              => 'Audit Log',
            'settings'           => 'Settings',
            'users'              => 'User Management',
            'totalGrants'        => 'Total Grants',
            'activeProjects'     => 'Active Projects',
            'totalFunding'       => 'Total Funding',
            'pendingApprovals'   => 'Pending Approvals',
            'monthlyApplications'=> 'Monthly Applications',
            'grantDistribution'  => 'Grant Distribution',
            'fundingTrend'       => 'Funding Trend',
            'quickActions'       => 'Quick Actions',
            'recentActivity'     => 'Recent Activity',
            'viewAllGrants'      => 'View All Grants',
            'applicant'          => 'Applicant',
            'amount'             => 'Amount',
            'sar'                => 'SAR',
            'status'             => 'Status',
            'actions'            => 'Actions',
            'all'                => 'All',
            'search'             => 'Search',
            'export'             => 'Export',
            'save'               => 'Save',
            'cancel'             => 'Cancel',
            'submit'             => 'Submit',
            'edit'               => 'Edit',
            'delete'             => 'Delete',
            'view'               => 'View',
            'add'                => 'Add',
            'next'               => 'Next',
            'previous'           => 'Previous',
            'close'              => 'Close',
            'noData'             => 'No data found',
            'submissionDate'     => 'Submission Date',
            'markAllRead'        => 'Mark All as Read',
            'email'              => 'Email',
            'role'               => 'Role',
            'department'         => 'Department',
            'addUser'            => 'Add User',
            'newApplication'     => 'New Application',
            'mainMenu'           => 'Main Menu',
            'administration'     => 'Administration',
            'system'             => 'System',
        ],
    ];
    return $translations[$lang][$key] ?? $key;
}

// Helper: return Arabic or English value based on language
function loc($ar, $en) {
    global $isRTL;
    return $isRTL ? $ar : $en;
}

// Build URL with given page (preserving lang)
function pageUrl($page) {
    global $lang;
    return '?page=' . urlencode($page) . '&lang=' . $lang;
}

// Build URL for lang switch (preserving page)
function langUrl($newLang) {
    $page = $_GET['page'] ?? 'dashboard';
    return '?page=' . urlencode($page) . '&lang=' . $newLang;
}
