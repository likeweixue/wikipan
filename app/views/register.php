<?php /** @var string $next */ ?>
<div class="auth-page">
    <div class="auth-visual" style="background-image:url('/assets/posters/gui-mie-zhi-ren.svg')">
        <div class="auth-visual-shade"></div>
        <div class="auth-visual-copy">
            <span class="auth-badge">WIKIPAN · 网盘资源维基</span>
            <h2>整理一次，<br>方便所有人。</h2>
            <p>创建作品仓库，发布你手里的版本，让好资源不再散落。</p>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-card">
            <p class="eyebrow">加入社区</p>
            <h1>注册账号</h1>
            <p class="auth-sub">只需要一个用户名和密码，就可以开始贡献。</p>
            <form method="post" action="/register" class="auth-form">
                <?= csrf_field() ?>
                <input type="hidden" name="next" value="<?= e($next) ?>">
                <label class="field">
                    <span>用户名</span>
                    <input name="username" value="<?= e(old('username')) ?>" required maxlength="20" autocomplete="username" placeholder="2-20 位中文、字母或数字">
                </label>
                <label class="field">
                    <span>邮箱（可选）</span>
                    <input type="email" name="email" value="<?= e(old('email')) ?>" maxlength="190" autocomplete="email" placeholder="用于登录，可留空">
                </label>
                <label class="field">
                    <span>密码</span>
                    <input type="password" name="password" required minlength="6" autocomplete="new-password" placeholder="至少 6 位">
                </label>
                <button class="btn btn-primary btn-block" type="submit">创建账号</button>
            </form>
            <p class="auth-switch">已有账号？<a href="/login?next=<?= rawurlencode($next) ?>">去登录</a></p>
        </div>
    </div>
</div>
