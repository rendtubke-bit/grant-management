<?php
/**
 * Authentication handling for Grant Management System
 */
require_once __DIR__ . '/db.php';

function authUser() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    }
    // Check for remember token
    if (isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        $user = dbOne("SELECT * FROM users WHERE remember_token = ?", [$token]);
        if ($user) {
            $_SESSION['user'] = $user;
            return $user;
        }
    }
    return null;
}

function requireAuth($redirect = '/login.php') {
    if (!authUser()) {
        header("Location: " . BASE_URL . $redirect);
        exit;
    }
}

function requireRole($role, $redirect = '/login.php') {
    $user = authUser();
    if (!$user) {
        header("Location: " . BASE_URL . $redirect);
        exit;
    }
    if ($user['role'] !== $role && $user['role'] !== 'admin') {
        header("Location: " . BASE_URL . $redirect);
        exit;
    }
}

function requireAdmin($redirect = '/login.php') {
    requireRole('admin', $redirect);
}

function login($email, $password) {
    $user = dbOne("SELECT * FROM users WHERE email = ?", [$email]);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        // Update last login
        dbExec("UPDATE users SET last_login = NOW() WHERE id = ?", [$user['id']]);
        return true;
    }
    return false;
}

function logout() {
    unset($_SESSION['user']);
    setcookie('remember_token', '', time() - 3600, '/');
    session_destroy();
}
