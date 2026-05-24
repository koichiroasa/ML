<?php
/**
 * ライセンスキー管理 & 仮登録管理
 */
require_once __DIR__ . '/../config.php';

// ─── ライセンスキー ────────────────────────────────────

function loadLicenses(): array {
    if (!file_exists(LICENSES_JSON)) return [];
    return json_decode(file_get_contents(LICENSES_JSON), true) ?? [];
}
function saveLicenses(array $data): bool {
    return file_put_contents(LICENSES_JSON,
        json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

/** キーのフォーマット: ML-XXXX-XXXX-XXXX-XXXX（英数大文字） */
function generateLicenseKey(): string {
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // 紛らわしい文字除外
    $seg = fn() => implode('', array_map(fn($i) => $chars[random_int(0, strlen($chars)-1)], range(1,4)));
    return 'ML-' . $seg() . '-' . $seg() . '-' . $seg() . '-' . $seg();
}

/** 指定枚数のキーを一括生成してDBに追加 */
function generateKeys(int $count): array {
    $licenses = loadLicenses();
    $existing = array_column($licenses, 'key');
    $new_keys = [];
    $attempts = 0;
    while (count($new_keys) < $count && $attempts < $count * 10) {
        $key = generateLicenseKey();
        if (!in_array($key, $existing) && !in_array($key, array_column($new_keys, 'key'))) {
            $new_keys[] = [
                'key'        => $key,
                'status'     => 'unused',   // unused | used | revoked
                'uid'        => null,
                'email'      => null,
                'created_at' => date('Y-m-d H:i:s'),
                'used_at'    => null,
                'note'       => '',
            ];
        }
        $attempts++;
    }
    $licenses = array_merge($licenses, $new_keys);
    saveLicenses($licenses);
    return $new_keys;
}

/** キーを検証（未使用かどうか確認） */
function validateLicenseKey(string $key): array|false {
    $key = strtoupper(trim($key));
    foreach (loadLicenses() as $lic) {
        if ($lic['key'] === $key) {
            return $lic; // status は呼び出し元で確認
        }
    }
    return false;
}

/** キーを使用済みにする */
function markKeyUsed(string $key, string $uid, string $email): bool {
    $licenses = loadLicenses();
    foreach ($licenses as &$lic) {
        if ($lic['key'] === strtoupper($key)) {
            $lic['status']  = 'used';
            $lic['uid']     = $uid;
            $lic['email']   = $email;
            $lic['used_at'] = date('Y-m-d H:i:s');
            break;
        }
    }
    unset($lic);
    return saveLicenses($licenses);
}

/** キーを無効化（管理者操作） */
function revokeKey(string $key): bool {
    $licenses = loadLicenses();
    foreach ($licenses as &$lic) {
        if ($lic['key'] === strtoupper($key)) { $lic['status'] = 'revoked'; break; }
    }
    unset($lic);
    return saveLicenses($licenses);
}

// ─── 仮登録（メール認証待ち）────────────────────────────

function loadPending(): array {
    if (!file_exists(PENDING_JSON)) return [];
    return json_decode(file_get_contents(PENDING_JSON), true) ?? [];
}
function savePending(array $data): bool {
    return file_put_contents(PENDING_JSON,
        json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

/** 期限切れ仮登録を削除してから返す */
function cleanPending(): array {
    $list = loadPending();
    $now  = time();
    $list = array_values(array_filter($list, fn($p) => ($now - strtotime($p['created_at'])) < PENDING_TTL));
    savePending($list);
    return $list;
}

/** 仮登録を追加 */
function addPending(string $key, string $email): string {
    // 既存の同メール・同キー仮登録は削除
    $list = array_values(array_filter(cleanPending(),
        fn($p) => $p['email'] !== strtolower($email) && $p['key'] !== strtoupper($key)));

    $token = bin2hex(random_bytes(32));
    $list[] = [
        'token'      => $token,
        'key'        => strtoupper($key),
        'email'      => strtolower($email),
        'created_at' => date('Y-m-d H:i:s'),
    ];
    savePending($list);
    return $token;
}

/** トークンで仮登録を取得 */
function getPending(string $token): array|false {
    $list = cleanPending();
    foreach ($list as $p) {
        if ($p['token'] === $token) return $p;
    }
    return false;
}

/** 仮登録を削除（登録完了後） */
function deletePending(string $token): void {
    $list = array_values(array_filter(loadPending(), fn($p) => $p['token'] !== $token));
    savePending($list);
}

// ─── メール送信 ──────────────────────────────────────────

function sendVerificationMail(string $to, string $token): bool {
    $url     = SITE_URL . '/verify.php?token=' . urlencode($token);
    $site    = SITE_NAME;
    $from    = MAIL_FROM_NAME . ' <' . MAIL_FROM . '>';
    $subject = '=?UTF-8?B?' . base64_encode("[{$site}] メールアドレスの確認") . '?=';
    $body    = <<<BODY
{$site} にご登録いただきありがとうございます。

下記のリンクをクリックして、メールアドレスの確認と登録を完了してください。
このリンクは 24時間以内 に使用してください。

{$url}

このメールに心当たりがない場合は無視してください。
---
{$site}
BODY;

    $headers  = "From: {$from}\r\n";
    $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";

    return mail($to, $subject, base64_encode($body), $headers);
}

// ─── メールドメイン検証 ───────────────────────────────────

function isAllowedEmail(string $email): bool {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;
    if (ALLOWED_EMAIL_DOMAINS === null) return true; // 制限なし
    $domain = strtolower(substr($email, strpos($email, '@') + 1));
    foreach (ALLOWED_EMAIL_DOMAINS as $allowed) {
        if ($domain === strtolower($allowed)) return true;
    }
    return false;
}
