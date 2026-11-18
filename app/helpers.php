<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function repo_url(array $repo): string
{
    $slug = trim((string) ($repo['slug'] ?? ''));

    return '/repo/' . (int) $repo['id'] . ($slug !== '' ? '-' . rawurlencode($slug) : '');
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function render_view(string $view, array $data = []): string
{
    extract($data, EXTR_SKIP);
    ob_start();
    require APP_PATH . '/views/' . $view . '.php';

    return (string) ob_get_clean();
}

function view(string $view, array $data = [], array $meta = []): void
{
    $content = render_view($view, $data);

    $meta += [
        'title'       => '盘维 · 网盘资源维基',
        'description' => '按作品聚合的网盘资源维基：一个仓库，收录 4K、1080P 等全部版本。',
        'nav'         => '',
        'body_class'  => '',
    ];

    extract($meta, EXTR_SKIP);
    require APP_PATH . '/views/layout.php';

    unset($_SESSION['_old']);
}

function flash(string $type, string $message): void
{
    $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
}

function flashes(): array
{
    $items = $_SESSION['_flash'] ?? [];
    unset($_SESSION['_flash']);

    return $items;
}

function keep_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function old(string $key, string $default = ''): string
{
    $value = $_SESSION['_old'][$key] ?? $default;

    return is_scalar($value) ? (string) $value : $default;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');

    return is_string($token) && $token !== '' && hash_equals(csrf_token(), $token);
}

function csrf_fail(): never
{
    http_response_code(419);
    view('message', [
        'heading' => '请求已失效',
        'message' => '页面停留时间过长，请刷新页面后重试。',
        'action'  => ['label' => '返回首页', 'href' => '/'],
    ], ['title' => '请求已失效 · 盘维']);
    exit;
}

function time_ago(?string $datetime): string
{
    if (!$datetime) {
        return '—';
    }

    $time = strtotime($datetime);
    if ($time === false) {
        return '—';
    }

    $diff = time() - $time;
    if ($diff < 60) {
        return '刚刚';
    }
    if ($diff < 3600) {
        return floor($diff / 60) . ' 分钟前';
    }
    if ($diff < 86400) {
        return floor($diff / 3600) . ' 小时前';
    }
    if ($diff < 86400 * 30) {
        return floor($diff / 86400) . ' 天前';
    }

    return date('Y-m-d', $time);
}

function heat_text(int|string $value): string
{
    $value = (int) $value;
    if ($value >= 10000) {
        return rtrim(rtrim(number_format($value / 10000, 1, '.', ''), '0'), '.') . '万';
    }

    return (string) $value;
}

function first_char(string $name): string
{
    return mb_strtoupper(mb_substr(trim($name) !== '' ? $name : '?', 0, 1));
}

function avatar_color(int|string $hue): string
{
    $hue = ((int) $hue) % 360;

    return "hsl({$hue} 62% 42%)";
}

function quality_class(string $quality): string
{
    $quality = strtoupper($quality);
    if (in_array($quality, ['4K', '2160P', 'UHD'], true)) {
        return 'q-4k';
    }
    if ($quality === '1080P') {
        return 'q-1080';
    }
    if ($quality === '720P') {
        return 'q-720';
    }

    return 'q-other';
}

function qualities(): array
{
    return ['4K', '2160P', '1080P', '720P', '其他'];
}

function netdisks(): array
{
    return [
        'quark'  => ['name' => '夸克网盘', 'short' => '夸克'],
        '115'    => ['name' => '115 网盘', 'short' => '115'],
        'aliyun' => ['name' => '阿里云盘', 'short' => '阿里'],
        'baidu'  => ['name' => '百度网盘', 'short' => '百度'],
        '123'    => ['name' => '123 云盘', 'short' => '123'],
        'xunlei' => ['name' => '迅雷云盘', 'short' => '迅雷'],
    ];
}

function netdisk_meta(string $key): array
{
    return netdisks()[$key] ?? ['name' => '其他网盘', 'short' => '网盘'];
}

function categories(): array
{
    static $cache = null;

    return $cache ??= all('SELECT * FROM categories ORDER BY sort_order, id');
}

function category_by_slug(string $slug): ?array
{
    return one('SELECT * FROM categories WHERE slug = ?', [$slug]);
}

function make_slug(string $title): string
{
    $slug = mb_strtolower(trim($title));
    $slug = (string) preg_replace('/[^\p{Han}a-z0-9]+/u', '-', $slug);
    $slug = trim($slug, '-');

    return $slug !== '' ? mb_substr($slug, 0, 100) : 'repo';
}

function post_string(string $key, int $max = 0): string
{
    $value = $_POST[$key] ?? '';
    if (!is_string($value)) {
        return '';
    }
    $value = trim($value);
    if ($max > 0) {
        $value = mb_substr($value, 0, $max);
    }

    return $value;
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function safe_next(string $next): string
{
    if ($next === '' || $next[0] !== '/' || str_starts_with($next, '//')) {
        return '/';
    }

    return $next;
}

function avatar_style(int|string $hue): string
{
    return 'background:' . avatar_color($hue);
}

function excerpt(?string $text, int $length = 120): string
{
    $text = trim((string) $text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }

    return mb_substr($text, 0, $length) . '…';
}
