<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/users.php';

if (!empty($_SESSION['uid'])) { header('Location: /index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid      = trim($_POST['uid'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$uid || !$password) {
        $error = 'IDとパスワードを入力してください。';
    } else {
        $user = authenticate($uid, $password);
        if (!$user) {
            $error = 'IDまたはパスワードが正しくありません。';
        } elseif (!isActiveUser($uid)) {
            $error = 'このアカウントはアクセスできません。';
        } else {
            // シングルセッション: 新しいsession_tokenを発行してDBに記録
            $session_token = bin2hex(random_bytes(16));
            $users = loadUsers();
            foreach ($users as &$u) {
                if ($u['uid'] === $uid) { $u['session_token'] = $session_token; break; }
            }
            unset($u);
            saveUsers($users);

            session_regenerate_id(true);
            $_SESSION['uid']           = $user['uid'];
            $_SESSION['name']          = $user['name'];
            $_SESSION['role']          = $user['role'];
            $_SESSION['session_token'] = $session_token;
            $_SESSION['last_activity'] = time();
            header('Location: /index.php'); exit;
        }
    }
}
if (!$error) {
    $msg = [
        'timeout' => 'セッションがタイムアウトしました。再度ログインしてください。',
        'plan'    => 'このアカウントはアクセスできません。',
        'session' => '別の端末からログインされたため、自動的にログアウトしました。',
    ];
    $error = $msg[$_GET['error'] ?? ''] ?? '';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=EB+Garamond:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
  :root{--navy:#0D1B2A;--cream:#F4EFE4;--gold:#B8960C;--red:#C8102E;--fog:#8C8070;--mist:#D6CFC2;--border:rgba(184,150,12,.25)}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background:var(--navy);background-image:radial-gradient(ellipse 80% 60% at 50% 0%,#1a2f45 0%,transparent 60%),url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");display:flex;align-items:center;justify-content:center;font-family:'EB Garamond',serif;color:var(--cream);padding:2rem}
  .wrap{width:100%;max-width:440px;animation:fadeUp .8s ease both}
  @keyframes fadeUp{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:translateY(0)}}
  .crest{text-align:center;margin-bottom:2.5rem}
  .crest-rule{display:flex;align-items:center;gap:1rem;margin-bottom:1rem}
  .crest-rule span{flex:1;height:1px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .diamond{width:10px;height:10px;background:var(--gold);transform:rotate(45deg);flex-shrink:0}
  .crest h1{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;color:var(--cream);line-height:1.1}
  .crest h1 em{font-style:italic;color:var(--gold)}
  .crest p{font-size:.78rem;letter-spacing:.3em;text-transform:uppercase;color:var(--fog);margin-top:.5rem}
  .card{background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:2px;padding:2.5rem 2.5rem 2rem;backdrop-filter:blur(8px);position:relative}
  .card::before{content:'';position:absolute;top:-1px;left:2rem;right:2rem;height:2px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .card-title{font-family:'Playfair Display',serif;font-size:1.05rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mist);text-align:center;margin-bottom:2rem}
  .field{margin-bottom:1.4rem}
  label{display:block;font-size:.72rem;letter-spacing:.25em;text-transform:uppercase;color:var(--fog);margin-bottom:.5rem}
  input[type=text],input[type=password]{width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(184,150,12,.2);border-radius:1px;padding:.75rem 1rem;font-family:'EB Garamond',serif;font-size:1.05rem;color:var(--cream);outline:none;transition:border-color .2s}
  input::placeholder{color:rgba(214,207,194,.3)}
  input:focus{border-color:var(--gold);background:rgba(0,0,0,.4)}
  .hint{font-size:.8rem;color:var(--fog);margin-top:.3rem}
  .error{background:rgba(200,16,46,.12);border:1px solid rgba(200,16,46,.4);border-radius:1px;padding:.7rem 1rem;font-size:.9rem;color:#e87a8a;margin-bottom:1.4rem;text-align:center}
  .btn{width:100%;padding:.85rem;background:var(--red);border:none;border-radius:1px;font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;color:#fff;cursor:pointer;transition:background .2s;margin-top:.5rem}
  .btn:hover{background:#a50d25}
  .pg-footer{text-align:center;margin-top:1.8rem;font-size:.8rem;color:var(--fog)}
  .pg-footer a{color:var(--gold);text-decoration:none}
  .pg-footer a:hover{text-decoration:underline}
</style>
</head>
<body>
<div class="wrap">
  <div class="crest">
    <div class="crest-rule"><span></span><div class="diamond"></div><span></span></div>
    <h1>Momo's <em>London</em></h1>
    <p>English Learning Materials</p>
  </div>
  <div class="card">
    <div class="card-title">Sign In</div>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" action="/login.php">
      <div class="field">
        <label>学籍番号 / 教員ID</label>
        <input type="text" name="uid"
               placeholder="例: 20240001 または T001"
               value="<?= htmlspecialchars($_POST['uid'] ?? '') ?>"
               autocomplete="username" required>
        <p class="hint">初めての方は <a href="/register.php" style="color:var(--gold)">こちらから登録</a></p>
      </div>
      <div class="field">
        <label>パスワード</label>
        <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
      </div>
      <button type="submit" class="btn">Enter</button>
    </form>
  </div>
  <div class="pg-footer">
    <a href="/reset_request.php">パスワードをお忘れの方はこちら</a>
    &nbsp;·&nbsp;
    <a href="/register.php">新規登録</a>
    <br style="margin-top:.5rem">
    <span style="margin-top:.7rem;display:inline-block">&copy; <?= date('Y') ?> <?= SITE_NAME ?> &nbsp;·&nbsp; Authorized Members Only</span>
  </div>
</div>
</body>
</html>
