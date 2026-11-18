<?php
/** @var int $page */
/** @var int $pages */
$query = $_GET;
$link = static function (int $target) use ($query): string {
    $query['page'] = $target;

    return '?' . http_build_query($query);
};
$start = max(1, $page - 2);
$end = min($pages, $start + 4);
$start = max(1, $end - 4);
?>
<?php if ($pages > 1): ?>
    <nav class="pagination" aria-label="分页">
        <a class="page-btn <?= $page <= 1 ? 'is-disabled' : '' ?>" href="<?= $page <= 1 ? '#' : e($link($page - 1)) ?>">上一页</a>
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <a class="page-btn <?= $i === $page ? 'is-active' : '' ?>" href="<?= e($link($i)) ?>"><?= $i ?></a>
        <?php endfor; ?>
        <a class="page-btn <?= $page >= $pages ? 'is-disabled' : '' ?>" href="<?= $page >= $pages ? '#' : e($link($page + 1)) ?>">下一页</a>
    </nav>
<?php endif; ?>
