<?php
/**
 * パスワードリセット Step 2 — 新パスワード設定
 */
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/users.php';

if (!empty($_SESSION['uid'])) { header('Location: /index.php'); exit; }

define('RESET_JSON', dirname(__DIR__) . '/momos_data/reset_tokens.json');

function loadResets(): array {
    if (!file_exists(RESET_JSON)) return [];
    return json_decode(file_get_contents(RESET_JSON), true) ?? [];
}
function saveResets(array $data): void {
    file_put_contents(RESET_JSON,
        json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
function getResetToken(string $token): array|false {
    $list = loadResets();
    // 期限チェック（1時間）
    foreach ($list as $r) {
        if ($r['token'] === $token) {
            if ((time() - strtotime($r['created_at'])) > 3600) return false;
            return $r;
        }
    }
    return false;
}
function deleteResetToken(string $token): void {
    saveResets(array_values(array_filter(loadResets(), fn($r) => $r['token'] !== $token)));
}

$token       = trim($_GET['token'] ?? $_POST['token'] ?? '');
$reset_data  = $token ? getResetToken($token) : false;
$token_error = !$reset_data;
$error       = '';
$done        = false;

if (!$token_error && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $pw1 = $_POST['password']  ?? '';
    $pw2 = $_POST['password2'] ?? '';

    if (!$pw1) {
        $error = '新しいパスワードを入力してください。';
    } elseif (strlen($pw1) < 8) {
        $error = 'パスワードは8文字以上にしてください。';
    } elseif ($pw1 !== $pw2) {
        $error = 'パスワードが一致しません。';
    } else {
        // パスワード更新
        $users = loadUsers();
        $updated = false;
        foreach ($users as &$u) {
            if ($u['uid'] === $reset_data['uid']) {
                $u['password']      = password_hash($pw1, PASSWORD_DEFAULT);
                $u['session_token'] = ''; // 既存セッションをすべて無効化
                $updated = true;
                break;
            }
        }
        unset($u);

        if ($updated) {
            saveUsers($users);
            deleteResetToken($token);
            $done = true;
        } else {
            $error = 'ユーザーが見つかりません。管理者にお問い合わせください。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>新しいパスワードの設定 — <?= SITE_NAME ?></title>
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
  .email-badge{background:rgba(184,150,12,.1);border:1px solid rgba(184,150,12,.22);border-radius:1px;padding:.45rem 1rem;text-align:center;color:var(--gold);font-size:.9rem;margin-bottom:1.4rem;word-break:break-all}
  .field{margin-bottom:1.2rem}
  label{display:block;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;color:var(--fog);margin-bottom:.4rem}
  input[type=password]{width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(184,150,12,.2);border-radius:1px;padding:.7rem 1rem;font-family:'EB Garamond',serif;font-size:1.05rem;color:var(--cream);outline:none;transition:border-color .2s}
  input::placeholder{color:rgba(214,207,194,.3)}
  input:focus{border-color:var(--gold);background:rgba(0,0,0,.4)}
  /* strength meter */
  .pw-meter{height:4px;border-radius:2px;background:rgba(255,255,255,.08);margin-top:.4rem;overflow:hidden}
  .pw-meter-bar{height:100%;width:0;border-radius:2px;transition:width .3s,background .3s}
  .pw-hint{font-size:.75rem;color:var(--fog);margin-top:.25rem}
  .error{background:rgba(200,16,46,.1);border:1px solid rgba(200,16,46,.35);border-radius:1px;padding:.65rem 1rem;font-size:.92rem;color:#e87a8a;margin-bottom:1.2rem;text-align:center}
  .btn{width:100%;padding:.82rem;background:var(--red);border:none;border-radius:1px;font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;color:#fff;cursor:pointer;transition:background .2s}
  .btn:hover{background:#a50d25}
  .btn-login{display:block;width:100%;padding:.82rem;background:rgba(76,175,133,.18);color:var(--gl);border:1px solid rgba(76,175,133,.38);border-radius:1px;font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;text-align:center;text-decoration:none;transition:background .2s;margin-top:.6rem}
  .btn-login:hover{background:rgba(76,175,133,.32)}
  .success-box{text-align:center;padding:.5rem 0}
  .success-box .icon{font-size:2.8rem;display:block;margin-bottom:.8rem}
  .success-box h3{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--gl);margin-bottom:.8rem}
  .success-box p{color:var(--mist);font-size:.95rem;line-height:1.8;margin-bottom:.3rem}
  .token-error{text-align:center;padding:.5rem 0}
  .token-error .icon{font-size:2.5rem;display:block;margin-bottom:.8rem}
  .token-error h3{font-family:'Playfair Display',serif;font-size:1.05rem;color:#e87a8a;margin-bottom:.7rem}
  .token-error p{color:var(--mist);font-size:.93rem;line-height:1.8}
  .token-error a{color:var(--gold);text-decoration:none}
  .token-error a:hover{text-decoration:underline}
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
    <?php if ($token_error): ?>
    <!-- トークン無効 -->
    <div class="token-error">
      <span class="icon">⚠️</span>
      <h3>リンクが無効または期限切れです</h3>
      <p>リセットリンクの有効期限（1時間）が過ぎているか、<br>すでに使用されています。<br><br>
      <a href="/reset_request.php">もう一度リセットを申請する</a></p>
    </div>

    <?php elseif ($done): ?>
    <!-- 変更完了 -->
    <div class="success-box">
      <span class="icon">✅</span>
      <h3>パスワードを変更しました</h3>
      <p>新しいパスワードで<br>ログインしてください。</p>
      <a href="/login.php" class="btn-login">ログインページへ →</a>
    </div>

    <?php else: ?>
    <!-- パスワード入力フォーム -->
    <div class="card-title">新しいパスワードの設定</div>
    <div class="email-badge">🔑 <?= htmlspecialchars($reset_data['uid']) ?> さんのアカウント</div>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" action="/reset_password.php">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="field">
        <label>新しいパスワード（8文字以上）</label>
        <input type="password" name="password" id="pw1"
               placeholder="••••••••" autocomplete="new-password"
               oninput="checkStrength(this.value)" autofocus>
        <div class="pw-meter"><div class="pw-meter-bar" id="pw-bar"></div></div>
        <p class="pw-hint" id="pw-hint">8文字以上で設定してください</p>
      </div>
      <div class="field">
        <label>新しいパスワード（確認）</label>
        <input type="password" name="password2" id="pw2"
               placeholder="••••••••" autocomplete="new-password"
               oninput="checkMatch()">
        <p class="pw-hint" id="pw-match-hint" style="display:none"></p>
      </div>
      <button type="submit" class="btn">パスワードを変更する</button>
    </form>
    <?php endif; ?>
  </div>

  <?php if (!$done && !$token_error): ?>
  <div class="pg-footer">
    <a href="/login.php">← ログインページへ戻る</a>
  </div>
  <?php endif; ?>
</div>

<script>
// パスワード強度メーター
function checkStrength(val) {
  const bar  = document.getElementById('pw-bar');
  const hint = document.getElementById('pw-hint');
  let score = 0;
  if (val.length >= 8)  score++;
  if (val.length >= 12) score++;
  if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;

  const levels = [
    { w:'0%',   c:'transparent', t:'' },
    { w:'25%',  c:'#e87a8a',    t:'弱い' },
    { w:'50%',  c:'#f0a040',    t:'普通' },
    { w:'75%',  c:'#B8960C',    t:'強い' },
    { w:'100%', c:'#4CAF85',    t:'とても強い' },
  ];
  const lv = Math.min(score, 4);
  bar.style.width      = levels[lv].w;
  bar.style.background = levels[lv].c;
  hint.textContent     = val.length < 8
    ? '8文字以上で設定してください'
    : levels[lv].t;
  hint.style.color = levels[lv].c || 'var(--fog)';
}

// 一致確認
function checkMatch() {
  const pw1  = document.getElementById('pw1').value;
  const pw2  = document.getElementById('pw2').value;
  const hint = document.getElementById('pw-match-hint');
  if (!pw2) { hint.style.display = 'none'; return; }
  hint.style.display = 'block';
  if (pw1 === pw2) {
    hint.textContent  = '✓ 一致しています';
    hint.style.color  = 'var(--gl)';
  } else {
    hint.textContent  = '✗ 一致していません';
    hint.style.color  = '#e87a8a';
  }
}
</script>
</body>
</html>
