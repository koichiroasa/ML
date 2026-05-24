<?php
require_once __DIR__ . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chapter 01 — A Leap Across the Globe | Momo's London</title>
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
    text-align: center;
    padding: 3.5rem 2rem 2.5rem;
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
  .ch-hero h1 .word {
    display: inline-block;
    animation: wordRise 0.6s cubic-bezier(0.22,1,0.36,1) both;
  }
  .ch-hero h1 .word em { font-style: italic; color: var(--gold); }
  @keyframes wordRise {
    from { opacity: 0; transform: translateY(60px) rotate(2deg); }
    to   { opacity: 1; transform: translateY(0)    rotate(0deg); }
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
  .reveal {
    opacity: 0; transform: translateY(26px);
    transition: opacity 0.65s ease, transform 0.65s cubic-bezier(0.22,1,0.36,1);
  }
  .reveal.visible { opacity: 1; transform: translateY(0); }

  /* ── LAYOUT ── */
  .page-body { max-width: 860px; margin: 0 auto; padding: 2.5rem 2rem 6rem; }

  /* ── SECTION HEADERS ── */
  .sec-head {
    display: flex; align-items: center; gap: 1rem;
    margin: 3rem 0 1.4rem; padding-bottom: .5rem;
    border-bottom: 1px solid rgba(184,150,12,.2);
  }
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

  /* ── DIALOGUE BLOCK ── */
  .dialogue { background: var(--bg2); border-left: 3px solid var(--gold); border-radius: 0 2px 2px 0; padding: 1.2rem 1.5rem; margin-bottom: 1.2rem; }
  .dialogue p { margin-bottom: .3rem; }
  .dialogue p:last-child { margin-bottom: 0; }
  .dialogue strong { color: var(--gold); }
  .fill-note  { font-style: italic; color: var(--fog); font-size: .9rem; margin-bottom: .8rem; }
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
    .phrase-list, .word-list { grid-template-columns: 1fr; }
    .story-wrap { padding: 1.2rem 1.1rem; }
  }
</style>
</head>
<body>

