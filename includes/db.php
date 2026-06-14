<?php
// includes/db.php — PDO MySQL connection (singleton)

function getDB(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '8889';
    $name = getenv('DB_NAME') ?: 'grant_management';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: 'root';

    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // DB not available — return null sentinel so pages can use mock data
        return nullDB();
    }
    return $pdo;
}

// Null-object pattern so pages work without MySQL
function nullDB(): PDO {
    // Return an in-memory SQLite stub for demo mode
    static $lite = null;
    if ($lite !== null) return $lite;
    $lite = new PDO('sqlite::memory:', null, null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $lite;
}

// Convenience: fetch all rows
function dbAll(string $sql, array $params = []): array {
    try {
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

// Convenience: fetch one row
function dbOne(string $sql, array $params = []): ?array {
    $rows = dbAll($sql, $params);
    return $rows[0] ?? null;
}

// Convenience: fetch scalar value
function dbVal(string $sql, array $params = []) {
    $row = dbOne($sql, $params);
    if (!$row) return null;
    return reset($row);
}

// Convenience: execute (INSERT/UPDATE/DELETE), return last insert id or rowCount
function dbExec(string $sql, array $params = []): int {
    try {
        $stmt = getDB()->prepare($sql);
        $stmt->execute($params);
        $id = (int) getDB()->lastInsertId();
        return $id ?: $stmt->rowCount();
    } catch (Exception $e) {
        return 0;
    }
}
