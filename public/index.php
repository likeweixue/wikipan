<?php
declare(strict_types=1);

require __DIR__ . '/../app/bootstrap.php';

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$path = '/' . trim($path, '/');
$path = $path === '/' ? '/' : rtrim($path, '/');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'GET' && $path === '/') {
    page_home();
    exit;
}

if ($method === 'GET' && $path === '/browse') {
    page_browse();
    exit;
}

if ($method === 'GET' && $path === '/search') {
    page_search();
    exit;
}

if ($path === '/create') {
    if ($method === 'POST') {
        action_create_repo();
    } else {
        page_create();
    }
    exit;
}

if ($path === '/login') {
    if ($method === 'POST') {
        action_login();
    } else {
        page_login();
    }
    exit;
}

if ($path === '/register') {
    if ($method === 'POST') {
        action_register();
    } else {
        page_register();
    }
    exit;
}

if ($method === 'POST' && $path === '/logout') {
    action_logout();
    exit;
}

if ($method === 'GET' && $path === '/me') {
    page_me();
    exit;
}

if ($method === 'POST' && $path === '/vote') {
    action_vote();
    exit;
}

if ($method === 'POST' && preg_match('#^/repo/(\d+)/resources$#', $path, $m)) {
    action_add_resource((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/repo/(\d+)/delete$#', $path, $m)) {
    action_delete_repo((int) $m[1]);
    exit;
}

if ($method === 'POST' && preg_match('#^/resource/(\d+)/delete$#', $path, $m)) {
    action_delete_resource((int) $m[1]);
    exit;
}

if ($method === 'GET' && preg_match('#^/repo/(\d+)(?:-[^/]*)?$#', $path, $m)) {
    page_repo((int) $m[1]);
    exit;
}

page_not_found();
