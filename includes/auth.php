<?php
// includes/auth.php — session-based auth helpers

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------- getters ----------

function authUser(): ?array {
    return $_SESSION['auth_user'] ?? null;
}

function authRole(): ?string {
    return $_SESSION['auth_user']['role'] ?? null;
}

function isLoggedIn(): bool {
    return isset($_SESSION['auth_user']);
}

function isAdmin(): bool {
    return authRole() === 'admin';
}

// ---------- gates ----------

function requireLogin(string $redirect = '/login.php'): void {
    if (!isLoggedIn()) {
        $base = defined('BASE_URL') ? BASE_URL : '';
        $url = str_starts_with($redirect, '/') ? $base . $redirect : $redirect;
        header('Location: ' . $url);
        exit;
    }
}

function requireRole(string $role, string $redirect = '/login.php'): void {
    requireLogin($redirect);
    if (authRole() !== $role && authRole() !== 'admin') {
        $base = defined('BASE_URL') ? BASE_URL : '';
        $url = str_starts_with($redirect, '/') ? $base . $redirect : $redirect;
        header('Location: ' . $url);
        exit;
    }
}

// ---------- actions ----------

function loginUser(array $user): void {
    session_regenerate_id(true);
    $_SESSION['auth_user'] = $user;
}

function logoutUser(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

// ---------- role redirect after login ----------

function roleHome(string $role): string {
    $base = defined('BASE_URL') ? BASE_URL : '';
    return $base . match($role) {
        'admin'      => '/admin/',
        'researcher' => '/researcher/',
        'student'    => '/student/',
        'donor'      => '/donor/',
        default      => '/login.php',
    };
}
