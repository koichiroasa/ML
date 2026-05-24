<?php
require_once __DIR__ . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chapter 02 — An Elevator of Confusion | Momo's London</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --navy:   #0D1B2A;
    --cream:  #F4EFE4;
    --red:    #C8102E;
    --gold:   #B8960C;
    --mist:   #D6CFC2;
    --fog:    #8C8070;
    --teal:   #1F7A8C;
    --gl:     #4CAF85;
    --bg2:    rgba(255,255,255,0.03);
    --border: rgba(184,150,12,0.18);
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

  /* ── CINEMATIC CURTAIN ── */
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
    font-size: clamp(6rem, 20vw, 14rem);
    font-weight: 700; color: transparent;
    -webkit-text-stroke: 1px rgba(184,150,12,.35);
    line-height: 1;
    animation: cNumReveal 0.7s 0.15s cubic-bezier(0.34,1.4,0.64,1) both;
  }
  @keyframes cNumReveal {
    from { opacity: 0; transform: scale(1.5); }
    to   { opacity: 1; transform: scale(1); }
  }
  #curtain .c-line {
    width: 0; height: 1px;
    background: linear-gradient(to right, transparent, var(--gold), transparent);
    margin-top: 1rem;
    animation: lineExpand 0.6s 0.55s ease both;
  }
  @keyframes lineExpand {
    from { width: 0; opacity: 0; }
    to   { width: min(320px, 60vw); opacity: 1; }
  }
  #curtain .c-label {
    font-size: .72rem; letter-spacing: .4em; text-transform: uppercase;
    color: var(--gold); margin-top: .8rem;
    animation: fadeSlideUp 0.5s 0.7s ease both;
  }

  /* ── HEADER ── */
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

  /* ── HERO ── */
  .ch-hero {
    text-align: center; padding: 3.5rem 2rem 2.5rem;
    border-bottom: 1px solid rgba(184,150,12,.08);
    overflow: hidden;
  }
  .ch-num-label {
    display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase;
    background: rgba(184,150,12,.12); border: 1px solid rgba(184,150,12,.28); color: var(--gold);
    padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem;
    animation: popIn 0.55s 1.25s cubic-bezier(0.34,1.56,0.64,1) both;
  }
  @keyframes popIn {
    from { opacity: 0; transform: scale(0.6) translateY(8px); }
    to   { opacity: 1; transform: scale(1)   translateY(0); }
  }
  .ch-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.7rem, 4vw, 2.8rem);
    font-weight: 700; line-height: 1.15;
  }
  .ch-hero h1 .word { display: inline-block; animation: wordRise 0.6s cubic-bezier(0.22,1,0.36,1) both; }
  .ch-hero h1 .word em { font-style: italic; color: var(--gold); }
  @keyframes wordRise {
    from { opacity: 0; transform: translateY(60px) rotate(2deg); }
    to   { opacity: 1; transform: translateY(0) rotate(0deg); }
  }
  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1rem auto; animation: fadeSlideUp 0.5s 2.1s ease both; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, var(--gold)); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, var(--gold)); }
  .ch-rule .d { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
  @keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ── SCROLL REVEAL ── */
  .reveal { opacity: 0; transform: translateY(26px); transition: opacity 0.65s ease, transform 0.65s cubic-bezier(0.22,1,0.36,1); }
  .reveal.visible { opacity: 1; transform: translateY(0); }

  /* ── LAYOUT ── */
  .page-body { max-width: 860px; margin: 0 auto; padding: 2.5rem 2rem 6rem; }

  /* ── SECTION HEADERS ── */
  .sec-head { display: flex; align-items: center; gap: 1rem; margin: 3rem 0 1.4rem; padding-bottom: .5rem; border-bottom: 1px solid rgba(184,150,12,.2); }
  .sec-num   { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: rgba(184,150,12,.45); line-height: 1; }
  .sec-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--cream); }
  .sec-emoji { font-size: 1.1rem; }

  /* ★ sub-head 統一: 1.25rem ★ */
  .sub-head { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--mist); margin: 1.8rem 0 .8rem; }

  /* ── QUIZ CARD ── */
  .quiz-card { background: rgba(184,150,12,.07); border: 1px solid rgba(184,150,12,.25); border-radius: 3px; padding: 1.4rem 1.6rem; margin-bottom: 1.5rem; }
  .quiz-card p { color: var(--mist); margin-bottom: .4rem; }
  .quiz-card strong { color: var(--gold); }

  /* ── PHRASE LIST ── */
  .phrase-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .8rem; margin-bottom: 1.2rem; }
  .phrase-list li { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .55rem 1rem; font-size: .97rem; }
  .phrase-list li strong { color: var(--gold); }

  /* ── DIALOGUE ── */
  .dialogue { background: var(--bg2); border-left: 3px solid var(--gold); border-radius: 0 2px 2px 0; padding: 1.2rem 1.5rem; margin-bottom: 1.2rem; }
  .dialogue p { margin-bottom: .3rem; }
  .dialogue p:last-child { margin-bottom: 0; }
  .dialogue strong { color: var(--gold); }
  .fill-note   { font-style: italic; color: var(--fog); font-size: .9rem; margin-bottom: .8rem; }
  .fill-choice { font-size: .9rem; color: var(--teal); opacity: .8; }

  /* ── REPEAT+1 ACCORDION ── */
  .rp1-wrap { border: 1px solid var(--border); border-radius: 3px; overflow: hidden; margin-bottom: 1.2rem; }
  .rp1-info { background: rgba(184,150,12,.06); padding: 1.1rem 1.4rem; font-size: .96rem; color: var(--mist); border-bottom: 1px solid var(--border); }
  .rp1-info p { margin-bottom: .5rem; }
  .rp1-info p:last-child { margin-bottom: 0; }
  .rp1-info strong { color: var(--gold); }
  .rp1-example { background: rgba(255,255,255,.02); padding: .9rem 1.4rem; border-bottom: 1px solid var(--border); font-size: .93rem; color: var(--fog); }
  .rp1-example p { margin-bottom: .3rem; }
  .rp1-example strong { color: var(--mist); }
  .rp1-example em { color: var(--teal); }
  .rp1-note { font-size: .85rem; color: var(--fog); font-style: italic; padding: .7rem 1.4rem; background: rgba(200,16,46,.05); border-bottom: 1px solid var(--border); }
  /* accordion trigger */
  .acc-toggle {
    width: 100%; background: rgba(184,150,12,.08); border: none; border-top: 1px solid var(--border);
    color: var(--gold); font-family: 'Playfair Display', serif; font-size: .88rem;
    letter-spacing: .12em; text-transform: uppercase; padding: .75rem 1.4rem;
    text-align: left; cursor: pointer; display: flex; align-items: center; justify-content: space-between;
    transition: background .2s;
  }
  .acc-toggle:hover { background: rgba(184,150,12,.15); }
  .acc-toggle .arr { transition: transform .3s; font-style: normal; }
  .acc-toggle.open .arr { transform: rotate(180deg); }
  .acc-body { display: none; }
  .acc-body.open { display: block; }
  /* phrase list inside accordion */
  .cat-block { padding: .8rem 1.4rem; border-bottom: 1px solid rgba(255,255,255,.04); }
  .cat-block:last-child { border-bottom: none; }
  .cat-title { font-family: 'Playfair Display', serif; font-size: .88rem; font-weight: 700; color: var(--gold); letter-spacing: .08em; margin-bottom: .5rem; }
  .rp1-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .25rem .6rem; }
  .rp1-list li { font-size: .88rem; color: var(--mist); padding: .22rem 0; }
  .rp1-list li .jp { font-size: .78rem; color: var(--fog); }

  /* ── STORY ── */
  .story-wrap { background: var(--bg2); border: 1px solid var(--border); border-radius: 3px; padding: 1.6rem 2rem; margin: 1rem 0; }
  .story-wrap p { margin-bottom: 1rem; color: var(--mist); }
  .story-wrap p:last-child { margin-bottom: 0; }

  /* ── WORD LIST ── */
  .word-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .45rem .8rem; }
  .word-list li { background: var(--bg2); border: 1px solid var(--border); padding: .5rem .9rem; border-radius: 2px; font-size: .93rem; }
  .word-list li strong { color: var(--gold); }
  .word-list li .pron { color: var(--fog); font-size: .82rem; }

  /* ★ MC — 縦1列 / var(--mist) ★ */
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

  /* ── SCANNING ── */
  .scan-list { list-style: none; }
  .scan-list li { display: flex; align-items: baseline; gap: .7rem; background: var(--bg2); border: 1px solid var(--border); padding: .65rem 1.1rem; border-radius: 2px; margin-bottom: .45rem; font-size: .95rem; }
  .scan-list li .arrow { color: var(--gold); flex-shrink: 0; }
  .scan-note { font-size: .88rem; color: var(--fog); font-style: italic; margin-bottom: .8rem; }

  /* ── PARAPHRASE ── */
  .para-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; margin-bottom: .7rem; }
  .para-box .orig { color: var(--fog); font-size: .92rem; margin-bottom: .3rem; }
  .para-box .blank-line { color: var(--mist); }
  .hint-box { background: rgba(184,150,12,.07); border: 1px solid rgba(184,150,12,.25); border-radius: 2px; padding: .65rem 1.1rem; margin-bottom: 1rem; font-size: .93rem; }
  .hint-box strong { color: var(--gold); }

  /* ── EMAIL ── */
  .email-frame { background: rgba(0,0,0,.25); border: 1px solid var(--border); border-radius: 2px; padding: 1.3rem 1.5rem; margin: .8rem 0; font-size: .97rem; }
  .email-frame .field { color: var(--fog); font-size: .88rem; margin-bottom: .2rem; }
  .email-frame .field strong { color: var(--mist); }
  .email-frame .body-note { color: rgba(184,150,12,.6); font-style: italic; padding: .8rem 0; }
  .email-frame .sig { color: var(--mist); margin-top: .6rem; }

  /* ── DISCUSSION ── */
  .discuss-box { background: rgba(31,122,140,.1); border: 1px solid rgba(31,122,140,.3); border-radius: 3px; padding: 1.3rem 1.5rem; margin: .8rem 0; }
  .discuss-box .topic { font-family: 'Playfair Display', serif; font-size: 1.05rem; color: var(--mist); margin-bottom: .6rem; font-style: italic; }
  .discuss-box .kw { font-size: .88rem; color: var(--fog); }
  .discuss-box .kw strong { color: #5bc4d8; }

  /* ── TOEIC ── */
  .toeic-intro { color: var(--mist); background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; margin-bottom: 1.2rem; font-size: .97rem; }
  .toeic-dir { font-style: italic; color: var(--fog); font-size: .9rem; margin-bottom: .8rem; }

  /* ── TIPS ── */
  .tips-box { background: rgba(46,125,94,.06); border: 1px solid rgba(76,175,133,.2); border-radius: 2px; padding: .9rem 1.2rem; margin-bottom: .65rem; }
  .tips-box .tip-label { font-size: .7rem; letter-spacing: .2em; text-transform: uppercase; color: var(--gl); margin-bottom: .3rem; }
  .tips-box p { color: var(--mist); font-size: .95rem; }

  /* ── AI CARD ── */
  .ai-card { background: rgba(31,122,140,.08); border: 1px solid rgba(31,122,140,.28); border-radius: 3px; padding: 1.3rem 1.5rem; }
  .ai-card p { color: var(--mist); font-size: .95rem; margin-bottom: .6rem; }
  .ai-card .example { font-style: italic; color: #5bc4d8; font-size: .93rem; }

  /* ── MISC ── */
  .inst-note  { font-size: .9rem; color: var(--fog); font-style: italic; margin-bottom: .9rem; }
  .recall-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; color: var(--mist); }

  /* ── FOOTER ── */
  footer { border-top: 1px solid rgba(184,150,12,.1); text-align: center; padding: 1.5rem; font-size: .77rem; letter-spacing: .12em; color: var(--fog); }

  @media (max-width: 640px) {
    header { padding: 1rem 1.2rem; }
    .page-body { padding: 1.5rem 1rem 4rem; }
    .phrase-list, .word-list, .rp1-list { grid-template-columns: 1fr; }
    .story-wrap { padding: 1.2rem 1.1rem; }
  }
</style>
</head>
<body>

<!-- ── CURTAIN ── -->
<div id="curtain" aria-hidden="true">
  <div class="c-num">02</div>
  <div class="c-line"></div>
  <div class="c-label">Chapter Two</div>
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
<div class="ch-hero">
  <span class="ch-num-label">Chapter 02</span>
  <h1>
    <?php
    $words = ['An', 'Elevator', 'of', 'Confusion'];
    foreach ($words as $i => $w) {
        $delay  = 1.5 + $i * 0.1;
        $italic = in_array($w, ['Elevator', 'of', 'Confusion']);
        $inner  = $italic ? "<em>{$w}</em>" : $w;
        echo "<span class=\"word\" style=\"animation-delay:{$delay}s\">{$inner}</span> ";
    }
    ?>
  </h1>
  <div class="ch-rule"><span></span><div class="d"></div><span></span></div>
</div>

<div class="page-body">

  <!-- ── Quick Start Quiz ── -->
  <div class="quiz-card reveal">
    <p style="font-family:'Playfair Display',serif;font-size:.85rem;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);margin-bottom:.8rem">Quick "Start" Quiz: Ask Your Partner!</p>
    <p><strong>A:</strong> In the UK, what do people call the floor at street level?</p>
    <p><strong>B:</strong> _______. By the way, what floor is the one directly above the street-level floor?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <!-- ── Important Phrases ── -->
  <h3 class="sub-head reveal">Important Phrases</h3>
  <ul class="phrase-list reveal">
    <li><strong>first day at work</strong>：仕事の初日</li>
    <li><strong>a mix of excitement and nerves</strong>：興奮と緊張が入り混じった気持ち</li>
    <li><strong>state-of-the-art technology</strong>：最新技術</li>
    <li><strong>a world of difference from</strong>：〜とは大違い</li>
    <li><strong>something caught her eye</strong>：何かが彼女の目に留まった</li>
    <li><strong>felt a bit overwhelmed</strong>：少し圧倒されていると感じた</li>
    <li><strong>head for the exit</strong>：出口に向かう</li>
    <li><strong>to her confusion</strong>：彼女が混乱したことに</li>
    <li><strong>chuckled</strong>：くすくす笑った</li>
    <li><strong>face turned bright red</strong>：顔が真っ赤になった</li>
  </ul>


  <!-- ══════════════════════════════════ -->
  <!-- 1 SPEAKING                         -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">1</span>
    <span class="sec-title">Speaking</span>
    <span class="sec-emoji">🗣️</span>
  </div>

  <h4 class="sub-head reveal">1.1 Role-play</h4>
  <div class="dialogue reveal">
    <p><strong>A:</strong> Excuse me, I'm looking for the marketing department. I think I'm lost.</p>
    <p><strong>B:</strong> Oh, the marketing department is on the third floor. You're on the second floor now.</p>
    <p><strong>A:</strong> Oh, I see. How do I get there?</p>
    <p><strong>B:</strong> Just take this elevator up one floor. You can't miss it.</p>
    <p><strong>A:</strong> Great, thank you so much for your help!</p>
    <p><strong>B:</strong> No problem at all. Welcome to the company!</p>
  </div>

  <h4 class="sub-head reveal">1.2 Let's complete the conversation!</h4>
  <p class="fill-note reveal">Fill in the blanks with your own ideas and practice the conversation with a partner. You may also choose from the words in parentheses. Once done, switch roles A and B. After that, make up a continuation and keep the conversation going.</p>
  <div class="dialogue reveal">
    <p><strong>A:</strong> Excuse me, this is my first day and I'm a bit lost. I'm looking for the ＿＿＿.<br>
      <span class="fill-choice">(restroom / meeting room C)</span></p>
    <p><strong>B:</strong> Of course. <strong>It's</strong> just down the hall on your left.</p>
    <p><strong>A:</strong> Oh, thank you! And do you know where I can find the ＿＿＿?<br>
      <span class="fill-choice">(stationery / stapler)</span></p>
    <p><strong>B:</strong> <strong>It's</strong> in the supply closet, right next to the copy machine.</p>
    <p><strong>A:</strong> I see. I feel ＿＿＿ today.<br>
      <span class="fill-choice">(so nervous / a little excited)</span></p>
    <p><strong>B:</strong> Don't worry, you'll get used to it. Everyone is friendly here!</p>
  </div>

  <!-- 1.3 Repeat +1 -->
  <h4 class="sub-head reveal">1.3 Let's repeat +1</h4>
  <div class="rp1-wrap reveal">
    <!-- 説明 -->
    <div class="rp1-info">
      <p>Repeat the completed conversation, but add one (+1) phrase each time you speak. Keep the conversation going until the teacher says <strong>"Stop."</strong> You may choose phrases from the <strong>"Repeat + 1 Phrase List"</strong>.</p>
      <p>もし完成された会話が下記のものだったとします。</p>
    </div>
    <!-- 例 -->
    <div class="rp1-example">
      <p><strong>A:</strong> I like your baseball cap!</p>
      <p><strong>B:</strong> Thanks! This is my favorite team.</p>
      <br>
      <p><strong>例1（後ろに +1）</strong></p>
      <p><em>A: I like your baseball cap! (+1) That's a nice color!</em></p>
      <p><em>B: Thanks! This is my favorite team. (+1) What's your favorite sport?</em></p>
      <br>
      <p><strong>例2（最初や途中に +1）</strong></p>
      <p><em>A: (+1) By the way, I like your baseball cap!</em></p>
      <p><em>B: Thanks (+1) for mentioning that! This is my favorite team.</em></p>
    </div>
    <!-- 注意 -->
    <div class="rp1-note">
      ※短すぎるもの（例: Oh）は +1 フレーズではありません。「Oh my god!」ならOKです。<br>
      　× +1 Oh. I like your baseball cap! &nbsp;&nbsp; ○ +1 Oh my god. I like your baseball cap!
    </div>
    <!-- 練習会話 -->
    <div class="cat-block" style="background:rgba(184,150,12,.04)">
      <p class="cat-title" style="color:var(--mist);font-size:.92rem;margin-bottom:.6rem">練習会話</p>
      <div class="dialogue" style="margin-bottom:0">
        <p><strong>A:</strong> Excuse me, this is my first day and I'm a bit lost. I'm looking for the restroom.</p>
        <p><strong>B:</strong> Of course. It's just down the hall on your left.</p>
        <p><strong>A:</strong> Oh, thank you! And do you know where I can find the stationery?</p>
        <p><strong>B:</strong> It's in the supply closet, right next to the copy machine.</p>
        <p><strong>A:</strong> I see. I feel so nervous today.</p>
        <p><strong>B:</strong> Don't worry, you'll get used to it. Everyone is friendly here!</p>
      </div>
    </div>
    <!-- アコーディオン: フレーズリスト -->
    <button class="acc-toggle" id="rp1-btn" onclick="toggleAcc()">
      <span>【Repeat + 1 Phrase List】全フレーズを表示する</span>
      <span class="arr">▼</span>
    </button>
    <div class="acc-body" id="rp1-body">

      <div class="cat-block">
        <p class="cat-title">① 気持ち・反応（Reaction）</p>
        <ul class="rp1-list">
          <li>I see. <span class="jp">（なるほど）</span></li>
          <li>That's right. <span class="jp">（そうですね）</span></li>
          <li>Really? <span class="jp">（本当ですか？）</span></li>
          <li>That's nice. <span class="jp">（いいですね）</span></li>
          <li>That's great. <span class="jp">（すばらしいですね）</span></li>
          <li>That's too bad. <span class="jp">（残念ですね）</span></li>
          <li>Oh, I see. <span class="jp">（ああ、なるほど）</span></li>
          <li>That makes sense. <span class="jp">（納得できます）</span></li>
          <li>I get what you mean. <span class="jp">（言いたいことはわかります）</span></li>
          <li>That's a good point. <span class="jp">（いい指摘ですね）</span></li>
          <li>That's interesting to hear. <span class="jp">（興味深いですね）</span></li>
          <li>I didn't expect that. <span class="jp">（予想外でした）</span></li>
          <li>That's surprising. <span class="jp">（驚きです）</span></li>
          <li>I can see why. <span class="jp">（理由がわかります）</span></li>
          <li>That's something to think about. <span class="jp">（考えさせられます）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">② 共感・サポート（Support）</p>
        <ul class="rp1-list">
          <li>I agree with you. <span class="jp">（賛成です）</span></li>
          <li>You're right. <span class="jp">（その通りです）</span></li>
          <li>Good idea. <span class="jp">（いい考えですね）</span></li>
          <li>That sounds nice. <span class="jp">（よさそうですね）</span></li>
          <li>No problem. <span class="jp">（大丈夫です）</span></li>
          <li>That's okay. <span class="jp">（大丈夫です）</span></li>
          <li>Don't worry. <span class="jp">（心配しないで）</span></li>
          <li>I think so too. <span class="jp">（私もそう思います）</span></li>
          <li>I feel the same way. <span class="jp">（同じ気持ちです）</span></li>
          <li>That sounds like a great idea. <span class="jp">（とても良い考えですね）</span></li>
          <li>That could be a good solution. <span class="jp">（良い解決策かもしれません）</span></li>
          <li>I see your point. <span class="jp">（意見は理解できます）</span></li>
          <li>That's a smart idea. <span class="jp">（賢い考えですね）</span></li>
          <li>I think that will work. <span class="jp">（うまくいくと思います）</span></li>
          <li>That sounds promising. <span class="jp">（期待できそうですね）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">③ 気持ち（Feeling）</p>
        <ul class="rp1-list">
          <li>I'm happy. <span class="jp">（うれしいです）</span></li>
          <li>I'm a bit tired. <span class="jp">（少し疲れています）</span></li>
          <li>I'm busy. <span class="jp">（忙しいです）</span></li>
          <li>I'm worried. <span class="jp">（心配です）</span></li>
          <li>I feel good. <span class="jp">（気分がいいです）</span></li>
          <li>I'm excited. <span class="jp">（ワクワクしています）</span></li>
          <li>I'm nervous. <span class="jp">（緊張しています）</span></li>
          <li>I'm a little nervous. <span class="jp">（少し緊張しています）</span></li>
          <li>I feel stressed. <span class="jp">（ストレスを感じています）</span></li>
          <li>I'm excited about it. <span class="jp">（楽しみにしています）</span></li>
          <li>I feel better now. <span class="jp">（今は気分がいいです）</span></li>
          <li>I'm not very confident. <span class="jp">（自信がありません）</span></li>
          <li>I'm a bit worried about it. <span class="jp">（少し心配です）</span></li>
          <li>I feel more relaxed now. <span class="jp">（少し落ち着きました）</span></li>
          <li>I'm getting used to it. <span class="jp">（慣れてきています）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">④ 意見・考え（Opinion）</p>
        <ul class="rp1-list">
          <li>I think so. <span class="jp">（そう思います）</span></li>
          <li>I don't think so. <span class="jp">（そうは思いません）</span></li>
          <li>I think it's nice. <span class="jp">（いいと思います）</span></li>
          <li>I think it's difficult. <span class="jp">（難しいと思います）</span></li>
          <li>I think it's fun. <span class="jp">（楽しいと思います）</span></li>
          <li>I think it's okay. <span class="jp">（大丈夫だと思います）</span></li>
          <li>I think it's important. <span class="jp">（重要だと思います）</span></li>
          <li>I think it depends. <span class="jp">（場合によると思います）</span></li>
          <li>I'm not sure about that. <span class="jp">（どうでしょう）</span></li>
          <li>I think it's worth trying. <span class="jp">（やる価値があります）</span></li>
          <li>I think it's not easy. <span class="jp">（簡単ではない）</span></li>
          <li>I think it could be better. <span class="jp">（もっと良くなる）</span></li>
          <li>I think it might be difficult. <span class="jp">（難しいかもしれません）</span></li>
          <li>I think it will help. <span class="jp">（役に立つと思います）</span></li>
          <li>I think it's a good idea overall. <span class="jp">（全体として良いと思います）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑤ 理由・説明（Reason）</p>
        <ul class="rp1-list">
          <li>Because it's fun. <span class="jp">（楽しいから）</span></li>
          <li>Because I like it. <span class="jp">（好きだから）</span></li>
          <li>Because it's easy. <span class="jp">（簡単だから）</span></li>
          <li>Because it's important. <span class="jp">（重要だから）</span></li>
          <li>Because I'm busy. <span class="jp">（忙しいから）</span></li>
          <li>Because I have time. <span class="jp">（時間があるから）</span></li>
          <li>Because it's interesting. <span class="jp">（おもしろいから）</span></li>
          <li>Because it helps me. <span class="jp">（役に立つから）</span></li>
          <li>Because it takes time. <span class="jp">（時間がかかるから）</span></li>
          <li>Because I need it. <span class="jp">（必要だから）</span></li>
          <li>Because I want to improve. <span class="jp">（上達したいから）</span></li>
          <li>Because it makes things easier. <span class="jp">（楽になるから）</span></li>
          <li>Because I enjoy it. <span class="jp">（楽しんでいるから）</span></li>
          <li>Because it's useful. <span class="jp">（役に立つから）</span></li>
          <li>Because it works well. <span class="jp">（うまくいくから）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑥ 追加情報（Extra info）</p>
        <ul class="rp1-list">
          <li>I like it. <span class="jp">（好きです）</span></li>
          <li>I don't like it. <span class="jp">（好きではありません）</span></li>
          <li>I do it every day. <span class="jp">（毎日します）</span></li>
          <li>I do it on weekends. <span class="jp">（週末にします）</span></li>
          <li>I want to try it. <span class="jp">（やってみたい）</span></li>
          <li>I often do it. <span class="jp">（よくします）</span></li>
          <li>I sometimes do it. <span class="jp">（ときどきします）</span></li>
          <li>I'm working on it now. <span class="jp">（今取り組んでいます）</span></li>
          <li>I tried it before. <span class="jp">（前にやりました）</span></li>
          <li>I haven't decided yet. <span class="jp">（まだ決めていません）</span></li>
          <li>I'm planning to do it. <span class="jp">（予定です）</span></li>
          <li>I'm getting better at it. <span class="jp">（上達しています）</span></li>
          <li>I've learned a lot. <span class="jp">（多くを学びました）</span></li>
          <li>I want to learn more. <span class="jp">（もっと学びたい）</span></li>
          <li>I'm still thinking about it. <span class="jp">（まだ考えています）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑦ フィラー（Filler）</p>
        <ul class="rp1-list">
          <li>Let me see. <span class="jp">（えっと）</span></li>
          <li>Well... <span class="jp">（ええと）</span></li>
          <li>Hmm... <span class="jp">（うーん）</span></li>
          <li>Let me think. <span class="jp">（考えます）</span></li>
          <li>Just a moment. <span class="jp">（少し待って）</span></li>
          <li>How can I say this? <span class="jp">（どう言えばいいかな）</span></li>
          <li>I'm not sure. <span class="jp">（わかりません）</span></li>
          <li>It's hard to say. <span class="jp">（言いにくい）</span></li>
          <li>I mean... <span class="jp">（つまり）</span></li>
          <li>You know... <span class="jp">（あの）</span></li>
          <li>Actually... <span class="jp">（実は）</span></li>
          <li>To be honest... <span class="jp">（正直に言うと）</span></li>
          <li>Well, I think... <span class="jp">（ええと）</span></li>
          <li>Let me explain. <span class="jp">（説明します）</span></li>
          <li>Give me a second. <span class="jp">（少し時間ください）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑧ 確認（Checking）</p>
        <ul class="rp1-list">
          <li>You mean like this? <span class="jp">（こういうこと？）</span></li>
          <li>Do you mean this? <span class="jp">（これ？）</span></li>
          <li>What do you mean? <span class="jp">（どういう意味？）</span></li>
          <li>Can you explain that? <span class="jp">（説明して）</span></li>
          <li>Can you say that again? <span class="jp">（もう一度）</span></li>
          <li>Did you say ~? <span class="jp">（〜って言った？）</span></li>
          <li>So, you mean ~? <span class="jp">（つまり〜？）</span></li>
          <li>Is that right? <span class="jp">（合ってる？）</span></li>
          <li>Am I right? <span class="jp">（これでいい？）</span></li>
          <li>What do you think? <span class="jp">（どう思う？）</span></li>
          <li>Is that correct? <span class="jp">（正しい？）</span></li>
          <li>Do you agree? <span class="jp">（賛成？）</span></li>
          <li>Can you give an example? <span class="jp">（例は？）</span></li>
          <li>What about you? <span class="jp">（あなたは？）</span></li>
          <li>How about you? <span class="jp">（あなたは？）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑨ フォローアップ質問（Follow-up）</p>
        <ul class="rp1-list">
          <li>Why do you think so? <span class="jp">（なぜ？）</span></li>
          <li>What kind of ~ do you like? <span class="jp">（どんな〜？）</span></li>
          <li>When do you usually ~? <span class="jp">（いつ？）</span></li>
          <li>Who do you ~ with? <span class="jp">（誰と？）</span></li>
          <li>How often do you ~? <span class="jp">（どのくらい？）</span></li>
          <li>Where do you usually ~? <span class="jp">（どこで？）</span></li>
          <li>How do you do that? <span class="jp">（どうやって？）</span></li>
          <li>What happened next? <span class="jp">（その後どうなった？）</span></li>
          <li>Can you tell me more? <span class="jp">（もっと教えて）</span></li>
          <li>Why is that? <span class="jp">（なぜそれ？）</span></li>
          <li>What do you like about it? <span class="jp">（何がいいの？）</span></li>
          <li>What's your favorite part? <span class="jp">（どこが一番好き？）</span></li>
          <li>How was it? <span class="jp">（どうだった？）</span></li>
          <li>What did you do? <span class="jp">（何したの？）</span></li>
          <li>What will you do next? <span class="jp">（次は何する？）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑩ 具体化・つなぎ（Detail &amp; Connecting）</p>
        <ul class="rp1-list">
          <li>For example, ~ <span class="jp">（例えば）</span></li>
          <li>Like ~ <span class="jp">（例えば）</span></li>
          <li>Such as ~ <span class="jp">（〜のような）</span></li>
          <li>And ~ <span class="jp">（そして）</span></li>
          <li>So ~ <span class="jp">（だから）</span></li>
          <li>But ~ <span class="jp">（しかし）</span></li>
          <li>Also ~ <span class="jp">（さらに）</span></li>
          <li>Then ~ <span class="jp">（それから）</span></li>
          <li>After that, ~ <span class="jp">（そのあと）</span></li>
          <li>In addition, ~ <span class="jp">（加えて）</span></li>
          <li>First, ~ <span class="jp">（まず）</span></li>
          <li>Next, ~ <span class="jp">（次に）</span></li>
          <li>Finally, ~ <span class="jp">（最後に）</span></li>
          <li>That's why ~ <span class="jp">（だから〜）</span></li>
          <li>Because of that, ~ <span class="jp">（そのため）</span></li>
          <li>In that case, ~ <span class="jp">（その場合は）</span></li>
          <li>In my case, ~ <span class="jp">（私の場合は）</span></li>
          <li>At the same time, ~ <span class="jp">（同時に）</span></li>
          <li>On the other hand, ~ <span class="jp">（一方で）</span></li>
          <li>Even so, ~ <span class="jp">（それでも）</span></li>
          <li>For me, ~ <span class="jp">（私にとっては）</span></li>
          <li>To be honest, ~ <span class="jp">（正直に言うと）</span></li>
          <li>Actually, ~ <span class="jp">（実は）</span></li>
        </ul>
      </div>

      <div class="cat-block">
        <p class="cat-title">⑪ 感謝（Gratitude）</p>
        <ul class="rp1-list">
          <li>Thanks. <span class="jp">（ありがとう）</span></li>
          <li>Thank you. <span class="jp">（ありがとうございます）</span></li>
          <li>Thanks a lot. <span class="jp">（どうもありがとう）</span></li>
          <li>Thank you very much. <span class="jp">（本当にありがとうございます）</span></li>
          <li>Thanks for your help. <span class="jp">（手伝ってくれてありがとう）</span></li>
          <li>Thanks for your advice. <span class="jp">（アドバイスありがとう）</span></li>
          <li>Thanks for telling me. <span class="jp">（教えてくれてありがとう）</span></li>
          <li>I really appreciate it. <span class="jp">（本当に感謝しています）</span></li>
          <li>I appreciate your help. <span class="jp">（助けに感謝します）</span></li>
          <li>That was very helpful. <span class="jp">（とても助かりました）</span></li>
          <li>Thanks, that helps a lot. <span class="jp">（助かります）</span></li>
          <li>I'm grateful for that. <span class="jp">（それに感謝しています）</span></li>
          <li>Thanks, I needed that. <span class="jp">（ちょうど必要でした）</span></li>
          <li>I couldn't have done it without you. <span class="jp">（あなたなしではできませんでした）</span></li>
          <li>Thanks for your support. <span class="jp">（サポートありがとう）</span></li>
          <li>Thanks for mentioning that. <span class="jp">（それを言ってくれてありがとう）</span></li>
        </ul>
      </div>

    </div><!-- /.acc-body -->
  </div><!-- /.rp1-wrap -->


  <!-- ══════════════════════════════════ -->
  <!-- 2 READING                          -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">2</span>
    <span class="sec-title">Reading</span>
    <span class="sec-emoji">📖</span>
  </div>

  <h4 class="sub-head reveal">2.0 Fun Pair-work Before Reading</h4>
  <div class="quiz-card reveal">
    <p><strong>A:</strong> What do you think is the biggest difference between a Japanese office and an office in another country?</p>
    <p><strong>B:</strong> _______. By the way, what do you think is the most important thing to do on your first day at a new job?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <h4 class="sub-head reveal">2.1 Scanning Practice</h4>
  <p class="scan-note reveal">TOEICでは全般的に同義語を文中から素早く探すスキャニング能力が必須です。下記の単語・表現と同じ意味を持つキーワード（カッコの中の頭文字で始まるもの）を本文から素早く探しましょう。見つけられるまでの秒数を各自計り、記録しましょう。TOEICは言い換えと引っかけのテストです。言い換え（パラフレーズ）を常に意識しておきましょう。セットで暗記すると語彙力２倍になりますね。</p>
  <ul class="scan-list reveal">
    <li><span style="color:var(--mist)">modern; up-to-date</span><span class="arrow">→</span><span style="color:var(--gold)">(s- )</span></li>
    <li><span style="color:var(--mist)">guided her through the office</span><span class="arrow">→</span><span style="color:var(--gold)">(s- )</span></li>
    <li><span style="color:var(--mist)">unable to understand clearly</span><span class="arrow">→</span><span style="color:var(--gold)">(c- )</span></li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">Story: <em>An Elevator of Confusion</em></h4>
  <div class="story-wrap reveal">
    <p>Momo's first day at work at Golden Star Company's London branch began. Dressed in her best suit, she entered the modern skyscraper with a mix of excitement and nerves. She arrived at the ground floor reception and announced her arrival. A friendly man named Liam greeted her. "Welcome, Momo! The branch manager is busy, but he asked me to show you around."</p>
    <p>Liam was cheerful and kind. He showed Momo her new desk, which had a fantastic view of the city. He introduced her to a few colleagues, who all gave her a warm welcome. The office was bright and open, filled with a quiet, energetic buzz. It was equipped with state-of-the-art technology, a world of difference from her cozy but old-fashioned office in Japan.</p>
    <p>As Liam walked her down a hallway decorated with photos telling the company's history, something caught her eye. In a black and white photo of the company's founders, one of them was wearing a necklace with a star symbol exactly like the one on her pendant. She froze for a second, but Liam called her name, and she quickly looked away. "What was that?" she wondered.</p>
    <p>After the tour, Momo felt a bit overwhelmed. There were so many new names and procedures to learn. At the end of the day, she was ready to go home. Wanting to exit the building, she stepped into the elevator. Seeing the buttons G, 1, 2, 3..., she assumed "1" was the first floor and pressed it.</p>
    <p>When the doors opened, she was still in an office area. Confused, she looked around. Where was the exit? "The exit should be on the 1st floor," she thought. An older gentleman in the elevator chuckled. "Leaving so soon? In the UK, 'G' is for the ground floor. That's where the exit is. The 1st floor is what you'd call the 2nd floor in Japan."</p>
    <p>Momo's face turned bright red. "Oh! I see. Thank you!" she said, quickly pressing the "G" button. Feeling embarrassed but also a little amused, she finally made it outside. To clear her head, she decided to take a walk along the nearby River Thames. The evening view of the Houses of Parliament and the London Eye was breathtaking. Watching the boats go by, she thought about her silly mistake. She wasn't just in a new job; she was in a whole new culture. She smiled, realizing she was more adaptable than she thought. "I can get used to this," she vowed.</p>
  </div>

  <h4 class="sub-head reveal">Word List</h4>
  <ul class="word-list reveal">
    <li><strong>skyscraper</strong> <span class="pron">(名) /skáɪskrèɪpər/</span><br>超高層ビル、摩天楼</li>
    <li><strong>reception</strong> <span class="pron">(名) /rɪsépʃən/</span><br>受付</li>
    <li><strong>colleague</strong> <span class="pron">(名) /kάliːg/</span><br>同僚</li>
    <li><strong>founder</strong> <span class="pron">(名) /fáʊndər/</span><br>創設者</li>
    <li><strong>overwhelmed</strong> <span class="pron">(形) /òʊvərhwélmd/</span><br>圧倒されて、途方にくれて</li>
    <li><strong>assume</strong> <span class="pron">(動) /əsúːm/</span><br>〜だと思い込む、当然のことと考える</li>
    <li><strong>breathtaking</strong> <span class="pron">(形) /bréθtèɪkɪŋ/</span><br>息をのむような</li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">2.2 Reading Comprehension: Multiple Choice</h4>
  <ul class="mc-list reveal">
    <li>
      <p>Who showed Momo around the office on her first day?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Her branch manager</li>
        <li><span>(B)</span>An older gentleman</li>
        <li><span>(C)</span>A man from reception</li>
        <li><span>(D)</span>A friendly colleague named Liam</li>
      </ul>
    </li>
    <li>
      <p>What was a big difference between the London office and her Japanese office?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The London office was smaller.</li>
        <li><span>(B)</span>The London office had more advanced technology.</li>
        <li><span>(C)</span>The London office had fewer windows.</li>
        <li><span>(D)</span>The colleagues in London were not friendly.</li>
      </ul>
    </li>
    <li>
      <p>What did Momo see in an old photograph on the wall?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>A picture of her boss</li>
        <li><span>(B)</span>A map of London</li>
        <li><span>(C)</span>A founder wearing a necklace with a familiar symbol</li>
        <li><span>(D)</span>The company's first-ever product</li>
      </ul>
    </li>
    <li>
      <p>Why couldn't Momo find the exit from the 1st floor?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The elevator was broken.</li>
        <li><span>(B)</span>The signs were only in Japanese.</li>
        <li><span>(C)</span>Because the exit is not located on the 1st floor in the UK.</li>
        <li><span>(D)</span>She was in the wrong building.</li>
      </ul>
    </li>
    <li>
      <p>What did Momo do after she left the office?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>She went straight back to her hotel.</li>
        <li><span>(B)</span>She took a walk along the River Thames.</li>
        <li><span>(C)</span>She met Liam for dinner.</li>
        <li><span>(D)</span>She went shopping for souvenirs.</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">2.3 Reading Comprehension: True / False</h4>
  <ul class="tf-list reveal">
    <li><span class="tf-badge">T / F</span>Momo felt very calm and not nervous at all on her first day.</li>
    <li><span class="tf-badge">T / F</span>The London office was old-fashioned, just like her office in Japan.</li>
    <li><span class="tf-badge">T / F</span>Momo recognized a symbol in a photo that was the same as on her pendant.</li>
    <li><span class="tf-badge">T / F</span>Momo pressed the "G" button in the elevator first to find the exit.</li>
    <li><span class="tf-badge">T / F</span>An older man helped Momo understand the difference in floor numbering.</li>
  </ul>

  <h4 class="sub-head reveal">2.4 Let's Recall! Pair-work from the previous chapter</h4>
  <div class="recall-box reveal">
    <p>Do you remember what you talked about in the Chapter 1 Pair-work, about your worries when traveling? Tell your new partner what you discussed. Now, looking at Momo's first day, what kind of worries or problems did she face? Let's talk about what you think.</p>
  </div>

  <h4 class="sub-head reveal" style="margin-top:1.8rem">2.5 Paraphrase Practice ✍️</h4>
  <p class="inst-note reveal">各問題の最初の文（Original）とほぼ同じ意味になるように、下の文の空欄（ ____ ）に最も適切な単語を<strong style="color:var(--gold)"> [ヒント] </strong>の中から選んで埋めましょう。（各単語は一度しか使えません）</p>
  <div class="hint-box reveal"><strong>[ヒント]</strong> &nbsp; view &nbsp;/&nbsp; modern &nbsp;/&nbsp; just &nbsp;/&nbsp; anxious &nbsp;/&nbsp; different &nbsp;/&nbsp; great</div>
  <div class="para-box reveal">
    <p class="orig">Original: The office was equipped with state-of-the-art technology.</p>
    <p class="blank-line">= The office had very ______ technology.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: …a symbol exactly like the one on her pendant.</p>
    <p class="blank-line">= …a symbol that was ______ the same as the one on her pendant.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: Momo felt a bit overwhelmed.</p>
    <p class="blank-line">= Momo felt a little ______ because there were so many new things to learn.</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 3 LISTENING                        -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">3</span>
    <span class="sec-title">Listening</span>
    <span class="sec-emoji">🎧</span>
  </div>

  <h4 class="sub-head reveal">3.1 Listening Comprehension: Multiple Choice</h4>
  <p class="inst-note reveal">Listen to the conversation and choose the best answer.</p>
  <ul class="mc-list reveal">
    <li>
      <p>What did Liam do on his first day at work?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>He walked into the wrong office.</li>
        <li><span>(B)</span>He broke the elevator.</li>
        <li><span>(C)</span>He spilled coffee on his laptop.</li>
        <li><span>(D)</span>He arrived late to his meeting.</li>
      </ul>
    </li>
    <li>
      <p>What does a "brew" mean in this conversation?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>A strong cup of coffee</li>
        <li><span>(B)</span>A cold beer</li>
        <li><span>(C)</span>A cup of tea</li>
        <li><span>(D)</span>A short break</li>
      </ul>
    </li>
    <li>
      <p>What is the "tea round" tradition?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Buying tea for the boss</li>
        <li><span>(B)</span>Offering to make tea for people sitting near you</li>
        <li><span>(C)</span>Drinking tea at exactly 3 PM</li>
        <li><span>(D)</span>Tasting different types of British tea</li>
      </ul>
    </li>
    <li>
      <p>What did Momo find interesting in the hallway?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The new coffee machine</li>
        <li><span>(B)</span>A map of London</li>
        <li><span>(C)</span>A photo of the original founders</li>
        <li><span>(D)</span>A painting of the building</li>
      </ul>
    </li>
    <li>
      <p>What does Liam offer to do for Momo tomorrow?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Make her a cup of tea</li>
        <li><span>(B)</span>Show her the best place to get coffee</li>
        <li><span>(C)</span>Introduce her to the boss</li>
        <li><span>(D)</span>Help her set up her computer</li>
      </ul>
    </li>
  </ul>


  <!-- ══════════════════════════════════ -->
  <!-- 4 WRITING                          -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">4</span>
    <span class="sec-title">Writing Challenge</span>
    <span class="sec-emoji">📧</span>
  </div>

  <p class="reveal" style="color:var(--mist);margin-bottom:.9rem">オフィスでの初日を終えたMomoの立場で、日本の親友に今日の出来事を伝えるEメールを書きましょう（約50–70語）。新しい同僚のリアムのことや、エレベーターでの恥ずかしい失敗について触れてみましょう。</p>
  <div class="email-frame reveal">
    <p class="field"><strong>To:</strong> Yumi (Friend)</p>
    <p class="field"><strong>Subject:</strong> My first day in London!</p>
    <hr style="border:none;border-top:1px solid rgba(255,255,255,.07);margin:.8rem 0">
    <p style="color:var(--mist)"><em>Hi Yumi,</em></p>
    <p class="body-note">[ ここにEメール本文を記入 ]</p>
    <p class="sig"><em>Best, Momo</em></p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 5 DISCUSSION                       -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">5</span>
    <span class="sec-title">Let's Discuss!</span>
    <span class="sec-emoji">🗣️</span>
  </div>

  <p class="reveal" style="color:var(--mist);margin-bottom:.8rem">Here is a discussion topic from the scene Momo faced. What do you think? Let's discuss it in English.</p>
  <div class="discuss-box reveal">
    <p class="topic">"In a new environment (like a new job or school), is it better to hide your mistakes to seem capable, or is it better to be open about them and ask for help?"</p>
    <p class="kw"><strong>Keywords:</strong> Mistakes &nbsp;·&nbsp; Capable &nbsp;·&nbsp; Asking for help</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 6 TOEIC                            -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">6</span>
    <span class="sec-title">TOEIC</span>
    <span class="sec-emoji">📝</span>
  </div>

  <h4 class="sub-head reveal">6.1 Story Scenes in the TOEIC：新入社員への社内案内・オリエンテーション</h4>
  <div class="toeic-intro reveal">
    Momoがリアムにオフィスを案内された「新入社員への社内案内（Office Orientation）」は、TOEICでも頻出のシチュエーションです。Part 3（会話問題）や Part 4（説明文問題）で、上司や先輩が新人にオフィスの設備やルールを説明する場面がよく登場します。誰が（Who）、どこで（Where）、何を（What）しているのかを素早く把握することが重要です。「the supply closet is next to the copy machine」のように、物の位置関係を示す前置詞（next to, opposite, between など）を聞き取る練習がスコアアップに繋がります。
  </div>

  <h4 class="sub-head reveal">6.2 TOEIC Practice: Part 5</h4>
  <p class="toeic-dir reveal">Directions: A word or phrase is missing in each of the sentences below. Four answer choices are given below each sentence. Select the best answer to complete the sentence. Then mark the letter (A), (B), (C), or (D) on your answer sheet.</p>
  <ul class="mc-list reveal">
    <li>
      <p>New employees will be given a tour of the facility on their first day of _______.</p>
      <ul class="mc-choices">
        <li><span>(A)</span>employ</li>
        <li><span>(B)</span>employment</li>
        <li><span>(C)</span>employee</li>
        <li><span>(D)</span>employing</li>
      </ul>
    </li>
    <li>
      <p>The reception desk is located directly _______ the main entrance.</p>
      <ul class="mc-choices">
        <li><span>(A)</span>opposite</li>
        <li><span>(B)</span>between</li>
        <li><span>(C)</span>along</li>
        <li><span>(D)</span>next</li>
      </ul>
    </li>
    <li>
      <p>Ms. Jones was _______ to learn that her proposal had been approved by the board of directors.</p>
      <ul class="mc-choices">
        <li><span>(A)</span>delightful</li>
        <li><span>(B)</span>delighted</li>
        <li><span>(C)</span>delighting</li>
        <li><span>(D)</span>delight</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">Business Scene Background</h4>
  <div class="toeic-intro reveal">
    新入社員や転属者に対する初日の社内案内（Office Orientation/Tour）は、どんな会社でも行われる重要なプロセスです。TOEICの世界でも、この場面は頻繁に登場します。目的は、新しい環境にスムーズに馴染めるよう、物理的な設備（自分のデスク、会議室、給湯室、コピー機など）の場所や、社内の基本的なルール、そして何よりも同僚たちを紹介することです。このプロセスを通じて、新人は自分がチームの一員であることを実感し、不安を和らげることができます。案内役は、人事部の担当者や直属の上司、あるいはMomoの場合のように、親切な同僚が務めることもあります。このシーンは、場所や位置関係、人物紹介に関する基本的な語彙や表現が多用されるため、ビジネスコミュニケーションの基礎を学ぶ上で格好の教材となります。
  </div>

  <h4 class="sub-head reveal">TIPS</h4>
  <div class="tips-box reveal">
    <p class="tip-label">🏢 フロアの数え方</p>
    <p>本文の通り、イギリスでは1階を「Ground Floor」、2階を「First Floor」と呼びます。これはフランスなどヨーロッパの多くの国でも同様です。一方、アメリカや日本では1階を「First Floor」と呼ぶため、海外のビルでは混乱しがちです。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">👤 ファーストネームの文化</p>
    <p>イギリスをはじめとする欧米の職場では、上司や社長であってもファーストネームで呼ぶのが一般的です。これは相手に敬意がないわけではなく、「フラットでオープンなコミュニケーション」を重視しているためです。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">☕ ティー・ラウンド (Tea Round)</p>
    <p>イギリスのオフィス文化に欠かせないのが「紅茶」です。自分が給湯室に紅茶やコーヒーを淹れに行くついでに、「Anyone want a brew?（お茶飲む人いる？）」と周囲の同僚に声をかけ、全員分をまとめて淹れる習慣を「ティー・ラウンド」と呼びます。同僚との絆を深める大切なスモールトークの機会にもなっています。</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 7 ASK AI                           -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">7</span>
    <span class="sec-title">Ask AI</span>
    <span class="sec-emoji">🤖</span>
  </div>
  <div class="ai-card reveal">
    <p>Ask the AI assistant for help. For example, you can type:</p>
    <p class="example">"Please give me five useful English phrases I can use when I get lost or need directions inside a large office building."</p>
  </div>

</div><!-- /.page-body -->

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
// ── スクロールリビール ─────────────────────────────
(function () {
  const els = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) {
    els.forEach(el => el.classList.add('visible'));
    return;
  }
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
    });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  els.forEach(el => obs.observe(el));
})();

// ── カーテン ──────────────────────────────────────
document.getElementById('curtain').addEventListener('animationend', function () {
  this.style.display = 'none';
});

// ── Repeat+1 アコーディオン ────────────────────────
function toggleAcc() {
  const btn  = document.getElementById('rp1-btn');
  const body = document.getElementById('rp1-body');
  const open = body.classList.toggle('open');
  btn.classList.toggle('open', open);
  btn.querySelector('span:first-child').textContent =
    open ? '【Repeat + 1 Phrase List】閉じる' : '【Repeat + 1 Phrase List】全フレーズを表示する';
}
</script>
</body>
</html>
