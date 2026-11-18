<?php
declare(strict_types=1);

function action_login(): void
{
    if (!verify_csrf()) {
        csrf_fail();
    }

    $login = post_string('login', 190);
    $password = (string) ($_POST['password'] ?? '');
    $next = safe_next((string) ($_POST['next'] ?? '/'));

    if ($login === '' || $password === '') {
        keep_old(['login' => $login, 'next' => $next]);
        flash('error', '请输入账号与密码');
        redirect('/login');
    }

    if (!auth_attempt($login, $password)) {
        keep_old(['login' => $login, 'next' => $next]);
        flash('error', '账号或密码不正确');
        redirect('/login');
    }

    flash('success', '欢迎回来，' . current_user()['username']);
    redirect($next);
}

function action_register(): void
{
    if (!verify_csrf()) {
        csrf_fail();
    }

    $username = post_string('username', 20);
    $email = post_string('email', 190);
    $password = (string) ($_POST['password'] ?? '');
    $next = safe_next((string) ($_POST['next'] ?? '/'));

    $old = ['username' => $username, 'email' => $email, 'next' => $next];
    $errors = [];

    if (!preg_match('/^[\p{Han}a-zA-Z0-9_\-]{2,20}$/u', $username)) {
        $errors[] = '用户名需为 2-20 位中文、字母、数字或下划线';
    }
    if (mb_strlen($password) < 6) {
        $errors[] = '密码至少 6 位';
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = '邮箱格式不正确';
    }
    if (!$errors && one('SELECT id FROM users WHERE username = ?', [$username])) {
        $errors[] = '该用户名已被占用';
    }
    if (!$errors && $email !== '' && one('SELECT id FROM users WHERE email = ?', [$email])) {
        $errors[] = '该邮箱已被注册';
    }

    if ($errors) {
        keep_old($old);
        flash('error', implode('；', $errors));
        redirect('/register');
    }

    q(
        'INSERT INTO users (username, email, password_hash, role, avatar_hue) VALUES (?, ?, ?, ?, ?)',
        [$username, $email !== '' ? $email : null, password_hash($password, PASSWORD_DEFAULT), 'user', random_int(0, 359)]
    );

    $user = one('SELECT * FROM users WHERE id = ?', [(int) db()->lastInsertId()]);
    auth_login_user($user);

    flash('success', '注册成功，开始分享你的第一个资源吧');
    redirect($next);
}

function action_logout(): void
{
    if (!verify_csrf()) {
        csrf_fail();
    }

    auth_logout();
    session_start();
    flash('success', '已退出登录');
    redirect('/');
}

function action_create_repo(): void
{
    $user = require_login();
    if (!verify_csrf()) {
        csrf_fail();
    }

    $title = post_string('title', 60);
    $originalTitle = post_string('original_title', 160);
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $year = (int) ($_POST['year'] ?? 0);
    $description = post_string('description', 2000);
    $tags = post_string('tags', 120);
    $poster = post_string('poster', 500);

    $old = [
        'title'          => $title,
        'original_title' => $originalTitle,
        'category_id'    => (string) $categoryId,
        'year'           => $year > 0 ? (string) $year : '',
        'description'    => $description,
        'tags'           => $tags,
        'poster'         => $poster,
    ];

    $errors = [];
    if (mb_strlen($title) < 2) {
        $errors[] = '作品名称至少 2 个字';
    }
    $category = one('SELECT * FROM categories WHERE id = ?', [$categoryId]);
    if (!$category) {
        $errors[] = '请选择有效分类';
    }
    if ($year !== 0 && ($year < 1900 || $year > (int) date('Y') + 2)) {
        $errors[] = '年份不合法';
    }
    if (mb_strlen($description) < 10) {
        $errors[] = '仓库简介至少 10 个字';
    }
    if ($poster !== '' && !preg_match('#^(https?://|/)#i', $poster)) {
        $errors[] = '海报地址需以 http(s):// 或 / 开头';
    }

    if ($errors) {
        keep_old($old);
        flash('error', implode('；', $errors));
        redirect('/create');
    }

    $baseSlug = make_slug($title);
    $slug = $baseSlug;
    $i = 2;
    while (one('SELECT id FROM repositories WHERE slug = ?', [$slug])) {
        $slug = $baseSlug . '-' . $i++;
    }

    q(
        'INSERT INTO repositories (slug, title, original_title, category_id, year, description, poster, tags, created_by)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $slug,
            $title,
            $originalTitle !== '' ? $originalTitle : null,
            $categoryId,
            $year > 0 ? $year : null,
            $description,
            $poster !== '' ? $poster : null,
            $tags !== '' ? $tags : null,
            (int) $user['id'],
        ]
    );

    $id = (int) db()->lastInsertId();
    flash('success', '仓库「' . $title . '」创建成功，现在去发布第一个资源吧');
    redirect(repo_url(['id' => $id, 'slug' => $slug]));
}

