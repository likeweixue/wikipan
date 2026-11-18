<?php
/** @var array $featured */
/** @var array $top */
/** @var array $rows */
/** @var array $latest */
/** @var array $stats */
?>
<?php if ($featured): ?>
    <section class="hero" id="hero" data-hero>
        <?php foreach ($featured as $index => $repo): ?>
            <article class="hero-slide<?= $index === 0 ? ' is-active' : '' ?>" data-hero-slide>
                <div class="hero-bg<?= empty($repo['poster']) ? ' no-poster' : '' ?>"<?php if (!empty($repo['poster'])): ?> style="background-image:url('<?= e($repo['poster']) ?>')"<?php endif; ?>></div>
                <div class="hero-overlay"></div>
                <div class="hero-content container">
                    <p class="hero-kicker"><span class="pulse-dot"></span>本周精选 · <?= e($repo['category_name']) ?></p>
                    <h1 class="hero-title"><?= e($repo['title']) ?></h1>
                    <div class="hero-meta">
                        <?php if (!empty($repo['year'])): ?><span><?= (int) $repo['year'] ?></span><?php endif; ?>
                        <span><?= e($repo['category_name']) ?></span>
                        <span><?= (int) $repo['resource_count'] ?> 个资源</span>
                        <span class="heat">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2c1.6 3.4 1 5.6-.4 7.2-1.2 1.4-1.4 2.7-.4 4 .9 1.2 2.4 1 3-.3.5-1 .4-2-.2-3 3 2 4.5 4.2 4.5 6.6A6.5 6.5 0 0 1 12 22a6.5 6.5 0 0 1-6.5-6.5c0-3.7 2.7-6.6 4.4-8.9C11 4.8 11.7 3.2 12 2Z" fill="currentColor"/></svg>
                            <?= e(heat_text($repo['heat'])) ?>
                        </span>
                    </div>
                    <p class="hero-desc"><?= e(excerpt($repo['description'], 140)) ?></p>
                    <div class="hero-actions">
                        <a class="btn btn-primary btn-lg" href="<?= e(repo_url($repo)) ?>">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5.5 19 12 8 18.5v-13Z" fill="currentColor"/></svg>
                            进入仓库
                        </a>
                        <a class="btn btn-glass btn-lg" href="<?= e(repo_url($repo)) ?>#resources">查看 <?= (int) $repo['resource_count'] ?> 个资源</a>
                    </div>
                    <div class="hero-tags">
                        <?php foreach (array_slice(array_filter(array_map('trim', explode(',', (string) $repo['tags']))), 0, 4) as $tag): ?>
                            <span class="tag"><?= e($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="hero-controls">
            <button type="button" class="hero-arrow" data-hero-prev aria-label="上一个精选">
                <svg viewBox="0 0 24 24"><path d="m14 6-6 6 6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="hero-dots" role="tablist" aria-label="精选轮播">
                <?php foreach ($featured as $index => $repo): ?>
                    <button type="button" class="hero-dot<?= $index === 0 ? ' is-active' : '' ?>" data-hero-dot="<?= $index ?>" aria-label="第 <?= $index + 1 ?> 个精选：<?= e($repo['title']) ?>"></button>
                <?php endforeach; ?>
            </div>
            <button type="button" class="hero-arrow" data-hero-next aria-label="下一个精选">
                <svg viewBox="0 0 24 24"><path d="m10 6 6 6-6 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>
    </section>
<?php endif; ?>

<section class="home-stats container">
    <div class="stat-item">
        <strong><?= (int) $stats['repos'] ?></strong>
        <span>个作品仓库</span>
    </div>
    <div class="stat-item">
        <strong><?= (int) $stats['resources'] ?></strong>
        <span>条网盘资源</span>
    </div>
    <div class="stat-item">
        <strong><?= (int) $stats['users'] ?></strong>
        <span>位分享者</span>
    </div>
    <p class="stats-note">一个作品一个仓库，所有画质版本集中归档，由社区共同维护。</p>
</section>

<section class="row-section container">
    <div class="row-head">
        <div>
            <h2>热播仓库 <span class="row-sub">TOP 10</span></h2>
            <p>按社区热度排序，看看大家都在转存什么</p>
        </div>
        <a class="row-more" href="/browse?sort=heat">查看全部
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    <div class="row-scroller" data-row>
        <?php foreach ($top as $index => $repo): ?>
            <?= render_view('partials/repo_card', ['repo' => $repo, 'rank' => $index + 1]) ?>
        <?php endforeach; ?>
    </div>
</section>

<section class="row-section container">
    <div class="row-head">
        <div>
            <h2>最新上架</h2>
            <p>刚刚入库的资源版本，按发布时间排序</p>
        </div>
        <a class="row-more" href="/browse?sort=new">更多资源
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
    <div class="row-scroller" data-row>
        <?php foreach ($latest as $res): ?>
            <a class="res-card" href="/repo/<?= (int) $res['repo_id'] ?>-<?= rawurlencode($res['repo_slug']) ?>">
                <div class="res-thumb<?= empty($res['repo_poster']) ? ' no-poster' : '' ?>"<?php if (!empty($res['repo_poster'])): ?> style="background-image:url('<?= e($res['repo_poster']) ?>')"<?php endif; ?>>
                    <span class="qtag <?= e(quality_class($res['quality'])) ?>"><?= e($res['quality']) ?></span>
                    <span class="disk-pill disk-<?= e($res['netdisk']) ?>"><?= e(netdisk_meta($res['netdisk'])['short']) ?></span>
                </div>
                <div class="res-info">
                    <h4><?= e($res['repo_title']) ?></h4>
                    <p><?= e($res['title']) ?></p>
                    <div class="res-meta">
                        <span><?= e($res['size'] ?: '—') ?></span>
                        <span><?= e($res['episodes'] ?: '正片') ?></span>
                        <span><?= e(time_ago($res['created_at'])) ?></span>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php foreach ($rows as $row): ?>
    <section class="row-section container">
        <div class="row-head">
            <div>
                <h2><?= e($row['category']['name']) ?></h2>
                <p>共 <?= count($row['items']) ?> 个作品仓库，持续更新中</p>
            </div>
            <a class="row-more" href="/browse?cat=<?= e($row['category']['slug']) ?>">查看全部
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <div class="row-scroller" data-row>
            <?php foreach ($row['items'] as $repo): ?>
                <?= render_view('partials/repo_card', ['repo' => $repo]) ?>
            <?php endforeach; ?>
        </div>
    </section>
<?php endforeach; ?>

<section class="cta-section container">
    <div class="cta-card">
        <div class="cta-copy">
            <p class="eyebrow">共同维护 · 自由归档</p>
            <h2>你的收藏，也值得一个仓库</h2>
            <p>创建作品仓库，把不同画质、不同网盘的版本整理到一起。别人只需找到这一部作品，就能看到全部资源。</p>
            <div class="cta-actions">
                <?php if (current_user()): ?>
                    <a class="btn btn-primary btn-lg" href="/create">新建资源仓库</a>
                <?php else: ?>
                    <a class="btn btn-primary btn-lg" href="/register">注册并开始</a>
                    <a class="btn btn-glass btn-lg" href="/login">已有账号，登录</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="cta-steps">
            <div class="step"><span>01</span><div><strong>建仓库</strong><p>用作品名创建一个仓库，填写简介与标签。</p></div></div>
            <div class="step"><span>02</span><div><strong>发资源</strong><p>按 4K / 1080P 等版本发布网盘链接与提取码。</p></div></div>
            <div class="step"><span>03</span><div><strong>等热度</strong><p>社区转存与投票，优质版本自动排在前面。</p></div></div>
        </div>
    </div>
</section>
