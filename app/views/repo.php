<?php
/** @var array $repo */
/** @var array $resources */
/** @var array|null $creator */
/** @var array $votedIds */
/** @var int $totalVotes */
/** @var array $related */
$user = current_user();
$canManage = $user && ((int) $user['id'] === (int) $repo['created_by'] || $user['role'] === 'admin');
$nextUrl = rawurlencode($_SERVER['REQUEST_URI'] ?? '/');
$poster = (string) ($repo['poster'] ?? '');
?>
<section class="detail-hero">
    <div class="detail-bg<?= $poster === '' ? ' no-poster' : '' ?>"<?php if ($poster !== ''): ?> style="background-image:url('<?= e($poster) ?>')"<?php endif; ?>></div>
    <div class="detail-overlay"></div>
    <div class="detail-inner container">
        <div class="detail-poster<?= $poster === '' ? ' no-poster' : '' ?>"<?php if ($poster !== ''): ?> style="background-image:url('<?= e($poster) ?>')"<?php endif; ?>>
            <span class="poster-tag">仓库</span>
        </div>
        <div class="detail-info">
            <nav class="crumbs" aria-label="面包屑">
                <a href="/">首页</a><span>/</span>
                <a href="/browse?cat=<?= e($repo['category_slug']) ?>"><?= e($repo['category_name']) ?></a><span>/</span>
                <em><?= e($repo['title']) ?></em>
            </nav>
            <h1><?= e($repo['title']) ?><?php if (!empty($repo['year'])): ?> <span class="detail-year"><?= (int) $repo['year'] ?></span><?php endif; ?></h1>
            <?php if (!empty($repo['original_title'])): ?>
                <p class="detail-original"><?= e($repo['original_title']) ?></p>
            <?php endif; ?>
            <div class="detail-meta">
                <span class="pill-cat"><?= e($repo['category_name']) ?></span>
                <span><strong><?= (int) $repo['resource_count'] ?></strong> 个资源</span>
                <span>总热度 <strong><?= e(heat_text($totalVotes)) ?></strong></span>
                <span>更新于 <?= e(time_ago($repo['last_resource_at'])) ?></span>
            </div>
            <p class="detail-desc"><?= nl2br(e($repo['description'])) ?></p>
            <?php $tagList = array_slice(array_filter(array_map('trim', explode(',', (string) $repo['tags']))), 0, 6); ?>
            <?php if ($tagList): ?>
                <div class="detail-tags">
                    <?php foreach ($tagList as $tag): ?><span class="tag"><?= e($tag) ?></span><?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="detail-actions">
                <?php if ($user): ?>
                    <button type="button" class="btn btn-primary" data-open-modal="publishModal">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        发布资源
                    </button>
                <?php else: ?>
                    <a class="btn btn-primary" href="/login?next=<?= $nextUrl ?>">登录后发布资源</a>
                <?php endif; ?>
                <a class="btn btn-glass" href="#resources">浏览资源表</a>
                <?php if ($canManage): ?>
                    <form method="post" action="/repo/<?= (int) $repo['id'] ?>/delete" class="inline-form" data-confirm="删除仓库会同时删除其中全部资源，确定继续？">
                        <?= csrf_field() ?>
                        <button class="btn btn-danger-ghost" type="submit">删除仓库</button>
                    </form>
                <?php endif; ?>
            </div>
            <?php if ($creator): ?>
                <p class="detail-creator">
                    由 <span class="avatar avatar-xs" style="<?= e(avatar_style($creator['avatar_hue'])) ?>"><?= e(first_char($creator['username'])) ?></span>
                    <strong><?= e($creator['username']) ?></strong> 创建 · 收录 <?= (int) $repo['resource_count'] ?> 个版本
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="container section" id="resources">
    <div class="section-head">
        <div>
            <h2>仓库资源 <span class="row-sub"><?= count($resources) ?> 条</span></h2>
            <p>按社区热度排序，支持按画质筛选、标题搜索与重新排序</p>
        </div>
        <?php if ($user): ?>
            <button type="button" class="btn btn-primary btn-sm" data-open-modal="publishModal">＋ 发布新资源</button>
        <?php endif; ?>
    </div>

    <?php if ($resources): ?>
        <div class="table-tools">
            <div class="chips" data-quality-filter>
                <button type="button" class="chip is-active" data-filter="all">全部</button>
                <button type="button" class="chip" data-filter="4k">4K / 2160P</button>
                <button type="button" class="chip" data-filter="1080p">1080P</button>
                <button type="button" class="chip" data-filter="720p">720P</button>
            </div>
            <div class="tools-right">
                <label class="table-search">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2"/><path d="m16.5 16.5 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                    <input type="search" id="resourceSearch" placeholder="筛选资源标题…" aria-label="筛选资源标题">
                </label>
                <label class="table-sort">
                    <span>排序</span>
                    <select id="resourceSort" aria-label="资源排序">
                        <option value="votes">热度优先</option>
                        <option value="time">最新发布</option>
                        <option value="size">体积从大到小</option>
                    </select>
                </label>
            </div>
        </div>

        <div class="table-wrap">
            <table class="res-table" id="resourceTable" data-resource-table>
                <thead>
                <tr>
                    <th class="col-q">画质</th>
                    <th class="col-title">资源名称</th>
                    <th class="col-disk">网盘</th>
                    <th class="col-size">大小</th>
                    <th class="col-sub">字幕 / 音轨</th>
                    <th class="col-ep">集数</th>
                    <th class="col-vote">热度</th>
                    <th class="col-user">发布者</th>
                    <th class="col-time">时间</th>
                    <th class="col-act">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($resources as $res): ?>
                    <?php $isVoted = in_array((int) $res['id'], $votedIds, true); ?>
                    <tr data-quality="<?= e($res['quality']) ?>" data-netdisk="<?= e($res['netdisk']) ?>"
                        data-title="<?= e(mb_strtolower($res['title'])) ?>"
                        data-votes="<?= (int) $res['votes'] ?>"
                        data-time="<?= (int) strtotime($res['created_at']) ?>"
                        data-size="<?= e($res['size'] ?: '') ?>">
                        <td><span class="qtag <?= e(quality_class($res['quality'])) ?>"><?= e($res['quality']) ?></span></td>
                        <td class="cell-title">
                            <div class="res-title"><?= e($res['title']) ?></div>
                            <?php if (!empty($res['note'])): ?><div class="res-note"><?= e($res['note']) ?></div><?php endif; ?>
                        </td>
                        <td>
                            <span class="disk-pill disk-<?= e($res['netdisk']) ?>"><?= e(netdisk_meta($res['netdisk'])['name']) ?></span>
                            <?php if (!empty($res['access_code'])): ?><div class="res-code">提取码 <?= e($res['access_code']) ?></div><?php endif; ?>
                        </td>
                        <td class="cell-num"><?= e($res['size'] ?: '—') ?></td>
                        <td class="cell-sub"><?= e($res['subtitle'] ?: '—') ?></td>
                        <td class="cell-sub"><?= e($res['episodes'] ?: '正片') ?></td>
                        <td>
                            <button type="button" class="vote-btn<?= $isVoted ? ' is-voted' : '' ?>" data-vote-id="<?= (int) $res['id'] ?>" aria-pressed="<?= $isVoted ? 'true' : 'false' ?>" title="有用，顶一下">
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2c1.6 3.4 1 5.6-.4 7.2-1.2 1.4-1.4 2.7-.4 4 .9 1.2 2.4 1 3-.3.5-1 .4-2-.2-3 3 2 4.5 4.2 4.5 6.6A6.5 6.5 0 0 1 12 22a6.5 6.5 0 0 1-6.5-6.5c0-3.7 2.7-6.6 4.4-8.9C11 4.8 11.7 3.2 12 2Z" fill="currentColor"/></svg>
                                <span data-vote-count><?= (int) $res['votes'] ?></span>
                            </button>
                        </td>
                        <td>
                            <span class="publisher">
                                <span class="avatar avatar-xs" style="<?= e(avatar_style($res['avatar_hue'])) ?>"><?= e(first_char($res['username'])) ?></span>
                                <?= e($res['username']) ?>
                            </span>
                        </td>
                        <td class="cell-time"><?= e(time_ago($res['created_at'])) ?></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" class="btn-mini" data-copy data-url="<?= e($res['share_url']) ?>" data-code="<?= e($res['access_code'] ?? '') ?>">
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="11" height="11" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M15 5.5A1.5 1.5 0 0 0 13.5 4H6a2 2 0 0 0-2 2v8.5A1.5 1.5 0 0 0 5.5 16" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                                    复制
                                </button>
                                <a class="btn-mini btn-mini-ghost" href="<?= e($res['share_url']) ?>" target="_blank" rel="noopener nofollow">
                                    打开
                                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p class="table-empty" data-table-empty hidden>没有符合条件的资源，试试其他画质或关键词。</p>
    <?php else: ?>
        <div class="empty-state">
            <span class="empty-icon">◍</span>
            <h3>这个仓库还没有资源</h3>
            <p>第一个版本由你来补齐：4K、1080P、高码收藏版都可以。</p>
            <?php if ($user): ?>
                <button type="button" class="btn btn-primary" data-open-modal="publishModal">发布第一个资源</button>
            <?php else: ?>
                <a class="btn btn-primary" href="/login?next=<?= $nextUrl ?>">登录后发布</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($related): ?>
    <section class="row-section container">
        <div class="row-head">
            <div>
                <h2>同类仓库</h2>
                <p>你可能也在找这些作品</p>
            </div>
            <a class="row-more" href="/browse?cat=<?= e($repo['category_slug']) ?>">查看全部
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <div class="row-scroller" data-row>
            <?php foreach ($related as $item): ?>
                <?= render_view('partials/repo_card', ['repo' => $item]) ?>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php if ($user): ?>
    <dialog class="modal" id="publishModal">
        <form method="post" action="/repo/<?= (int) $repo['id'] ?>/resources" class="modal-card">
            <?= csrf_field() ?>
            <header class="modal-head">
                <div>
                    <p class="eyebrow">向「<?= e($repo['title']) ?>」提交资源</p>
                    <h3>发布网盘资源</h3>
                </div>
                <button type="button" class="modal-close" data-close-modal aria-label="关闭">×</button>
            </header>
            <div class="modal-body">
                <label class="field span-2">
                    <span>资源标题 <i>*</i></span>
                    <input name="title" value="<?= e(old('title')) ?>" required maxlength="200" placeholder="例如：神雕侠侣 2006 4K 修复版 全40集 国语中字">
                </label>
                <label class="field">
                    <span>画质</span>
                    <select name="quality">
                        <?php foreach (qualities() as $qualityOption): ?>
                            <option value="<?= e($qualityOption) ?>" <?= old('quality', '4K') === $qualityOption ? 'selected' : '' ?>><?= e($qualityOption) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="field">
                    <span>网盘</span>
                    <select name="netdisk">
                        <?php foreach (netdisks() as $key => $disk): ?>
                            <option value="<?= e($key) ?>" <?= old('netdisk', 'quark') === $key ? 'selected' : '' ?>><?= e($disk['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="field span-2">
                    <span>分享链接 <i>*</i></span>
                    <input name="share_url" value="<?= e(old('share_url')) ?>" required maxlength="500" placeholder="https://pan.quark.cn/s/xxxxxx">
                </label>
                <label class="field">
                    <span>提取码</span>
                    <input name="access_code" value="<?= e(old('access_code')) ?>" maxlength="16" placeholder="例如 8fs3">
                </label>
                <label class="field">
                    <span>文件大小</span>
                    <input name="size" value="<?= e(old('size')) ?>" maxlength="24" placeholder="例如 186.4 GB">
                </label>
                <label class="field">
                    <span>字幕 / 音轨</span>
                    <input name="subtitle" value="<?= e(old('subtitle')) ?>" maxlength="120" placeholder="例如 国语 / 简繁中字">
                </label>
                <label class="field">
                    <span>集数</span>
                    <input name="episodes" value="<?= e(old('episodes')) ?>" maxlength="24" placeholder="例如 全40集">
                </label>
                <label class="field span-2">
                    <span>补充说明</span>
                    <input name="note" value="<?= e(old('note')) ?>" maxlength="255" placeholder="可选，例如 H265 编码 / 杜比视界 / 无删减">
                </label>
            </div>
            <footer class="modal-foot">
                <span class="modal-hint">发布后立即出现在资源表中，可按热度排序</span>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-ghost btn-sm" data-close-modal>取消</button>
                    <button type="submit" class="btn btn-primary btn-sm">发布资源</button>
                </div>
            </footer>
        </form>
    </dialog>
<?php endif; ?>
