<?php
declare(strict_types=1);

function current_user(): ?array
{
    static $loaded = false;
    static $user = null;

    if ($loaded) {
        return $user;
    }
    $loaded = true;

    if (empty($_SESSION['uid'])) {
        return null;
    }

    $user = one('SELECT * FROM users WHERE id = ?', [(int) $_SESSION['uid']]);
    if (!$user) {
        unset($_SESSION['uid'], $user);
        $user = null;
    }

    return $user;
}

function is_admin(): bool
{
    $user = current_user();

    return $user !== null && $user['role'] === 'admin';
}

function auth_attempt(string $login, string $password): bool
{
    $user = one('SELECT * FROM users WHERE username = ? OR email = ?', [$login, $login]);

    if (!$user || !password_verify($password, $user['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['uid'] = (int) $user['id'];
    unset($_SESSION['_csrf']);

    return true;
}

function auth_login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['uid'] = (int) $user['id'];
    unset($_SESSION['_csrf']);
}

function auth_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function require_login(): array
{
    $user = current_user();
    if ($user !== null) {
        return $user;
    }

    flash('error', '请先登录后再继续操作');
    redirect('/login?next=' . rawurlencode($_SERVER['REQUEST_URI'] ?? '/'));
}
