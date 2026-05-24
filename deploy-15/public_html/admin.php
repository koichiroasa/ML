<?php
/**
 * Momo's London — 管理者パネル
 * ライセンスキー管理 ＋ ユーザー管理
 */
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/users.php';
require_once __DIR__ . '/includes/license.php';

define('ADMIN_PW', 'admin_secret_change_me'); // ← 必ず変更！

// ── 認証 ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_pass'])) {
    if ($_POST['admin_pass'] === ADMIN_PW) $_SESSION['admin_auth'] = true;
    else $login_error = '管理者パスワードが違います。';
}
if (isset($_POST['admin_logout'])) { unset($_SESSION['admin_auth']); }
$auth = !empty($_SESSION['admin_auth']);

// ── アクション ────────────────────────────────────────
$flash = null;
if ($auth && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── ライセンスキー生成 ──
    if (($_POST['action'] ?? '') === 'gen_keys') {
        $count = max(1, min(200, (int)($_POST['count'] ?? 10)));
        $keys  = generateKeys($count);
        $flash = ['type' => 'ok', 'msg' => "{$count}枚のライセンスキーを生成しました。"];
    }

    // ── ライセンスキー無効化 ──
    if (($_POST['action'] ?? '') === 'revoke_key') {
        $key = strtoupper(trim($_POST['key'] ?? ''));
        revokeKey($key);
        $flash = ['type' => 'ok', 'msg' => "キー「{$key}」を無効化しました。"];
    }

    // ── ユーザー追加（教員手動登録） ──
    if (($_POST['action'] ?? '') === 'add_user') {
        $uid  = trim($_POST['uid'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $role = $_POST['role'] ?? 'student';
        $pw   = $_POST['password'] ?? '';
        $email = trim($_POST['email'] ?? '');
        if (!$uid || !$name || !$pw) {
            $flash = ['type' => 'error', 'msg' => 'ID・氏名・パスワードは必須です。'];
        } elseif (strlen($pw) < 8) {
            $flash = ['type' => 'error', 'msg' => 'パスワードは8文字以上にしてください。'];
        } elseif (getUserByUid($uid)) {
            $flash = ['type' => 'error', 'msg' => "ID「{$uid}」はすでに登録されています。"];
        } else {
            $users = loadUsers();
            $users[] = [
                'uid'           => $uid,
                'name'          => $name,
                'email'         => $email,
                'role'          => $role,
                'password'      => password_hash($pw, PASSWORD_DEFAULT),
                'license_key'   => 'MANUAL',
                'session_token' => '',
                'created_at'    => date('Y-m-d H:i:s'),
            ];
            saveUsers($users);
            $flash = ['type' => 'ok', 'msg' => "「{$name}」を追加しました。"];
        }
    }

    // ── ユーザー編集 ──
    if (($_POST['action'] ?? '') === 'edit_user') {
        $uid    = trim($_POST['uid'] ?? '');
        $name   = trim($_POST['name'] ?? '');
        $role   = $_POST['role'] ?? 'student';
        $new_pw = $_POST['password'] ?? '';
        if (!$uid || !$name) {
            $flash = ['type' => 'error', 'msg' => 'ID・氏名は必須です。'];
        } elseif ($new_pw && strlen($new_pw) < 8) {
            $flash = ['type' => 'error', 'msg' => 'パスワードは8文字以上にしてください。'];
        } else {
            $users = loadUsers();
            foreach ($users as &$u) {
                if ($u['uid'] === $uid) {
                    $u['name'] = $name;
                    $u['role'] = $role;
                    if ($new_pw) $u['password'] = password_hash($new_pw, PASSWORD_DEFAULT);
                    break;
                }
            }
            unset($u);
            saveUsers($users);
            $flash = ['type' => 'ok', 'msg' => "「{$name}」を更新しました。"];
            header('Location: /admin.php?tab=users&updated=1'); exit;
        }
    }

    // ── ユーザー削除 ──
    if (($_POST['action'] ?? '') === 'delete_user') {
        $uid   = trim($_POST['uid'] ?? '');
        $users = array_values(array_filter(loadUsers(), fn($u) => $u['uid'] !== $uid));
        saveUsers($users);
        $flash = ['type' => 'ok', 'msg' => "ID「{$uid}」を削除しました。"];
    }

    // ── ユーザー強制ログアウト（セッション無効化） ──
    if (($_POST['action'] ?? '') === 'force_logout') {
        $uid   = trim($_POST['uid'] ?? '');
        $users = loadUsers();
        foreach ($users as &$u) {
            if ($u['uid'] === $uid) { $u['session_token'] = ''; break; }
        }
        unset($u);
        saveUsers($users);
        $flash = ['type' => 'ok', 'msg' => "「{$uid}」を強制ログアウトしました。"];
    }

    // ── CSV一括インポート ──
    if (($_POST['action'] ?? '') === 'csv_import') {
        $csv = trim($_POST['csv_data'] ?? '');
        $rows = explode("\n", $csv);
        $users = loadUsers();
        $exist = array_column($users, 'uid');
        $added = $skipped = 0;
        foreach ($rows as $row) {
            $row = trim($row); if (!$row) continue;
            $p = str_getcsv($row);
            if (count($p) < 4) { $skipped++; continue; }
            [$uid, $name, $role, $pw] = array_map('trim', $p);
            $email = isset($p[4]) ? trim($p[4]) : '';
            if (in_array($uid, $exist, true) || strlen($pw) < 8) { $skipped++; continue; }
            if (!in_array($role, ['student','teacher','free'], true)) $role = 'student';
            $users[] = ['uid'=>$uid,'name'=>$name,'email'=>$email,'role'=>$role,
                        'password'=>password_hash($pw,PASSWORD_DEFAULT),
                        'license_key'=>'MANUAL','session_token'=>'',
                        'created_at'=>date('Y-m-d H:i:s')];
            $exist[] = $uid; $added++;
        }
        saveUsers($users);
        $flash = ['type' => 'ok', 'msg' => "{$added}件追加、{$skipped}件スキップしました。"];
    }
}

// URL パラメータによるフラッシュ
if (!$flash && isset($_GET['updated'])) $flash = ['type'=>'ok','msg'=>'ユーザー情報を更新しました。'];

$tab      = $_GET['tab'] ?? 'licenses';
$users    = loadUsers();
$licenses = loadLicenses();
$pending  = cleanPending();

$role_lbl = ['student'=>'学生','teacher'=>'教員','free'=>'停止'];
$role_col = ['student'=>'#B8960C','teacher'=>'#4CAF85','free'=>'#8C8070'];
$lic_col  = ['unused'=>'#4CAF85','used'=>'#8C8070','revoked'=>'#e87a8a'];
$lic_lbl  = ['unused'=>'未使用','used'=>'使用済み','revoked'=>'無効'];

// 編集モード
$edit_user = null;
if ($auth && isset($_GET['edit'])) $edit_user = getUserByUid($_GET['edit']);

// CSV ダウンロード（ライセンスキー）
if ($auth && isset($_GET['dl']) && $_GET['dl'] === 'licenses') {
    $filter = $_GET['filter'] ?? 'unused';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="licenses_' . date('Ymd') . '.csv"');
    echo "\xEF\xBB\xBF"; // BOM
    echo "ライセンスキー,状態,使用者ID,メール,生成日,使用日\n";
    foreach ($licenses as $l) {
        if ($filter !== 'all' && $l['status'] !== $filter) continue;
        echo implode(',', [
            $l['key'], $lic_lbl[$l['status']] ?? $l['status'],
            $l['uid'] ?? '', $l['email'] ?? '',
            $l['created_at'], $l['used_at'] ?? ''
        ]) . "\n";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>管理者パネル — <?= SITE_NAME ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=EB+Garamond:ital,wght@0,400;1,400&display=swap" rel="stylesheet">
<style>
  :root{--navy:#0D1B2A;--cream:#F4EFE4;--gold:#B8960C;--red:#C8102E;--fog:#8C8070;--mist:#D6CFC2;--gl:#4CAF85;--border:rgba(184,150,12,.2);--bg2:rgba(255,255,255,.03)}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{background:var(--navy);color:var(--cream);font-family:'EB Garamond',serif;min-height:100vh}
  /* header */
  header{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 2.5rem;border-bottom:1px solid var(--border);background:rgba(13,27,42,.9);position:sticky;top:0;z-index:100;backdrop-filter:blur(10px)}
  .logo{font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--cream);text-decoration:none}
  .logo em{color:var(--gold);font-style:italic}
  .hdr-r{display:flex;align-items:center;gap:.8rem}
  .hbtn{font-size:.72rem;letter-spacing:.15em;text-transform:uppercase;color:var(--fog);text-decoration:none;border:1px solid rgba(140,128,112,.35);padding:.28rem .75rem;border-radius:1px;cursor:pointer;background:none;font-family:'EB Garamond',serif;transition:color .2s,border-color .2s}
  .hbtn:hover{color:var(--cream);border-color:var(--mist)}
  /* layout */
  .wrap{max-width:1040px;margin:0 auto;padding:2rem 2rem 6rem}
  .ptitle{font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:700}
  .ptitle em{color:var(--gold);font-style:italic}
  .psub{font-size:.78rem;letter-spacing:.2em;text-transform:uppercase;color:var(--fog);margin:.3rem 0 1.8rem}
  /* tabs */
  .tabs{display:flex;gap:0;border-bottom:1px solid var(--border);margin-bottom:1.8rem}
  .tab{padding:.65rem 1.4rem;font-size:.82rem;letter-spacing:.12em;text-transform:uppercase;color:var(--fog);text-decoration:none;border-bottom:2px solid transparent;transition:color .2s,border-color .2s}
  .tab:hover{color:var(--mist)}
  .tab.active{color:var(--gold);border-bottom-color:var(--gold)}
  /* flash */
  .flash{padding:.72rem 1.1rem;border-radius:2px;margin-bottom:1.4rem;font-size:.96rem}
  .flash.ok{background:rgba(76,175,133,.1);border:1px solid rgba(76,175,133,.3);color:#7ec8a4}
  .flash.error{background:rgba(200,16,46,.1);border:1px solid rgba(200,16,46,.3);color:#e87a8a}
  /* srule */
  .srule{display:flex;align-items:center;gap:.9rem;margin:2rem 0 1.1rem}
  .srule span{font-family:'Playfair Display',serif;font-size:.9rem;letter-spacing:.14em;text-transform:uppercase;color:var(--mist);white-space:nowrap}
  .srule hr{flex:1;border:none;border-top:1px solid var(--border)}
  /* card */
  .card{background:var(--bg2);border:1px solid var(--border);border-radius:3px;padding:1.5rem 1.8rem;margin-bottom:1.4rem}
  /* form */
  .frow{display:grid;gap:.8rem;margin-bottom:.9rem}
  .frow2{grid-template-columns:1fr 1fr}
  .frow3{grid-template-columns:1fr 1fr 1fr}
  .frow4{grid-template-columns:2fr 2fr 1fr 2fr}
  .frow5{grid-template-columns:2fr 2fr 1fr 2fr 2fr}
  .field label{display:block;font-size:.68rem;letter-spacing:.22em;text-transform:uppercase;color:var(--fog);margin-bottom:.38rem}
  input[type=text],input[type=password],input[type=email],input[type=number],select,textarea{width:100%;background:rgba(0,0,0,.28);border:1px solid rgba(184,150,12,.2);color:var(--cream);padding:.6rem .9rem;font-family:'EB Garamond',serif;font-size:1rem;outline:none;border-radius:1px;transition:border-color .2s}
  input:focus,select:focus,textarea:focus{border-color:var(--gold)}
  select option{background:#1a2d40}
  textarea{resize:vertical;font-family:monospace;font-size:.88rem}
  .hint{font-size:.78rem;color:var(--fog);font-style:italic;margin-top:.28rem}
  /* buttons */
  .btn{display:inline-flex;align-items:center;gap:.35rem;padding:.55rem 1.2rem;border:none;border-radius:1px;font-family:'Playfair Display',serif;font-size:.88rem;cursor:pointer;transition:background .18s,transform .1s}
  .btn:active{transform:scale(.98)}
  .btn-red{background:var(--red);color:#fff}
  .btn-red:hover{background:#a50d25}
  .btn-green{background:rgba(76,175,133,.18);color:var(--gl);border:1px solid rgba(76,175,133,.35)}
  .btn-green:hover{background:rgba(76,175,133,.3)}
  .btn-gold{background:rgba(184,150,12,.18);color:var(--gold);border:1px solid rgba(184,150,12,.35)}
  .btn-gold:hover{background:rgba(184,150,12,.3)}
  .btn-ghost{background:transparent;color:var(--fog);border:1px solid rgba(140,128,112,.35);font-family:'EB Garamond',serif;font-size:.88rem;padding:.42rem .9rem;text-decoration:none;display:inline-flex;align-items:center}
  .btn-ghost:hover{color:var(--cream);border-color:var(--mist)}
  .btn-del{background:transparent;color:#e87a8a;border:1px solid rgba(200,16,46,.28);font-family:'EB Garamond',serif;font-size:.8rem;padding:.3rem .65rem;border-radius:1px;cursor:pointer;white-space:nowrap}
  .btn-del:hover{background:rgba(200,16,46,.1)}
  .btn-edit{background:transparent;color:var(--gold);border:1px solid rgba(184,150,12,.3);font-family:'EB Garamond',serif;font-size:.8rem;padding:.3rem .65rem;border-radius:1px;text-decoration:none;white-space:nowrap}
  .btn-edit:hover{background:rgba(184,150,12,.1)}
  .btn-sm-red{background:transparent;color:#e87a8a;border:1px solid rgba(200,16,46,.28);font-family:'EB Garamond',serif;font-size:.78rem;padding:.22rem .55rem;border-radius:1px;cursor:pointer;white-space:nowrap}
  .btn-sm-red:hover{background:rgba(200,16,46,.1)}
  /* tables */
  .tbl{width:100%;border-collapse:collapse;font-size:.93rem}
  .tbl th{text-align:left;font-size:.65rem;letter-spacing:.22em;text-transform:uppercase;color:var(--fog);border-bottom:1px solid var(--border);padding:.5rem .8rem}
  .tbl td{padding:.68rem .8rem;border-bottom:1px solid rgba(255,255,255,.04);color:var(--mist);vertical-align:middle}
  .tbl tr:last-child td{border-bottom:none}
  .tbl tr:hover td{background:rgba(255,255,255,.02)}
  .badge{font-size:.63rem;letter-spacing:.15em;text-transform:uppercase;padding:.16rem .52rem;border-radius:999px;white-space:nowrap}
  .acts{display:flex;align-items:center;gap:.45rem;flex-wrap:wrap}
  code{font-family:monospace;font-size:.88rem;letter-spacing:.06em}
  /* stats pills */
  .stats-row{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.4rem}
  .stat{background:var(--bg2);border:1px solid var(--border);border-radius:2px;padding:.4rem .9rem;font-size:.82rem}
  .stat .n{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--gold);display:block;line-height:1.1}
  .stat .l{color:var(--fog);font-size:.72rem;letter-spacing:.1em}
  /* login box */
  .login-box{max-width:400px;margin:5rem auto;padding:0 1.5rem}
  /* filter bar */
  .filter-bar{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem;align-items:center}
  .filter-bar label{font-size:.78rem;color:var(--fog)}
  .filter-bar select{width:auto;padding:.3rem .7rem;font-size:.85rem}
  @media(max-width:720px){
    header{padding:1rem 1.2rem}.wrap{padding:1.5rem 1rem 4rem}
    .frow2,.frow3,.frow4,.frow5{grid-template-columns:1fr}
  }
</style>
</head>
<body>
<header>
  <a href="/index.php" class="logo">Momo's <em>London</em></a>
  <div class="hdr-r">
    <?php if ($auth): ?>
      <form method="POST" style="margin:0"><button name="admin_logout" class="hbtn">ログアウト</button></form>
    <?php endif; ?>
    <a href="/index.php" class="hbtn">← サイトへ</a>
  </div>
</header>

<?php if (!$auth): ?>
<!-- ══ ログイン ══ -->
<div class="login-box">
  <div style="text-align:center;margin-bottom:2rem">
    <p style="font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700">管理者<em style="color:var(--gold);font-style:italic">パネル</em></p>
    <p style="font-size:.78rem;letter-spacing:.2em;text-transform:uppercase;color:var(--fog);margin-top:.3rem">Administrator Only</p>
  </div>
  <?php if (isset($login_error)): ?><div class="flash error"><?= htmlspecialchars($login_error) ?></div><?php endif; ?>
  <div class="card">
    <form method="POST">
      <div class="field" style="margin-bottom:1.1rem">
        <label>管理者パスワード</label>
        <input type="password" name="admin_pass" autofocus placeholder="••••••••">
      </div>
      <button class="btn btn-red" style="width:100%;justify-content:center">ログイン</button>
    </form>
  </div>
</div>

<?php else: ?>
<!-- ══ 管理画面 ══ -->
<div class="wrap">
  <p class="ptitle">管理者<em>パネル</em></p>
  <p class="psub">Momo's London — Admin</p>

  <?php if ($flash): ?>
    <div class="flash <?= $flash['type'] ?>"><?= htmlspecialchars($flash['msg']) ?></div>
  <?php endif; ?>

  <!-- タブ -->
  <div class="tabs">
    <a class="tab <?= $tab==='licenses' ? 'active' : '' ?>" href="?tab=licenses">🔑 ライセンスキー</a>
    <a class="tab <?= $tab==='users'    ? 'active' : '' ?>" href="?tab=users">👥 ユーザー管理</a>
    <a class="tab <?= $tab==='pending'  ? 'active' : '' ?>" href="?tab=pending">⏳ 認証待ち（<?= count($pending) ?>）</a>
  </div>

  <?php /* ━━━━━━━━━━━━━━━━━━━━━━━━━
         TAB 1 — ライセンスキー管理
         ━━━━━━━━━━━━━━━━━━━━━━━━━ */ ?>
  <?php if ($tab === 'licenses'): ?>

  <!-- 統計 -->
  <?php
    $cnt_unused  = count(array_filter($licenses, fn($l) => $l['status']==='unused'));
    $cnt_used    = count(array_filter($licenses, fn($l) => $l['status']==='used'));
    $cnt_revoked = count(array_filter($licenses, fn($l) => $l['status']==='revoked'));
  ?>
  <div class="stats-row">
    <div class="stat"><span class="n"><?= count($licenses) ?></span><span class="l">総キー数</span></div>
    <div class="stat"><span class="n" style="color:var(--gl)"><?= $cnt_unused ?></span><span class="l">未使用</span></div>
    <div class="stat"><span class="n" style="color:var(--fog)"><?= $cnt_used ?></span><span class="l">使用済み</span></div>
    <div class="stat"><span class="n" style="color:#e87a8a"><?= $cnt_revoked ?></span><span class="l">無効</span></div>
  </div>

  <!-- キー生成 -->
  <div class="srule"><span>➕ キーを生成する</span><hr></div>
  <div class="card">
    <form method="POST" style="display:flex;align-items:flex-end;gap:1rem;flex-wrap:wrap">
      <input type="hidden" name="action" value="gen_keys">
      <div class="field" style="margin-bottom:0;min-width:160px">
        <label>生成枚数（最大200）</label>
        <input type="number" name="count" value="30" min="1" max="200">
      </div>
      <button class="btn btn-red">🔑 ライセンスキーを生成</button>
    </form>
    <p class="hint" style="margin-top:.8rem">生成後、下の「未使用キーをCSV出力」ボタンで印刷用データをダウンロードできます。</p>
  </div>

  <!-- CSV ダウンロード -->
  <div style="display:flex;gap:.6rem;margin-bottom:1.2rem;flex-wrap:wrap">
    <a href="?dl=licenses&filter=unused"  class="btn btn-green">📥 未使用キーをCSV出力</a>
    <a href="?dl=licenses&filter=used"    class="btn btn-ghost">📥 使用済みCSV</a>
    <a href="?dl=licenses&filter=all"     class="btn btn-ghost">📥 全キーCSV</a>
  </div>

  <!-- キー一覧 -->
  <div class="srule"><span>🔑 キー一覧（直近100件）</span><hr></div>
  <div class="filter-bar">
    <label>絞り込み:</label>
    <select onchange="filterTable(this.value)" id="lic-filter">
      <option value="all">すべて</option>
      <option value="unused" selected>未使用</option>
      <option value="used">使用済み</option>
      <option value="revoked">無効</option>
    </select>
  </div>
  <div class="card" style="padding:0;overflow:hidden">
    <table class="tbl" id="lic-table">
      <thead>
        <tr>
          <th>ライセンスキー</th>
          <th>状態</th>
          <th>使用者</th>
          <th>メール</th>
          <th>使用日時</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $shown = array_slice(array_reverse($licenses), 0, 100);
        foreach ($shown as $l):
          $col = $lic_col[$l['status']] ?? '#fff';
          $lbl = $lic_lbl[$l['status']] ?? $l['status'];
      ?>
        <tr data-status="<?= $l['status'] ?>">
          <td><code><?= htmlspecialchars($l['key']) ?></code></td>
          <td><span class="badge" style="background:<?= $col ?>22;color:<?= $col ?>;border:1px solid <?= $col ?>44"><?= $lbl ?></span></td>
          <td><?= htmlspecialchars($l['uid'] ?? '—') ?></td>
          <td style="font-size:.82rem"><?= htmlspecialchars($l['email'] ?? '—') ?></td>
          <td style="font-size:.78rem;color:var(--fog)"><?= htmlspecialchars($l['used_at'] ?? '—') ?></td>
          <td>
            <?php if ($l['status'] !== 'revoked'): ?>
            <form method="POST" style="margin:0" onsubmit="return confirm('「<?= htmlspecialchars($l['key']) ?>」を無効化しますか？')">
              <input type="hidden" name="action" value="revoke_key">
              <input type="hidden" name="key" value="<?= htmlspecialchars($l['key']) ?>">
              <button class="btn-sm-red">無効化</button>
            </form>
            <?php else: ?><span style="color:var(--fog);font-size:.8rem">—</span><?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php /* ━━━━━━━━━━━━━━━━━━━━━━
         TAB 2 — ユーザー管理
         ━━━━━━━━━━━━━━━━━━━━━━ */ ?>
  <?php elseif ($tab === 'users'): ?>

  <!-- ユーザー一覧 -->
  <div class="srule"><span>👥 登録ユーザー一覧（<?= count($users) ?>件）</span><hr></div>
  <div class="card" style="padding:0;overflow:hidden">
    <table class="tbl">
      <thead>
        <tr><th>学籍番号/ID</th><th>氏名</th><th>メール</th><th>ロール</th><th>ライセンスキー</th><th>登録日</th><th>操作</th></tr>
      </thead>
      <tbody>
      <?php foreach ($users as $u):
        $col = $role_col[$u['role']] ?? '#fff';
        $lbl = $role_lbl[$u['role']] ?? $u['role'];
      ?>
        <tr>
          <td><code><?= htmlspecialchars($u['uid']) ?></code></td>
          <td><?= htmlspecialchars($u['name']) ?></td>
          <td style="font-size:.82rem"><?= htmlspecialchars($u['email'] ?? '—') ?></td>
          <td><span class="badge" style="background:<?= $col ?>22;color:<?= $col ?>;border:1px solid <?= $col ?>44"><?= $lbl ?></span></td>
          <td style="font-size:.78rem;color:var(--fog)"><?= htmlspecialchars($u['license_key'] ?? '—') ?></td>
          <td style="font-size:.78rem;color:var(--fog)"><?= htmlspecialchars($u['created_at'] ?? '—') ?></td>
          <td>
            <div class="acts">
              <a href="?tab=users&edit=<?= urlencode($u['uid']) ?>" class="btn-edit">✏️ 編集</a>
              <form method="POST" style="margin:0" onsubmit="return confirm('「<?= htmlspecialchars($u['name']) ?>」を強制ログアウトしますか？')">
                <input type="hidden" name="action" value="force_logout">
                <input type="hidden" name="uid" value="<?= htmlspecialchars($u['uid']) ?>">
                <button class="btn-sm-red" title="別端末で不正利用中の場合などに使用">🚫 強制ログアウト</button>
              </form>
              <form method="POST" style="margin:0" onsubmit="return confirm('「<?= htmlspecialchars($u['name']) ?>」を削除しますか？')">
                <input type="hidden" name="action" value="delete_user">
                <input type="hidden" name="uid" value="<?= htmlspecialchars($u['uid']) ?>">
                <button class="btn-del">🗑 削除</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- 編集フォーム -->
  <?php if ($edit_user): ?>
  <div class="srule"><span>✏️ ユーザー編集</span><hr></div>
  <div class="card" style="border-color:rgba(184,150,12,.4)">
    <p style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--mist);margin-bottom:1.2rem">「<?= htmlspecialchars($edit_user['name']) ?>」を編集</p>
    <form method="POST">
      <input type="hidden" name="action" value="edit_user">
      <input type="hidden" name="uid" value="<?= htmlspecialchars($edit_user['uid']) ?>">
      <div class="frow frow3">
        <div class="field">
          <label>ID（変更不可）</label>
          <input type="text" value="<?= htmlspecialchars($edit_user['uid']) ?>" disabled style="opacity:.45">
        </div>
        <div class="field">
          <label>氏名</label>
          <input type="text" name="name" value="<?= htmlspecialchars($edit_user['name']) ?>" required>
        </div>
        <div class="field">
          <label>ロール</label>
          <select name="role">
            <option value="student" <?= $edit_user['role']==='student'?'selected':'' ?>>学生</option>
            <option value="teacher" <?= $edit_user['role']==='teacher'?'selected':'' ?>>教員</option>
            <option value="free"    <?= $edit_user['role']==='free'   ?'selected':'' ?>>停止</option>
          </select>
        </div>
      </div>
      <div class="frow" style="max-width:320px">
        <div class="field">
          <label>新しいパスワード（空欄＝変更なし、8文字以上）</label>
          <input type="password" name="password" placeholder="空欄 = 変更しない">
        </div>
      </div>
      <div style="display:flex;gap:.8rem;margin-top:.4rem;flex-wrap:wrap">
        <button class="btn btn-green">💾 更新する</button>
        <a href="?tab=users" class="btn-ghost">キャンセル</a>
      </div>
    </form>
  </div>
  <?php endif; ?>

  <!-- 手動ユーザー追加（教員など） -->
  <div class="srule"><span>➕ 手動でユーザーを追加（教員・特例登録）</span><hr></div>
  <div class="card">
    <p style="color:var(--fog);font-size:.88rem;margin-bottom:1rem">通常は学生がライセンスキーで自己登録します。教員アカウントや特例の場合はここから追加してください。</p>
    <form method="POST">
      <input type="hidden" name="action" value="add_user">
      <div class="frow frow5">
        <div class="field"><label>ID / 学籍番号</label><input type="text" name="uid" placeholder="例: T003"></div>
        <div class="field"><label>氏名</label><input type="text" name="name" placeholder="例: 鈴木 先生"></div>
        <div class="field"><label>ロール</label>
          <select name="role">
            <option value="student">学生</option>
            <option value="teacher">教員</option>
            <option value="free">停止</option>
          </select>
        </div>
        <div class="field"><label>パスワード（8文字以上）</label><input type="password" name="password" placeholder="••••••••"></div>
        <div class="field"><label>メール（任意）</label><input type="email" name="email" placeholder="teacher@example.com"></div>
      </div>
      <button class="btn btn-red">➕ 追加する</button>
    </form>
  </div>

  <!-- CSV一括 -->
  <div class="srule"><span>📋 CSV一括インポート</span><hr></div>
  <div class="card">
    <p style="color:var(--fog);font-size:.88rem;margin-bottom:.8rem">フォーマット: <code style="color:var(--gold)">学籍番号,氏名,ロール,パスワード[,メール]</code><br>パスワードは8文字以上。IDが重複する行はスキップ。</p>
    <form method="POST">
      <input type="hidden" name="action" value="csv_import">
      <div class="field" style="margin-bottom:1rem">
        <label>CSVデータを貼り付け</label>
        <textarea name="csv_data" rows="6" placeholder="T004,山田先生,teacher,password123,yamada@univ.ac.jp&#10;T005,田中先生,teacher,password456,tanaka@univ.ac.jp"></textarea>
      </div>
      <button class="btn btn-green">📥 一括追加する</button>
    </form>
  </div>

  <?php /* ━━━━━━━━━━━━━━━━━━━━━━━━━━━
         TAB 3 — メール認証待ち
         ━━━━━━━━━━━━━━━━━━━━━━━━━━━ */ ?>
  <?php elseif ($tab === 'pending'): ?>

  <div class="srule"><span>⏳ メール認証待ち（<?= count($pending) ?>件）</span><hr></div>
  <?php if (empty($pending)): ?>
    <p style="color:var(--fog);padding:1.5rem 0">認証待ちのアカウントはありません。</p>
  <?php else: ?>
  <div class="card" style="padding:0;overflow:hidden">
    <table class="tbl">
      <thead>
        <tr><th>ライセンスキー</th><th>メールアドレス</th><th>申請日時</th><th>残り時間</th></tr>
      </thead>
      <tbody>
      <?php foreach ($pending as $p):
        $elapsed = time() - strtotime($p['created_at']);
        $remain  = max(0, PENDING_TTL - $elapsed);
        $h = floor($remain/3600); $m = floor(($remain%3600)/60);
      ?>
        <tr>
          <td><code><?= htmlspecialchars($p['key']) ?></code></td>
          <td><?= htmlspecialchars($p['email']) ?></td>
          <td style="font-size:.8rem;color:var(--fog)"><?= htmlspecialchars($p['created_at']) ?></td>
          <td style="font-size:.85rem;color:<?= $remain < 3600 ? '#e87a8a' : 'var(--mist)' ?>"><?= "{$h}時間{$m}分" ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <p style="color:var(--fog);font-size:.82rem;margin-top:.6rem">※ 有効期限（24時間）を過ぎた仮登録は自動的に削除されます。</p>
  <?php endif; ?>

  <?php endif; // end tabs ?>

</div><!-- /.wrap -->
<?php endif; ?>

<script>
// ライセンスキー絞り込みフィルター
function filterTable(val) {
  document.querySelectorAll('#lic-table tbody tr').forEach(tr => {
    tr.style.display = (val === 'all' || tr.dataset.status === val) ? '' : 'none';
  });
}
// 初期表示: 未使用のみ
document.addEventListener('DOMContentLoaded', () => {
  const f = document.getElementById('lic-filter');
  if (f) filterTable(f.value);
});
</script>
</body>
</html>
