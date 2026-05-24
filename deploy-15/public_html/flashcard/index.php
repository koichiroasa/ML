<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/flashcard_manager.php';

/* ═══════════════════════════════════════════════════════════
   設定
═══════════════════════════════════════════════════════════ */
$csv_file = isset($_GET['csv']) ? basename($_GET['csv']) : 'vocab.csv';
$manager = new FlashcardManager($current_uid, $csv_file);

// データ読み込み（マイグレーション含む）
$data = $manager->loadData();

/* ═══════════════════════════════════════════════════════════
   API: POST リクエスト処理
═══════════════════════════════════════════════════════════ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['api'])) {
    header('Content-Type: application/json; charset=UTF-8');
    
    $action = $_POST['api'];
    $word   = $_POST['word'] ?? '';
    $mode   = $_POST['mode'] ?? 'order';
    $index  = isset($_POST['index']) ? (int)$_POST['index'] : 0;

    if ($action === 'mark_learned') {
        // 「覚えた」を選択
        if ($word) {
            $data = $manager->markAsLearned($word, $data);
            $manager->saveData($data);
        }
        echo json_encode(['ok' => true, 'stats' => $manager->getStats($data)]);

    } elseif ($action === 'mark_review') {
        // 「要復習」を選択
        if ($word) {
            $data = $manager->markAsReview($word, $data);
            $manager->saveData($data);
        }
        echo json_encode(['ok' => true, 'stats' => $manager->getStats($data)]);

    } elseif ($action === 'save_session') {
        // セッション位置を保存（次回の「続きから」用）
        $data = $manager->saveLastSession($data, $mode, $index);
        $manager->saveData($data);
        echo json_encode(['ok' => true]);

    } elseif ($action === 'reset') {
        // 進捗をリセット
        $data = $manager->resetProgress($data);
        $manager->saveData($data);
        echo json_encode(['ok' => true]);

    } elseif ($action === 'get_data') {
        // 最新データを取得
        echo json_encode($data);
    }

    exit;
}

/* ═══════════════════════════════════════════════════════════
   ページレンダリング用データ
═══════════════════════════════════════════════════════════ */
$stats = $manager->getStats($data);
$lastSession = $manager->getLastSession($data);

$data_json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Flashcards — <?= htmlspecialchars($csv_file) ?> | Momo's London</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
/* ── CSS変数 ── */
:root {
  --navy:    #0D1B2A;
  --cream:   #F4EFE4;
  --gold:    #B8960C;
  --red:     #C8102E;
  --mist:    #D6CFC2;
  --fog:     #8C8070;
  --gl:      #4CAF85;
  --teal:    #1F7A8C;
  --bg2:     rgba(255,255,255,0.04);
  --border:  rgba(184,150,12,0.2);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
  background: var(--navy);
  background-image: radial-gradient(ellipse 100% 40% at 50% 0%, #162030 0%, transparent 55%);
  font-family: 'EB Garamond', Georgia, serif;
  color: var(--cream);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* ── HEADER ── */
header {
  border-bottom: 1px solid var(--border);
  padding: .9rem 2rem;
  display: flex; align-items: center; justify-content: space-between;
  position: sticky; top: 0; z-index: 100;
  background: rgba(13,27,42,.92); backdrop-filter: blur(12px);
}
.site-title { font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; color: var(--cream); text-decoration: none; }
.site-title em { font-style: italic; color: var(--gold); }
.hdr-r { display: flex; align-items: center; gap: .8rem; }
.btn-sm { font-size: .7rem; letter-spacing: .14em; text-transform: uppercase; color: var(--fog); text-decoration: none; border: 1px solid rgba(140,128,112,.4); padding: .26rem .7rem; border-radius: 1px; transition: color .2s, border-color .2s; cursor: pointer; background: none; font-family: 'EB Garamond', serif; }
.btn-sm:hover { color: var(--cream); border-color: var(--mist); }

/* ── SETUP SCREEN ── */
#setup-screen {
  flex: 1; display: flex; align-items: center; justify-content: center;
  padding: 2rem; animation: fadeIn .6s ease both;
}
.setup-card {
  width: 100%; max-width: 520px;
  background: var(--bg2); border: 1px solid var(--border); border-radius: 3px;
  padding: 2.5rem 2.5rem 2rem; position: relative;
}
.setup-card::before {
  content: ''; position: absolute; top: -1px; left: 2rem; right: 2rem;
  height: 2px; background: linear-gradient(to right, transparent, var(--gold), transparent);
}
.setup-title { font-family: 'Playfair Display', serif; font-size: 1.4rem; font-weight: 700; margin-bottom: .3rem; }
.setup-title em { font-style: italic; color: var(--gold); }
.setup-meta { color: var(--fog); font-size: .85rem; margin-bottom: 1.8rem; }

.mode-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: .7rem; margin-bottom: 1.6rem; }
.mode-btn {
  background: rgba(255,255,255,.03); border: 1px solid var(--border);
  border-radius: 2px; padding: 1rem .8rem; text-align: center;
  cursor: pointer; transition: all .2s; color: var(--mist);
}
.mode-btn:hover { background: rgba(184,150,12,.1); border-color: rgba(184,150,12,.4); }
.mode-btn.selected { background: rgba(184,150,12,.15); border-color: var(--gold); color: var(--gold); }
.mode-btn .mode-icon { font-size: 1.6rem; display: block; margin-bottom: .4rem; }
.mode-btn .mode-label { font-family: 'Playfair Display', serif; font-size: .92rem; display: block; }
.mode-btn .mode-sub { font-size: .75rem; color: var(--fog); display: block; margin-top: .2rem; }

