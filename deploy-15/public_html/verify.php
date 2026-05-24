<?php
/**
 * 新規登録 Step 2 — メール認証 ＆ アカウント情報設定
 */
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/users.php';
require_once __DIR__ . '/includes/license.php';

if (!empty($_SESSION['uid'])) { header('Location: /index.php'); exit; }

$token   = trim($_GET['token'] ?? $_POST['token'] ?? '');
$pending = $token ? getPending($token) : false;
$error   = '';
$done    = false;

// トークン無効チェック
if (!$pending) {
    $token_error = true;
} else {
    $token_error = false;
}

// アカウント情報の保存（POST）
if (!$token_error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid  = trim($_POST['uid'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $pw1  = $_POST['password'] ?? '';
    $pw2  = $_POST['password2'] ?? '';

    if (!$uid || !$name || !$pw1) {
        $error = '学籍番号・氏名・パスワードはすべて必須です。';
    } elseif (strlen($pw1) < 8) {
        $error = 'パスワードは8文字以上にしてください。';
    } elseif ($pw1 !== $pw2) {
        $error = 'パスワードが一致しません。';
    } elseif (getUserByUid($uid)) {
        $error = 'その学籍番号はすでに登録されています。';
    } elseif (getUserByEmail($pending['email'])) {
        $error = 'そのメールアドレスはすでに登録されています。';
    } else {
        // アカウント作成
        $users = loadUsers();
        $session_token = bin2hex(random_bytes(16));
        $users[] = [
            'uid'           => $uid,
            'name'          => $name,
            'email'         => $pending['email'],
            'role'          => 'student',
            'password'      => password_hash($pw1, PASSWORD_DEFAULT),
            'license_key'   => $pending['key'],
            'session_token' => $session_token,
            'created_at'    => date('Y-m-d H:i:s'),
        ];
        saveUsers($users);

        // ライセンスキーを使用済みに
        markKeyUsed($pending['key'], $uid, $pending['email']);

        // 仮登録削除
        deletePending($token);

        // 自動ログイン
        session_regenerate_id(true);
        $_SESSION['uid']           = $uid;
        $_SESSION['name']          = $name;
        $_SESSION['role']          = 'student';
        $_SESSION['session_token'] = $session_token;
        $_SESSION['last_activity'] = time();

        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>登録確認 — <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=EB+Garamond:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
  :root{--navy:#0D1B2A;--cream:#F4EFE4;--gold:#B8960C;--red:#C8102E;--fog:#8C8070;--mist:#D6CFC2;--gl:#4CAF85;--border:rgba(184,150,12,.25)}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background:var(--navy);background-image:radial-gradient(ellipse 80% 60% at 50% 0%,#1a2f45 0%,transparent 60%);display:flex;align-items:center;justify-content:center;font-family:'EB Garamond',serif;color:var(--cream);padding:2rem}
  .wrap{width:100%;max-width:480px;animation:fadeUp .8s ease both}
  @keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
  .crest{text-align:center;margin-bottom:2rem}
  .crest-rule{display:flex;align-items:center;gap:1rem;margin-bottom:.8rem}
  .crest-rule span{flex:1;height:1px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .diamond{width:9px;height:9px;background:var(--gold);transform:rotate(45deg);flex-shrink:0}
  .crest h1{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--cream)}
  .crest h1 em{font-style:italic;color:var(--gold)}
  .crest p{font-size:.78rem;letter-spacing:.28em;text-transform:uppercase;color:var(--fog);margin-top:.4rem}
  .steps{display:flex;align-items:center;justify-content:center;gap:.4rem;margin-bottom:1.8rem}
  .step{display:flex;align-items:center;gap:.4rem;font-size:.78rem;letter-spacing:.1em;color:var(--fog)}
  .step .dot{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;border:1px solid var(--fog);color:var(--fog)}
  .step.done .dot{background:rgba(76,175,133,.2);border-color:var(--gl);color:var(--gl)}
  .step.active .dot{background:var(--gold);border-color:var(--gold);color:var(--navy);font-weight:bold}
  .step.active{color:var(--gold)}
  .step.done{color:var(--gl)}
  .step-line{width:32px;height:1px;background:rgba(140,128,112,.35)}
  .card{background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:2px;padding:2.2rem 2.2rem 1.8rem;position:relative;backdrop-filter:blur(8px)}
  .card::before{content:'';position:absolute;top:-1px;left:2rem;right:2rem;height:2px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .card-title{font-family:'Playfair Display',serif;font-size:1.05rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mist);text-align:center;margin-bottom:1.8rem}
  .email-badge{background:rgba(184,150,12,.1);border:1px solid rgba(184,150,12,.25);border-radius:1px;padding:.5rem 1rem;text-align:center;color:var(--gold);font-size:.92rem;margin-bottom:1.4rem;word-break:break-all}
  .field{margin-bottom:1.3rem}
  label{display:block;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;color:var(--fog);margin-bottom:.4rem}
  input[type=text],input[type=password]{width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(184,150,12,.2);border-radius:1px;padding:.7rem 1rem;font-family:'EB Garamond',serif;font-size:1.05rem;color:var(--cream);outline:none;transition:border-color .2s}
  input::placeholder{color:rgba(214,207,194,.3)}
  input:focus{border-color:var(--gold);background:rgba(0,0,0,.4)}
  .hint{font-size:.78rem;color:var(--fog);margin-top:.35rem;font-style:italic}
  .error{background:rgba(200,16,46,.1);border:1px solid rgba(200,16,46,.35);border-radius:1px;padding:.65rem 1rem;font-size:.92rem;color:#e87a8a;margin-bottom:1.2rem;text-align:center}
  .btn{width:100%;padding:.82rem;background:var(--red);border:none;border-radius:1px;font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;color:#fff;cursor:pointer;transition:background .2s;margin-top:.4rem}
  .btn:hover{background:#a50d25}
  .btn-green{background:rgba(76,175,133,.2);color:var(--gl);border:1px solid rgba(76,175,133,.4);font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;padding:.82rem;width:100%;cursor:pointer;border-radius:1px;transition:background .2s;margin-top:.6rem;display:block;text-align:center;text-decoration:none}
  .btn-green:hover{background:rgba(76,175,133,.35)}
  .success-box{text-align:center}
  .success-box .icon{font-size:3rem;display:block;margin-bottom:.8rem}
  .success-box h3{font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--gl);margin-bottom:.8rem}
  .success-box p{color:var(--mist);line-height:1.8;margin-bottom:.3rem}
  .token-error{text-align:center;padding:1rem 0}
  .token-error .icon{font-size:2.5rem;display:block;margin-bottom:.8rem}
  .token-error h3{font-family:'Playfair Display',serif;font-size:1.1rem;color:#e87a8a;margin-bottom:.7rem}
  .token-error p{color:var(--mist);font-size:.95rem;line-height:1.8}
  .token-error a{color:var(--gold);text-decoration:none}
  .token-error a:hover{text-decoration:underline}
</style>
</head>
<body>
<div class="wrap">
  <div class="crest">
    <div class="crest-rule"><span></span><div class="diamond"></div><span></span></div>
    <h1>Momo's <em>London</em></h1>
    <p>New Student Registration</p>
  </div>

  <div class="steps">
    <div class="step done"><span class="dot">✓</span><span>キー入力</span></div>
    <div class="step-line"></div>
    <div class="step <?= $done ? 'done' : 'active' ?>"><span class="dot"><?= $done ? '✓' : '2' ?></span><span>メール確認</span></div>
    <div class="step-line"></div>
    <div class="step <?= $done ? 'active' : '' ?>"><span class="dot">3</span><span>登録完了</span></div>
  </div>

  <div class="card">
    <?php if ($token_error): ?>
    <!-- トークン無効 -->
    <div class="token-error">
      <span class="icon">⚠️</span>
      <h3>リンクが無効または期限切れです</h3>
      <p>確認リンクの有効期限（24時間）が過ぎているか、すでに使用されています。<br><br>
      <a href="/register.php">登録をやり直す</a></p>
    </div>

    <?php elseif ($done): ?>
    <!-- 登録完了 -->
    <div class="success-box">
      <span class="icon">🎉</span>
      <h3>登録が完了しました！</h3>
      <p>ようこそ、<strong style="color:var(--cream)"><?= htmlspecialchars($_SESSION['name']) ?></strong> さん！</p>
      <p style="margin-top:.5rem;color:var(--fog);font-size:.9rem">Momo's London へのアクセスが有効になりました。</p>
      <a href="/index.php" class="btn-green">学習をはじめる →</a>
    </div>

    <?php else: ?>
    <!-- 情報入力フォーム -->
    <div class="card-title">Step 2 — アカウント情報の設定</div>
    <div class="email-badge">📧 <?= htmlspecialchars($pending['email']) ?></div>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" action="/verify.php">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="field">
        <label>学籍番号</label>
        <input type="text" name="uid"
               placeholder="例: 20240001"
               value="<?= htmlspecialchars($_POST['uid'] ?? '') ?>"
               autocomplete="username" required>
        <p class="hint">ログインIDになります。</p>
      </div>
      <div class="field">
        <label>氏名</label>
        <input type="text" name="name"
               placeholder="例: 山田 花子"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
               autocomplete="name" required>
      </div>
      <div class="field">
        <label>パスワード（8文字以上）</label>
        <input type="password" name="password" placeholder="••••••••" autocomplete="new-password" required>
      </div>
      <div class="field">
        <label>パスワード（確認）</label>
        <input type="password" name="password2" placeholder="••••••••" autocomplete="new-password" required>
      </div>
      <button type="submit" class="btn">登録を完了する</button>
    </form>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
