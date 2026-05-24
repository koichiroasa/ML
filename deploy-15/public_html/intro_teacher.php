<?php
require_once __DIR__ . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>先生方へ：本書の教育的デザインと教授法 | Momo's London</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
  :root {
    --navy:   #0D1B2A;
    --cream:  #F4EFE4;
    --gold:   #B8960C;
    --mist:   #D6CFC2;
    --fog:    #8C8070;
    --gl:     #4CAF85;
    --teal:   #1F7A8C;
    --red:    #C8102E;
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
    line-height: 1.9;
    overflow-x: hidden;
  }

  /* ── CURTAIN ── */
  #curtain {
    position: fixed; inset: 0; z-index: 9000;
    background: var(--navy);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    pointer-events: none;
    animation: curtainLift 0.9s 1.0s cubic-bezier(0.76,0,0.24,1) both;
  }
  @keyframes curtainLift { from { transform: translateY(0); } to { transform: translateY(-102%); } }
  #curtain .c-icon { font-size: clamp(5rem,18vw,11rem); line-height:1; animation: cReveal 0.7s 0.15s cubic-bezier(0.34,1.4,0.64,1) both; }
  @keyframes cReveal { from { opacity:0; transform:scale(1.5); } to { opacity:1; transform:scale(1); } }
  #curtain .c-line { width:0; height:1px; background:linear-gradient(to right,transparent,var(--gold),transparent); margin-top:1.2rem; animation: lineExp 0.6s 0.55s ease both; }
  @keyframes lineExp { from { width:0; opacity:0; } to { width:min(320px,60vw); opacity:1; } }
  #curtain .c-label { font-size:.72rem; letter-spacing:.4em; text-transform:uppercase; color:var(--gold); margin-top:.9rem; animation: fsu 0.5s 0.7s ease both; }
  @keyframes fsu { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

  /* ── HEADER ── */
  header {
    border-bottom: 1px solid var(--border); padding: 1.1rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 200;
    background: rgba(13,27,42,.9); backdrop-filter: blur(12px);
    animation: hDrop 0.5s 1.7s ease both;
  }
  @keyframes hDrop { from { opacity:0; transform:translateY(-100%); } to { opacity:1; transform:translateY(0); } }
  .site-title { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; color: var(--cream); text-decoration: none; }
  .site-title em { font-style: italic; color: var(--gold); }
  .hdr-r { display: flex; align-items: center; gap: 1rem; }
  .btn-sm { font-size: .72rem; letter-spacing: .15em; text-transform: uppercase; color: var(--fog); text-decoration: none; border: 1px solid rgba(140,128,112,.4); padding: .28rem .75rem; border-radius: 1px; transition: color .2s, border-color .2s; }
  .btn-sm:hover { color: var(--cream); border-color: var(--mist); }

  /* ── HERO ── */
  .page-hero { text-align: center; padding: 3.5rem 2rem 2.8rem; border-bottom: 1px solid rgba(184,150,12,.08); overflow: hidden; }
  .plabel { display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase; background: rgba(184,150,12,.12); border: 1px solid rgba(184,150,12,.28); color: var(--gold); padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem; animation: popIn 0.55s 1.2s cubic-bezier(0.34,1.56,0.64,1) both; }
  @keyframes popIn { from { opacity:0; transform:scale(0.6) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
  .page-hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(1.4rem,3.2vw,2.2rem); font-weight: 700; line-height: 1.2; }
  .page-hero h1 .word { display: inline-block; animation: wRise 0.55s cubic-bezier(0.22,1,0.36,1) both; }
  @keyframes wRise { from { opacity:0; transform:translateY(50px) rotate(1.5deg); } to { opacity:1; transform:translateY(0) rotate(0); } }
  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1.1rem auto .5rem; animation: fsu 0.5s 2.0s ease both; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, var(--gold)); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, var(--gold)); }
  .ch-rule .d { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
  .hero-sub { color: var(--fog); font-size: .9rem; letter-spacing: .05em; animation: fsu 0.5s 2.1s ease both; }

  /* ── REVEAL ── */
  .reveal { opacity:0; transform:translateY(24px); transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.22,1,0.36,1); }
  .reveal.visible { opacity:1; transform:translateY(0); }

  /* ── LAYOUT ── */
  .page-body { max-width: 820px; margin: 0 auto; padding: 2.5rem 2rem 6rem; }

  /* ── SECTION HEADER ── */
  .sec-head { display: flex; align-items: center; gap: 1rem; margin: 3rem 0 1.3rem; padding-bottom: .6rem; border-bottom: 1px solid rgba(184,150,12,.2); }
  .sec-head .icon { font-size: 1.4rem; }
  .sec-head h2 { font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--cream); }
  .sec-num { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 700; color: rgba(184,150,12,.45); line-height: 1; margin-right: .2rem; }

  /* ── BODY TEXT ── */
  .body-text { color: var(--mist); margin-bottom: 1.2rem; }

  /* ── HIGHLIGHT QUOTE ── */
  .highlight-quote { background: rgba(184,150,12,.06); border-left: 3px solid var(--gold); border-radius: 0 2px 2px 0; padding: 1rem 1.4rem; margin: 1.1rem 0 1.3rem; color: var(--mist); }
  .highlight-quote strong { color: var(--gold); }

  /* ── FEATURE CARDS (本書の特長) ── */
  .feature-list { display: flex; flex-direction: column; gap: .8rem; margin-top: .5rem; }
  .feature-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 3px; overflow: hidden; transition: border-color .22s, transform .15s; }
  .feature-card:hover { border-color: rgba(184,150,12,.38); transform: translateX(3px); }
  .feature-card .fc-head { display: flex; align-items: center; gap: .8rem; padding: .75rem 1.3rem; background: rgba(184,150,12,.05); border-bottom: 1px solid var(--border); }
  .feature-card .fc-head .ficon { font-size: 1.1rem; flex-shrink: 0; }
  .feature-card .fc-head h3 { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: var(--cream); }
  .feature-card .fc-body { padding: .85rem 1.3rem; color: var(--mist); font-size: .97rem; }

  /* ── EFFECT LIST ── */
  .effect-list { list-style: none; display: flex; flex-direction: column; gap: .55rem; margin-top: .4rem; }
  .effect-list li { display: flex; align-items: baseline; gap: .8rem; background: var(--bg2); border: 1px solid var(--border); border-radius: 2px; padding: .7rem 1.1rem; }
  .effect-list li .emark { color: var(--gold); flex-shrink: 0; font-size: 1rem; }
  .effect-list li .econtent { color: var(--mist); font-size: .97rem; }
  .effect-list li .econtent strong { color: var(--cream); }

  /* ── CONCEPT BOX ── */
  .concept-box { background: rgba(31,122,140,.07); border: 1px solid rgba(31,122,140,.28); border-radius: 3px; padding: 1.3rem 1.6rem; margin-top: .5rem; }
  .concept-box p { color: var(--mist); margin-bottom: .9rem; }
  .concept-box p:last-child { margin-bottom: 0; }
  .concept-box strong { color: #5bc4d8; }

  /* ── FACILITATION CARDS ── */
  .facil-list { display: flex; flex-direction: column; gap: 1rem; margin-top: .5rem; }
  .facil-card { background: var(--bg2); border: 1px solid var(--border); border-radius: 3px; overflow: hidden; }
  .facil-card .fac-head { display: flex; align-items: center; gap: .8rem; padding: .7rem 1.3rem; background: rgba(184,150,12,.05); border-bottom: 1px solid var(--border); }
  .facil-card .fac-head .ftag { font-size: .65rem; letter-spacing: .18em; text-transform: uppercase; background: rgba(184,150,12,.15); color: var(--gold); border: 1px solid rgba(184,150,12,.3); padding: .15rem .5rem; border-radius: 999px; white-space: nowrap; }
  .facil-card .fac-head h3 { font-family: 'Playfair Display', serif; font-size: 1rem; font-weight: 700; color: var(--cream); }
  .facil-card .fac-body { padding: .9rem 1.3rem; }
  .fac-item { margin-bottom: .8rem; }
  .fac-item:last-child { margin-bottom: 0; }
  .fac-item .flabel { display: inline-block; font-size: .67rem; letter-spacing: .2em; text-transform: uppercase; color: var(--gold); margin-bottom: .22rem; }
  .fac-item p { color: var(--mist); font-size: .97rem; }
  .fac-item p strong { color: var(--cream); }
  .fac-item p em { color: var(--gl); font-style: normal; }

  /* ── FOOTER ── */
  footer { border-top: 1px solid rgba(184,150,12,.1); text-align: center; padding: 1.5rem; font-size: .77rem; letter-spacing: .12em; color: var(--fog); }

  @media (max-width: 640px) {
    header { padding: 1rem 1.2rem; }
    .page-body { padding: 1.5rem 1rem 4rem; }
  }
</style>
</head>
<body>

<div id="curtain" aria-hidden="true">
  <div class="c-icon">👨‍🏫</div>
  <div class="c-line"></div>
  <div class="c-label">For Instructors</div>
</div>

<header>
  <a href="/index.php" class="site-title">Momo's <em>London</em></a>
  <div class="hdr-r">
    <a href="/index.php" class="btn-sm">← Contents</a>
    <a href="/logout.php" class="btn-sm">Logout</a>
  </div>
</header>

<div class="page-hero">
  <span class="plabel">Note</span>
  <h1>
    <?php
    $words = ['先生方へ：', '本書の教育的デザインと教授法'];
    foreach ($words as $i => $w) {
        $delay = 1.3 + $i * 0.18;
        echo '<span class="word" style="animation-delay:' . $delay . 's">' . htmlspecialchars($w) . '</span> ';
    }
    ?>
  </h1>
  <div class="ch-rule"><span></span><div class="d"></div><span></span></div>
  <p class="hero-sub">Momo's London — Instructor's Guide</p>
</div>

<div class="page-body">

  <!-- ══════════════════════ -->
  <!-- 2. ねらい              -->
  <!-- ══════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">2</span>
    <span class="icon">🎯</span>
    <h2>ねらい（本書が目指すもの）</h2>
  </div>

  <div class="highlight-quote reveal">
    <strong>「物語への没入感」を英語学習の最大の推進力</strong>。主人公Momoの成功と成長の物語を追体験することで、学習者は「次が知りたい」という自然な知的好奇心に導かれ、挫折することなく一冊を最後までやり遂げることができます。
  </div>

  <p class="body-text reveal">
    本書が目指すものは「大学英語とTOEICの橋渡し」です。現在の教科書は「TOEICはTOEIC、Academic EnglishはAcademic English」と全く別ジャンルで扱われていますが、2005年から大学で英語教員としてTOEIC指導に関わってきて感じるのは、大学生はもちろん会社員経験がないので、そもそも日本語でも「会議の常識とは」「飛行機のチェックインとは」「海外の空港にあるものとは」を知らない、知るすべもない、ということです。ビジネス英語のTOEICを受験し、英語でimmigrationを聞いて「？」、訳の「入国審査」を聞いても「？」、行ったことがないのでピンとこない、というのが現状で、もはや想像の産物を武器に戦っており、これからもそうであることが予想されます。TOEIC、Academic English、社会人経験のない大学生、それらを繋ぐ橋渡し的、かつ、力の付く教科書はなかなか存在しません。
  </p>

  <p class="body-text reveal">
    本教科書は、ほとんどの大学生が「もしかしたら英語を使うかもしれない環境」である「就職してからの若手としての海外出向」を舞台としています。少し英語が苦手な若手社員の主人公の奮闘を物語形式で読んでいきます。それは、大学生であればすぐ目と鼻の先である「若手社員」という絶妙な心的距離設定で、その中で主人公の体験を通して、ビジネスシーンをリアルに体験します。
  </p>

  <p class="body-text reveal">
    本文はTOEIC 500〜550点レベルを目指す学習者用の語彙で作りました。本文の前半は主人公のビジネスシーン、そのまま最後までビジネス英語だと力が尽きる学生のために、後半は主人公が必ずロンドンやヨーロッパの街並みに出かけ文化的なストーリー展開を入れています。ただ、それだけだと今までも存在する教科書のつぎはぎにしかならないので、さらに謎解き風の物語に仕立て没入感を醸し出しています。
  </p>

  <p class="body-text reveal">
    物語を通して<strong style="color:var(--gold)">異文化理解力</strong>や<strong style="color:var(--gold)">実践的なコミュニケーション能力</strong>を養い、ビジネスシーンを垣間見る。さらにはそのシチュエーションでのTOEIC問題を最後に配し、単なる物語楽しかったな、で終わらせない工夫をしています。それらを通して、グローバルな舞台で活躍するための礎を築くことを最終的なねらいとします。
  </p>


  <!-- ══════════════════════ -->
  <!-- 3. 本書の特長          -->
  <!-- ══════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">3</span>
    <span class="icon">✨</span>
    <h2>本書の特長</h2>
  </div>

  <div class="feature-list">

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">📖</span>
        <h3>没入感を高めるストーリー形式</h3>
      </div>
      <div class="fc-body">
        英語に自信のない日本の若手社員Momoが、ロンドンへの出向を機に数々の困難を乗り越え、成功するまでの3年間を描く連続ドラマ形式。学習者はMomoに感情移入し、彼女の成功を応援しながら、共に成長を実感できます。章をまたぐ「謎解き」の要素も、学習者の興味を引きつけます。
      </div>
    </div>

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">🔗</span>
        <h3>凝った章立て構成</h3>
      </div>
      <div class="fc-body">
        一つ前のChapterのペアワークで話し合った内容がそれ以降のChapterの本文で出てきて「あ！？」となる凝った章立てにより、様々な発見を導く。雑学の質問から始まり、本文で回収。教師用資料にはその雑学の追加資料が充実していて、新人教員でも「昔からその雑学を知っていたかのように授業で話せる」興味を引く授業展開をサポート。
      </div>
    </div>

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">🌍</span>
        <h3>異文化理解と実用知識の習得</h3>
      </div>
      <div class="fc-body">
        英国と日本の働き方や習慣の違い（昼食、休暇、会議での発言、謝罪の仕方など）を随所に盛り込み、語学力だけではない真のグローバルコミュニケーション能力を養います。ロンドンの観光名所やLCCの乗り方といった、大学生の知的好奇心を刺激するリアルな情報も満載です。
      </div>
    </div>

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">📝</span>
        <h3>TOEIC® 頻出シーンを完全網羅</h3>
      </div>
      <div class="fc-body">
        物語の舞台は、空港での入国審査、オフィスでの会議、電話応対、Eメールでの報告、同僚との交渉など、TOEIC® Part 3, 4, 7で頻出のビジネスシーンで構成されています。生きた文脈の中で重要語彙やフレーズを学ぶため、記憶への定着率が飛躍的に向上します。逆に意図的にTOEIC頻出シーンを主人公の奮闘シーンにして、本文からの流れを自然なものにしています。
      </div>
    </div>

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">🔄</span>
        <h3>学習サイクルを確立する章構成</h3>
      </div>
      <div class="fc-body">
        各章は「重要フレーズ→ロールプレイ→本文→内容理解問題→リスニング→TOEIC形式問題」という一貫したフォーマットで構成。これにより、「インプット→実践→確認」という効果的な学習サイクルが自然に身につき、自律的な学習習慣を確立します。
      </div>
    </div>

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">🗂️</span>
        <h3>教員の負担を軽減する充実の補助教材</h3>
      </div>
      <div class="fc-body">
        全訳、全問題の解答・解説はもちろん、授業でそのまま使える文化的な背景知識の補足（雑学情報）、指導ポイント、TOEIC®各パートの解法アドバイス、テストは章毎・中間・期末の3種、中間期末は同試験範囲で3バージョンまで網羅した、<strong>詳細な「Teacher's Notes」</strong>を完備。教員の皆様の授業準備にかかる負担を大幅に軽減します。
      </div>
    </div>

    <div class="feature-card reveal">
      <div class="fc-head">
        <span class="ficon">🤖</span>
        <h3>NotebookLMによる拡張性</h3>
      </div>
      <div class="fc-body">
        NotebookLMはファイルにある情報のみを切り貼りしてくれるAIです。教科書の全内容を取り込んだ共有NotebookLMを作ります。採用者の先生にのみリンクが発行され自由に使うことが可能です。用意されている期末は全Chapterが試験範囲ですが、そこまで進まないと使えません。そこでNotebookLMに「期末テストをCh1–13までで作って」と打ち込めば新たな試験範囲で同形式のテストが出力されます。他にも「語彙のリストを頂戴」でリストがダウンロードできたり、「もっとCh5のワークを作って」とお願いしてオンデマンド対応したりもできます。
      </div>
    </div>

  </div><!-- /.feature-list -->


  <!-- ══════════════════════ -->
  <!-- 4. 期待される学習効果  -->
  <!-- ══════════════════════ -->
  <div class="sec-head reveal">
    <span class="sec-num">4</span>
    <span class="icon">📈</span>
    <h2>期待される学習効果</h2>
  </div>

  <ul class="effect-list reveal">
    <li>
      <span class="emark">◆</span>
      <span class="econtent"><strong>語彙・表現力の向上：</strong>物語の文脈の中で、TOEIC® 550点レベルの必須語彙・フレーズが自然に身につきます。</span>
    </li>
    <li>
      <span class="emark">◆</span>
      <span class="econtent"><strong>読解・聴解力の向上：</strong>ストーリーの続きが気になるため、楽しみながら大量の英語に触れることができ、速読力とリスニング力が向上します。</span>
    </li>
    <li>
      <span class="emark">◆</span>
      <span class="econtent"><strong>実践的運用能力の育成：</strong>ロールプレイやペアワークを通して、知識を「使える」スキルへと転換します。</span>
    </li>
    <li>
      <span class="emark">◆</span>
      <span class="econtent"><strong>学習意欲の維持・向上：</strong>主人公への共感が学習意欲に繋がり、「やらされる学習」から「やりたい学習」へと変革します。</span>
    </li>
    <li>
      <span class="emark">◆</span>
      <span class="econtent"><strong>TOEIC® スコアの達成：</strong>各章に組み込まれたTOEIC® 形式問題と解法アドバイスにより、目標スコア達成を強力にサポートします。</span>
    </li>
  </ul>


  <!-- ══════════════════════════════════════ -->
  <!-- コンセプト: Narrative-Based Learning   -->
  <!-- ══════════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="icon" style="font-size:1.5rem">🌟</span>
    <h2>本書のコンセプト（Narrative-Based Learning）</h2>
  </div>

  <div class="concept-box reveal">
    <p>本書は、学習者のモチベーション低下を防ぐため、<strong>「ストーリーテリング」を軸としたコンテクスト（文脈）重視の学習法</strong>を採用しています。</p>
    <p>モモという等身大の主人公が、異文化やビジネスの壁（電話会議、LCCの利用、多様なアクセント、予約のミスなど）に直面し、それを乗り越えていく過程（＝TOEIC頻出のビジネスシーン）と、数百年前の謎を追うミステリー要素がシームレスに融合しています。</p>
    <p>「次はどうなるのだろう？」という知的好奇心が、そのまま「次の章の英語を読みたい、聞きたい」という学習意欲に直結するよう設計されています。</p>
  </div>


  <!-- ══════════════════════════════════════════ -->
  <!-- 各設問の深いねらいとファシリテーションのコツ -->
  <!-- ══════════════════════════════════════════ -->
  <div class="sec-head reveal">
    <span class="icon" style="font-size:1.4rem">💡</span>
    <h2>各設問の深いねらいとファシリテーションのコツ</h2>
  </div>

  <div class="facil-list">

    <div class="facil-card reveal">
      <div class="fac-head">
        <span class="ftag">Speaking</span>
        <h3>1.3 Let's repeat +1 ─ スピーキング・流暢さの向上</h3>
      </div>
      <div class="fac-body">
        <div class="fac-item">
          <span class="flabel">ねらい</span>
          <p>日本の学習者にありがちな「一問一答で会話が終わってしまう」課題を克服するための、画期的なスパイラル学習です。自律的にリアクションやフォローアップの質問を足していくことで、<strong>「会話のターンテーキング（順番の交代）」の自動化</strong>を促します。</p>
        </div>
        <div class="fac-item">
          <span class="flabel">教授法</span>
          <p>「文法的な正しさ」よりも「会話を止めないこと（<em>Fluency</em>）」を大いに褒めてください。「Oh my god!」のような大げさな感情表現（FillerやReaction）を恥ずかしがらずに使えるよう、先生ご自身がノリ良くお手本を見せると、教室の空気が一気に温まります。</p>
        </div>
      </div>
    </div>

    <div class="facil-card reveal">
      <div class="fac-head">
        <span class="ftag">TOEIC対策</span>
        <h3>2.1 Scanning &amp; 2.6 Paraphrase ─ TOEIC対策の文脈化</h3>
      </div>
      <div class="fac-body">
        <div class="fac-item">
          <span class="flabel">ねらい</span>
          <p>TOEIC攻略の核となる「Synonym（同義語）の認識」と「Information Retrieval（情報検索）」のスキルを、無味乾燥なテスト対策としてではなく、物語の謎解きの一環として訓練します。</p>
        </div>
        <div class="fac-item">
          <span class="flabel">教授法</span>
          <p>Scanningはストップウォッチを使ってゲーム感覚（タイムアタック）で行うと盛り上がります。Paraphraseでは、「テスト作成者はどのように言葉の形を変えて引っかけてくるか」という<strong>「テストの裏側」の視点</strong>を持たせると効果的です。</p>
        </div>
      </div>
    </div>

    <div class="facil-card reveal">
      <div class="fac-head">
        <span class="ftag">Reading</span>
        <h3>2.2 &amp; 2.3 Reading Comprehension ─ 読解と異文化理解</h3>
      </div>
      <div class="fac-body">
        <div class="fac-item">
          <span class="flabel">ねらい</span>
          <p>CEFR A2〜B2レベルの語彙を文脈の中で定着させます。また、ビジネスシーンを垣間見てTOEIC対策を行うだけでなく、イギリス文化（ティー・ラウンドや階の数え方）や雑学を通して、英語学習の動機付けを狙います。</p>
        </div>
        <div class="fac-item">
          <span class="flabel">教授法</span>
          <p>精読（和訳）に時間をかけすぎず、ストーリーの展開とモモの感情の変化にフォーカスしてください。「先生用のTeacher Notes」に豊富な文化的背景（EU261法、パブ文化、サービス・リカバリー・パラドックスなど）を用意していますので、雑学として披露することで学習者の興味を惹きつけてください。</p>
        </div>
      </div>
    </div>

    <div class="facil-card reveal">
      <div class="fac-head">
        <span class="ftag">Writing</span>
        <h3>4 Writing Challenge ─ ビジネス・ライティング</h3>
      </div>
      <div class="fac-body">
        <div class="fac-item">
          <span class="flabel">ねらい</span>
          <p>実際に物語の中で起きたトラブル（例：フライト欠航、予約ミス）に対して、当事者として上司や顧客にメールを書く「タスクベース」のアウトプットです。</p>
        </div>
        <div class="fac-item">
          <span class="flabel">教授法</span>
          <p>「謝罪＋代替案」「状況報告＋Call to action」など、ビジネスメール特有の<strong>「型（フォーマット）」を意識</strong>させてください。AIにプロンプトを出して模範解答を作る「Ask AI」のサンプルも提示し、現代のテクノロジーを活用した学習法も啓蒙できます。</p>
        </div>
      </div>
    </div>

    <div class="facil-card reveal">
      <div class="fac-head">
        <span class="ftag">Discussion</span>
        <h3>5 Let's Discuss! ─ クリティカル・シンキング</h3>
      </div>
      <div class="fac-body">
        <div class="fac-item">
          <span class="flabel">ねらい</span>
          <p>「ミスを隠すか、打ち明けるか」「標準英語か、多様な英語か」など、正解のない問いに対して、自分の意見を論理的に英語で述べる訓練です。</p>
        </div>
        <div class="fac-item">
          <span class="flabel">教授法</span>
          <p>意見が出ず沈黙してしまった場合は、教員用資料の「Facilitator Information」にある【Aの視点の長所/短所】【Bの視点の長所/短所】を提示し、ヒントを与えてください。ディベートの形をとることで、<strong>批判的思考（Critical Thinking）</strong>を深く養うことができます。</p>
        </div>
      </div>
    </div>

  </div><!-- /.facil-list -->

</div><!-- /.page-body -->

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
(function () {
  const els = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) { els.forEach(el => el.classList.add('visible')); return; }
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.07, rootMargin: '0px 0px -30px 0px' });
  els.forEach(el => obs.observe(el));
})();
document.getElementById('curtain').addEventListener('animationend', function () { this.style.display = 'none'; });
</script>
</body>
</html>
