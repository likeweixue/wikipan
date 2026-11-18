<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'wikipan';
    $user = getenv('DB_USER') ?: 'wikipan';
    $pass = getenv('DB_PASS') ?: 'wikipan123';

    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><meta charset="utf-8"><body style="background:#0b0b10;color:#eee;font-family:sans-serif;padding:48px">'
            . '<h2>数据库连接失败</h2><p style="color:#999">请确认 MySQL 容器已启动（docker compose ps）。</p><pre style="color:#f66">'
            . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre></body>';
        exit;
    }

    return $pdo;
}

function q(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt;
}

function all(string $sql, array $params = []): array
{
    return q($sql, $params)->fetchAll();
}

function one(string $sql, array $params = []): ?array
{
    $row = q($sql, $params)->fetch();

    return $row === false ? null : $row;
}

function scalar(string $sql, array $params = []): mixed
{
    $value = q($sql, $params)->fetchColumn();

    return $value === false ? null : $value;
}
