<?php
/** @var string $q */
/** @var array $repos */
/** @var array $resources */
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">全站搜索</p>
        <h1><?= $q !== '' ? '搜索「' . e($q) . '」' : '搜索资源' ?></h1>
        <form class="search-page-form" action="/search" method="get" role="search">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="m16.5 16.5 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <input type="search" name="q" value="<?= e($q) ?>" placeholder="输入作品名、资源标题或标签，例如：三体 4K" autofocus>
            <button class="btn btn-primary" type="submit">搜索</button>
        </form>
        <?php if ($q === ''): ?>
            <div class="search-suggest">
                <span>热门搜索</span>
                <a href="/search?q=<?= rawurlencode('神雕侠侣') ?>">神雕侠侣</a>
                <a href="/search?q=<?= rawurlencode('三体') ?>">三体</a>
                <a href="/search?q=<?= rawurlencode('4K') ?>">4K</a>
                <a href="/search?q=<?= rawurlencode('鬼灭之刃') ?>">鬼灭之刃</a>
                <a href="/search?q=<?= rawurlencode('进击的巨人') ?>">进击的巨人</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="container page-body">
    <?php if ($q === ''): ?>
        <div class="empty-state">
            <span class="empty-icon">⌕</span>
            <h3>输入关键词开始搜索</h3>
            <p>支持作品名、资源标题、画质、标签等内容。</p>
        </div>
    <?php else: ?>
        <?php if ($repos): ?>
            <div class="list-toolbar">
                <h2 class="toolbar-title">作品仓库 <span><?= count($repos) ?></span></h2>
            </div>
            <div class="repo-grid">
                <?php foreach ($repos as $repo): ?>
                    <?= render_view('partials/repo_card', ['repo' => $repo]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($resources): ?>
            <div class="list-toolbar">
                <h2 class="toolbar-title">资源条目 <span><?= count($resources) ?></span></h2>
            </div>
            <div class="result-list">
                <?php foreach ($resources as $res): ?>
                    <a class="result-row" href="/repo/<?= (int) $res['repo_id'] ?>-<?= rawurlencode($res['repo_slug']) ?>#resources">
                        <span class="qtag <?= e(quality_class($res['quality'])) ?>"><?= e($res['quality']) ?></span>
                        <div class="result-main">
                            <strong><?= e($res['title']) ?></strong>
                            <span><?= e($res['repo_title']) ?> · <?= e($res['subtitle'] ?: '无字幕信息') ?> · <?= e($res['size'] ?: '大小未知') ?></span>
                        </div>
                        <span class="disk-pill disk-<?= e($res['netdisk']) ?>"><?= e(netdisk_meta($res['netdisk'])['short']) ?></span>
                        <span class="result-votes">热度 <?= e(heat_text($res['votes'])) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!$repos && !$resources): ?>
            <div class="empty-state">
                <span class="empty-icon">◌</span>
                <h3>没有找到与「<?= e($q) ?>」相关的内容</h3>
                <p>换个关键词试试，或者创建一个新的作品仓库。</p>
                <a class="btn btn-primary" href="/create">新建仓库</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
