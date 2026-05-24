<?php
require_once __DIR__ . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chapter 03 — A Different Rhythm of Work | Momo's London</title>
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

  /* ════════════════════════════════════════
     CINEMATIC ENTRANCE OVERLAY
  ════════════════════════════════════════ */
  #curtain {
    position: fixed; inset: 0; z-index: 9000;
    background: var(--navy);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    pointer-events: none;
    animation: curtainLift 0.9s 1.1s cubic-bezier(0.76, 0, 0.24, 1) both;
  }
  @keyframes curtainLift {
    from { transform: translateY(0);    opacity: 1; }
    to   { transform: translateY(-102%); opacity: 1; }
  }
  /* Big chapter number shown on curtain */
  #curtain .c-num {
    font-family: 'Playfair Display', serif;
    font-size: clamp(6rem, 20vw, 14rem);
    font-weight: 700;
    color: transparent;
    -webkit-text-stroke: 1px rgba(184,150,12,0.35);
    letter-spacing: -.02em;
    line-height: 1;
    animation: cNumReveal 0.7s 0.15s cubic-bezier(0.34, 1.4, 0.64, 1) both;
  }
  @keyframes cNumReveal {
    from { opacity: 0; transform: scale(1.5); }
    to   { opacity: 1; transform: scale(1); }
  }
  /* Golden sweep line under number */
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
  /* Chapter label on curtain */
  #curtain .c-label {
    font-size: .72rem; letter-spacing: .4em; text-transform: uppercase;
    color: var(--gold); margin-top: .8rem;
    animation: fadeSlideUp 0.5s 0.7s ease both;
  }
  @keyframes fadeSlideUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
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
     HERO — word-by-word stagger
  ════════════════════════════════════════ */
  .ch-hero {
    text-align: center;
    padding: 3.5rem 2rem 2.5rem;
    border-bottom: 1px solid rgba(184,150,12,.08);
    overflow: hidden;
  }
  /* Badge pops in after curtain lifts */
  .ch-num-label {
    display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase;
    background: rgba(184,150,12,.12); border: 1px solid rgba(184,150,12,.28); color: var(--gold);
    padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem;
    animation: popIn 0.55s 1.25s cubic-bezier(0.34, 1.56, 0.64, 1) both;
  }
  @keyframes popIn {
    from { opacity: 0; transform: scale(0.6) translateY(8px); }
    to   { opacity: 1; transform: scale(1)   translateY(0); }
  }
  .ch-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.7rem, 4vw, 2.8rem);
    font-weight: 700; line-height: 1.15;
    overflow: hidden;
  }
  /* Each word in <em> or .word spans slides up */
  .ch-hero h1 .word {
    display: inline-block;
    animation: wordRise 0.6s cubic-bezier(0.22, 1, 0.36, 1) both;
  }
  /* Stagger delays set by inline style via PHP below */
  .ch-hero h1 .word em { font-style: italic; color: var(--gold); }

  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1rem auto; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, var(--gold)); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, var(--gold)); }
  .ch-rule .d { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
  .ch-rule { animation: fadeSlideUp 0.5s 2.1s ease both; }

  @keyframes wordRise {
    from { opacity: 0; transform: translateY(60px) rotate(2deg); }
    to   { opacity: 1; transform: translateY(0)    rotate(0deg); }
  }

  /* ════════════════════════════════════════
     SCROLL-REVEAL for body sections
  ════════════════════════════════════════ */
  .reveal {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.65s ease, transform 0.65s cubic-bezier(0.22,1,0.36,1);
  }
  .reveal.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* ════════════════════════════════════════
     LAYOUT
  ════════════════════════════════════════ */
  .page-body { max-width: 860px; margin: 0 auto; padding: 2.5rem 2rem 6rem; }

  /* ── SECTION HEADERS ── */
  .sec-head {
    display: flex; align-items: center; gap: 1rem;
    margin: 3rem 0 1.4rem; padding-bottom: .5rem;
    border-bottom: 1px solid rgba(184,150,12,.2);
  }
  .sec-num { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: rgba(184,150,12,.45); line-height: 1; }
  .sec-title { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--cream); }
  .sec-emoji { font-size: 1.1rem; }

  /* ★ sub-head: 常に 1.25rem ★ */
  .sub-head {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--mist);
    margin: 1.8rem 0 .8rem;
  }

  /* ── QUIZ CARD ── */
  .quiz-card { background: rgba(184,150,12,.07); border: 1px solid rgba(184,150,12,.25); border-radius: 3px; padding: 1.4rem 1.6rem; margin-bottom: 1.5rem; }
  .quiz-card p { color: var(--mist); margin-bottom: .4rem; }
  .quiz-card strong { color: var(--gold); }

  /* ── PHRASE LIST ── */
  .phrase-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .5rem .8rem; margin-bottom: 1.2rem; }
  .phrase-list li { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .55rem 1rem; font-size: .97rem; }
  .phrase-list li strong { color: var(--gold); }

  /* ── DIALOGUE BLOCK ── */
  .dialogue { background: var(--bg2); border-left: 3px solid var(--gold); border-radius: 0 2px 2px 0; padding: 1.2rem 1.5rem; margin-bottom: 1.2rem; }
  .dialogue p { margin-bottom: .3rem; }
  .dialogue p:last-child { margin-bottom: 0; }
  .dialogue strong { color: var(--gold); }

  /* ── FILL DIALOGUE ── */
  .fill-note { font-style: italic; color: var(--fog); font-size: .9rem; margin-bottom: .8rem; }
  .fill-choice { font-size: .9rem; color: var(--teal); opacity: .8; }

  /* ── STORY ── */
  .story-wrap { background: var(--bg2); border: 1px solid var(--border); border-radius: 3px; padding: 1.6rem 2rem; margin: 1rem 0; }
  .story-wrap p { margin-bottom: 1rem; color: var(--mist); }
  .story-wrap p:last-child { margin-bottom: 0; }

  /* ── WORD LIST ── */
  .word-list { list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: .45rem .8rem; }
  .word-list li { background: var(--bg2); border: 1px solid var(--border); padding: .5rem .9rem; border-radius: 2px; font-size: .93rem; }
  .word-list li strong { color: var(--gold); }
  .word-list li .pron { color: var(--fog); font-size: .82rem; }

  /* ════════════════════════════════════
     ★ MC / T-F  (縦並び・色修正) ★
  ════════════════════════════════════ */
  .mc-list { list-style: none; margin-bottom: 1.2rem; }
  .mc-list > li { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .9rem 1.2rem; margin-bottom: .55rem; }
  .mc-list > li p { color: var(--mist); font-weight: 500; margin-bottom: .6rem; }

  /* ★ 縦1列・var(--mist) ★ */
  .mc-choices { list-style: none; display: flex; flex-direction: column; gap: .3rem; }
  .mc-choices li { font-size: .95rem; color: var(--mist); display: flex; align-items: baseline; gap: .5rem; }
  .mc-choices li span { color: var(--gold); font-weight: bold; flex-shrink: 0; min-width: 1.8rem; }

  .tf-list { list-style: none; }
  .tf-list li { display: flex; align-items: flex-start; gap: 1rem; background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .75rem 1.1rem; margin-bottom: .5rem; }
  .tf-badge { font-size: .68rem; letter-spacing: .15em; border-radius: 999px; padding: .18rem .55rem; flex-shrink: 0; margin-top: .2rem; border: 1px solid rgba(140,128,112,.3); color: var(--fog); white-space: nowrap; }

  /* ── PARAPHRASE ── */
  .para-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; margin-bottom: .7rem; }
  .para-box .orig { color: var(--fog); font-size: .92rem; margin-bottom: .3rem; }
  .para-box .blank-line { color: var(--mist); }
  .hint-box { background: rgba(184,150,12,.07); border: 1px solid rgba(184,150,12,.25); border-radius: 2px; padding: .65rem 1.1rem; margin-bottom: 1rem; font-size: .93rem; }
  .hint-box strong { color: var(--gold); }

  /* ── WRITING / EMAIL ── */
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

  /* ── TIPS BOX ── */
  .tips-box { background: rgba(46,125,94,.06); border: 1px solid rgba(76,175,133,.2); border-radius: 2px; padding: .9rem 1.2rem; margin-bottom: .65rem; }
  .tips-box .tip-label { font-size: .7rem; letter-spacing: .2em; text-transform: uppercase; color: var(--gl); margin-bottom: .3rem; }
  .tips-box p { color: var(--mist); font-size: .95rem; }

  /* ── SCANNING ── */
  .scan-list { list-style: none; }
  .scan-list li { display: flex; align-items: baseline; gap: .7rem; background: var(--bg2); border: 1px solid var(--border); padding: .65rem 1.1rem; border-radius: 2px; margin-bottom: .45rem; font-size: .95rem; }
  .scan-list li .arrow { color: var(--gold); flex-shrink: 0; }
  .scan-note { font-size: .88rem; color: var(--fog); font-style: italic; margin-bottom: .8rem; }

  /* ── AI CARD ── */
  .ai-card { background: rgba(31,122,140,.08); border: 1px solid rgba(31,122,140,.28); border-radius: 3px; padding: 1.3rem 1.5rem; }
  .ai-card p { color: var(--mist); font-size: .95rem; margin-bottom: .6rem; }
  .ai-card .example { font-style: italic; color: #5bc4d8; font-size: .93rem; }

  /* ── MISC ── */
  .inst-note { font-size: .9rem; color: var(--fog); font-style: italic; margin-bottom: .9rem; }
  .recall-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; color: var(--mist); }

  /* ── FOOTER ── */
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
  <div class="c-num">03</div>
  <div class="c-line"></div>
  <div class="c-label">Chapter Three</div>
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
  <span class="ch-num-label">Chapter 03</span>
  <h1>
    <?php
    // Word-by-word stagger: each word gets its own delay after curtain lifts (base: 1.5s)
    $words = ['A', 'Different', 'Rhythm', 'of', 'Work'];
    $base  = 1.5;
    $step  = 0.1;
    foreach ($words as $i => $w) {
        $delay = $base + $i * $step;
        $italic = in_array($w, ['Different','Rhythm','of','Work']);
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
    <p><strong>A:</strong> What is a "pub" short for in the UK?</p>
    <p><strong>B:</strong> _______. By the way, what is the standard unit for ordering a beer in a British pub?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <!-- ── Important Phrases ── -->
  <h3 class="sub-head reveal">Important Phrases</h3>
  <ul class="phrase-list reveal">
    <li><strong>brainstorming session</strong>：アイデアを出し合う会議</li>
    <li><strong>speak up</strong>：意見を言う、発言する</li>
    <li><strong>cut each other off</strong>：人の話を遮る</li>
    <li><strong>culture shock</strong>：カルチャーショック</li>
    <li><strong>desk lunch</strong>：机での昼食</li>
    <li><strong>work-life balance</strong>：仕事と私生活のバランス</li>
    <li><strong>annual leave</strong>：年次有給休暇</li>
    <li><strong>a completely different approach</strong>：全く異なるアプローチ</li>
    <li><strong>modernized version</strong>：現代版</li>
    <li><strong>community hub</strong>：コミュニティの中心</li>
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
    <p><strong>A:</strong> I'm finished for the day. Are you heading home?</p>
    <p><strong>B:</strong> Not yet. I'm going for a quick drink with some colleagues. Would you like to join us?</p>
    <p><strong>A:</strong> I'd love to, but I have other plans tonight. Maybe next time!</p>
    <p><strong>B:</strong> Sure, no problem. Have a good evening!</p>
    <p><strong>A:</strong> You too! See you tomorrow.</p>
  </div>

  <h4 class="sub-head reveal">1.2 Let's complete the conversation!</h4>
  <p class="fill-note reveal">Fill in the blanks with your own ideas and practice the conversation with a partner. You may also choose from the words in parentheses. Once done, switch roles A and B. After that, make up a continuation and keep the conversation going.</p>
  <div class="dialogue reveal">
    <p><strong>A:</strong> That was a long meeting. I need a break.</p>
    <p><strong>B:</strong> Me too. Are you taking a lunch break now?</p>
    <p><strong>A:</strong> Yes. I'm going to the cafe ＿＿＿. Do you want to come?<br>
      <span class="fill-choice">(downstairs / across the street)</span></p>
    <p><strong>B:</strong> I'd like to, but I brought a sandwich from home today. I usually eat at my desk.</p>
    <p><strong>A:</strong> Oh, okay. In my previous job, we all went out for lunch together every day. It feels ＿＿＿.<br>
      <span class="fill-choice">(a little strange / different)</span></p>
    <p><strong>B:</strong> I see. Well, maybe we can have lunch together tomorrow.</p>
    <p><strong>A:</strong> That sounds great!</p>
  </div>

  <h4 class="sub-head reveal">1.3 Let's repeat +1</h4>
  <p class="inst-note reveal">Repeat the completed conversation, but add one (+1) phrase each time you speak. Keep the conversation going until the teacher says "Stop."<br>(Please refer to Chapter 2 for examples and the "Repeat + 1 Phrase List".)</p>
  <div class="dialogue reveal">
    <p><strong>A:</strong> That was a long meeting. I need a break.</p>
    <p><strong>B:</strong> Me too. Are you taking a lunch break now?</p>
    <p><strong>A:</strong> Yes. I'm going to the cafe downstairs. Do you want to come?</p>
    <p><strong>B:</strong> I'd like to, but I brought a sandwich from home today. I usually eat at my desk.</p>
    <p><strong>A:</strong> Oh, okay. In my previous job, we all went out for lunch together every day. It feels a little strange.</p>
    <p><strong>B:</strong> I see. Well, maybe we can have lunch together tomorrow.</p>
    <p><strong>A:</strong> That sounds great!</p>
  </div>


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
    <p><strong>A:</strong> In a meeting, is it more important to listen quietly or to share your ideas actively? Why?</p>
    <p><strong>B:</strong> _______. Speaking of which, what is a typical lunch break like for a university student in Japan?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <h4 class="sub-head reveal">2.1 Scanning Practice</h4>
  <p class="scan-note reveal">TOEICでは全般的に同義語を文中から素早く探すスキャニング能力が必須です。下記の単語・表現と同じ意味を持つキーワード（カッコの中の頭文字で始まるもの）を本文から素早く探しましょう。見つけられるまでの秒数を各自計り、記録しましょう。TOEICは言い換えと引っかけのテストです。言い換え（パラフレーズ）を常に意識しておきましょう。セットで暗記すると語彙力２倍になりますね。</p>
  <ul class="scan-list reveal">
    <li><span style="color:var(--mist)">energetic and passionate discussion</span><span class="arrow">→</span><span style="color:var(--gold)">(h- )</span></li>
    <li><span style="color:var(--mist)">impolite</span><span class="arrow">→</span><span style="color:var(--gold)">(r- )</span></li>
    <li><span style="color:var(--mist)">yearly vacation</span><span class="arrow">→</span><span style="color:var(--gold)">(a- )</span></li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">Story: <em>A Different Rhythm of Work</em></h4>
  <div class="story-wrap reveal">
    <p>A week into her job, Momo was asked to join her first team meeting. In Japan, meetings were often quiet, with junior employees listening respectfully as their seniors spoke. Momo expected something similar. She could not have been more wrong.</p>
    <p>The meeting was a brainstorming session for a new tour package. Her boss, Mr. Davies, encouraged everyone to speak up. Her colleagues started sharing ideas one after another. Liam proposed a history tour, while another colleague, Chloe, advocated for a food-focused tour. Soon, it turned into a heated debate. They would cut each other off, challenge one another, and defend their ideas with passion. Momo was shocked. In Japan, this might be seen as rude, but here, it seemed to be normal.</p>
    <p>Another culture shock came at lunchtime. Around noon, Momo saw her colleagues take out sandwiches and salads. They ate right at their desks while continuing to work. No one seemed to take a proper lunch break. In Japan, she had always gone out to eat with her colleagues for an hour. It was an important time for bonding. She whispered to Liam, "Does no one go out for lunch?"</p>
    <p>Liam explained, "Most of us prefer a quick desk lunch to finish work earlier. It's all about work-life balance." He also mentioned that everyone takes at least two weeks of annual leave in the summer. It was a completely different approach to work.</p>
    <p>During the meeting, Momo noticed something on the presentation slides. In the corner of each slide was a small, stylized star logo. It looked just like the symbol on her pendant, but different. A modernized version, perhaps?</p>
    <p>That Friday evening, as promised, Liam invited her to a pub. The pub was loud and crowded, but the atmosphere was very friendly. Liam ordered her a pint of ale. It was bitter but had a rich flavor. She saw people of all ages chatting, laughing, and watching football. It wasn't just a bar; it was a community hub.</p>
    <p>"So, what are your plans for the weekend?" Liam asked over the noise.</p>
    <p>"I'm thinking of going to a museum," Momo replied, feeling a bit more relaxed.</p>
    <p>"Great choice! You'll love them," he smiled.</p>
    <p>Momo thought about the lively meetings and quick lunches. It was all part of a new rhythm she had to learn. The different work procedures were challenging, but she was starting to find this new culture fascinating.</p>
  </div>

  <h4 class="sub-head reveal">Word List</h4>
  <ul class="word-list reveal">
    <li><strong>brainstorming</strong> <span class="pron">(名) /bréɪnstɔ̀ːrmɪŋ/</span><br>ブレインストーミング、アイデア出し</li>
    <li><strong>advocate</strong> <span class="pron">(動) /ˈædvəkèɪt/</span><br>～を主張する、支持する</li>
    <li><strong>debate</strong> <span class="pron">(名) /dɪbéɪt/</span><br>討論、ディベート</li>
    <li><strong>rude</strong> <span class="pron">(形) /rúːd/</span><br>失礼な、無作法な</li>
    <li><strong>procedure</strong> <span class="pron">(名) /prəsíːdʒər/</span><br>手順、手続き</li>
    <li><strong>pint</strong> <span class="pron">(名) /paɪnt/</span><br>パイント（約568ml）</li>
    <li><strong>fascinating</strong> <span class="pron">(形) /fǽsənèɪṭɪŋ/</span><br>魅力的な、とても面白い</li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">2.2 Reading Comprehension: Multiple Choice</h4>
  <ul class="mc-list reveal">
    <li>
      <p>What was the purpose of Momo's first team meeting?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>To decide the summer vacation schedule</li>
        <li><span>(B)</span>To brainstorm ideas for a new tour package</li>
        <li><span>(C)</span>To discuss the company's financial results</li>
        <li><span>(D)</span>To introduce herself to the team</li>
      </ul>
    </li>
    <li>
      <p>How did Momo feel about the way her colleagues behaved in the meeting?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>She was shocked because it seemed rude compared to Japan.</li>
        <li><span>(B)</span>She thought it was productive and efficient.</li>
        <li><span>(C)</span>She was happy to see everyone agreeing with each other.</li>
        <li><span>(D)</span>She was bored because nobody shared any ideas.</li>
      </ul>
    </li>
    <li>
      <p>What did Momo learn about lunch breaks at the London office?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Nobody is allowed to eat in the office.</li>
        <li><span>(B)</span>Everyone goes out to a restaurant for one hour.</li>
        <li><span>(C)</span>The company provides a free lunch for all employees.</li>
        <li><span>(D)</span>Most people eat a quick lunch at their desks.</li>
      </ul>
    </li>
    <li>
      <p>According to Liam, why do people prefer a quick "desk lunch"?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>To save money</li>
        <li><span>(B)</span>Because they don't like the nearby restaurants</li>
        <li><span>(C)</span>To achieve a better work-life balance</li>
        <li><span>(D)</span>Because the boss forces them to</li>
      </ul>
    </li>
    <li>
      <p>What did Momo do on Friday evening?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>She went to a pub with her colleague, Liam.</li>
        <li><span>(B)</span>She went to a museum by herself.</li>
        <li><span>(C)</span>She went home to recover from jet lag.</li>
        <li><span>(D)</span>She worked late at the office.</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">2.3 Reading Comprehension: True / False</h4>
  <ul class="tf-list reveal">
    <li><span class="tf-badge">T / F</span>The team meeting was very quiet and formal.</li>
    <li><span class="tf-badge">T / F</span>Momo actively participated in the debate during the meeting.</li>
    <li><span class="tf-badge">T / F</span>Liam explained that taking long summer holidays is common.</li>
    <li><span class="tf-badge">T / F</span>Momo noticed that the company's logo looked like a modernized version of the symbol on her pendant.</li>
    <li><span class="tf-badge">T / F</span>Momo did not enjoy the taste of the ale at the pub.</li>
  </ul>

  <h4 class="sub-head reveal">2.4 Let's Recall! Pair-work from the previous chapter</h4>
  <div class="recall-box reveal">
    <p>Do you remember what you talked about in the Chapter 2 Pair-work, about small cultural differences? Tell your new partner what you discussed. Now, looking at the meeting and lunch scenes in today's story, what was different from Japanese culture? Let's talk about what you felt.</p>
  </div>

  <h4 class="sub-head reveal" style="margin-top:1.8rem">2.5 Paraphrase Practice ✍️</h4>
  <p class="inst-note reveal">各問題の最初の文（Original）とほぼ同じ意味になるように、下の文の空欄（ ____ ）に最も適切な単語を<strong style="color:var(--gold)"> [ヒント] </strong>の中から選んで埋めましょう。（各単語は一度しか使えません）</p>
  <div class="hint-box reveal"><strong>[ヒント]</strong> &nbsp; interrupt &nbsp;/&nbsp; common &nbsp;/&nbsp; suggested &nbsp;/&nbsp; shocked &nbsp;/&nbsp; earlier &nbsp;/&nbsp; passionate</div>
  <div class="para-box reveal">
    <p class="orig">Original: Liam proposed a history tour.</p>
    <p class="blank-line">= Liam ______ that they should create a history tour.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: They would cut each other off…</p>
    <p class="blank-line">= They would often ______ each other while speaking.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: …here, it seemed to be normal.</p>
    <p class="blank-line">= …but in London, it seemed to be a ______ way to behave.</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 3 LISTENING                        -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">3</span>
    <span class="sec-title">Listening</span>
    <span class="sec-emoji">🎧</span>
  </div>

  <h4 class="sub-head reveal">3.1 Listening Comprehension: True / False</h4>
  <p class="inst-note reveal">Listen to the conversation and decide if the following statements are true or false.</p>
  <ul class="tf-list reveal">
    <li><span class="tf-badge">T / F</span>Momo thinks a British pub is very similar to a Japanese izakaya.</li>
    <li><span class="tf-badge">T / F</span>A <em>nomikai</em> is a type of Japanese food that Liam wants to try.</li>
    <li><span class="tf-badge">T / F</span>According to Momo, attending a <em>nomikai</em> can sometimes feel like part of the job.</li>
    <li><span class="tf-badge">T / F</span>In a <em>nomikai</em>, junior employees often pour drinks for their seniors.</li>
    <li><span class="tf-badge">T / F</span>Liam explains that in a pub, the boss always buys drinks for everyone.</li>
  </ul>


  <!-- ══════════════════════════════════ -->
  <!-- 4 WRITING                          -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">4</span>
    <span class="sec-title">Writing Challenge</span>
    <span class="sec-emoji">📧</span>
  </div>

  <p class="reveal" style="color:var(--mist);margin-bottom:.9rem">ロンドンの職場の文化（会議の進め方や昼食の習慣）に驚いているMomoになったつもりで、日本の元の同僚にその違いを伝えるEメールを書きましょう（約50–70語）。</p>
  <div class="email-frame reveal">
    <p class="field"><strong>To:</strong> Kenji (Former Colleague)</p>
    <p class="field"><strong>Subject:</strong> London office culture is so different!</p>
    <hr style="border:none;border-top:1px solid rgba(255,255,255,.07);margin:.8rem 0">
    <p style="color:var(--mist)"><em>Hi Kenji,</em></p>
    <p class="body-note">[ ここにEメール本文を記入 ]</p>
    <p class="sig"><em>All the best, Momo</em></p>
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
    <p class="topic">"In a business meeting, is it better for junior employees to listen respectfully, or to actively challenge the ideas of their seniors?"</p>
    <p class="kw"><strong>Keywords:</strong> Respect &nbsp;·&nbsp; Active participation &nbsp;·&nbsp; Hierarchy</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 6 TOEIC                            -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">6</span>
    <span class="sec-title">TOEIC</span>
    <span class="sec-emoji">📝</span>
  </div>

  <h4 class="sub-head reveal">6.1 Story Scenes in the TOEIC</h4>
  <p class="reveal" style="font-size:.82rem;color:var(--fog);margin-bottom:.7rem">ブレインストーミング会議・同僚との意見交換</p>
  <div class="toeic-intro reveal">
    MomoがはじめてのTOEICのPart 3（会話問題）や Part 7（Eメール、チャット）で非常によく出題されます。複数の同僚が新しい企画について議論したり、業務改善の提案をしたりする場面では、誰がどの意見に賛成 (agree/support) で、誰が反対 (disagree/oppose) しているのか、そしてどのような提案 (suggestion/proposal) がなされているのかを正確に聞き取ることが重要です。「Why don't we…?」「How about…?」「I think we should…」といった提案の定型表現に注意しましょう。
  </div>

  <h4 class="sub-head reveal">6.2 TOEIC Practice: Part 3</h4>
  <p class="toeic-dir reveal">Directions: You will hear some conversations between two or more people. You will be asked to answer three questions about what the speakers say in each conversation. Select the best response to each question and mark the letter (A), (B), (C), or (D) on your answer sheet.</p>
  <ul class="mc-list reveal">
    <li>
      <p>What is the main purpose of the meeting?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>To generate marketing ideas</li>
        <li><span>(B)</span>To review a budget report</li>
        <li><span>(C)</span>To plan a company party</li>
        <li><span>(D)</span>To hire a new employee</li>
      </ul>
    </li>
    <li>
      <p>What is David's concern about a video contest?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>It might be too old-fashioned.</li>
        <li><span>(B)</span>It would take too much time.</li>
        <li><span>(C)</span>It could be too expensive.</li>
        <li><span>(D)</span>It would not be popular.</li>
      </ul>
    </li>
    <li>
      <p>What will Kenji most likely do next?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Contact a photographer</li>
        <li><span>(B)</span>Schedule the next meeting</li>
        <li><span>(C)</span>Create a video</li>
        <li><span>(D)</span>Write a report on costs</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">Business Scene Background</h4>
  <div class="toeic-intro reveal">
    欧米のビジネス文化において「ブレインストーミング」は、非常に重要かつ一般的な会議手法です。その目的は、役職や年齢に関係なく、参加者全員が自由にアイデアを出し合い、創造的な解決策や新しい企画を生み出すことにあります。日本の「根回し」を重視し、会議の場では決定事項を確認することが多い文化とは対照的に、ブレインストーミングではその場で活発な議論が交わされることが「良い会議」の証とされます。重要なルールは「批判の禁止（No Criticism）」です。どんなに突飛なアイデアでも、まずは否定せずに受け入れることで、参加者が萎縮せず、より斬新な発想が生まれやすくなると考えられています。Momoが体験したように、人の意見を遮ってでも自分のアイデアを主張することが、やる気や貢献意欲の表れと見なされることもあります。
  </div>

  <h4 class="sub-head reveal">TIPS</h4>
  <div class="tips-box reveal">
    <p class="tip-label">🥪 イギリスのランチ事情</p>
    <p>本文の通り、オフィスワーカーがサンドイッチやサラダなどをデスクで手早く済ませる「デスクランチ」は非常に一般的です。スーパーには "Meal Deal" という、サンドイッチ＋スナック＋飲み物のセットが£3〜£4程度で買えるお得なランチセットがあり、多くの人が利用しています。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">🏖️ 長期休暇の考え方</p>
    <p>イギリスでは、法律で定められた年次有給休暇（年間最低5.6週間）をしっかり取得することが労働者の権利として強く認識されています。特に夏には2週間程度のまとまった休みを取り、家族と海外旅行などに出かけるのが一般的です。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">🍺 パブでの一杯</p>
    <p>金曜の仕事終わりに同僚とパブに飲みに行くのは "TGIF" (Thank God It's Friday) の習慣として根付いています。ビールやエールを1パイント（約568ml）ずつ頼み、立ったままおしゃべりを楽しむのが一般的なスタイルです。</p>
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
    <p class="example">"Give me 5 useful English phrases for politely interrupting someone during a business meeting."</p>
  </div>

</div><!-- /.page-body -->

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
// ── スクロール・リビール (IntersectionObserver) ──────────
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

// カーテンが完全に消えたら DOM から削除（スクロールの邪魔をしないように）
document.getElementById('curtain').addEventListener('animationend', function () {
  this.style.display = 'none';
});
</script>
</body>
</html>
