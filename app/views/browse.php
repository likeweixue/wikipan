<?php
/** @var array|null $category */
/** @var array $items */
/** @var int $total */
/** @var int $page */
/** @var int $pages */
/** @var string $sort */
/** @var string $heading */
$sortLink = static function (string $mode) use ($category): string {
    $query = ['sort' => $mode];
    if ($category) {
        $query['cat'] = $category['slug'];
    }

    return '/browse?' . http_build_query($query);
};
?>
<section class="page-hero">
    <div class="container">
        <p class="eyebrow">资源仓库 · <?= $category ? e($category['name']) : '全部' ?></p>
        <h1><?= e($heading) ?></h1>
        <p class="page-sub">按作品聚合全部网盘版本，共收录 <?= (int) $total ?> 个仓库</p>
    </div>
</section>

<div class="container page-body">
    <div class="list-toolbar">
        <div class="sort-tabs">
            <a href="<?= e($sortLink('heat')) ?>" class="<?= $sort === 'heat' ? 'is-active' : '' ?>">最热</a>
            <a href="<?= e($sortLink('new')) ?>" class="<?= $sort === 'new' ? 'is-active' : '' ?>">最新</a>
        </div>
        <span class="result-count"><?= (int) $total ?> 个仓库</span>
    </div>

    <?php if ($items): ?>
        <div class="repo-grid">
            <?php foreach ($items as $repo): ?>
                <?= render_view('partials/repo_card', ['repo' => $repo]) ?>
            <?php endforeach; ?>
        </div>
        <?php require APP_PATH . '/views/partials/pagination.php'; ?>
    <?php else: ?>
        <div class="empty-state">
            <span class="empty-icon">◍</span>
            <h3>这个分类还没有仓库</h3>
            <p>成为第一个整理者，把散落的资源归档成一个仓库。</p>
            <a class="btn btn-primary" href="/create">新建仓库</a>
        </div>
    <?php endif; ?>
</div>
