<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Shanghai');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('wikipan_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require APP_PATH . '/db.php';
require APP_PATH . '/helpers.php';
require APP_PATH . '/auth.php';
require APP_PATH . '/queries.php';
require APP_PATH . '/actions.php';
require APP_PATH . '/pages.php';
