<?php
require_once __DIR__ . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chapter 04 — Finding Her Voice | Momo's London</title>
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
  @keyframes curtainLift { from { transform: translateY(0); } to { transform: translateY(-102%); } }
  #curtain .c-num {
    font-family: 'Playfair Display', serif;
    font-size: clamp(6rem, 20vw, 14rem); font-weight: 700; color: transparent;
    -webkit-text-stroke: 1px rgba(184,150,12,.35); line-height: 1;
    animation: cNumReveal 0.7s 0.15s cubic-bezier(0.34,1.4,0.64,1) both;
  }
  @keyframes cNumReveal { from { opacity:0; transform:scale(1.5); } to { opacity:1; transform:scale(1); } }
  #curtain .c-line {
    width: 0; height: 1px;
    background: linear-gradient(to right, transparent, var(--gold), transparent);
    margin-top: 1rem;
    animation: lineExpand 0.6s 0.55s ease both;
  }
  @keyframes lineExpand { from { width:0; opacity:0; } to { width:min(320px,60vw); opacity:1; } }
  #curtain .c-label {
    font-size: .72rem; letter-spacing: .4em; text-transform: uppercase;
    color: var(--gold); margin-top: .8rem;
    animation: fadeSlideUp 0.5s 0.7s ease both;
  }

  /* ── HEADER ── */
  header {
    border-bottom: 1px solid var(--border); padding: 1.1rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 200;
    background: rgba(13,27,42,.9); backdrop-filter: blur(12px);
    animation: headerDrop 0.5s 1.8s ease both;
  }
  @keyframes headerDrop { from { opacity:0; transform:translateY(-100%); } to { opacity:1; transform:translateY(0); } }
  .site-title { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; color: var(--cream); text-decoration: none; }
  .site-title em { font-style: italic; color: var(--gold); }
  .hdr-r { display: flex; align-items: center; gap: 1rem; }
  .btn-sm { font-size: .72rem; letter-spacing: .15em; text-transform: uppercase; color: var(--fog); text-decoration: none; border: 1px solid rgba(140,128,112,.4); padding: .28rem .75rem; border-radius: 1px; transition: color .2s, border-color .2s; }
  .btn-sm:hover { color: var(--cream); border-color: var(--mist); }

  /* ── HERO ── */
  .ch-hero { text-align: center; padding: 3.5rem 2rem 2.5rem; border-bottom: 1px solid rgba(184,150,12,.08); overflow: hidden; }
  .ch-num-label {
    display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase;
    background: rgba(184,150,12,.12); border: 1px solid rgba(184,150,12,.28); color: var(--gold);
    padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem;
    animation: popIn 0.55s 1.25s cubic-bezier(0.34,1.56,0.64,1) both;
  }
  @keyframes popIn { from { opacity:0; transform:scale(0.6) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
  .ch-hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(1.7rem,4vw,2.8rem); font-weight: 700; line-height: 1.15; }
  .ch-hero h1 .word { display: inline-block; animation: wordRise 0.6s cubic-bezier(0.22,1,0.36,1) both; }
  .ch-hero h1 .word em { font-style: italic; color: var(--gold); }
  @keyframes wordRise { from { opacity:0; transform:translateY(60px) rotate(2deg); } to { opacity:1; transform:translateY(0) rotate(0deg); } }
  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1rem auto; animation: fadeSlideUp 0.5s 2.1s ease both; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, var(--gold)); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, var(--gold)); }
  .ch-rule .d { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
  @keyframes fadeSlideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

  /* ── SCROLL REVEAL ── */
  .reveal { opacity:0; transform:translateY(26px); transition: opacity 0.65s ease, transform 0.65s cubic-bezier(0.22,1,0.36,1); }
  .reveal.visible { opacity:1; transform:translateY(0); }

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

  /* ── PAIR-WORK BOX ── */
  .pairwork-box { background: rgba(31,122,140,.07); border: 1px solid rgba(31,122,140,.25); border-radius: 2px; padding: 1.1rem 1.4rem; margin-bottom: 1rem; }
  .pairwork-box p { color: var(--mist); font-size: .97rem; }
  .pairwork-box strong { color: #5bc4d8; }

  /* ── RECALL BOX ── */
  .recall-box { background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: 1rem 1.3rem; color: var(--mist); }

  /* ── EMAIL (Writing) ── */
  .email-frame { background: rgba(0,0,0,.25); border: 1px solid var(--border); border-radius: 2px; padding: 1.3rem 1.5rem; margin: .8rem 0; font-size: .97rem; }
  .email-frame .field { color: var(--fog); font-size: .88rem; margin-bottom: .2rem; }
  .email-frame .field strong { color: var(--mist); }
  .email-frame .body-note { color: rgba(184,150,12,.6); font-style: italic; padding: .8rem 0; }
  .email-frame .sig { color: var(--mist); margin-top: .6rem; }

  /* ── TOEIC MEMO (Part 7 email) ── */
  .toeic-memo {
    background: rgba(255,255,255,.025); border: 1px solid rgba(184,150,12,.22);
    border-radius: 2px; padding: 1.3rem 1.6rem; margin: 1rem 0; font-size: .95rem;
  }
  .toeic-memo .memo-header { border-bottom: 1px solid rgba(184,150,12,.15); padding-bottom: .8rem; margin-bottom: .9rem; }
  .toeic-memo .memo-row { display: flex; gap: .6rem; margin-bottom: .25rem; font-size: .9rem; }
  .toeic-memo .memo-row .label { color: var(--gold); font-weight: bold; min-width: 4.5rem; flex-shrink: 0; }
  .toeic-memo .memo-row .val { color: var(--mist); }
  .toeic-memo .memo-body p { color: var(--mist); margin-bottom: .7rem; }
  .toeic-memo .memo-body p:last-child { margin-bottom: 0; }

  /* ── DISCUSSION ── */
  .discuss-box { background: rgba(31,122,140,.1); border: 1px solid rgba(31,122,140,.3); border-radius: 3px; padding: 1.3rem 1.5rem; margin: .8rem 0; }
  .discuss-box .topic { font-family: 'Playfair Display', serif; font-size: 1.05rem; color: var(--mist); margin-bottom: .6rem; font-style: italic; }
  .discuss-box .kw { font-size: .88rem; color: var(--fog); }
  .discuss-box .kw strong { color: #5bc4d8; }

  /* ── TOEIC INTRO ── */
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
  .inst-note { font-size: .9rem; color: var(--fog); font-style: italic; margin-bottom: .9rem; }

  /* ── FOOTER ── */
  footer { border-top: 1px solid rgba(184,150,12,.1); text-align: center; padding: 1.5rem; font-size: .77rem; letter-spacing: .12em; color: var(--fog); }

  @media (max-width: 640px) {
    header { padding: 1rem 1.2rem; }
    .page-body { padding: 1.5rem 1rem 4rem; }
    .phrase-list, .word-list { grid-template-columns: 1fr; }
    .story-wrap { padding: 1.2rem 1.1rem; }
    .toeic-memo .memo-row { flex-direction: column; gap: .1rem; }
  }
</style>
</head>
<body>

<!-- ── CURTAIN ── -->
<div id="curtain" aria-hidden="true">
  <div class="c-num">04</div>
  <div class="c-line"></div>
  <div class="c-label">Chapter Four</div>
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
  <span class="ch-num-label">Chapter 04</span>
  <h1>
    <?php
    $words = ['Finding', 'Her', 'Voice'];
    foreach ($words as $i => $w) {
        $delay = 1.5 + $i * 0.1;
        $inner = "<em>{$w}</em>";
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
    <p><strong>A:</strong> Can you name a famous art museum located in Trafalgar Square, London?</p>
    <p><strong>B:</strong> _______. By the way, how much does it cost to enter the main collection of many national museums in the UK?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <!-- ── Important Phrases ── -->
  <h3 class="sub-head reveal">Important Phrases</h3>
  <ul class="phrase-list reveal">
    <li><strong>was determined to</strong>：〜しようと固く決心していた</li>
    <li><strong>take a deep breath</strong>：深呼吸をする</li>
    <li><strong>raise her hand</strong>：手を挙げる</li>
    <li><strong>Could you please explain...?</strong>：〜を説明していただけますか？</li>
    <li><strong>a spark of confidence</strong>：小さな自信の火花</li>
    <li><strong>on the right track</strong>：正しい方向に進んで</li>
    <li><strong>do some research</strong>：少し調べる</li>
    <li><strong>look up information</strong>：情報を調べる</li>
    <li><strong>based on</strong>：〜に基づいて</li>
    <li><strong>free of charge</strong>：無料で</li>
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
    <p><strong>A:</strong> ...so, the next step is to implement the Q2 strategy. Any questions?</p>
    <p><strong>B:</strong> Yes, I have a quick question.</p>
    <p><strong>A:</strong> Go ahead, Ken.</p>
    <p><strong>B:</strong> Could you please explain what you mean by "Q2 strategy"? I'm not familiar with that term.</p>
    <p><strong>A:</strong> Of course. It stands for the strategy for the second quarter, from April to June.</p>
    <p><strong>B:</strong> I see. Thank you for clarifying.</p>
  </div>

  <h4 class="sub-head reveal">1.2 Let's complete the conversation!</h4>
  <p class="fill-note reveal">Fill in the blanks with your own ideas and practice the conversation with a partner. You may also choose from the words in parentheses. Once done, switch roles A and B. After that, make up a continuation and keep the conversation going.</p>
  <div class="dialogue reveal">
    <p><strong>A:</strong> Okay, class, for homework, please read the article and write a summary.</p>
    <p><strong>B:</strong> Excuse me, professor. I have a question.</p>
    <p><strong>A:</strong> Yes? What is it?</p>
    <p><strong>B:</strong> I'm sorry, but what does the word "＿＿＿" mean?<br>
      <span class="fill-choice">(ambiguous / significant)</span></p>
    <p><strong>A:</strong> That's a great question. It means ＿＿＿.<br>
      <span class="fill-choice">(unclear / important)</span></p>
    <p><strong>B:</strong> Oh, I understand now. And could you tell me how long the summary should be?</p>
    <p><strong>A:</strong> It should be about ＿＿＿ words.<br>
      <span class="fill-choice">(200 / 500)</span></p>
    <p><strong>B:</strong> Thank you very much! I feel much better now.</p>
  </div>

  <h4 class="sub-head reveal">1.3 Let's repeat +1</h4>
  <p class="inst-note reveal">Repeat the completed conversation, but add one (+1) phrase each time you speak. Keep the conversation going until the teacher says "Stop."<br>
  (Please refer to Chapter 2 for examples and the "Repeat + 1 Phrase List".)</p>
  <div class="dialogue reveal">
    <p><strong>A:</strong> Okay, class, for homework, please read the article and write a summary.</p>
    <p><strong>B:</strong> Excuse me, professor. I have a question.</p>
    <p><strong>A:</strong> Yes? What is it?</p>
    <p><strong>B:</strong> I'm sorry, but what does the word "ambiguous" mean?</p>
    <p><strong>A:</strong> That's a great question. It means unclear.</p>
    <p><strong>B:</strong> Oh, I understand now. And could you tell me how long the summary should be?</p>
    <p><strong>A:</strong> It should be about 200 words.</p>
    <p><strong>B:</strong> Thank you very much! I feel much better now.</p>
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
    <p><strong>A:</strong> Do you prefer to ask questions immediately when you don't understand something, or do you wait until later? Why?</p>
    <p><strong>B:</strong> _______. By the way, have you ever been to a famous museum? If yes, what did you see? If no, which museum would you like to visit?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <h4 class="sub-head reveal">2.1 Scanning Practice</h4>
  <p class="scan-note reveal">TOEICでは全般的に同義語を文中から素早く探すスキャニング能力が必須です。下記の単語・表現と同じ意味を持つキーワード（カッコの中の頭文字で始まるもの）を本文から素早く探しましょう。見つけられるまでの秒数を各自計り、記録しましょう。TOEICは言い換えと引っかけのテストです。言い換え（パラフレーズ）を常に意識しておきましょう。セットで暗記すると語彙力２倍になりますね。</p>
  <ul class="scan-list reveal">
    <li><span style="color:var(--mist)">without any limits</span><span class="arrow">→</span><span style="color:var(--gold)">(w- )</span></li>
    <li><span style="color:var(--mist)">a company's internal website for employees</span><span class="arrow">→</span><span style="color:var(--gold)">(i- )</span></li>
    <li><span style="color:var(--mist)">famous works of art</span><span class="arrow">→</span><span style="color:var(--gold)">(m- )</span></li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">Story: <em>Finding Her Voice</em></h4>
  <div class="story-wrap reveal">
    <p>Remembering Mr. Davies's advice, Momo was determined to be more proactive. In the next brainstorming session, the team was discussing ideas for a luxury tour. Mr. Davies said, "We need some real 'blue-sky thinking' for this client."</p>
    <p>Momo had never heard that expression before. Her heart pounded, but she took a deep breath and raised her hand. She was worried it might be rude to interrupt, but she had to ask. "Excuse me, Mr. Davies," she said in a small but clear voice. "Could you please explain what 'blue-sky thinking' means?"</p>
    <p>The room went quiet for a second. Momo's face felt hot. But then, Mr. Davies smiled warmly. "Great question, Momo. I'm glad you asked." He explained, "'Blue-sky thinking' means coming up with ideas without any restrictions. Imagine a clear blue sky—there are no borders." He continued, "In other words, let's not worry about budget or practical details for now. Let's just think of the most creative and wild ideas possible." Liam gave her a thumbs-up. After the meeting, he told her, "Asking questions to clarify a point shows you are engaged. It's a sign of a proactive attitude."</p>
    <p>Momo felt a wave of relief and a spark of confidence. She felt she was finally on the right track. Feeling empowered, Momo decided to do some research. She logged into the company intranet and looked up information about past company logos. She found an article about the company's 50th anniversary. It showed the original logo from 1970—it was identical to the symbol on her pendant. The article explained that the logo was based on the North Star that has guided travelers for centuries. It symbolized the company's mission to be a guiding light for travelers. But why had the Japanese branch manager given this to her? The mystery deepened.</p>
    <p>That weekend, Momo visited a museum as planned. She went to the National Gallery in Trafalgar Square. She was amazed by the grand building, the giant lion statues, and Nelson's Column. Inside, she saw famous masterpieces she had only seen in textbooks, like Van Gogh's "Sunflowers." She spent hours wandering the quiet halls. The best part? Admission to the main collection was completely free of charge. She learned that most of London's national museums are free, a legacy of a 19th-century belief that culture should be accessible to everyone. Standing in front of a beautiful painting by Turner, Momo felt a sense of peace. There was still so much to learn about her job and the mystery of the pendant, but for the first time since arriving, she was truly excited about her future in London.</p>
  </div>

  <h4 class="sub-head reveal">Word List</h4>
  <ul class="word-list reveal">
    <li><strong>determined</strong> <span class="pron">(形)</span><br>固く決心した</li>
    <li><strong>proactive</strong> <span class="pron">(形)</span><br>率先した、事前に行動を起こす</li>
    <li><strong>expression</strong> <span class="pron">(名)</span><br>表現、言い回し</li>
    <li><strong>clarify</strong> <span class="pron">(動)</span><br>明確にする、はっきりさせる</li>
    <li><strong>intranet</strong> <span class="pron">(名)</span><br>イントラネット（企業内ネットワーク）</li>
    <li><strong>identical</strong> <span class="pron">(形)</span><br>全く同じ、そっくりの</li>
    <li><strong>masterpiece</strong> <span class="pron">(名)</span><br>傑作、名作</li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">2.2 Reading Comprehension: Multiple Choice</h4>
  <ul class="mc-list reveal">
    <li>
      <p>What did Momo ask Mr. Davies to clarify during the meeting?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The meeting schedule</li>
        <li><span>(B)</span>The name of the client</li>
        <li><span>(C)</span>The budget for the new tour</li>
        <li><span>(D)</span>The meaning of the expression "blue-sky thinking"</li>
      </ul>
    </li>
    <li>
      <p>How did Mr. Davies react to Momo's question?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>He seemed annoyed.</li>
        <li><span>(B)</span>He smiled and explained it kindly.</li>
        <li><span>(C)</span>He ignored her question.</li>
        <li><span>(D)</span>He told her to ask Liam later.</li>
      </ul>
    </li>
    <li>
      <p>What did Momo discover on the company intranet?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The company was planning to change its logo.</li>
        <li><span>(B)</span>Her branch manager in Japan used to work in London.</li>
        <li><span>(C)</span>There was an error in the presentation slides.</li>
        <li><span>(D)</span>The company's original logo was identical to the symbol on her pendant.</li>
      </ul>
    </li>
    <li>
      <p>What is the company's original logo based on?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The Guiding Light</li>
        <li><span>(B)</span>The North Star</li>
        <li><span>(C)</span>A compass</li>
        <li><span>(D)</span>The Golden Star</li>
      </ul>
    </li>
    <li>
      <p>What surprised Momo about the National Gallery?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>It was very small.</li>
        <li><span>(B)</span>Photography was not allowed.</li>
        <li><span>(C)</span>The entrance fee was free of charge.</li>
        <li><span>(D)</span>It was closed on weekends.</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">2.3 Reading Comprehension: True / False</h4>
  <ul class="tf-list reveal">
    <li><span class="tf-badge">T / F</span>Liam was unhappy that Momo interrupted the meeting.</li>
    <li><span class="tf-badge">T / F</span>Momo had to pay an expensive admission fee to see Van Gogh's "Sunflowers."</li>
    <li><span class="tf-badge">T / F</span>The symbol on Momo's pendant is based on the North Star.</li>
    <li><span class="tf-badge">T / F</span>"Blue-sky thinking" means thinking about ideas in a very practical and realistic way.</li>
    <li><span class="tf-badge">T / F</span>Momo was afraid to ask a question because she thought it would be considered rude.</li>
  </ul>

  <h4 class="sub-head reveal">2.4 Let's Recall! Pair-work from the previous chapter</h4>
  <div class="recall-box reveal">
    <p>Do you remember what you talked about in the Chapter 3 Pair-work, about expressing your opinion? Tell your new partner what you discussed. Now, looking at Momo's action in today's story, do you think she did the right thing to ask a question? Why or why not?</p>
  </div>

  <h4 class="sub-head reveal" style="margin-top:1.8rem">2.5 Pair-work</h4>
  <div class="pairwork-box reveal">
    <p>Have you ever been a guide for a friend or family member visiting your town? What was it like? <strong>If you have never done it, imagine what would be difficult and discuss. （やったことが無い場合は想像して何が大変そうか話し合いましょう。）</strong> Discuss with your partner.</p>
  </div>

  <h4 class="sub-head reveal" style="margin-top:1.8rem">2.6 Paraphrase Practice ✍️</h4>
  <p class="inst-note reveal">各問題の最初の文（Original）とほぼ同じ意味になるように、下の文の空欄（ ____ ）に最も適切な単語を<strong style="color:var(--gold)"> [ヒント] </strong>の中から選んで埋めましょう。（各単語は一度しか使えません）</p>
  <div class="hint-box reveal"><strong>[ヒント]</strong> &nbsp; happy &nbsp;/&nbsp; shows &nbsp;/&nbsp; explain &nbsp;/&nbsp; glad &nbsp;/&nbsp; same &nbsp;/&nbsp; exactly</div>
  <div class="para-box reveal">
    <p class="orig">Original: I'm glad you asked.</p>
    <p class="blank-line">= I'm ______ that you asked the question.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: …it was identical to the symbol on her pendant.</p>
    <p class="blank-line">= …it was ______ the ______ as the symbol on her pendant.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: Asking questions… shows you are engaged.</p>
    <p class="blank-line">= Asking questions… ______ that you are participating actively.</p>
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
      <p>How did Momo feel at the National Gallery?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Bored and tired</li>
        <li><span>(B)</span>Angry about the crowds</li>
        <li><span>(C)</span>Disappointed by the art</li>
        <li><span>(D)</span>A little overwhelmed but impressed</li>
      </ul>
    </li>
    <li>
      <p>According to Liam, why are many national museums in London free?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>They are funded by the government through taxes.</li>
        <li><span>(B)</span>They are sponsored by private companies.</li>
        <li><span>(C)</span>They receive donations from tourists.</li>
        <li><span>(D)</span>They are not very popular.</li>
      </ul>
    </li>
    <li>
      <p>Which museum does Liam recommend for modern art?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The British Museum</li>
        <li><span>(B)</span>The National Gallery</li>
        <li><span>(C)</span>The Tate Modern</li>
        <li><span>(D)</span>The Victoria and Albert Museum</li>
      </ul>
    </li>
    <li>
      <p>What is special about the Tate Modern's building?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>It is the oldest building in London.</li>
        <li><span>(B)</span>It is underground.</li>
        <li><span>(C)</span>It is made entirely of glass.</li>
        <li><span>(D)</span>It is located in an old power station.</li>
      </ul>
    </li>
    <li>
      <p>What advice does Liam give Momo for visiting the British Museum?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>To hire a private guide</li>
        <li><span>(B)</span>To go on a weekday</li>
        <li><span>(C)</span>To buy tickets online</li>
        <li><span>(D)</span>Not to try to see everything in one day</li>
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

  <p class="reveal" style="color:var(--mist);margin-bottom:.9rem">会議で勇気を出して質問したMomoの立場で、励ましてくれた同僚のリアムにお礼のEメールを書きましょう（約40–60語）。彼のアドバイスのおかげで自信がついたことを伝えましょう。</p>
  <div class="email-frame reveal">
    <p class="field"><strong>To:</strong> Liam</p>
    <p class="field"><strong>Subject:</strong> Thank you for your advice</p>
    <hr style="border:none;border-top:1px solid rgba(255,255,255,.07);margin:.8rem 0">
    <p style="color:var(--mist)"><em>Hi Liam,</em></p>
    <p class="body-note">[ ここにEメール本文を記入 ]</p>
    <p class="sig"><em>Thanks again, Momo</em></p>
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
    <p class="topic">"In a business meeting, is it better to ask a 'silly' question to understand something, or to stay quiet and look it up later?"</p>
    <p class="kw"><strong>Keywords:</strong> Asking questions &nbsp;·&nbsp; Efficiency &nbsp;·&nbsp; Teamwork</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 6 TOEIC                            -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">6</span>
    <span class="sec-title">TOEIC</span>
    <span class="sec-emoji">📝</span>
  </div>

  <h4 class="sub-head reveal">6.1 Story Scenes in the TOEIC：会議での質問・確認（Clarification）</h4>
  <div class="toeic-intro reveal">
    Momoが会議中に不明な点を質問した場面は、TOEICで試される重要なコミュニケーションスキルの一つです。Part 2（応答問題）では、「Could you explain that again?」のような依頼に対する適切な応答が問われます。また、Part 3（会話問題）では、会話の途中で一方が「What does that mean?」と質問し、もう一方が説明を加えるやり取りが頻出します。誰かが何かを「clarify（明確にする）」している場面に遭遇したら、その説明部分に重要な情報が含まれている可能性が高いので、集中して聞くようにしましょう。
  </div>

  <h4 class="sub-head reveal">6.2 TOEIC Practice: Part 7</h4>
  <p class="toeic-dir reveal">Directions: In this part you will read a selection of texts, such as magazine and newspaper articles, e-mails, and instant messages. Each text or set of texts is followed by several questions. Select the best answer for each question and mark the letter (A), (B), (C), or (D) on your answer sheet.</p>

  <!-- Part 7 メール本文 -->
  <div class="toeic-memo reveal">
    <div class="memo-header">
      <div class="memo-row"><span class="label">To:</span><span class="val">All Marketing Staff</span></div>
      <div class="memo-row"><span class="label">From:</span><span class="val">Sarah Chen, Marketing Director</span></div>
      <div class="memo-row"><span class="label">Subject:</span><span class="val">Brainstorming Meeting Follow-up</span></div>
      <div class="memo-row"><span class="label">Date:</span><span class="val">September 22</span></div>
    </div>
    <div class="memo-body">
      <p>Thank you all for your active participation in today's brainstorming meeting. We generated some excellent initial ideas for the "Visit Scotland" campaign.</p>
      <p>I want to clarify the next steps. As discussed, we have divided into two teams. Team A, led by Kenji, will research the feasibility of a social media campaign. Team B, led by Maria, will develop a concept for a series of print advertisements.</p>
      <p>Please submit a brief outline of your initial findings to me by this Friday. We will use these outlines as a basis for a more detailed discussion at our next meeting on October 1. Let's maintain this proactive momentum.</p>
    </div>
  </div>

  <ul class="mc-list reveal">
    <li>
      <p>What is the purpose of this memo?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>To request budget proposals</li>
        <li><span>(B)</span>To cancel a meeting</li>
        <li><span>(C)</span>To summarize the results of a meeting</li>
        <li><span>(D)</span>To announce a new campaign</li>
      </ul>
    </li>
    <li>
      <p>What is Kenji's team responsible for?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Creating print advertisements</li>
        <li><span>(B)</span>Investigating a social media campaign</li>
        <li><span>(C)</span>Planning the next meeting</li>
        <li><span>(D)</span>Leading the "Visit Scotland" campaign</li>
      </ul>
    </li>
    <li>
      <p>What are the staff members asked to do by Friday?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Complete the campaign</li>
        <li><span>(B)</span>Form two new teams</li>
        <li><span>(C)</span>Contact Maria</li>
        <li><span>(D)</span>Submit a short summary</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">Business Scene Background</h4>
  <div class="toeic-intro reveal">
    欧米のビジネス文化、特に会議の場では、「質問をしないこと」は「無関心」や「理解していない」と見なされる可能性があります。Momoが勇気を出して質問した行動は、実際には非常に高く評価されます。不明な専門用語や社内用語（jargon）をそのままにしておくと、後で大きな誤解やミスに繋がるからです。積極的に質問し、理解を明確にすることは、仕事に対する真剣な姿勢（a proactive attitude）を示すことになります。上司や同僚も、質問されることを「自分の説明が不十分だったかもしれない」と捉え、快く説明してくれるのが一般的です。この「わからないことはその場で解決する」という文化は、効率と正確性を重視する欧米のビジネススタイルを象徴していると言えるでしょう。
  </div>

  <h4 class="sub-head reveal">TIPS</h4>
  <div class="tips-box reveal">
    <p class="tip-label">🏛️ 無料の博物館・美術館</p>
    <p>ロンドンには、大英博物館、ナショナル・ギャラリー、テート・モダン、自然史博物館、科学博物館など、世界トップクラスの国立の博物館・美術館が数多くあり、そのほとんどが常設展への入場が無料です。これは、文化や知識は万人に開かれているべきだという国の考え方に基づいています。ただし、特別展は有料の場合が多く、また、運営を支えるための寄付（donation）はいつでも歓迎されています。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">🦁 トラファルガー広場</p>
    <p>本文でMomoが訪れたナショナル・ギャラリーがあるトラファルガー広場は、ロンドンの中心的な広場の一つです。名前は、1805年にネルソン提督がナポレオン率いるフランス・スペイン連合艦隊を破った「トラファルガーの海戦」の勝利を記念して名付けられました。広場の中央に立つ高い柱がネルソン記念柱（Nelson's Column）です。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">🎨 J.M.W. ターナー</p>
    <p>本文でMomoが見たターナー（J.M.W. Turner）は、19世紀のイギリスを代表するロマン主義の風景画家です。光や大気を大胆に描いたその作風は、後の印象派の画家たちにも大きな影響を与えました。「光の画家」とも呼ばれています。</p>
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
    <p class="example">"What are five different ways to ask for clarification in a professional email?"</p>
  </div>

</div><!-- /.page-body -->

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
(function () {
  const els = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) { els.forEach(el => el.classList.add('visible')); return; }
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
  els.forEach(el => obs.observe(el));
})();
document.getElementById('curtain').addEventListener('animationend', function () { this.style.display = 'none'; });
</script>
</body>
</html>
