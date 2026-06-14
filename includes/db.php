<?php
/**
 * Database connection helper functions
 */
$dbHost = 'localhost';
$dbName = 'grant_management';
$dbUser = 'root';
$dbPass = '';

try {
    $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ]);
} catch (PDOException $e) {
    // Silent fallback - functions will return null/defaults
    $pdo = null;
}

/**
 * Execute a query and return all rows
 */
function dbAll($sql, $params = []) {
    global $pdo;
    if (!$pdo) return null;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Execute a query and return a single row
 */
function dbOne($sql, $params = []) {
    global $pdo;
    if (!$pdo) return null;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Execute a query and return a single value
 */
function dbVal($sql, $params = []) {
    global $pdo;
    if (!$pdo) return null;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() ?: null;
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Execute an INSERT/UPDATE/DELETE query
 */
function dbExec($sql, $params = []) {
    global $pdo;
    if (!$pdo) return false;
    try {
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($params);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get the last inserted ID
 */
function dbLastId() {
    global $pdo;
    return $pdo ? $pdo->lastInsertId() : 0;
}
