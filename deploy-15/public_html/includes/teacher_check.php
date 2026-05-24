<?php
// 教員専用ページ用チェック（auth_check.php の後に追加チェック）
require_once __DIR__ . '/auth_check.php';
if (!$is_teacher) {
    header('Location: /index.php'); exit;
}