.start-btn {
  width: 100%; padding: .85rem; background: var(--red); border: none; border-radius: 1px;
  font-family: 'Playfair Display', serif; font-size: 1rem; letter-spacing: .1em;
  color: #fff; cursor: pointer; transition: background .2s;
}
.start-btn:hover { background: #a50d25; }
.start-btn:disabled { background: rgba(140,128,112,.3); color: var(--fog); cursor: not-allowed; }

.stats-row { display: flex; gap: .6rem; margin-bottom: 1.4rem; flex-wrap: wrap; }
.stat-pill { background: rgba(255,255,255,.03); border: 1px solid var(--border); border-radius: 1px; padding: .3rem .8rem; font-size: .82rem; }
.stat-pill .n { font-family: 'Playfair Display', serif; font-size: 1.1rem; color: var(--gold); }
.stat-pill.green .n { color: var(--gl); }
.stat-pill.red-c .n { color: #e87a8a; }

/* ── STUDY SCREEN ── */
#study-screen { flex: 1; display: none; flex-direction: column; align-items: center; padding: 1.5rem 1.5rem 2rem; }

/* Progress bar */
.progress-wrap { width: 100%; max-width: 680px; margin-bottom: 1rem; }
.progress-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: .4rem; font-size: .82rem; color: var(--fog); }
.progress-top .counter { font-family: 'Playfair Display', serif; font-size: 1rem; color: var(--mist); }
.progress-bar { height: 4px; background: rgba(255,255,255,.06); border-radius: 2px; overflow: hidden; }
.progress-fill { height: 100%; background: linear-gradient(to right, var(--gold), #d4a820); border-radius: 2px; transition: width .4s ease; }

/* Flashcard */
.card-scene { width: 100%; max-width: 680px; perspective: 1200px; flex-shrink: 0; }
.card-wrap {
  position: relative; width: 100%;
  padding-top: min(60%, 340px);
  cursor: pointer;
  transform-style: preserve-3d;
  transition: transform .55s cubic-bezier(0.4, 0, 0.2, 1);
}
.card-wrap.flipped { transform: rotateY(180deg); }
.card-face {
  position: absolute; inset: 0;
  border-radius: 4px;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 2rem;
  backface-visibility: hidden;
  -webkit-backface-visibility: hidden;
}
.card-front {
  background: linear-gradient(135deg, rgba(255,255,255,.05) 0%, rgba(255,255,255,.02) 100%);
  border: 1px solid rgba(184,150,12,.25);
}
.card-front::before {
  content: ''; position: absolute; top: -1px; left: 15%; right: 15%;
  height: 2px; background: linear-gradient(to right, transparent, var(--gold), transparent);
}
.card-back {
  background: linear-gradient(135deg, rgba(76,175,133,.07) 0%, rgba(76,175,133,.03) 100%);
  border: 1px solid rgba(76,175,133,.2);
  transform: rotateY(180deg);
}
.card-back::before {
  content: ''; position: absolute; top: -1px; left: 15%; right: 15%;
  height: 2px; background: linear-gradient(to right, transparent, var(--gl), transparent);
}
.card-badge {
  position: absolute; top: .9rem; left: 1.1rem;
  font-size: .64rem; letter-spacing: .18em; text-transform: uppercase;
  padding: .15rem .5rem; border-radius: 999px;
}
.card-front .card-badge { background: rgba(184,150,12,.12); color: var(--gold); border: 1px solid rgba(184,150,12,.25); }
.card-back  .card-badge { background: rgba(76,175,133,.12); color: var(--gl);   border: 1px solid rgba(76,175,133,.25); }
.card-hint {
  position: absolute; bottom: .9rem;
  font-size: .72rem; color: rgba(140,128,112,.5); letter-spacing: .1em;
}
.front-word {
  font-family: 'Playfair Display', serif;
  font-size: clamp(2rem, 6vw, 3.5rem);
  font-weight: 700;
  color: var(--cream);
  text-align: center;
  line-height: 1.15;
}
.back-meaning {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.5rem, 4vw, 2.4rem);
  font-weight: 700;
  color: var(--cream);
  text-align: center;
  margin-bottom: .7rem;
}
.back-chunk {
  font-style: italic;
  font-size: clamp(.9rem, 2.5vw, 1.2rem);
  color: var(--gl);
  text-align: center;
  line-height: 1.6;
  max-width: 90%;
}

/* ── BUTTONS ── */
.btn-row { display: flex; gap: .8rem; margin-top: 1.2rem; width: 100%; max-width: 680px; }
.action-btn {
  flex: 1; padding: .85rem .5rem; border: none; border-radius: 2px;
  font-family: 'Playfair Display', serif; font-size: 1rem; letter-spacing: .06em;
  cursor: pointer; transition: all .18s; display: flex; flex-direction: column;
  align-items: center; gap: .2rem; position: relative;
}
.action-btn .key-hint {
  font-size: .62rem; letter-spacing: .18em; opacity: .55;
  font-family: 'EB Garamond', serif; text-transform: uppercase;
}
.btn-flip   { background: rgba(184,150,12,.15); color: var(--gold); border: 1px solid rgba(184,150,12,.3); }
.btn-flip:hover   { background: rgba(184,150,12,.28); }
.btn-ok     { background: rgba(76,175,133,.15); color: var(--gl); border: 1px solid rgba(76,175,133,.3); }
.btn-ok:hover     { background: rgba(76,175,133,.3); transform: translateY(-1px); }
.btn-ng     { background: rgba(200,16,46,.12); color: #e87a8a; border: 1px solid rgba(200,16,46,.25); }
.btn-ng:hover     { background: rgba(200,16,46,.25); transform: translateY(-1px); }
.action-btn:disabled { opacity: .3; cursor: not-allowed; transform: none; }

.action-btn.flash { transform: scale(.95); }

/* ── RESULT SCREEN ── */
#result-screen {
  flex: 1; display: none; align-items: center; justify-content: center;
  padding: 2rem; animation: fadeIn .6s ease both;
}
.result-card {
  width: 100%; max-width: 520px;
  background: var(--bg2); border: 1px solid var(--border); border-radius: 3px;
  padding: 2.5rem 2.5rem 2rem; position: relative; text-align: center;
}
.result-card::before {
  content: ''; position: absolute; top: -1px; left: 2rem; right: 2rem;
  height: 2px; background: linear-gradient(to right, transparent, var(--gold), transparent);
}
.result-icon { font-size: 3rem; display: block; margin-bottom: .8rem; }
.result-title { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 700; margin-bottom: 1.2rem; }
.result-stats { display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 1.8rem; flex-wrap: wrap; }
.result-stat .n { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; display: block; line-height: 1; }
.result-stat .l { font-size: .8rem; color: var(--fog); letter-spacing: .1em; }
.result-stat.green .n { color: var(--gl); }
.result-stat.red-c .n  { color: #e87a8a; }
.ng-list { text-align: left; background: rgba(200,16,46,.06); border: 1px solid rgba(200,16,46,.15); border-radius: 2px; padding: .8rem 1rem; margin-bottom: 1.4rem; max-height: 180px; overflow-y: auto; }
.ng-list li { font-size: .92rem; color: var(--mist); padding: .18rem 0; border-bottom: 1px solid rgba(255,255,255,.04); }
.ng-list li:last-child { border-bottom: none; }
.ng-list li strong { color: #e87a8a; }
.result-btns { display: flex; flex-direction: column; gap: .7rem; }
.result-btns button { padding: .78rem; border: none; border-radius: 1px; font-family: 'Playfair Display', serif; font-size: .95rem; letter-spacing: .08em; cursor: pointer; transition: all .18s; }
.btn-retry-ng { background: rgba(200,16,46,.18); color: #e87a8a; border: 1px solid rgba(200,16,46,.3) !important; }
.btn-retry-ng:hover { background: rgba(200,16,46,.3); }
.btn-retry-all { background: rgba(184,150,12,.15); color: var(--gold); border: 1px solid rgba(184,150,12,.3) !important; }
.btn-retry-all:hover { background: rgba(184,150,12,.28); }
.btn-back { background: var(--bg2); color: var(--fog); border: 1px solid var(--border) !important; }
.btn-back:hover { color: var(--cream); border-color: var(--mist) !important; }

@keyframes fadeIn { from { opacity:0; transform: translateY(16px); } to { opacity:1; } }

.empty-state { text-align: center; padding: 3rem 2rem; color: var(--fog); }
.empty-state .icon { font-size: 2.5rem; display: block; margin-bottom: .8rem; }

@media (max-width: 560px) {
  header { padding: .8rem 1rem; }
  #study-screen { padding: 1rem 1rem 1.5rem; }
  .btn-row { gap: .5rem; }
  .action-btn { padding: .7rem .4rem; font-size: .9rem; }
  .setup-card { padding: 1.8rem 1.4rem; }
  .mode-grid { grid-template-columns: 1fr; gap: .5rem; }
}
</style>
</head>
<body>

<header>
   <a href="/index.php" class="site-title"><img src="../images/ML-logo1s.png" width=25% align="top" style="margin: 0px 0px;"> Flash<em>card</em></a>
  <div class="hdr-r">
    <span id="hdr-csv" style="font-size:.75rem;color:var(--fog)"></span>
    <a href="/index.php" class="btn-sm">← Contents</a>
    <a href="/logout.php" class="btn-sm">Logout</a>
  </div>
</header>

<!-- ═══════════════ SETUP SCREEN ═══════════════ -->
<div id="setup-screen">
  <div class="setup-card">
    <h2 class="setup-title">Flash<em>card</em></h2>
    <p class="setup-meta" id="setup-meta">読み込み中...</p>

    <!-- 進捗サマリー -->
    <div class="stats-row" id="setup-stats"></div>

    <!-- 学習モード選択 -->
    <div class="mode-grid">
      <div class="mode-btn selected" data-mode="order" onclick="selectMode(this)">
        <span class="mode-icon">📋</span>
        <span class="mode-label">順番通り</span>
        <span class="mode-sub">まずはここから</span>
      </div>
      <div class="mode-btn" data-mode="random" onclick="selectMode(this)">
        <span class="mode-icon">🔀</span>
        <span class="mode-label">ランダム</span>
        <span class="mode-sub">シャッフル</span>
      </div>
      <div class="mode-btn" data-mode="review_only" onclick="selectMode(this)" id="mode-ng-btn">
        <span class="mode-icon">❓</span>
        <span class="mode-label">要復習のみ</span>
        <span class="mode-sub" id="ng-count-sub">0語</span>
      </div>
    </div>

    <!-- 裏面からスタート トグル -->
    <div style="display:flex;align-items:center;gap:.8rem;margin-bottom:1.1rem;padding:.7rem 1rem;background:rgba(255,255,255,.02);border:1px solid var(--border);border-radius:2px;cursor:pointer" onclick="toggleReverse()" id="reverse-row">
      <div id="reverse-toggle" style="width:36px;height:20px;border-radius:999px;background:rgba(140,128,112,.25);border:1px solid rgba(140,128,112,.35);position:relative;transition:background .2s,border-color .2s;flex-shrink:0">
        <div id="reverse-knob" style="position:absolute;top:2px;left:2px;width:14px;height:14px;border-radius:50%;background:var(--fog);transition:transform .2s,background .2s"></div>
      </div>
      <div>
        <span style="font-size:.95rem;color:var(--mist)">裏面（日本語）からスタート</span>
        <span style="display:block;font-size:.75rem;color:var(--fog)">日本語を見て英語を思い出す練習</span>
      </div>
    </div>

    <!-- 続きから / 最初から -->
    <div style="display:flex;gap:.5rem;margin-bottom:1.1rem">
      <button class="resume-btn selected" id="resume-yes" onclick="selectResume(true)"
        style="flex:1;padding:.55rem .5rem;background:rgba(184,150,12,.15);border:1px solid rgba(184,150,12,.3);border-radius:1px;color:var(--gold);font-family:'EB Garamond',serif;font-size:.9rem;cursor:pointer;transition:all .18s">
        ⏩ 前回の続きから
      </button>
      <button class="resume-btn" id="resume-no" onclick="selectResume(false)"
        style="flex:1;padding:.55rem .5rem;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:1px;color:var(--fog);font-family:'EB Garamond',serif;font-size:.9rem;cursor:pointer;transition:all .18s">
        ↩ 最初から
      </button>
    </div>

    <button class="start-btn" id="start-btn" onclick="startStudy()">▶ 学習をスタートする</button>

    <!-- リセット -->
    <div style="text-align:center;margin-top:1.1rem">
      <button onclick="resetProgress()" style="background:none;border:none;font-size:.8rem;color:var(--fog);cursor:pointer;font-family:'EB Garamond',serif;text-decoration:underline;text-underline-offset:2px">
        🔄 このリストの学習履歴をリセット
      </button>
    </div>
  </div>
</div>

<!-- ═══════════════ STUDY SCREEN ═══════════════ -->
<div id="study-screen">

  <!-- Progress -->
  <div class="progress-wrap">
    <div class="progress-top">
      <span class="counter" id="counter">1 / 20</span>
      <span id="session-score" style="display:flex;gap:.8rem">
        <span style="color:var(--gl)">👍 <span id="ok-count">0</span></span>
        <span style="color:#e87a8a">❓ <span id="ng-count">0</span></span>
      </span>
    </div>
    <div class="progress-bar"><div class="progress-fill" id="progress-fill" style="width:0%"></div></div>
  </div>

  <!-- Card -->
  <div class="card-scene">
    <div class="card-wrap" id="card-wrap" onclick="flipCard()">
      <!-- Front -->
      <div class="card-face card-front">
        <span class="card-badge">English</span>
        <div class="front-word" id="front-word">—</div>
        <span class="card-hint" id="front-hint">クリック / Space で裏返す</span>
      </div>
      <!-- Back -->
      <div class="card-face card-back">
        <span class="card-badge">日本語</span>
        <div class="back-meaning" id="back-meaning">—</div>
        <div class="back-chunk" id="back-chunk"></div>
        <span class="card-hint">A / J = 覚えた &nbsp;·&nbsp; F = 要復習</span>
      </div>
    </div>
  </div>

  <!-- Buttons -->
  <div class="btn-row">
    <button class="action-btn btn-flip" id="btn-flip" onclick="flipCard()">
      <span>🔄 裏返す</span>
      <span class="key-hint">Space</span>
    </button>
    <button class="action-btn btn-ok" id="btn-ok" onclick="markCard('learned')" disabled>
      <span>👍 覚えていた</span>
      <span class="key-hint">A / J key</span>
    </button>
    <button class="action-btn btn-ng" id="btn-ng" onclick="markCard('review')" disabled>
      <span>❓ 覚えていなかった</span>
      <span class="key-hint">F key</span>
    </button>
  </div>

</div>

<!-- ═══════════════ RESULT SCREEN ═══════════════ -->
<div id="result-screen">
  <div class="result-card">
    <span class="result-icon" id="result-icon">🎉</span>
    <h2 class="result-title" id="result-title">お疲れさまでした！</h2>
    <div class="result-stats">
      <div class="result-stat green"><span class="n" id="result-ok">0</span><span class="l">👍 覚えていた</span></div>
      <div class="result-stat red-c"><span class="n"  id="result-ng">0</span><span class="l">❓ 要復習</span></div>
      <div class="result-stat"><span class="n" id="result-total">0</span><span class="l">Total</span></div>
    </div>
    <ul class="ng-list" id="ng-list" style="display:none"></ul>
    <div class="result-btns">
      <button class="btn-retry-ng" id="retry-ng-btn" onclick="retryNG()" style="display:none">❓ 要復習のみもう一度</button>
      <button class="btn-retry-all" onclick="retryAll()">🔀 全部もう一度（ランダム）</button>
      <button class="btn-back" onclick="showSetup()">← 学習設定に戻る</button>
    </div>
  </div>
</div>

<script>
/* ═══════════════════════════════════════════════
   データ
═══════════════════════════════════════════════ */
const FULL_DATA = <?= $data_json ?>;
const CSV_LABEL = <?= json_encode($csv_file) ?>;

let currentData = structuredClone(FULL_DATA);
let deck      = [];
let current   = 0;
let isFlipped = false;
let sessionOK = 0;
let sessionNG = 0;
let selectedMode = 'order';
let reverseMode  = false;
let resumeMode   = true;

/* ═══════════════════════════════════════════════
   初期化
═══════════════════════════════════════════════ */
window.addEventListener('DOMContentLoaded', () => {
  document.getElementById('hdr-csv').textContent = CSV_LABEL;
  updateSetupUI();
});

function updateSetupUI() {
  const stats = {
    total: currentData.total_cards ?? currentData.cards.length,
    learned: currentData.cards.filter(c => c.learned).length,
    review_count: currentData.review_queue.length
  };

  document.getElementById('setup-meta').textContent =
    `全 ${stats.total} 語 — ${CSV_LABEL}`;

  document.getElementById('setup-stats').innerHTML = `
    <div class="stat-pill"><span class="n">${stats.total}</span><br><span style="font-size:.72rem;color:var(--fog)">総語数</span></div>
    <div class="stat-pill green"><span class="n">${stats.learned}</span><br><span style="font-size:.72rem;color:var(--fog)">👍 覚えた</span></div>
    <div class="stat-pill red-c"><span class="n">${stats.review_count}</span><br><span style="font-size:.72rem;color:var(--fog)">❓ 要復習</span></div>
    <div class="stat-pill"><span class="n">${stats.total - stats.learned - stats.review_count}</span><br><span style="font-size:.72rem;color:var(--fog)">未学習</span></div>
  `;

  document.getElementById('ng-count-sub').textContent = stats.review_count + '語';
  const ngBtn = document.getElementById('mode-ng-btn');
  if (stats.review_count === 0) {
    ngBtn.style.opacity = '.4';
    ngBtn.style.pointerEvents = 'none';
    if (selectedMode === 'review_only') {
      selectedMode = 'order';
      document.querySelector('[data-mode=order]').classList.add('selected');
      ngBtn.classList.remove('selected');
    }
  } else {
    ngBtn.style.opacity = '1';
    ngBtn.style.pointerEvents = '';
  }
}

/* ═══════════════════════════════════════════════
   セットアップ操作
═══════════════════════════════════════════════ */
function selectResume(val) {
  resumeMode = val;
  const yes = document.getElementById('resume-yes');
  const no  = document.getElementById('resume-no');
  if (val) {
    yes.style.background = 'rgba(184,150,12,.15)';
    yes.style.borderColor = 'rgba(184,150,12,.3)';
    yes.style.color = 'var(--gold)';
    no.style.background  = 'rgba(255,255,255,.03)';
    no.style.borderColor = 'var(--border)';
    no.style.color = 'var(--fog)';
  } else {
    no.style.background  = 'rgba(184,150,12,.15)';
    no.style.borderColor = 'rgba(184,150,12,.3)';
    no.style.color = 'var(--gold)';
    yes.style.background = 'rgba(255,255,255,.03)';
    yes.style.borderColor = 'var(--border)';
    yes.style.color = 'var(--fog)';
  }
}

function toggleReverse() {
  reverseMode = !reverseMode;
  const tog   = document.getElementById('reverse-toggle');
  const knob  = document.getElementById('reverse-knob');
  if (reverseMode) {
    tog.style.background   = 'rgba(184,150,12,.4)';
    tog.style.borderColor  = 'var(--gold)';
    knob.style.transform   = 'translateX(16px)';
    knob.style.background  = 'var(--gold)';
  } else {
    tog.style.background   = 'rgba(140,128,112,.25)';
    tog.style.borderColor  = 'rgba(140,128,112,.35)';
    knob.style.transform   = 'translateX(0)';
    knob.style.background  = 'var(--fog)';
  }
}

function selectMode(el) {
  document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('selected'));
  el.classList.add('selected');
  selectedMode = el.dataset.mode;
}

function startStudy(mode) {
  const m = mode || selectedMode;
  let cards = getCardsForMode(m);

  if (cards.length === 0) {
    alert('学習するカードがありません。');
    return;
  }

  deck      = cards;
  sessionOK = 0;
  sessionNG = 0;
  selectedMode = m;

  // 続きから処理
  if (resumeMode) {
    const savedIndex = currentData.last_index ?? 0;
    current = Math.min(savedIndex, cards.length - 1);
  } else {
    current = 0;
  }

  document.getElementById('setup-screen').style.display  = 'none';
  document.getElementById('result-screen').style.display = 'none';
  document.getElementById('study-screen').style.display  = 'flex';

  renderCard();
}

function getCardsForMode(mode) {
  let cards = [...currentData.cards];

  if (mode === 'review_only') {
    const reviewWords = new Set(currentData.review_queue);
    cards = cards.filter(c => reviewWords.has(c.word));
  }

  if (mode === 'random') {
    shuffle(cards);
  }

  return cards;
}

function retryNG()  { startStudy('review_only'); }
function retryAll() { startStudy('random'); }

function showSetup() {
  // サーバーに session を保存
  api('save_session', { mode: selectedMode, index: current });
  
  document.getElementById('result-screen').style.display = 'none';
  document.getElementById('study-screen').style.display  = 'none';
  document.getElementById('setup-screen').style.display  = 'flex';
  updateSetupUI();
}

async function resetProgress() {
  if (!confirm(`「${CSV_LABEL}」の学習履歴をリセットしますか？`)) return;
  await api('reset', {});
  currentData = await api('get_data', {});
  updateSetupUI();
}

/* ═══════════════════════════════════════════════
   カード操作
═══════════════════════════════════════════════ */
function renderCard() {
  if (current >= deck.length) { showResult(); return; }

  const card = deck[current];
  isFlipped = false;

  const wrap = document.getElementById('card-wrap');
  if (reverseMode) {
    wrap.classList.add('flipped');
    isFlipped = true;
  } else {
    wrap.classList.remove('flipped');
  }

  document.getElementById('front-word').textContent    = card.word;
  document.getElementById('back-meaning').textContent  = card.meaning;
  document.getElementById('back-chunk').textContent    = card.chunk ? `"${card.chunk}"` : '';

  const frontHint = document.getElementById('front-hint');
  if (frontHint) {
    frontHint.textContent = reverseMode
      ? 'A / J = 覚えた · F = 要復習'
      : 'クリック / Space で裏返す';
  }

  if (reverseMode) {
    document.getElementById('btn-ok').disabled   = false;
    document.getElementById('btn-ng').disabled   = false;
    document.getElementById('btn-flip').disabled = true;
  } else {
    document.getElementById('btn-ok').disabled   = true;
    document.getElementById('btn-ng').disabled   = true;
    document.getElementById('btn-flip').disabled = false;
  }

  document.getElementById('counter').textContent = `${current + 1} / ${deck.length}`;
  document.getElementById('progress-fill').style.width = `${(current / deck.length) * 100}%`;
  document.getElementById('ok-count').textContent = sessionOK;
  document.getElementById('ng-count').textContent = sessionNG;
}

function flipCard() {
  isFlipped = !isFlipped;
  const wrap = document.getElementById('card-wrap');

  if (isFlipped) {
    wrap.classList.add('flipped');
    document.getElementById('btn-ok').disabled = false;
    document.getElementById('btn-ng').disabled = false;
  } else {
    wrap.classList.remove('flipped');
    if (!reverseMode) {
      document.getElementById('btn-ok').disabled = true;
      document.getElementById('btn-ng').disabled = true;
    }
  }
}

async function markCard(action) {
  if (document.getElementById('btn-ok').disabled && document.getElementById('btn-ng').disabled) return;

  const word = deck[current].word;

  if (action === 'learned') {
    await api('mark_learned', { word });
    sessionOK++;
  } else if (action === 'review') {
    await api('mark_review', { word });
    sessionNG++;
  }

  // ローカルデータも更新
  currentData = await api('get_data', {});

  current++;
  renderCard();
}

/* ═══════════════════════════════════════════════
   結果画面
═══════════════════════════════════════════════ */
function showResult() {
  document.getElementById('study-screen').style.display  = 'none';
  document.getElementById('result-screen').style.display = 'flex';

  const total  = deck.length;
  const rate   = total > 0 ? Math.round(sessionOK / total * 100) : 0;

  document.getElementById('result-ok').textContent    = sessionOK;
  document.getElementById('result-ng').textContent    = sessionNG;
  document.getElementById('result-total').textContent = total;
  document.getElementById('progress-fill').style.width = '100%';

  let icon = '🎉', title = 'パーフェクト！';
  if (rate < 100 && rate >= 80) { icon = '😊'; title = 'よくできました！'; }
  else if (rate < 80 && rate >= 60) { icon = '📚'; title = 'もう少し！'; }
  else if (rate < 60) { icon = '💪'; title = '復習しましょう！'; }
  document.getElementById('result-icon').textContent  = icon;
  document.getElementById('result-title').textContent = `${title} (${rate}%)`;

  const ngWords = deck.filter(v => currentData.cards.find(c => c.word === v.word && c.need_review));
  const ngListEl = document.getElementById('ng-list');
  if (ngWords.length > 0) {
    ngListEl.style.display = 'block';
    ngListEl.innerHTML = ngWords.map(v =>
      `<li><strong>${v.word}</strong> — ${v.meaning}</li>`
    ).join('');
    document.getElementById('retry-ng-btn').style.display = 'block';
  } else {
    ngListEl.style.display = 'none';
    document.getElementById('retry-ng-btn').style.display = 'none';
  }
}

/* ═══════════════════════════════════════════════
   キーボード操作
═══════════════════════════════════════════════ */
document.addEventListener('keydown', e => {
  if (['INPUT','TEXTAREA','SELECT','BUTTON'].includes(e.target.tagName)) return;
  if (document.getElementById('study-screen').style.display !== 'flex') return;

  if (e.code === 'Space') {
    e.preventDefault();
    flashBtn('btn-flip');
    flipCard();
  } else if (e.key === 'a' || e.key === 'A' || e.key === 'j' || e.key === 'J') {
    if (!document.getElementById('btn-ok').disabled) {
      flashBtn('btn-ok');
      markCard('learned');
    }
  } else if (e.key === 'f' || e.key === 'F') {
    if (!document.getElementById('btn-ng').disabled) {
      flashBtn('btn-ng');
      markCard('review');
    }
  }
});

function flashBtn(id) {
  const btn = document.getElementById(id);
  btn.classList.add('flash');
  setTimeout(() => btn.classList.remove('flash'), 150);
}

/* ═══════════════════════════════════════════════
   ユーティリティ
═══════════════════════════════════════════════ */
function shuffle(arr) {
  const a = [...arr];
  for (let i = a.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [a[i], a[j]] = [a[j], a[i]];
  }
  return a;
}

async function api(action, params) {
  const body = new URLSearchParams({ api: action, ...params });
  try {
    const res  = await fetch('./', { method: 'POST', body });
    return await res.json();
  } catch(e) {
    console.error('API error:', e);
    return { ok: false };
  }
}

if (currentData.cards.length === 0) {
  document.getElementById('setup-screen').innerHTML = `
    <div class="empty-state">
      <span class="icon">📭</span>
      <p style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--mist);margin-bottom:.5rem">CSVファイルが見つかりません</p>
      <p style="color:var(--fog);font-size:.88rem">同じディレクトリに <code style="color:var(--gold)">${CSV_LABEL}</code> を配置してください。</p>
      <p style="margin-top:1rem"><a href="/index.php" style="color:var(--gold)">← トップに戻る</a></p>
    </div>`;
}
</script>
</body>
</html>
