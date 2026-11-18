<?php /** @var array $repo */ ?>
<?php $rank = $rank ?? null; ?>
<a class="repo-card<?= $rank ? ' rank-card' : '' ?>" href="<?= e(repo_url($repo)) ?>">
    <?php if ($rank): ?>
        <span class="rank-number" aria-hidden="true"><?= (int) $rank ?></span>
    <?php endif; ?>
    <div class="repo-thumb<?= empty($repo['poster']) ? ' no-poster' : '' ?>"
        <?php if (!empty($repo['poster'])): ?>style="background-image:url('<?= e($repo['poster']) ?>')"<?php endif; ?>>
        <span class="repo-cat"><?= e($repo['category_name']) ?></span>
        <div class="repo-shade"></div>
        <div class="repo-hover">
            <div class="repo-hover-top">
                <span class="mini-play" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><path d="M8 5.5 19 12 8 18.5v-13Z" fill="currentColor"/></svg>
                </span>
                <span class="mini-badge"><?= (int) $repo['resource_count'] ?> 个资源</span>
            </div>
            <p><?= e(excerpt($repo['description'], 64)) ?></p>
            <span class="repo-hover-link">查看仓库 <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
        </div>
    </div>
    <div class="repo-info">
        <h3><?= e($repo['title']) ?></h3>
        <p>
            <?php if (!empty($repo['year'])): ?><?= (int) $repo['year'] ?> · <?php endif; ?>
            <?= e($repo['category_name']) ?> · <?= (int) $repo['resource_count'] ?> 个资源 · 热度 <?= e(heat_text($repo['heat'])) ?>
        </p>
    </div>
</a>
