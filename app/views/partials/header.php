<?php
$user = current_user();
$navCategories = categories();
?>
<header class="site-header" id="siteHeader">
    <div class="header-inner">
        <a class="brand" href="/" aria-label="盘维首页">
            <svg class="brand-mark" width="30" height="30" viewBox="0 0 32 32" aria-hidden="true">
                <defs>
                    <linearGradient id="brandGrad" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#ff6a3d"/>
                        <stop offset="1" stop-color="#ff2d55"/>
                    </linearGradient>
                </defs>
                <rect x="1" y="1" width="30" height="30" rx="9" fill="url(#brandGrad)"/>
                <path d="M12 10.4 23 16l-11 5.6V10.4Z" fill="#0a0a0f" opacity="0.9"/>
                <circle cx="8.2" cy="9" r="1.6" fill="#0a0a0f" opacity="0.45"/>
                <circle cx="8.2" cy="23" r="1.6" fill="#0a0a0f" opacity="0.45"/>
            </svg>
            <span class="brand-text">
                <strong>盘维</strong>
                <em>WIKIPAN</em>
            </span>
        </a>

        <nav class="main-nav" aria-label="分类导航">
            <a href="/" class="<?= $nav === 'home' ? 'is-active' : '' ?>">首页</a>
            <?php foreach ($navCategories as $category): ?>
                <a href="/browse?cat=<?= e($category['slug']) ?>"
                   class="<?= $nav === $category['slug'] ? 'is-active' : '' ?>"><?= e($category['name']) ?></a>
            <?php endforeach; ?>
        </nav>

        <div class="header-actions">
            <form class="search-box" action="/search" method="get" role="search">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/>
                    <path d="m16.5 16.5 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input type="search" name="q" value="<?= e($_GET['q'] ?? '') ?>" placeholder="搜索作品或资源" aria-label="搜索作品或资源">
            </form>

            <?php if ($user): ?>
                <a class="btn btn-primary btn-sm header-create" href="/create">＋ 新建仓库</a>
                <details class="user-menu">
                    <summary>
                        <span class="avatar avatar-sm" style="<?= e(avatar_style($user['avatar_hue'])) ?>"><?= e(first_char($user['username'])) ?></span>
                        <span class="user-name"><?= e($user['username']) ?></span>
                    </summary>
                    <div class="user-dropdown">
                        <a href="/me">我的贡献</a>
                        <a href="/create">新建仓库</a>
                        <form method="post" action="/logout">
                            <?= csrf_field() ?>
                            <button type="submit">退出登录</button>
                        </form>
                    </div>
                </details>
            <?php else: ?>
                <a class="btn btn-ghost btn-sm" href="/login">登录</a>
                <a class="btn btn-primary btn-sm" href="/register">注册</a>
            <?php endif; ?>
        </div>
    </div>
</header>
