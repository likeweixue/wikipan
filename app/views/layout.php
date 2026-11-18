<?php
/** @var string $content */
/** @var string $title */
/** @var string $description */
/** @var string $nav */
/** @var string $body_class */
$flashItems = flashes();
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <meta name="description" content="<?= e($description) ?>">
    <meta name="theme-color" content="#07070b">
    <meta name="csrf-token" content="<?= e(csrf_token()) ?>">
    <link rel="icon" type="image/svg+xml" href="/assets/img/logo.svg">
    <link rel="stylesheet" href="/assets/css/app.css?v=<?= filemtime(BASE_PATH . '/public/assets/css/app.css') ?>">
</head>
<body class="<?= e($body_class) ?>">
<?php require APP_PATH . '/views/partials/header.php'; ?>

<?php if ($flashItems): ?>
    <div class="flash-stack" id="flashStack">
        <?php foreach ($flashItems as $flash): ?>
            <div class="flash flash-<?= e($flash['type']) ?>">
                <span class="flash-dot" aria-hidden="true"></span>
                <span><?= e($flash['message']) ?></span>
                <button type="button" class="flash-close" aria-label="关闭提示">×</button>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<main class="site-main">
    <?= $content ?>
</main>

<?php require APP_PATH . '/views/partials/footer.php'; ?>

<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script src="/assets/js/app.js?v=<?= filemtime(BASE_PATH . '/public/assets/js/app.js') ?>" defer></script>
</body>
</html>
