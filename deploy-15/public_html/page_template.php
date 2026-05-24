<?php
/**
 * Momo's London — 共通ページテンプレート
 *
 * 呼び出し元で以下の変数を定義してから require してください:
 *   $page_type     : 'student' | 'teacher'
 *   $page_label    : バッジに表示するラベル（例: 'Chapter 01', 'Guide', 'Manual'）
 *   $page_title    : ページタイトル（h1）
 *   $page_subtitle : サブタイトル（任意、省略可）
 *   $chapter_num   : カーテン演出用の章番号文字列（任意、例: '01'）
 *                    省略した場合は $page_label をそのまま使います
 *
 * ── 標準 CSS クラス（全 ch*.php / tm*.php で統一） ──────
 *   .sub-head       : font-size: 1.25rem（固定）
 *   .mc-choices     : 縦1列・color: var(--mist)
 *   .reveal         : スクロールリビール（IntersectionObserver）
 * ────────────────────────────────────────────────────────
 */

if ($page_type === 'teacher') {
    require_once __DIR__ . '/includes/teacher_check.php';
} else {
    require_once __DIR__ . '/includes/auth_check.php';
}

$is_t         = ($page_type === 'teacher');
$accent       = $is_t ? 'var(--gl)'               : 'var(--gold)';
$accent_bg    = $is_t ? 'rgba(76,175,133,.12)'     : 'rgba(184,150,12,.12)';
$accent_brd   = $is_t ? 'rgba(76,175,133,.28)'     : 'rgba(184,150,12,.28)';
$curtain_text = $chapter_num ?? $page_label;
$curtain_sub  = isset($chapter_num) ? 'Chapter ' . ltrim($chapter_num, '0') : $page_label;