<!-- ── CURTAIN ── -->
<div id="curtain" aria-hidden="true">
  <div class="c-num">01</div>
  <div class="c-line"></div>
  <div class="c-label">Chapter One</div>
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
  <span class="ch-num-label">Chapter 01</span>
  <h1>
    <?php
    $words = ['A', 'Leap', 'Across', 'the', 'Globe'];
    foreach ($words as $i => $w) {
        $delay  = 1.5 + $i * 0.1;
        $italic = in_array($w, ['Leap','Across','the','Globe']);
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
    <p><strong>A:</strong> What is the name of the express train that connects Heathrow Airport to central London?</p>
    <p><strong>B:</strong> _______. By the way, do you know how many terminals Heathrow Airport has?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <!-- ── Important Phrases ── -->
  <h3 class="sub-head reveal">Important Phrases</h3>
  <ul class="phrase-list reveal">
    <li><strong>echoed in her mind</strong>：心の中でこだました</li>
    <li><strong>a mix of excitement and anxiety</strong>：興奮と不安が入り混じった気持ち</li>
    <li><strong>a big step for her career</strong>：彼女のキャリアにとって大きな一歩</li>
    <li><strong>go through immigration</strong>：入国審査を通過する</li>
    <li><strong>the purpose of your visit</strong>：あなたの訪問目的</li>
    <li><strong>claim her baggage</strong>：彼女の荷物を受け取る</li>
    <li><strong>formally entered the country</strong>：正式に入国した</li>
    <li><strong>a wave of loneliness washed over her</strong>：孤独の波が押し寄せてきた</li>
    <li><strong>jet lag was kicking in</strong>：時差ボケが始まっていた</li>
    <li><strong>drifted off to a deep sleep</strong>：深い眠りに落ちた</li>
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
    <p><strong>A:</strong> Good afternoon. May I see your passport, please?</p>
    <p><strong>B:</strong> Here you are.</p>
    <p><strong>A:</strong> What is the purpose of your visit?</p>
    <p><strong>B:</strong> I'm here for business. I'll be working at my company's London branch.</p>
    <p><strong>A:</strong> How long will you be staying?</p>
    <p><strong>B:</strong> For three years.</p>
    <p><strong>A:</strong> Alright. Everything seems to be in order. Enjoy your stay in the UK.</p>
    <p><strong>B:</strong> Thank you.</p>
  </div>

  <h4 class="sub-head reveal">1.2 Let's complete the conversation!</h4>
  <p class="fill-note reveal">Fill in the blanks with your own ideas and practice the conversation with a partner. You may also choose from the words in parentheses. Once done, switch roles A and B. After that, make up a continuation and keep the conversation going.</p>
  <div class="dialogue reveal">
    <p><strong>A:</strong> Welcome to the UK. May I have your passport, please?</p>
    <p><strong>B:</strong> Sure, here you go.</p>
    <p><strong>A:</strong> What's the purpose of your visit? Business or ＿＿＿?<br>
      <span class="fill-choice">(pleasure / sightseeing)</span></p>
    <p><strong>B:</strong> It's for pleasure. I'm here on holiday.</p>
    <p><strong>A:</strong> How long are you going to stay in the UK?</p>
    <p><strong>B:</strong> For ＿＿＿.<br>
      <span class="fill-choice">(about two weeks / ten days)</span></p>
    <p><strong>A:</strong> I see. Where will you be staying?</p>
    <p><strong>B:</strong> I've booked a hotel in London.</p>
  </div>

  <h4 class="sub-head reveal">1.3 Let's continue the conversation!</h4>
  <p class="inst-note reveal">Let's continue the conversation from the completion exercise. React in English to what your partner said. Enjoy extending the conversation. Then make a follow-up response to their reaction. Continue until the teacher says "STOP."</p>
  <div class="dialogue reveal">
    <p style="color:var(--fog);font-size:.88rem;margin-bottom:.6rem;font-style:italic">Example:</p>
    <p><strong>A:</strong> Welcome to the UK. May I have your passport, please?</p>
    <p><strong>B:</strong> Sure, ……</p>
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
    <p><strong>A:</strong> When you travel abroad, what's the first thing you want to do after arriving at your hotel?</p>
    <p><strong>B:</strong> _______. By the way, what kind of things do you worry about when you travel to a new country for the first time?</p>
    <p><strong>A:</strong> _______</p>
  </div>

  <h4 class="sub-head reveal">2.1 Scanning Practice</h4>
  <p class="scan-note reveal">TOEICでは全般的に同義語を文中から素早く探すスキャニング能力が必須です。下記の単語・表現と同じ意味を持つキーワード（カッコの中の頭文字で始まるもの）を本文から素早く探しましょう。見つけられるまでの秒数を各自計り、記録しましょう。TOEICは言い換えと引っかけのテストです。言い換え（パラフレーズ）を常に意識しておきましょう。セットで暗記すると語彙力２倍になりますね。</p>
  <ul class="scan-list reveal">
    <li><span style="color:var(--mist)">very large; huge</span><span class="arrow">→</span><span style="color:var(--gold)">(v- )</span></li>
    <li><span style="color:var(--mist)">reason; objective</span><span class="arrow">→</span><span style="color:var(--gold)">(p- )</span></li>
    <li><span style="color:var(--mist)">very tired; worn out</span><span class="arrow">→</span><span style="color:var(--gold)">(e- )</span></li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">Story: <em>A Leap Across the Globe</em></h4>
  <div class="story-wrap reveal">
    <p>Momo stared out the small airplane window at the sea of clouds below. The 14-hour flight from Haneda was coming to an end. "You're young and adaptable. Think of it as a chance for self-improvement," her branch manager's words echoed in her mind. She clutched the small pendant he had given her. It was old, with a strange symbol carved into it. And, the back of it has the words "Path of Light Across Roads Illuminating Souls" on it. "This will protect you," he had said with a wink. "If you're ever in trouble in London, look for this symbol."</p>
    <p>Momo had complicated feelings about this sudden assignment. She felt a mix of excitement and anxiety about exploring a new country, but she was also nervous about her English skills. She was assigned to the London branch of the Golden Star Company, which was a big step for her career, but also a scary one.</p>
    <p>"We will be landing at London Heathrow Airport shortly," the flight attendant announced. Momo's heart beat faster. This was it.</p>
    <p>After landing, she followed the signs for "Arrivals." The airport was vast and crowded with people from all over the world. Her first challenge was to go through immigration. The officer had a serious look. He asked her for the purpose of your visit, and she explained her three-year assignment, her voice trembling a little. Thankfully, he stamped her passport without much trouble.</p>
    <p>Next, she had to claim her baggage. She watched the carousel go round and round, growing anxious until she finally spotted her big, red suitcase. After passing through customs with nothing to declare, she had formally entered the country.</p>
    <p>She decided to take the Heathrow Express, the fastest way to central London. The train was clean and comfortable, and it arrived at Paddington Station in just 15 minutes. The moment she stepped out of the station, the cool London air hit her. The historic buildings and iconic double-decker buses were just as she had imagined. She managed to find a black cab to take her to her hotel. The driver was friendly and chatted about the weather.</p>
    <p>By the time she checked into her room, she was exhausted. The terrible jet lag was kicking in. As she looked out her hotel window at the London skyline, a wave of loneliness washed over her. "I hope I can get used to life here," she whispered to herself, and drifted off to a deep sleep, dreaming of an incomprehensible symbol.</p>
  </div>

  <h4 class="sub-head reveal">Word List</h4>
  <ul class="word-list reveal">
    <li><strong>echo</strong> <span class="pron">(動)</span><br>こだまする</li>
    <li><strong>adaptable</strong> <span class="pron">(形)</span><br>適応性のある</li>
    <li><strong>assignment</strong> <span class="pron">(名)</span><br>任務、宿題</li>
    <li><strong>immigration</strong> <span class="pron">(名)</span><br>入国審査</li>
    <li><strong>thankfully</strong> <span class="pron">(副)</span><br>ありがたいことに</li>
    <li><strong>vast</strong> <span class="pron">(形)</span><br>広大な</li>
    <li><strong>exhausted</strong> <span class="pron">(形)</span><br>疲れ果てた</li>
    <li><strong>skyline</strong> <span class="pron">(名)</span><br>スカイライン、空を背景にした輪郭</li>
  </ul>

  <h4 class="sub-head reveal" style="margin-top:2rem">2.2 Reading Comprehension: Multiple Choice</h4>
  <ul class="mc-list reveal">
    <li>
      <p>Why was Momo going to London?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>For a short holiday</li>
        <li><span>(B)</span>To study English</li>
        <li><span>(C)</span>For a three-year work assignment</li>
        <li><span>(D)</span>To visit her family</li>
      </ul>
    </li>
    <li>
      <p>What did Momo's branch manager give her?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>A new suitcase</li>
        <li><span>(B)</span>A travel guidebook</li>
        <li><span>(C)</span>A letter of recommendation</li>
        <li><span>(D)</span>An old pendant with a symbol</li>
      </ul>
    </li>
    <li>
      <p>How did Momo feel about her move to London?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Only excited</li>
        <li><span>(B)</span>A mix of excitement and anxiety</li>
        <li><span>(C)</span>Completely calm</li>
        <li><span>(D)</span>Angry and upset</li>
      </ul>
    </li>
    <li>
      <p>What was Momo's first challenge at the airport?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Finding her suitcase</li>
        <li><span>(B)</span>Going through immigration</li>
        <li><span>(C)</span>Taking the Heathrow Express</li>
        <li><span>(D)</span>Finding a taxi</li>
      </ul>
    </li>
    <li>
      <p>How did Momo feel in her hotel room at the end of the day?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Energetic and happy</li>
        <li><span>(B)</span>Hungry and thirsty</li>
        <li><span>(C)</span>Exhausted and lonely</li>
        <li><span>(D)</span>Angry and frustrated</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">2.3 Reading Comprehension: True / False</h4>
  <ul class="tf-list reveal">
    <li><span class="tf-badge">T / F</span>Momo's flight from Haneda to London was very short.</li>
    <li><span class="tf-badge">T / F</span>The immigration officer was very friendly and smiled a lot.</li>
    <li><span class="tf-badge">T / F</span>Momo took a bus from the airport to her hotel.</li>
    <li><span class="tf-badge">T / F</span>The Heathrow Express took 15 minutes to get to Paddington Station.</li>
    <li><span class="tf-badge">T / F</span>The pendant Momo received had a strange symbol and a message on the back.</li>
  </ul>

  <h4 class="sub-head reveal">2.4 Pair-work</h4>
  <div class="recall-box reveal">
    <p>Momo took the Heathrow Express to Paddington Station. What are some famous train stations in London? What do you know about them? Let's talk about it with your partner.</p>
  </div>

  <h4 class="sub-head reveal" style="margin-top:1.8rem">2.5 Paraphrase Practice ✍️</h4>
  <p class="inst-note reveal">各問題の最初の文（Original）とほぼ同じ意味になるように、下の文の空欄（ ____ ）に最も適切な単語を<strong style="color:var(--gold)"> [ヒント] </strong>の中から選んで埋めましょう。（各単語は一度しか使えません）</p>
  <div class="hint-box reveal"><strong>[ヒント]</strong> &nbsp; lonely &nbsp;/&nbsp; nervous &nbsp;/&nbsp; big &nbsp;/&nbsp; felt &nbsp;/&nbsp; full &nbsp;/&nbsp; excited</div>
  <div class="para-box reveal">
    <p class="orig">Original: She felt a mix of excitement and anxiety.</p>
    <p class="blank-line">= She was feeling both ______ and ______.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: The airport was vast and crowded with people.</p>
    <p class="blank-line">= The airport was very ______ and ______ of many travelers.</p>
  </div>
  <div class="para-box reveal">
    <p class="orig">Original: A wave of loneliness washed over her.</p>
    <p class="blank-line">= She suddenly ______ very ______.</p>
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
      <p>Why did Liam call Momo?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>To ask her to come to the office.</li>
        <li><span>(B)</span>To check if she arrived safely.</li>
        <li><span>(C)</span>To invite her to dinner.</li>
        <li><span>(D)</span>To ask about Japanese food.</li>
      </ul>
    </li>
    <li>
      <p>How is Momo feeling?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Excited</li>
        <li><span>(B)</span>Angry</li>
        <li><span>(C)</span>Exhausted</li>
        <li><span>(D)</span>Hungry</li>
      </ul>
    </li>
    <li>
      <p>What is the Japanese word for food delivery that Momo mentions?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>Ramen</li>
        <li><span>(B)</span>Demae</li>
        <li><span>(C)</span>Sushi</li>
        <li><span>(D)</span>Takeaway</li>
      </ul>
    </li>
    <li>
      <p>What is the main difference between "takeaway" and "demae" as explained in the conversation?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The type of food.</li>
        <li><span>(B)</span>The price of the food.</li>
        <li><span>(C)</span>"Takeaway" means you pick up the food yourself.</li>
        <li><span>(D)</span>"Demae" is only for pizza.</li>
      </ul>
    </li>
    <li>
      <p>What did Liam joke about at the end of the call?</p>
      <ul class="mc-choices">
        <li><span>(A)</span>The weather in London.</li>
        <li><span>(B)</span>How tired Momo was.</li>
        <li><span>(C)</span>His own English skills.</li>
        <li><span>(D)</span>Getting ramen delivered in London.</li>
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

  <p class="reveal" style="color:var(--mist);margin-bottom:.9rem">ロンドンのホテルに無事到着したMomoになったつもりで、日本の支店長に到着報告のEメールを書きましょう（約40–60語）。フライトの様子やロンドンに着いた感想を簡潔に含めてみましょう。</p>
  <div class="email-frame reveal">
    <p class="field"><strong>To:</strong> Branch Manager Sasaki</p>
    <p class="field"><strong>Subject:</strong> Safe Arrival in London</p>
    <hr style="border:none;border-top:1px solid rgba(255,255,255,.07);margin:.8rem 0">
    <p style="color:var(--mist)"><em>Dear Branch Manager Sasaki,</em></p>
    <p class="body-note">[ ここにEメール本文を記入 ]</p>
    <p class="sig"><em>Sincerely, Momo Sasaki</em></p>
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
    <p class="topic">"When starting a new job or living in a new place, is it better to hide your anxiety to make a strong first impression, or is it better to be open about your feelings?"</p>
    <p class="kw"><strong>Keywords:</strong> Anxiety &nbsp;·&nbsp; First Impression &nbsp;·&nbsp; Honesty</p>
  </div>


  <!-- ══════════════════════════════════ -->
  <!-- 6 TOEIC                            -->
  <!-- ══════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">6</span>
    <span class="sec-title">TOEIC</span>
    <span class="sec-emoji">📝</span>
  </div>

  <h4 class="sub-head reveal">6.1 Story Scenes in the TOEIC：空港での入国審査・到着</h4>
  <div class="toeic-intro reveal">
    Momoがロンドン到着直後に緊張しながら挑んだ「入国審査（Immigration）」や、空港での手続きのシーンは、TOEICのリスニング（Part 1, 2, 3）やリーディング（Part 7のEメールや案内文）で頻繁に登場します。特に、訪問の「目的 (purpose)」や滞在「期間 (duration)」を尋ねる表現は重要です。これらのキーワードを聞き取る練習をすることで、設問の正解率を上げることができます。また、空港内のアナウンスや標識に関する語彙（Arrivals, Baggage Claim, Customsなど）も覚えておくと、様々な問題に対応できるようになります。
  </div>

  <h4 class="sub-head reveal">6.2 TOEIC Practice: Part 2</h4>
  <p class="toeic-dir reveal">Directions: You will hear a question or statement and three responses spoken in English. They will not be printed in your test book and will be spoken only one time. Select the best response to the question or statement and mark the letter (A), (B), or (C) on your answer sheet.</p>
  <ul class="mc-list reveal">
    <li>
      <p>Question 1</p>
      <ul class="mc-choices">
        <li><span>(A)</span>&nbsp;</li>
        <li><span>(B)</span>&nbsp;</li>
        <li><span>(C)</span>&nbsp;</li>
      </ul>
    </li>
    <li>
      <p>Question 2</p>
      <ul class="mc-choices">
        <li><span>(A)</span>&nbsp;</li>
        <li><span>(B)</span>&nbsp;</li>
        <li><span>(C)</span>&nbsp;</li>
      </ul>
    </li>
    <li>
      <p>Question 3</p>
      <ul class="mc-choices">
        <li><span>(A)</span>&nbsp;</li>
        <li><span>(B)</span>&nbsp;</li>
        <li><span>(C)</span>&nbsp;</li>
      </ul>
    </li>
  </ul>

  <h4 class="sub-head reveal">Business Scene Background</h4>
  <div class="toeic-intro reveal">
    空港の入国審査は、海外出張の最初の関門です。TOEICでは、ビジネスパーソンが海外の支社へ向かう、あるいは国際会議に出席するといった設定で、空港でのやり取りが描かれることがよくあります。入国審査官との会話は、リスニングPart 3の典型的なシチュエーションです。審査官は訪問目的（Purpose of visit）、滞在期間（Length of stay）、滞在場所（Accommodation）などを質問します。これらの質問に簡潔かつ正確に答える能力が求められます。ビジネス渡航の場合、会社の招待状や就労ビザなど、必要な書類を提示する場面も考えられます。スムーズな入国は、その後のビジネスを円滑に進めるための第一歩であり、基本的な英会話能力が試される場面と言えるでしょう。
  </div>

  <h4 class="sub-head reveal">TIPS</h4>
  <div class="tips-box reveal">
    <p class="tip-label">🛂 イギリスの入国審査</p>
    <p>イギリスの入国審査は、特に学生や長期滞在者に対して厳しいことで知られています。滞在目的、滞在先、帰りの航空券の有無などを明確に答えられるように準備しておくと安心です。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">🚄 ヒースロー・エクスプレス</p>
    <p>パディントン駅までわずか15分と非常に速いですが、運賃はかなり高額です（片道£25程度）。時間に余裕があれば、同じくヒースロー空港から出ている地下鉄ピカデリー線を利用すると、市中心部まで約1時間かかりますが、運賃は£6以下で済みます。</p>
  </div>
  <div class="tips-box reveal">
    <p class="tip-label">🚕 ブラックキャブ</p>
    <p>ロンドンの有名な「ブラックキャブ（黒タクシー）」の運転手になるには、「ザ・ナレッジ」と呼ばれる、ロンドン市内の全ストリートと主要な建物を記憶する非常に難しい試験に合格しなければなりません。そのため、彼らはナビなしでどんな場所へも行くことができます。</p>
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
    <p class="example">"Please give me five common questions I might be asked by an immigration officer when entering the UK for work."</p>
  </div>

</div><!-- /.page-body -->

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
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
document.getElementById('curtain').addEventListener('animationend', function () {
  this.style.display = 'none';
});
</script>
</body>
</html>
