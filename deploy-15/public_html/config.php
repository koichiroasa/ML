<?php
/**
 * Momo's London — サイト設定
 * =====================================================
 * ★ 本番環境にアップロードする前にすべての項目を設定してください
 * =====================================================
 */

// ── パス設定 ─────────────────────────────────────────
define('USERS_JSON',    dirname(__DIR__) . '/momos_data/users.json');
define('LICENSES_JSON', dirname(__DIR__) . '/momos_data/licenses.json');
define('PENDING_JSON',  dirname(__DIR__) . '/momos_data/pending.json');

// ── サイト設定 ────────────────────────────────────────
// ★ サイトのURLを設定（末尾スラッシュなし）
define('SITE_URL',   'https://example.com');
define('SITE_NAME',  "Momo's London");

// ── メール設定 ────────────────────────────────────────
// ★ 送信元メールアドレス（レンタルサーバーで使えるアドレスに変更）
define('MAIL_FROM',      'noreply@example.com');
define('MAIL_FROM_NAME', "Momo's London");

// ── 認証設定 ──────────────────────────────────────────
// 仮登録の有効期限（秒）: 86400 = 24時間
define('PENDING_TTL',    86400);
// セッションタイムアウト（秒）
define('SESSION_TTL',    7200);
// true = 1アカウント1セッションのみ（後からログインした端末が有効）
define('SINGLE_SESSION', true);

// ── ドメイン制限 ──────────────────────────────────────
// null = 制限なし。配列で指定: ['abc-u.ac.jp', 'xyz.ac.jp']
define('ALLOWED_EMAIL_DOMAINS', null);
