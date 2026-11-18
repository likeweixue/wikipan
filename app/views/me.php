<?php
/** @var array $user */
/** @var array $myRepos */
/** @var array $myResources */
/** @var int $votesReceived */
?>
<section class="profile-hero">
    <div class="container profile-head">
        <span class="avatar avatar-xl" style="<?= e(avatar_style($user['avatar_hue'])) ?>"><?= e(first_char($user['username'])) ?></span>
        <div class="profile-main">
            <p class="eyebrow">我的贡献</p>
            <h1><?= e($user['username']) ?><?php if ($user['role'] === 'admin'): ?> <span class="role-badge">管理员</span><?php endif; ?></h1>
            <p class="profile-sub">加入于 <?= e(date('Y-m-d', strtotime($user['created_at']))) ?> · 一个作品一个仓库</p>
        </div>
        <div class="profile-stats">
            <div><strong><?= count($myRepos) ?></strong><span>创建的仓库</span></div>
            <div><strong><?= count($myResources) ?></strong><span>发布的资源</span></div>
            <div><strong><?= (int) $votesReceived ?></strong><span>获得的顶</span></div>
        </div>
        <a class="btn btn-primary" href="/create">＋ 新建仓库</a>
    </div>
</section>

<div class="container page-body">
    <div class="panel">
        <div class="panel-head">
            <div>
                <h2>我创建的仓库</h2>
                <p>仓库被删除时，其中的资源会一并移除。</p>
            </div>
        </div>
        <?php if ($myRepos): ?>
            <div class="manage-list">
                <?php foreach ($myRepos as $repo): ?>
                    <div class="manage-row">
                        <div class="manage-thumb<?= empty($repo['poster']) ? ' no-poster' : '' ?>"<?php if (!empty($repo['poster'])): ?> style="background-image:url('<?= e($repo['poster']) ?>')"<?php endif; ?>></div>
                        <div class="manage-main">
                            <a href="<?= e(repo_url($repo)) ?>"><?= e($repo['title']) ?></a>
                            <span><?= e($repo['category_name']) ?> · <?= (int) $repo['resource_count'] ?> 个资源 · 热度 <?= e(heat_text($repo['heat'])) ?> · <?= e(time_ago($repo['created_at'])) ?>创建</span>
                        </div>
                        <a class="btn-mini btn-mini-ghost" href="<?= e(repo_url($repo)) ?>">查看</a>
                        <form method="post" action="/repo/<?= (int) $repo['id'] ?>/delete" data-confirm="确定删除仓库「<?= e($repo['title']) ?>」及其全部资源？">
                            <?= csrf_field() ?>
                            <button class="btn-mini btn-mini-danger" type="submit">删除</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="panel-empty">还没有创建仓库。<a href="/create">创建第一个 →</a></p>
        <?php endif; ?>
    </div>

    <div class="panel">
        <div class="panel-head">
            <div>
                <h2>我发布的资源</h2>
                <p>共 <?= count($myResources) ?> 条，删除后热度会同步扣减。</p>
            </div>
        </div>
        <?php if ($myResources): ?>
            <div class="table-wrap">
                <table class="res-table compact">
                    <thead>
                    <tr>
                        <th>画质</th>
                        <th>资源</th>
                        <th>所属仓库</th>
                        <th>网盘</th>
                        <th>热度</th>
                        <th>时间</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($myResources as $res): ?>
                        <tr>
                            <td><span class="qtag <?= e(quality_class($res['quality'])) ?>"><?= e($res['quality']) ?></span></td>
                            <td class="cell-title"><div class="res-title"><?= e($res['title']) ?></div></td>
                            <td><a class="text-link" href="/repo/<?= (int) $res['repo_id'] ?>-<?= rawurlencode($res['repo_slug']) ?>"><?= e($res['repo_title']) ?></a></td>
                            <td><span class="disk-pill disk-<?= e($res['netdisk']) ?>"><?= e(netdisk_meta($res['netdisk'])['short']) ?></span></td>
                            <td class="cell-num"><?= (int) $res['votes'] ?></td>
                            <td class="cell-time"><?= e(time_ago($res['created_at'])) ?></td>
                            <td>
                                <form method="post" action="/resource/<?= (int) $res['id'] ?>/delete" data-confirm="确定删除这条资源？">
                                    <?= csrf_field() ?>
                                    <button class="btn-mini btn-mini-danger" type="submit">删除</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="panel-empty">还没有发布资源。<a href="/browse">去仓库里贡献一条 →</a></p>
        <?php endif; ?>
    </div>
</div>
