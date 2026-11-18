<?php /** @var string $next */ ?>
<div class="auth-page">
    <div class="auth-visual" style="background-image:url('/assets/posters/san-ti.svg')">
        <div class="auth-visual-shade"></div>
        <div class="auth-visual-copy">
            <span class="auth-badge">WIKIPAN · 网盘资源维基</span>
            <h2>一个作品，<br>一个仓库。</h2>
            <p>把散落在各个网盘的 4K、1080P、高码版本，整理成一张清晰的资源表。</p>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="auth-card">
            <p class="eyebrow">欢迎回来</p>
            <h1>登录盘维</h1>
            <p class="auth-sub">登录后即可创建仓库、发布资源、参与投票。</p>
            <form method="post" action="/login" class="auth-form">
                <?= csrf_field() ?>
                <input type="hidden" name="next" value="<?= e($next) ?>">
                <label class="field">
                    <span>用户名或邮箱</span>
                    <input name="login" value="<?= e(old('login')) ?>" required autocomplete="username" placeholder="请输入用户名或邮箱">
                </label>
                <label class="field">
                    <span>密码</span>
                    <input type="password" name="password" required autocomplete="current-password" placeholder="请输入密码">
                </label>
                <button class="btn btn-primary btn-block" type="submit">登录</button>
            </form>
            <div class="demo-box">
                <span>体验账号</span>
                <div class="demo-row"><code>admin</code><span>/</span><code>admin1234</code><em>管理员</em></div>
                <div class="demo-row"><code>星尘资源组</code><span>/</span><code>demo1234</code><em>普通用户</em></div>
            </div>
            <p class="auth-switch">还没有账号？<a href="/register?next=<?= rawurlencode($next) ?>">立即注册</a></p>
        </div>
    </div>
</div>