// タイトルを単語分割してアニメーション用に準備
$title_words  = explode(' ', $page_title);
$word_base_delay = 1.5;
$word_step    = 0.1;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($page_label . ' — ' . $page_title) ?> — Momo's London</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --navy:  #0D1B2A;
    --cream: #F4EFE4;
    --red:   #C8102E;
    --gold:  #B8960C;
    --mist:  #D6CFC2;
    --fog:   #8C8070;
    --teal:  #1F7A8C;
    --gl:    #4CAF85;
    --bg2:   rgba(255,255,255,0.03);
    --border:rgba(184,150,12,0.18);
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    background-color: var(--navy);
    background-image: radial-gradient(ellipse 100% 40% at 50% 0%, #162030 0%, transparent 55%);
    font-family: 'EB Garamond', Georgia, serif;
    color: var(--cream);
    min-height: 100vh;
    font-size: 1.08rem;
    line-height: 1.85;
    overflow-x: hidden;
  }

  /* ════════════════════════════════════════
     CINEMATIC CURTAIN
  ════════════════════════════════════════ */
  #curtain {
    position: fixed; inset: 0; z-index: 9000;
    background: var(--navy);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    pointer-events: none;
    animation: curtainLift 0.9s 1.1s cubic-bezier(0.76,0,0.24,1) both;
  }
  @keyframes curtainLift {
    from { transform: translateY(0); }
    to   { transform: translateY(-102%); }
  }
  #curtain .c-num {
    font-family: 'Playfair Display', serif;
    font-size: clamp(5rem, 18vw, 13rem);
    font-weight: 700;
    color: transparent;
    -webkit-text-stroke: 1px rgba(<?= $is_t ? '76,175,133' : '184,150,12' ?>,.35);
    line-height: 1;
    animation: cNumReveal 0.7s 0.15s cubic-bezier(0.34,1.4,0.64,1) both;
  }
  @keyframes cNumReveal {
    from { opacity: 0; transform: scale(1.5); }
    to   { opacity: 1; transform: scale(1); }
  }
  #curtain .c-line {
    width: 0; height: 1px;
    background: linear-gradient(to right, transparent, <?= $accent ?>, transparent);
    margin-top: 1rem;
    animation: lineExpand 0.6s 0.55s ease both;
  }
  @keyframes lineExpand {
    from { width: 0; opacity: 0; }
    to   { width: min(300px, 55vw); opacity: 1; }
  }
  #curtain .c-label {
    font-size: .72rem; letter-spacing: .4em; text-transform: uppercase;
    color: <?= $accent ?>; margin-top: .8rem;
    animation: fadeSlideUp 0.5s 0.7s ease both;
  }

  /* ════════════════════════════════════════
     HEADER
  ════════════════════════════════════════ */
  header {
    border-bottom: 1px solid var(--border);
    padding: 1.1rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 200;
    background: rgba(13,27,42,.9); backdrop-filter: blur(12px);
    animation: headerDrop 0.5s 1.8s ease both;
  }
  @keyframes headerDrop {
    from { opacity: 0; transform: translateY(-100%); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .site-title { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; color: var(--cream); text-decoration: none; }
  .site-title em { font-style: italic; color: var(--gold); }
  .hdr-r { display: flex; align-items: center; gap: 1rem; }
  .btn-sm { font-size: .72rem; letter-spacing: .15em; text-transform: uppercase; color: var(--fog); text-decoration: none; border: 1px solid rgba(140,128,112,.4); padding: .28rem .75rem; border-radius: 1px; transition: color .2s, border-color .2s; }
  .btn-sm:hover { color: var(--cream); border-color: var(--mist); }

  /* ════════════════════════════════════════
     HERO
  ════════════════════════════════════════ */
  .page-hero {
    text-align: center;
    padding: 3.5rem 2rem 2.5rem;
    border-bottom: 1px solid rgba(184,150,12,.08);
    overflow: hidden;
  }
  .plabel {
    display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase;
    background: <?= $accent_bg ?>; border: 1px solid <?= $accent_brd ?>; color: <?= $accent ?>;
    padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem;
    animation: popIn 0.55s 1.25s cubic-bezier(0.34,1.56,0.64,1) both;
  }
  @keyframes popIn {
    from { opacity: 0; transform: scale(0.6) translateY(8px); }
    to   { opacity: 1; transform: scale(1)   translateY(0); }
  }
  .page-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem, 4vw, 2.6rem);
    font-weight: 700; line-height: 1.2;
  }
  .page-hero h1 .word {
    display: inline-block;
    animation: wordRise 0.6s cubic-bezier(0.22,1,0.36,1) both;
  }
  @keyframes wordRise {
    from { opacity: 0; transform: translateY(50px) rotate(1.5deg); }
    to   { opacity: 1; transform: translateY(0)     rotate(0deg); }
  }
  .page-hero .sub {
    font-style: italic; color: var(--fog); font-size: 1rem; margin-top: .5rem;
    animation: fadeSlideUp 0.5s 2.1s ease both;
  }
  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1rem auto; animation: fadeSlideUp 0.5s 2.0s ease both; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, <?= $accent ?>); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, <?= $accent ?>); }
  .ch-rule .d { width: 7px; height: 7px; background: <?= $accent ?>; transform: rotate(45deg); }
  <?php if ($is_t): ?>
  .teacher-notice {
    display: flex; align-items: center; justify-content: center; gap: .5rem;
    margin-top: 1rem; font-size: .8rem; color: var(--gl); opacity: .75;
    animation: fadeSlideUp 0.5s 2.2s ease both;
  }
  <?php endif; ?>

  @keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ════════════════════════════════════════
     SCROLL REVEAL
  ════════════════════════════════════════ */
  .reveal {
    opacity: 0;
    transform: translateY(26px);
    transition: opacity 0.65s ease, transform 0.65s cubic-bezier(0.22,1,0.36,1);
  }
  .reveal.visible { opacity: 1; transform: translateY(0); }

  /* ════════════════════════════════════════
     LAYOUT
  ════════════════════════════════════════ */
  .page-body { max-width: 860px; margin: 0 auto; padding: 2.5rem 2rem 6rem; }

  /* ── Section headers ── */
  .sec-head {
    display: flex; align-items: center; gap: 1rem;
    margin: 3rem 0 1.4rem; padding-bottom: .5rem;
    border-bottom: 1px solid rgba(184,150,12,.2);
  }
  .sec-num   { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: rgba(184,150,12,.45); line-height: 1; }
  .sec-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--cream); }
  .sec-emoji { font-size: 1.1rem; }

  /* ★ sub-head 統一: 常に 1.25rem ★ */
  .sub-head {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--mist);
    margin: 1.8rem 0 .8rem;
  }

  /* ── Quiz card ── */
  .quiz-card { background: rgba(184,150,12,.07); border: 1px solid rgba(184,150,12,.25); border-radius: 3px; padding: 1.4rem 1.6rem; margin-bottom: 1.5rem; }
  .quiz-card p { color: var(--mist); margin-bottom: .4rem; }
  .quiz-card strong { color: var(--gold); }

  /* ── Phrase list ── */
  .phrase-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .8rem; margin-bottom: 1.2rem; }
  .phrase-list li { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .55rem 1rem; font-size: .97rem; }
  .phrase-list li strong { color: var(--gold); }

  /* ── Dialogue ── */
  .dialogue { background: var(--bg2); border-left: 3px solid var(--gold); border-radius: 0 2px 2px 0; padding: 1.2rem 1.5rem; margin-bottom: 1.2rem; }
  .dialogue p { margin-bottom: .3rem; }
  .dialogue p:last-child { margin-bottom: 0; }
  .dialogue strong { color: var(--gold); }

  /* ── Fill-in notes ── */
  .fill-note  { font-style: italic; color: var(--fog); font-size: .9rem; margin-bottom: .8rem; }
  .fill-choice { font-size: .9rem; color: var(--teal); opacity: .8; }

  /* ── Story ── */
  .story-wrap { background: var(--bg2); border: 1px solid var(--border); border-radius: 3px; padding: 1.6rem 2rem; margin: 1rem 0; }
  .story-wrap p { margin-bottom: 1rem; color: var(--mist); }
  .story-wrap p:last-child { margin-bottom: 0; }

  /* ── Word list ── */
  .word-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .45rem .8rem; }
  .word-list li { background: var(--bg2); border: 1px solid var(--border); padding: .5rem .9rem; border-radius: 2px; font-size: .93rem; }
  .word-list li strong { color: var(--gold); }
  .word-list li .pron { color: var(--fog); font-size: .82rem; }

  /* ════════════════════════════════════
     ★ MC — 縦1列 / color: var(--mist) ★
  ════════════════════════════════════ */
  .mc-list { list-style: none; margin-bottom: 1.2rem; }
  .mc-list > li { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .9rem 1.2rem; margin-bottom: .55rem; }
  .mc-list > li > p { color: var(--mist); font-weight: 500; margin-bottom: .6rem; }
  .mc-choices { list-style: none; display: flex; flex-direction: column; gap: .3rem; }
  .mc-choices li { font-size: .95rem; color: var(--mist); display: flex; align-items: baseline; gap: .5rem; }
  .mc-choices li span { color: var(--gold); font-weight: bold; flex-shrink: 0; min-width: 1.8rem; }

  /* ── T/F ── */
  .tf-list { list-style: none; }
  .tf-list li { display: flex; align-items: flex-start; gap: 1rem; background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .75rem 1.1rem; margin-bottom: .5rem; }
  .tf-badge { font-size: .68rem; letter-spacing: .15em; border-radius: 999px; padding: .18rem .55rem; flex-shrink: 0; margin-top: .2rem; border: 1px solid rgba(140,128,112,.3); color: var(--fog); white-space: nowrap; }

  /* ── Paraphrase ── */
  .para-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; margin-bottom: .7rem; }
  .para-box .orig { color: var(--fog); font-size: .92rem; margin-bottom: .3rem; }
  .para-box .blank-line { color: var(--mist); }
  .hint-box { background: rgba(184,150,12,.07); border: 1px solid rgba(184,150,12,.25); border-radius: 2px; padding: .65rem 1.1rem; margin-bottom: 1rem; font-size: .93rem; }
  .hint-box strong { color: var(--gold); }

  /* ── Email frame ── */
  .email-frame { background: rgba(0,0,0,.25); border: 1px solid var(--border); border-radius: 2px; padding: 1.3rem 1.5rem; margin: .8rem 0; font-size: .97rem; }
  .email-frame .field { color: var(--fog); font-size: .88rem; margin-bottom: .2rem; }
  .email-frame .field strong { color: var(--mist); }
  .email-frame .body-note { color: rgba(184,150,12,.6); font-style: italic; padding: .8rem 0; }
  .email-frame .sig { color: var(--mist); margin-top: .6rem; }

  /* ── Discussion ── */
  .discuss-box { background: rgba(31,122,140,.1); border: 1px solid rgba(31,122,140,.3); border-radius: 3px; padding: 1.3rem 1.5rem; margin: .8rem 0; }
  .discuss-box .topic { font-family: 'Playfair Display', serif; font-size: 1.05rem; color: var(--mist); margin-bottom: .6rem; font-style: italic; }
  .discuss-box .kw { font-size: .88rem; color: var(--fog); }
  .discuss-box .kw strong { color: #5bc4d8; }

  /* ── TOEIC ── */
  .toeic-intro { color: var(--mist); background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; margin-bottom: 1.2rem; font-size: .97rem; }
  .toeic-dir { font-style: italic; color: var(--fog); font-size: .9rem; margin-bottom: .8rem; }

  /* ── Tips ── */
  .tips-box { background: rgba(46,125,94,.06); border: 1px solid rgba(76,175,133,.2); border-radius: 2px; padding: .9rem 1.2rem; margin-bottom: .65rem; }
  .tips-box .tip-label { font-size: .7rem; letter-spacing: .2em; text-transform: uppercase; color: var(--gl); margin-bottom: .3rem; }
  .tips-box p { color: var(--mist); font-size: .95rem; }

  /* ── Scanning ── */
  .scan-list { list-style: none; }
  .scan-list li { display: flex; align-items: baseline; gap: .7rem; background: var(--bg2); border: 1px solid var(--border); padding: .65rem 1.1rem; border-radius: 2px; margin-bottom: .45rem; font-size: .95rem; }
  .scan-list li .arrow { color: var(--gold); flex-shrink: 0; }
  .scan-note { font-size: .88rem; color: var(--fog); font-style: italic; margin-bottom: .8rem; }

  /* ── AI card ── */
  .ai-card { background: rgba(31,122,140,.08); border: 1px solid rgba(31,122,140,.28); border-radius: 3px; padding: 1.3rem 1.5rem; }
  .ai-card p { color: var(--mist); font-size: .95rem; margin-bottom: .6rem; }
  .ai-card .example { font-style: italic; color: #5bc4d8; font-size: .93rem; }

  /* ── Misc ── */
  .inst-note  { font-size: .9rem; color: var(--fog); font-style: italic; margin-bottom: .9rem; }
  .recall-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; color: var(--mist); }

  /* ── Placeholder (コンテンツ未追加ページ) ── */
  .placeholder { border: 1px dashed <?= $accent_brd ?>; border-radius: 2px; padding: 3.5rem; text-align: center; color: var(--fog); }
  .placeholder p { font-style: italic; margin-bottom: .5rem; }
  .placeholder small { font-size: .8rem; letter-spacing: .1em; }

  /* ── Footer ── */
  footer { border-top: 1px solid rgba(184,150,12,.1); text-align: center; padding: 1.5rem; font-size: .77rem; letter-spacing: .12em; color: var(--fog); }

  @media (max-width: 640px) {
    header { padding: 1rem 1.2rem; }
    .page-body { padding: 1.5rem 1rem 4rem; }
    .phrase-list, .word-list { grid-template-columns: 1fr; }
    .story-wrap { padding: 1.2rem 1.1rem; }
  }
</style>
</head>
<body>

<!-- ════ CINEMATIC CURTAIN ════ -->
<div id="curtain" aria-hidden="true">
  <div class="c-num"><?= htmlspecialchars($curtain_text) ?></div>
  <div class="c-line"></div>
  <div class="c-label"><?= htmlspecialchars($curtain_sub) ?></div>
</div>

<!-- ── HEADER ── -->
<header>
  <a href="/index.php" class="site-title">Momo's <em>London</em></a>
  <div class="hdr-r">
    <a href="/index.php" class="btn-sm">← Contents</a>
    <a href="/logout.php" class="btn-sm">Logout</a>
  </div>
</header>

<!-- ── HERO ── -->
<div class="page-hero">
  <span class="plabel"><?= htmlspecialchars($page_label) ?></span>
  <h1>
    <?php foreach ($title_words as $i => $w):
      $delay = $word_base_delay + $i * $word_step;
    ?>
    <span class="word" style="animation-delay:<?= $delay ?>s"><?= htmlspecialchars($w) ?></span><?= ($i < count($title_words) - 1) ? ' ' : '' ?>
    <?php endforeach; ?>
  </h1>
  <?php if (!empty($page_subtitle)): ?>
  <p class="sub"><?= htmlspecialchars($page_subtitle) ?></p>
  <?php endif; ?>
  <div class="ch-rule"><span></span><div class="d"></div><span></span></div>
  <?php if ($is_t): ?>
  <p class="teacher-notice">🔒 このページは教員専用です</p>
  <?php endif; ?>
</div>

<!-- ── CONTENT ── -->
<div class="page-body">
  <!--
    ══════════════════════════════════════════════
    ここにコンテンツを追加してください
    .reveal クラスを各ブロックに付けると
    スクロール連動のフェードインが適用されます
    ══════════════════════════════════════════════
  -->
  <div class="placeholder reveal">
    <p><?= htmlspecialchars($page_title) ?></p>
    <small>Content coming soon.</small>
  </div>
</div>

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
// ── スクロールリビール ──────────────────────────────
(function () {
  const els = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) {
    els.forEach(el => el.classList.add('visible'));
    return;
  }
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('visible');
        obs.unobserve(e.target);
      }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  els.forEach(el => obs.observe(el));
})();

// カーテンのアニメーション完了後に要素を非表示
document.getElementById('curtain').addEventListener('animationend', function () {
  this.style.display = 'none';
});
</script>
</body>
</html>
