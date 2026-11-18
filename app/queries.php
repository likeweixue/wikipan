<?php
declare(strict_types=1);

const REPO_SELECT = "SELECT r.*, c.name AS category_name, c.slug AS category_slug,
        (SELECT COUNT(*) FROM resources x WHERE x.repository_id = r.id) AS resource_count,
        (SELECT MAX(x.created_at) FROM resources x WHERE x.repository_id = r.id) AS last_resource_at
    FROM repositories r
    JOIN categories c ON c.id = r.category_id";

function repo_find(int $id): ?array
{
    return one(REPO_SELECT . ' WHERE r.id = ?', [$id]);
}

function repository_resources(int $repoId): array
{
    return all(
        'SELECT res.*, u.username, u.avatar_hue
           FROM resources res
           JOIN users u ON u.id = res.publisher_id
          WHERE res.repository_id = ?
          ORDER BY res.votes DESC, res.created_at DESC',
        [$repoId]
    );
}

function voted_resource_ids(?int $userId, int $repoId): array
{
    if (!$userId) {
        return [];
    }

    $rows = all(
        'SELECT v.resource_id
           FROM resource_votes v
           JOIN resources res ON res.id = v.resource_id
          WHERE v.user_id = ? AND res.repository_id = ?',
        [$userId, $repoId]
    );

    return array_map(static fn (array $row): int => (int) $row['resource_id'], $rows);
}

function count_rows(string $table, string $where = '1=1', array $params = []): int
{
    return (int) scalar("SELECT COUNT(*) FROM {$table} WHERE {$where}", $params);
}
