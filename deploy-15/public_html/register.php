<?php
/**
 * 新規登録 Step 1 — ライセンスキー ＆ メールアドレス入力
 */
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/users.php';
require_once __DIR__ . '/includes/license.php';

// すでにログイン済みならトップへ
if (!empty($_SESSION['uid'])) { header('Location: /index.php'); exit; }

$error = '';
$sent  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key   = strtoupper(trim($_POST['license_key'] ?? ''));
    $email = strtolower(trim($_POST['email'] ?? ''));

    // ── バリデーション ──
    if (!$key || !$email) {
        $error = 'ライセンスキーとメールアドレスを入力してください。';
    } elseif (!isAllowedEmail($email)) {
        $error = '有効なメールアドレスを入力してください。';
    } elseif (getUserByEmail($email)) {
        $error = 'そのメールアドレスはすでに登録されています。';
    } else {
        $lic = validateLicenseKey($key);
        if (!$lic) {
            $error = 'ライセンスキーが正しくありません。';
        } elseif ($lic['status'] === 'used') {
            $error = 'このライセンスキーはすでに使用されています。';
        } elseif ($lic['status'] === 'revoked') {
            $error = 'このライセンスキーは無効化されています。';
        } else {
            // 仮登録作成 → メール送信
            $token = addPending($key, $email);
            $ok    = sendVerificationMail($email, $token);
            if ($ok) {
                $sent = true;
            } else {
                $error = 'メールの送信に失敗しました。しばらく待ってから再度お試しください。';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>新規登録 — <?= SITE_NAME ?></title>
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
  /* step bar */
  .steps{display:flex;align-items:center;justify-content:center;gap:.4rem;margin-bottom:1.8rem}
  .step{display:flex;align-items:center;gap:.4rem;font-size:.78rem;letter-spacing:.1em;color:var(--fog)}
  .step .dot{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;flex-shrink:0;border:1px solid var(--fog);color:var(--fog)}
  .step.active .dot{background:var(--gold);border-color:var(--gold);color:var(--navy);font-weight:bold}
  .step.active{color:var(--gold)}
  .step-line{width:32px;height:1px;background:rgba(140,128,112,.35)}
  /* card */
  .card{background:rgba(255,255,255,.04);border:1px solid var(--border);border-radius:2px;padding:2.2rem 2.2rem 1.8rem;position:relative;backdrop-filter:blur(8px)}
  .card::before{content:'';position:absolute;top:-1px;left:2rem;right:2rem;height:2px;background:linear-gradient(to right,transparent,var(--gold),transparent)}
  .card-title{font-family:'Playfair Display',serif;font-size:1.05rem;letter-spacing:.12em;text-transform:uppercase;color:var(--mist);text-align:center;margin-bottom:1.8rem}
  /* form */
  .field{margin-bottom:1.3rem}
  label{display:block;font-size:.7rem;letter-spacing:.22em;text-transform:uppercase;color:var(--fog);margin-bottom:.4rem}
  input[type=text],input[type=email]{width:100%;background:rgba(0,0,0,.3);border:1px solid rgba(184,150,12,.2);border-radius:1px;padding:.7rem 1rem;font-family:'EB Garamond',serif;font-size:1.05rem;color:var(--cream);outline:none;transition:border-color .2s;letter-spacing:.04em}
  input::placeholder{color:rgba(214,207,194,.3)}
  input:focus{border-color:var(--gold);background:rgba(0,0,0,.4)}
  .hint{font-size:.78rem;color:var(--fog);margin-top:.35rem;font-style:italic}
  /* error / success */
  .error{background:rgba(200,16,46,.1);border:1px solid rgba(200,16,46,.35);border-radius:1px;padding:.65rem 1rem;font-size:.92rem;color:#e87a8a;margin-bottom:1.2rem;text-align:center}
  .success-box{background:rgba(76,175,133,.1);border:1px solid rgba(76,175,133,.35);border-radius:2px;padding:1.8rem 1.6rem;text-align:center}
  .success-box .icon{font-size:2.8rem;margin-bottom:.8rem;display:block}
  .success-box h3{font-family:'Playfair Display',serif;font-size:1.15rem;color:var(--gl);margin-bottom:.7rem}
  .success-box p{color:var(--mist);font-size:.97rem;line-height:1.8}
  .success-box strong{color:var(--cream)}
  /* button */
  .btn{width:100%;padding:.82rem;background:var(--red);border:none;border-radius:1px;font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.1em;color:#fff;cursor:pointer;transition:background .2s;margin-top:.4rem}
  .btn:hover{background:#a50d25}
  /* footer */
  .pg-footer{text-align:center;margin-top:1.6rem;font-size:.78rem;color:var(--fog)}
  .pg-footer a{color:var(--gold);text-decoration:none}
  .pg-footer a:hover{text-decoration:underline}
</style>
</head>
<body>
<div class="wrap">
  <div class="crest">
    <div class="crest-rule"><span></span><div class="diamond"></div><span></span></div>
    <h1>Momo's <em>London</em></h1>
    <p>New Student Registration</p>
  </div>

  <!-- Step Indicator -->
  <div class="steps">
    <div class="step active"><span class="dot">1</span><span>キー入力</span></div>
    <div class="step-line"></div>
    <div class="step"><span class="dot">2</span><span>メール確認</span></div>
    <div class="step-line"></div>
    <div class="step"><span class="dot">3</span><span>登録完了</span></div>
  </div>

  <div class="card">
    <?php if ($sent): ?>
    <!-- 送信完了 -->
    <div class="success-box">
      <span class="icon">📧</span>
      <h3>確認メールを送信しました</h3>
      <p>
        <strong><?= htmlspecialchars($_POST['email'] ?? '') ?></strong> に確認メールを送信しました。<br>
        メール内のリンクをクリックして登録を完了してください。<br><br>
        リンクの有効期限は <strong>24時間</strong> です。<br>
        メールが届かない場合は迷惑メールフォルダをご確認ください。
      </p>
    </div>

    <?php else: ?>
    <!-- 入力フォーム -->
    <div class="card-title">Step 1 — ライセンスキーとメールアドレス</div>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST" action="/register.php">
      <div class="field">
        <label>ライセンスキー</label>
        <input type="text" name="license_key"
               placeholder="ML-XXXX-XXXX-XXXX-XXXX"
               value="<?= htmlspecialchars($_POST['license_key'] ?? '') ?>"
               autocomplete="off" autocapitalize="characters" spellcheck="false">
        <p class="hint">ライセンスカードに記載されているキーを入力してください。</p>
      </div>
      <div class="field">
        <label>メールアドレス</label>
        <input type="email" name="email"
               placeholder="your@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               autocomplete="email">
        <p class="hint">確認メールが届くアドレスを入力してください。</p>
      </div>
      <button type="submit" class="btn">確認メールを送信する</button>
    </form>
    <?php endif; ?>
  </div>

  <div class="pg-footer">
    すでにアカウントをお持ちの方は <a href="/login.php">こちらからログイン</a>
  </div>
</div>
</body>
</html>