function action_add_resource(int $repoId): void
{
    $user = require_login();
    if (!verify_csrf()) {
        csrf_fail();
    }

    $repo = repo_find($repoId);
    if (!$repo) {
        page_not_found();
    }

    $title = post_string('title', 200);
    $quality = post_string('quality', 16);
    $netdisk = post_string('netdisk', 16);
    $shareUrl = post_string('share_url', 500);
    $accessCode = post_string('access_code', 16);
    $size = post_string('size', 24);
    $subtitle = post_string('subtitle', 120);
    $episodes = post_string('episodes', 24);
    $note = post_string('note', 255);

    $errors = [];
    if (mb_strlen($title) < 4) {
        $errors[] = '资源标题至少 4 个字';
    }
    if (!in_array($quality, qualities(), true)) {
        $errors[] = '请选择有效画质';
    }
    if (!array_key_exists($netdisk, netdisks())) {
        $errors[] = '请选择有效网盘';
    }
    if (!filter_var($shareUrl, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $shareUrl)) {
        $errors[] = '分享链接需为合法的 http(s) 地址';
    }
    if (!$errors && scalar(
        'SELECT COUNT(*) FROM resources WHERE repository_id = ? AND share_url = ?',
        [$repoId, $shareUrl]
    ) > 0) {
        $errors[] = '该链接已在此仓库中发布过';
    }

    if ($errors) {
        keep_old([
            'title' => $title, 'quality' => $quality, 'netdisk' => $netdisk,
            'share_url' => $shareUrl, 'access_code' => $accessCode, 'size' => $size,
            'subtitle' => $subtitle, 'episodes' => $episodes, 'note' => $note,
        ]);
        flash('error', implode('；', $errors));
        redirect(repo_url($repo) . '#resources');
    }

    q(
        'INSERT INTO resources (repository_id, title, quality, netdisk, share_url, access_code, size, subtitle, episodes, note, publisher_id)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $repoId,
            $title,
            $quality,
            $netdisk,
            $shareUrl,
            $accessCode !== '' ? $accessCode : null,
            $size !== '' ? $size : null,
            $subtitle !== '' ? $subtitle : null,
            $episodes !== '' ? $episodes : null,
            $note !== '' ? $note : null,
            (int) $user['id'],
        ]
    );

    q('UPDATE repositories SET heat = heat + 6, updated_at = NOW() WHERE id = ?', [$repoId]);

    flash('success', '资源发布成功，感谢分享');
    redirect(repo_url($repo) . '#resources');
}

function action_vote(): void
{
    if (!verify_csrf()) {
        json_response(['ok' => false, 'message' => '页面已过期，请刷新重试'], 419);
    }

    $user = current_user();
    if (!$user) {
        json_response(['ok' => false, 'need_login' => true, 'message' => '请先登录'], 401);
    }

    $id = (int) ($_POST['id'] ?? 0);
    $resource = one('SELECT id, repository_id FROM resources WHERE id = ?', [$id]);
    if (!$resource) {
        json_response(['ok' => false, 'message' => '资源不存在'], 404);
    }

    $voted = one(
        'SELECT 1 FROM resource_votes WHERE user_id = ? AND resource_id = ?',
        [(int) $user['id'], $id]
    ) !== null;

    if ($voted) {
        q('DELETE FROM resource_votes WHERE user_id = ? AND resource_id = ?', [(int) $user['id'], $id]);
        q('UPDATE resources SET votes = GREATEST(votes - 1, 0) WHERE id = ?', [$id]);
        q('UPDATE repositories SET heat = GREATEST(heat - 1, 0) WHERE id = ?', [(int) $resource['repository_id']]);
    } else {
        q('INSERT INTO resource_votes (user_id, resource_id) VALUES (?, ?)', [(int) $user['id'], $id]);
        q('UPDATE resources SET votes = votes + 1 WHERE id = ?', [$id]);
        q('UPDATE repositories SET heat = heat + 1 WHERE id = ?', [(int) $resource['repository_id']]);
    }

    $votes = (int) scalar('SELECT votes FROM resources WHERE id = ?', [$id]);

    json_response(['ok' => true, 'voted' => !$voted, 'votes' => $votes]);
}

function action_delete_resource(int $id): void
{
    $user = require_login();
    if (!verify_csrf()) {
        csrf_fail();
    }

    $resource = one('SELECT * FROM resources WHERE id = ?', [$id]);
    if (!$resource) {
        page_not_found();
    }
    if ((int) $resource['publisher_id'] !== (int) $user['id'] && !is_admin()) {
        flash('error', '只能删除自己发布的资源');
        redirect('/me');
    }

    q('DELETE FROM resources WHERE id = ?', [$id]);
    q(
        'UPDATE repositories SET heat = GREATEST(heat - (? + 6), 0) WHERE id = ?',
        [(int) $resource['votes'], (int) $resource['repository_id']]
    );
    flash('success', '资源已删除');
    redirect('/me');
}

function action_delete_repo(int $id): void
{
    $user = require_login();
    if (!verify_csrf()) {
        csrf_fail();
    }

    $repo = one('SELECT * FROM repositories WHERE id = ?', [$id]);
    if (!$repo) {
        page_not_found();
    }
    if ((int) $repo['created_by'] !== (int) $user['id'] && !is_admin()) {
        flash('error', '只能删除自己创建的仓库');
        redirect('/me');
    }

    q('DELETE FROM repositories WHERE id = ?', [$id]);
    flash('success', '仓库「' . $repo['title'] . '」已删除');
    redirect('/me');
}
