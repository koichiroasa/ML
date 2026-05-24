<?php
/**
 * 認証チェック（全ユーザー共通）
 * セッション管理・シングルセッション制御
 */
session_start();
require_once __DIR__ . '/users.php';

// ── ログイン確認 ──
if (empty($_SESSION['uid'])) {
    header('Location: /login.php'); exit;
}
if (!isActiveUser($_SESSION['uid'])) {
    session_destroy();
    header('Location: /login.php?error=plan'); exit;
}

// ── セッションタイムアウト ──
if (isset($_SESSION['last_activity']) &&
    (time() - $_SESSION['last_activity']) > SESSION_TTL) {
    session_unset(); session_destroy();
    header('Location: /login.php?error=timeout'); exit;
}

// ── シングルセッション確認 ──
// ログイン時に session_token をDBに記録し、現在のトークンと一致しない場合は強制ログアウト
if (SINGLE_SESSION) {
    $u = getUserByUid($_SESSION['uid']);
    if ($u && isset($u['session_token']) &&
        $u['session_token'] !== ($_SESSION['session_token'] ?? '')) {
        session_unset(); session_destroy();
        header('Location: /login.php?error=session'); exit;
    }
}

$_SESSION['last_activity'] = time();
$current_uid  = $_SESSION['uid'];
$current_name = $_SESSION['name'] ?? '';
$current_role = $_SESSION['role'] ?? 'student';
$is_teacher   = ($current_role === 'teacher');
