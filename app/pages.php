<?php
declare(strict_types=1);

function page_home(): void
{
    $featured = all(REPO_SELECT . ' WHERE r.featured = 1 ORDER BY r.heat DESC LIMIT 5');
    if (!$featured) {
        $featured = all(REPO_SELECT . ' ORDER BY r.heat DESC LIMIT 5');
    }

    $top = all(REPO_SELECT . ' ORDER BY r.heat DESC LIMIT 10');

    $rows = [];
    foreach (categories() as $category) {
        $items = all(
            REPO_SELECT . ' WHERE r.category_id = ? ORDER BY r.heat DESC LIMIT 12',
            [(int) $category['id']]
        );
        if ($items) {
            $rows[] = ['category' => $category, 'items' => $items];
        }
    }

    $latest = all(
        "SELECT res.*, r.title AS repo_title, r.slug AS repo_slug, r.id AS repo_id, r.poster AS repo_poster
           FROM resources res
           JOIN repositories r ON r.id = res.repository_id
          ORDER BY res.created_at DESC
          LIMIT 12"
    );

    $stats = one(
        'SELECT (SELECT COUNT(*) FROM repositories) AS repos,
                (SELECT COUNT(*) FROM resources) AS resources,
                (SELECT COUNT(*) FROM users) AS users'
    );

    view('home', compact('featured', 'top', 'rows', 'latest', 'stats'), [
        'title'      => '盘维 · 网盘资源维基',
        'nav'        => 'home',
        'body_class' => 'is-home',
    ]);
}

function page_browse(): void
{
    $slug = trim((string) ($_GET['cat'] ?? ''));
    $category = $slug !== '' ? category_by_slug($slug) : null;
    if ($slug !== '' && !$category) {
        page_not_found();
    }

    $sort = ($_GET['sort'] ?? 'heat') === 'new' ? 'new' : 'heat';
    $page = max(1, (int) ($_GET['page'] ?? 1));
    $perPage = 18;

    $where = $category ? 'r.category_id = ?' : '1=1';
    $params = $category ? [(int) $category['id']] : [];

    $total = count_rows('repositories r', $where, $params);
    $pages = max(1, (int) ceil($total / $perPage));
    $page = min($page, $pages);
    $offset = ($page - 1) * $perPage;

    $order = $sort === 'new' ? 'r.created_at DESC' : 'r.heat DESC';
    $items = all(
        REPO_SELECT . " WHERE {$where} ORDER BY {$order} LIMIT {$perPage} OFFSET {$offset}",
        $params
    );

    $heading = $category['name'] ?? '全部仓库';
    view('browse', compact('category', 'items', 'total', 'page', 'pages', 'sort', 'heading'), [
        'title'      => $heading . ' · 盘维',
        'nav'        => $category['slug'] ?? 'browse',
        'body_class' => 'is-list',
    ]);
}

function page_search(): void
{
    $q = trim((string) ($_GET['q'] ?? ''));
    $q = mb_substr($q, 0, 50);

    $repos = [];
    $resources = [];
    if ($q !== '') {
        $like = '%' . $q . '%';
        $repos = all(
            REPO_SELECT . ' WHERE r.title LIKE ? OR r.original_title LIKE ? OR r.tags LIKE ?
              ORDER BY r.heat DESC LIMIT 24',
            [$like, $like, $like]
        );
        $resources = all(
            "SELECT res.*, r.title AS repo_title, r.slug AS repo_slug, r.id AS repo_id, u.username
               FROM resources res
               JOIN repositories r ON r.id = res.repository_id
               JOIN users u ON u.id = res.publisher_id
              WHERE res.title LIKE ? OR res.subtitle LIKE ?
              ORDER BY res.votes DESC LIMIT 24",
            [$like, $like]
        );
    }

    view('search', compact('q', 'repos', 'resources'), [
        'title'      => ($q !== '' ? '搜索「' . $q . '」' : '搜索') . ' · 盘维',
        'nav'        => 'search',
        'body_class' => 'is-list',
    ]);
}

function page_repo(int $id): void
{
    $repo = repo_find($id);
    if (!$repo) {
        page_not_found();
    }

    $resources = repository_resources($id);
    $creator = one('SELECT id, username, avatar_hue FROM users WHERE id = ?', [(int) $repo['created_by']]);
    $user = current_user();
    $votedIds = voted_resource_ids($user ? (int) $user['id'] : null, $id);
    $totalVotes = (int) scalar('SELECT COALESCE(SUM(votes), 0) FROM resources WHERE repository_id = ?', [$id]);
    $related = all(
        REPO_SELECT . ' WHERE r.category_id = ? AND r.id <> ? ORDER BY r.heat DESC LIMIT 8',
        [(int) $repo['category_id'], $id]
    );

    view('repo', compact('repo', 'resources', 'creator', 'votedIds', 'totalVotes', 'related'), [
        'title'       => $repo['title'] . ' · 资源仓库 · 盘维',
        'description' => excerpt($repo['description'], 100),
        'nav'         => $repo['category_slug'],
        'body_class'  => 'is-detail',
    ]);
}

function page_create(): void
{
    require_login();
    view('create', ['cats' => categories()], [
        'title'      => '新建资源仓库 · 盘维',
        'nav'        => 'create',
        'body_class' => 'is-form',
    ]);
}

function page_login(): void
{
    if (current_user()) {
        redirect('/');
    }

    $next = safe_next((string) ($_GET['next'] ?? '/'));
    view('login', ['next' => $next], [
        'title'      => '登录 · 盘维',
        'nav'        => 'login',
        'body_class' => 'is-auth',
    ]);
}

function page_register(): void
{
    if (current_user()) {
        redirect('/');
    }

    $next = safe_next((string) ($_GET['next'] ?? '/'));
    view('register', ['next' => $next], [
        'title'      => '注册 · 盘维',
        'nav'        => 'register',
        'body_class' => 'is-auth',
    ]);
}

function page_me(): void
{
    $user = require_login();

    $myRepos = all(
        REPO_SELECT . ' WHERE r.created_by = ? ORDER BY r.created_at DESC',
        [(int) $user['id']]
    );
    $myResources = all(
        "SELECT res.*, r.title AS repo_title, r.slug AS repo_slug, r.id AS repo_id
           FROM resources res
           JOIN repositories r ON r.id = res.repository_id
          WHERE res.publisher_id = ?
          ORDER BY res.created_at DESC",
        [(int) $user['id']]
    );
    $votesReceived = (int) scalar(
        'SELECT COALESCE(SUM(votes), 0) FROM resources WHERE publisher_id = ?',
        [(int) $user['id']]
    );

    view('me', compact('user', 'myRepos', 'myResources', 'votesReceived'), [
        'title'      => '我的贡献 · 盘维',
        'nav'        => 'me',
        'body_class' => 'is-list',
    ]);
}

function page_not_found(): never
{
    http_response_code(404);
    view('message', [
        'heading' => '页面不存在',
        'message' => '你访问的仓库或页面可能已被移动、删除，或者链接有误。',
        'action'  => ['label' => '回到首页', 'href' => '/'],
    ], ['title' => '页面不存在 · 盘维']);

    exit;
}
