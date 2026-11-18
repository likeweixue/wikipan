<?php
/** @var array $cats */
?>
<section class="page-hero compact">
    <div class="container">
        <p class="eyebrow">社区贡献</p>
        <h1>新建资源仓库</h1>
        <p class="page-sub">一个作品一个仓库，后续所有版本都发布在仓库下面。</p>
    </div>
</section>

<div class="container form-layout">
    <form class="panel form-panel" method="post" action="/create" novalidate>
        <?= csrf_field() ?>
        <div class="form-grid">
            <label class="field span-2">
                <span>作品名称 <i>*</i></span>
                <input name="title" value="<?= e(old('title')) ?>" required maxlength="60" placeholder="例如：神雕侠侣">
                <em>仓库名称就是作品名，资源条目会挂在这个仓库下面。</em>
            </label>
            <label class="field span-2">
                <span>原名 / 英文名</span>
                <input name="original_title" value="<?= e(old('original_title')) ?>" maxlength="160" placeholder="例如：The Return of the Condor Heroes">
            </label>
            <label class="field">
                <span>分类 <i>*</i></span>
                <select name="category_id" required>
                    <option value="">请选择分类</option>
                    <?php foreach ($cats as $cat): ?>
                        <option value="<?= (int) $cat['id'] ?>" <?= old('category_id') === (string) $cat['id'] ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span>年份</span>
                <input type="number" name="year" value="<?= e(old('year')) ?>" min="1900" max="<?= date('Y') + 2 ?>" placeholder="例如：2006">
            </label>
            <label class="field span-2">
                <span>标签</span>
                <input name="tags" value="<?= e(old('tags')) ?>" maxlength="120" placeholder="用英文逗号分隔，例如：武侠,古装,金庸,经典">
            </label>
            <label class="field span-2">
                <span>海报地址</span>
                <input name="poster" value="<?= e(old('poster')) ?>" maxlength="500" placeholder="https://… 或 /assets/posters/xxx.svg（留空使用渐变封面）">
            </label>
            <label class="field span-2">
                <span>仓库简介 <i>*</i></span>
                <textarea name="description" rows="5" required maxlength="2000" placeholder="介绍这部作品收录了哪些版本，方便其他人了解仓库内容。"><?= e(old('description')) ?></textarea>
                <em>至少 10 个字。</em>
            </label>
        </div>
        <div class="form-actions">
            <a class="btn btn-ghost" href="/">取消</a>
            <button class="btn btn-primary" type="submit">创建仓库</button>
        </div>
    </form>

    <aside class="form-aside">
        <div class="aside-card">
            <h3>仓库里放什么？</h3>
            <ul>
                <li><strong>一个作品</strong>：剧集、电影、动漫、纪录片都行。</li>
                <li><strong>多个版本</strong>：4K、1080P、高码、国粤双语……</li>
                <li><strong>网盘链接</strong>：夸克、115、阿里、百度、123 均可。</li>
            </ul>
        </div>
        <div class="aside-card muted">
            <h3>示例</h3>
            <p class="example-repo">神雕侠侣</p>
            <div class="example-row"><span class="qtag q-4k">4K</span><span>4K 修复版 · 全40集 · 186.4 GB</span></div>
            <div class="example-row"><span class="qtag q-1080">1080P</span><span>1080P 国粤双语 · 全40集 · 92.6 GB</span></div>
            <div class="example-row"><span class="qtag q-other">720P</span><span>720P 精简版 · 全40集 · 38.2 GB</span></div>
        </div>
    </aside>
</div>
