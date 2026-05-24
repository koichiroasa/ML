<?php
/**
 * パスワードリセット Step 1 — メールアドレス入力
 */
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/users.php';

if (!empty($_SESSION['uid'])) { header('Location: /index.php'); exit; }

// リセットトークンの保存場所（pending.json と同じ momos_data 以下）
define('RESET_JSON', dirname(__DIR__) . '/momos_data/reset_tokens.json');

function loadResets(): array {
    if (!file_exists(RESET_JSON)) return [];
    return json_decode(file_get_contents(RESET_JSON), true) ?? [];
}
function saveResets(array $data): void {
    file_put_contents(RESET_JSON,
        json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
function cleanResets(): array {
    $list = array_values(array_filter(loadResets(),
        fn($r) => (time() - strtotime($r['created_at'])) < 3600)); // 1時間有効
    saveResets($list);
    return $list;
}

$sent  = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));

    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = '有効なメールアドレスを入力してください。';
    } else {
        $user = getUserByEmail($email);

        // ユーザーが存在しない場合も「送信した」と表示
        // （メールアドレスの存在確認を防ぐため）
        if ($user && isActiveUser($user['uid'])) {
            // 既存のトークンを削除してから新規発行
            $resets = array_values(array_filter(cleanResets(),
                fn($r) => $r['email'] !== $email));

            $token = bin2hex(random_bytes(32));
            $resets[] = [
                'token'      => $token,
                'email'      => $email,
                'uid'        => $user['uid'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
            saveResets($resets);

            // メール送信
            $url     = SITE_URL . '/reset_password.php?token=' . urlencode($token);
            $site    = SITE_NAME;
            $from    = MAIL_FROM_NAME . ' <' . MAIL_FROM . '>';
            $subject = '=?UTF-8?B?' . base64_encode("[{$site}] パスワードリセットのご案内") . '?=';
            $body    = <<<BODY
パスワードリセットのリクエストを受け付けました。

下記のリンクをクリックして、新しいパスワードを設定してください。
このリンクは 1時間以内 に使用してください。

{$url}

このメールに心当たりがない場合は無視してください。
パスワードは変更されません。
---
{$site}
BODY;
            $headers  = "From: {$from}\r\n";
            $headers .= "Reply-To: " . MAIL_FROM . "\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: base64\r\n";
            mail($email, $subject, base64_encode($body), $headers);
        }

        $sent = true; // 存在有無に関わらず送信済みページを表示
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>パスワードリセット — <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=EB+Garamond:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
  :root{--navy:#0D1B2A;--cream:#F4EFE4;--gold:#B8960C;--red:#C8102E;--fog:#8C8070;--mist:#D6CFC2;--gl:#4CAF85;--border:rgba(184,150,12,.25)}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background:var(--navy);background-image:radial-gradient(ellipse 80% 60% at 50% 0%,#1a2f45 0%,transparent 60%);display:flex;align-items:center;justify-content:center;font-family:'EB Garamond',serif;color:var(--cream);padding:2rem}
  .wrap{width:100%;max-width:440px;animation:fadeUp .8s ease both}
  @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
  .crest{text-align:center;margin-bottom:2rem}
  .crest-rule{display:flex;align-items:center;gap:1rem;margin-bottom:.8rem}
  .crest-rule span{flex:1;height:1px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .diamond{width:9px;height:9px;background:var(--gold);transform:rotate(45deg);flex-shrink:0}
  .crest h1{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--cream)}
  .crest h1 em{font-style:italic;color:var(--gold)}
  .crest p{font-size:.78rem;letter-spacing:.28em;text-transform:uppercase;color:var(--fog);margin-top:.4rem}
  .card{background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:2px;padding:2.2rem;position:relative;backdrop-filter:blur(8px)}
  .card::before{content:'';position:absolute;top:-1px;left:2rem;right:2rem;height:2px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .card-title{font-family:'Playfair Display',serif;font-size:1.05rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mist);text-align:center;margin-bottom:.6rem}
  .card-sub{color:var(--fog);font-size:.92rem;text-align:center;margin-bottom:1.6rem;line-height:1.7}
  .field{margin-bottom:1.2rem}
  label{display:block;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;color:var(--fog);margin-bottom:.4rem}
  input[type=email]{width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(184,150,12,.2);border-radius:1px;padding:.7rem 1rem;font-family:'EB Garamond',serif;font-size:1.05rem;color:var(--cream);outline:none;transition:border-color .2s}
  input::placeholder{color:rgba(214,207,194,.3)}
  input:focus{border-color:var(--gold);background:rgba(0,0,0,.4)}
  .error{background:rgba(200,16,46,.1);border:1px solid rgba(200,16,46,.35);border-radius:1px;padding:.65rem 1rem;font-size:.92rem;color:#e87a8a;margin-bottom:1.2rem;text-align:center}
  .btn{width:100%;padding:.82rem;background:var(--red);border:none;border-radius:1px;font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;color:#fff;cursor:pointer;transition:background .2s}
  .btn:hover{background:#a50d25}
  .success-box{text-align:center;padding:.5rem 0}
  .success-box .icon{font-size:2.8rem;display:block;margin-bottom:.8rem}
  .success-box h3{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--gl);margin-bottom:.8rem}
  .success-box p{color:var(--mist);font-size:.95rem;line-height:1.8}
  .success-box strong{color:var(--cream)}
  .pg-footer{text-align:center;margin-top:1.6rem;font-size:.8rem;color:var(--fog)}
  .pg-footer a{color:var(--gold);text-decoration:none}
  .pg-footer a:hover{text-decoration:underline}
</style>
</head>
<body>
<div class="wrap">
  <div class="crest">
    <div class="crest-rule"><span></span><div class="diamond"></div><span></span></div>
    <h1>Momo's <em>London</em></h1>
    <p>Password Reset</p>
  </div>

  <div class="card">
    <?php if ($sent): ?>
    <div class="success-box">
      <span class="icon">📧</span>
      <h3>メールを送信しました</h3>
      <p>登録済みのメールアドレスに<br>パスワードリセット用のリンクを送信しました。<br><br>
      リンクの有効期限は <strong>1時間</strong> です。<br>
      メールが届かない場合は<br>迷惑メールフォルダをご確認ください。</p>
    </div>

    <?php else: ?>
    <div class="card-title">パスワードをお忘れの方</div>
    <p class="card-sub">登録済みのメールアドレスを入力してください。<br>パスワードリセット用のリンクをお送りします。</p>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" action="/reset_request.php">
      <div class="field">
        <label>登録済みメールアドレス</label>
        <input type="email" name="email"
               placeholder="your@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               autocomplete="email" autofocus>
      </div>
      <button type="submit" class="btn">リセットリンクを送信する</button>
    </form>
    <?php endif; ?>
  </div>

  <div class="pg-footer">
    <a href="/login.php">← ログインページへ戻る</a>
  </div>
</div>
</body>
</html>
