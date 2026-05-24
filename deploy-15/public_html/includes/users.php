<?php
/**
 * ユーザー管理 — users.json の読み書き
 * config.php の定数を使用します
 */
require_once __DIR__ . '/../config.php';

function loadUsers(): array {
    if (!file_exists(USERS_JSON)) return [];
    return json_decode(file_get_contents(USERS_JSON), true) ?? [];
}
function saveUsers(array $users): bool {
    return file_put_contents(USERS_JSON,
        json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}
function getUserByUid(string $uid): array|false {
    foreach (loadUsers() as $u) { if ($u['uid'] === $uid) return $u; }
    return false;
}
function getUserByEmail(string $email): array|false {
    foreach (loadUsers() as $u) {
        if (isset($u['email']) && strtolower($u['email']) === strtolower($email)) return $u;
    }
    return false;
}
function authenticate(string $uid, string $password): array|false {
    $u = getUserByUid($uid);
    if (!$u || !password_verify($password, $u['password'])) return false;
    return ['uid' => $u['uid'], 'name' => $u['name'], 'role' => $u['role']];
}
function isActiveUser(string $uid): bool {
    $u = getUserByUid($uid);
    return $u && in_array($u['role'], ['student', 'teacher'], true);
}
function isTeacher(string $uid): bool {
    $u = getUserByUid($uid);
    return $u && $u['role'] === 'teacher';
}
