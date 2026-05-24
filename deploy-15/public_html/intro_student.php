<?php
require_once __DIR__ . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>学生の皆さんへ：このテキストの歩き方 | Momo's London</title>
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
    --gl:     #4CAF85;
    --teal:   #1F7A8C;
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

  /* ── CINEMATIC CURTAIN ── */
  #curtain {
    position: fixed; inset: 0; z-index: 9000;
    background: var(--navy);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    pointer-events: none;
    animation: curtainLift 0.9s 1.0s cubic-bezier(0.76,0,0.24,1) both;
  }
  @keyframes curtainLift { from { transform: translateY(0); } to { transform: translateY(-102%); } }
  #curtain .c-icon {
    font-size: clamp(5rem, 18vw, 11rem);
    line-height: 1;
    animation: cNumReveal 0.7s 0.15s cubic-bezier(0.34,1.4,0.64,1) both;
  }
  @keyframes cNumReveal { from { opacity:0; transform:scale(1.5); } to { opacity:1; transform:scale(1); } }
  #curtain .c-line {
    width: 0; height: 1px;
    background: linear-gradient(to right, transparent, var(--gold), transparent);
    margin-top: 1.2rem;
    animation: lineExpand 0.6s 0.55s ease both;
  }
  @keyframes lineExpand { from { width:0; opacity:0; } to { width:min(320px,60vw); opacity:1; } }
  #curtain .c-label {
    font-size: .72rem; letter-spacing: .4em; text-transform: uppercase;
    color: var(--gold); margin-top: .9rem;
    animation: fadeSlideUp 0.5s 0.7s ease both;
  }
  @keyframes fadeSlideUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }

  /* ── HEADER ── */
  header {
    border-bottom: 1px solid var(--border); padding: 1.1rem 3rem;
    display: flex; align-items: center; justify-content: space-between;
    position: sticky; top: 0; z-index: 200;
    background: rgba(13,27,42,.9); backdrop-filter: blur(12px);
    animation: headerDrop 0.5s 1.7s ease both;
  }
  @keyframes headerDrop { from { opacity:0; transform:translateY(-100%); } to { opacity:1; transform:translateY(0); } }
  .site-title { font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 700; color: var(--cream); text-decoration: none; }
  .site-title em { font-style: italic; color: var(--gold); }
  .hdr-r { display: flex; align-items: center; gap: 1rem; }
  .btn-sm { font-size: .72rem; letter-spacing: .15em; text-transform: uppercase; color: var(--fog); text-decoration: none; border: 1px solid rgba(140,128,112,.4); padding: .28rem .75rem; border-radius: 1px; transition: color .2s, border-color .2s; }
  .btn-sm:hover { color: var(--cream); border-color: var(--mist); }

  /* ── HERO ── */
  .page-hero {
    text-align: center;
    padding: 3.5rem 2rem 2.8rem;
    border-bottom: 1px solid rgba(184,150,12,.08);
    overflow: hidden;
  }
  .plabel {
    display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase;
    background: rgba(184,150,12,.12); border: 1px solid rgba(184,150,12,.28); color: var(--gold);
    padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem;
    animation: popIn 0.55s 1.2s cubic-bezier(0.34,1.56,0.64,1) both;
  }
  @keyframes popIn { from { opacity:0; transform:scale(0.6) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
  .page-hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.5rem, 3.5vw, 2.4rem);
    font-weight: 700; line-height: 1.2;
  }
  .page-hero h1 .word { display: inline-block; animation: wordRise 0.55s cubic-bezier(0.22,1,0.36,1) both; }
  @keyframes wordRise { from { opacity:0; transform:translateY(50px) rotate(1.5deg); } to { opacity:1; transform:translateY(0) rotate(0); } }
  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1.1rem auto .5rem; animation: fadeSlideUp 0.5s 2.0s ease both; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, var(--gold)); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, var(--gold)); }
  .ch-rule .d { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
  .hero-sub { color: var(--fog); font-size: .9rem; letter-spacing: .05em; animation: fadeSlideUp 0.5s 2.1s ease both; }

  /* ── SCROLL REVEAL ── */
  .reveal { opacity:0; transform:translateY(24px); transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.22,1,0.36,1); }
  .reveal.visible { opacity:1; transform:translateY(0); }

  /* ── LAYOUT ── */
  .page-body { max-width: 800px; margin: 0 auto; padding: 2.5rem 2rem 6rem; }

  /* ── INTRO SECTION HEADER ── */
  .intro-head {
    display: flex; align-items: center; gap: 1rem;
    margin: 3rem 0 1.4rem; padding-bottom: .6rem;
    border-bottom: 1px solid rgba(184,150,12,.2);
  }
  .intro-head .icon { font-size: 1.5rem; }
  .intro-head h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.25rem; font-weight: 700; color: var(--cream);
  }

  /* ── BODY TEXT ── */
  .body-text { color: var(--mist); margin-bottom: 1.2rem; }
  .body-text + .body-text { margin-top: -.2rem; }

  /* ── AUTHOR SIGN ── */
  .author-sign {
    text-align: right;
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-style: italic;
    color: var(--gold);
    margin-top: 1.6rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
  }

  /* ── CORNER GUIDE CARDS ── */
  .corner-grid {
    display: flex; flex-direction: column; gap: 1rem;
    margin-top: .5rem;
  }
  .corner-card {
    background: var(--bg2);
    border: 1px solid var(--border);
    border-radius: 3px;
    overflow: hidden;
    transition: border-color .25s, transform .15s;
  }
  .corner-card:hover { border-color: rgba(184,150,12,.38); transform: translateX(3px); }

  .corner-header {
    display: flex; align-items: center; gap: .9rem;
    padding: .85rem 1.3rem;
    background: rgba(184,150,12,.055);
    border-bottom: 1px solid var(--border);
  }
  .corner-header .cicon { font-size: 1.2rem; flex-shrink: 0; }
  .corner-header h3 {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 700; color: var(--cream);
  }

  .corner-body { padding: .95rem 1.3rem 1.1rem; }

  .corner-item { margin-bottom: .75rem; }
  .corner-item:last-child { margin-bottom: 0; }
  .corner-item .clabel {
    display: inline-block;
    font-size: .68rem; letter-spacing: .2em; text-transform: uppercase;
    color: var(--gold); margin-bottom: .25rem;
  }
  .corner-item p { color: var(--mist); font-size: .97rem; }
  .corner-item p strong { color: var(--cream); }
  .corner-item p em { color: var(--gl); font-style: normal; }

  /* ── HIGHLIGHT QUOTE ── */
  .highlight-quote {
    background: rgba(184,150,12,.06);
    border-left: 3px solid var(--gold);
    border-radius: 0 2px 2px 0;
    padding: 1rem 1.4rem;
    margin: 1.2rem 0;
    color: var(--mist);
    font-size: 1rem;
  }
  .highlight-quote strong { color: var(--gold); }

  /* ── FOOTER ── */
  footer { border-top: 1px solid rgba(184,150,12,.1); text-align: center; padding: 1.5rem; font-size: .77rem; letter-spacing: .12em; color: var(--fog); }

  @media (max-width: 640px) {
    header { padding: 1rem 1.2rem; }
    .page-body { padding: 1.5rem 1rem 4rem; }
  }
</style>
</head>
<body>

<!-- ── CURTAIN ── -->
<div id="curtain" aria-hidden="true">
  <div class="c-icon">🌟</div>
  <div class="c-line"></div>
  <div class="c-label">Guide for Students</div>
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
  <span class="plabel">Guide</span>
  <h1>
    <?php
    $words = ['学生の皆さんへ：', 'このテキストの歩き方'];
    foreach ($words as $i => $w) {
        $delay = 1.3 + $i * 0.15;
        echo "<span class=\"word\" style=\"animation-delay:{$delay}s\">" . htmlspecialchars($w) . "</span> ";
    }
    ?>
  </h1>
  <div class="ch-rule"><span></span><div class="d"></div><span></span></div>
  <p class="hero-sub">Momo's London — English Learning Materials</p>
</div>

<div class="page-body">

  <!-- ══════════════════════════════════════ -->
  <!-- これはどんな教科書？                   -->
  <!-- ══════════════════════════════════════ -->
  <div class="intro-head reveal">
    <span class="icon">🌟</span>
    <h2>これはどんな教科書？</h2>
  </div>

  <p class="body-text reveal">
    このテキストは、ただ読む、ただ聞く、ひたすら話す、ひたすら書く、ただただTOEICを勉強するための本ではありません。アカデミック英語からビジネス英語の橋渡しを目指し、4技能＋AI活用力まで身につく、小説仕立てのTOEIC対策付き（謎でしょ？でもそれみんなに必要、でもそんな教科書はない。だから作りました）な新しい教科書です。
  </p>

  <p class="body-text reveal">
    主人公のMomoは、ワクワクと不安を抱えながらロンドンのオフィスに配属されたばかりの若手社員。言葉の壁、文化の違い、仕事のミス……彼女が直面するリアルな壁を一緒に乗り越えながら、ロンドン、ローマ、パリを舞台に「ある歴史的な謎」を解き明かしていくミステリー小説仕立てになっています。
  </p>

  <p class="body-text reveal">
    高校までに勉強してきたアカデミック英語は、興味を引く内容が多く（小説もアカデミック英語ですね）、勉強しやすいですが、実際に会社で使う英語はビジネス英語です。仕事の場面（出張、会議、交渉、契約など）で使う英語ですね。
  </p>

  <div class="highlight-quote reveal">
    皆さんが就活で聞かれるであろうTOEICスコア、TOEICで使われるのがビジネス英語なわけですが、約20年大学でTOEICを教えて来ていつも言うのは<strong>「ビジネス英語の語彙が入っていない皆さんは、肉も玉ねぎもルーもないのに、カレーを作ってね、と言われているようなもの」</strong>という事実です。高校までのアカデミック英語の語彙では全く太刀打ちできません（「会議の議事録」って英語で言えますか？「budget」って何ですか？）。語彙だけでなく、就職したことのない皆さんにとって（当然）「予算って何？」「仕事の会議はどんなものなのか？」は知る術もないのに、TOEICでは「当然知ってるよね？」という常識としてサラッとインストールされています。なんとなくの想像で、なんとなくの背伸びで、問題に挑む。それって本当にきついテストですよね。だからTOEICってやりたくないテストなんです（やらないといけないけれど）。
  </div>

  <p class="body-text reveal">
    「そんな苦行嫌だ、そもそもこの時代に何のために英語を勉強するの？AIもあるし、別に海外志向ではないし」。そうですよね、でも急に降ってくるのが英語環境。入社直後のTOEIC試験で本社に残るか地方に飛ばされるかが決まったり、突如会社が外資と合併し会議がすべて英語に。私の大学時代の友人（前者は富士通の、後者は日産の）がまさにそれでした。単発的であればAIでよいですが、毎日が英語の生活だと「さーてAIアプリを取り出して…」とは行きません。一日中となるとAIでは対応しきれません。まあそんなことは私には起こらないよと思うかもしれませんが、単発のAI利用でも「この英語、これで合っているのかな？」の確認は必要で、AIを上回る英語力がないときちんと確認できない、となると、AIを使いこなすにはそれ相応の英語力が必要なのです、実は。
  </p>

  <p class="body-text reveal">
    そう考えると、やはり英語力は必要です。でもやっぱり苦行は嫌ですよね。それに急に英語環境に投げ込まれてそこで初めて「英語を勉強しとけばよかった…」と思うよりも先に、学生時代に、その体験をしておくと本当は良いですよね。でもそんなのはなかなかありえません。だからこの本があります。
  </p>

  <p class="body-text reveal">
    この本では、海外経験のない若手社員のMomoが急にロンドンに転勤になり、旅行会社社員として英語圏で奮闘するストーリーをあなた自身も疑似体験して、「海外で英語を使って働く」「ビジネスの現場」はこんな感じなのか、というのが少し体得できる（そのつもりで進めてくださいね）です。各Chapterで、TOEICに出てきそうなシーンでMomoが奮闘しますが、Chapterの後半は必ず、「じゃぁ、このシーンはどんな風にTOEICで出てくるのか、実際に問題を解いてみよう」に繋げてあります。アカデミック英語な小説→ビジネスシーンが出てくる、けど謎解き小説→ビジネス英語のTOEIC。さらにロンドン大学UCL卒の著者がロンドン雑学を豊富に盛り込み、ただの謎解き小説なだけでもない。ロンドン観光をしているような気分も少し味わえる。
  </p>

  <p class="body-text reveal">
    Momoと一緒に笑い、悩み、成長しながら、気づけば「本当に使える生きた英語」と「TOEICのスコアアップのコツ」が身についているはずです。さあ、一緒にロンドンへの扉を開きましょう（大丈夫、ページをめくればOKです）。
  </p>

  <div class="author-sign reveal">阿佐　宏一郎</div>


  <!-- ══════════════════════════════════════ -->
  <!-- 各コーナーのねらいと学習のコツ         -->
  <!-- ══════════════════════════════════════ -->
  <div class="intro-head reveal" style="margin-top:3.5rem">
    <span class="icon">💡</span>
    <h2>各コーナーのねらいと学習のコツ</h2>
  </div>

  <div class="corner-grid">

    <!-- Quick Start Quiz & Fun Pair-work -->
    <div class="corner-card reveal">
      <div class="corner-header">
        <span class="cicon">🎯</span>
        <h3>Quick "Start" Quiz &amp; Fun Pair-work</h3>
      </div>
      <div class="corner-body">
        <div class="corner-item">
          <span class="clabel">ねらい</span>
          <p>脳を「英語モード」に切り替える準備体操。</p>
        </div>
        <div class="corner-item">
          <span class="clabel">学習のコツ</span>
          <p>正解することが目的ではありません。パートナーと楽しく英語で雑談し、これから読むストーリーのテーマ（文化の違いや旅行のトラブルなど）について想像を膨らませましょう。予習して来た人は答えを知っているのでそれを英語にするだけ。この時点で報われますよ。👍</p>
        </div>
      </div>
    </div>

    <!-- Speaking -->
    <div class="corner-card reveal">
      <div class="corner-header">
        <span class="cicon">🗣️</span>
        <h3>Speaking（Role-play &amp; Let's repeat +1）</h3>
      </div>
      <div class="corner-body">
        <div class="corner-item">
          <span class="clabel">ねらい</span>
          <p>台本のないリアルな英会話のキャッチボールに慣れること。</p>
        </div>
        <div class="corner-item">
          <span class="clabel">学習のコツ</span>
          <p>一番の目玉は「<strong>+1（プラスワン）</strong>」です。「はい自由に話して！」は難しくても、「一言加えよう」なら一歩踏み出せる。<em>I like this hat.</em> だけで終わらせるのではなく +1 して、<em>I like this hat. This is my favorite team.</em> ならできそうでしょ？+1用に収録したフレーズリストを武器にして、そこから +1 もOK。</p>
        </div>
      </div>
    </div>

    <!-- Scanning & Paraphrase -->
    <div class="corner-card reveal">
      <div class="corner-header">
        <span class="cicon">🔍</span>
        <h3>Scanning &amp; Paraphrase Practice</h3>
      </div>
      <div class="corner-body">
        <div class="corner-item">
          <span class="clabel">ねらい</span>
          <p>TOEICで最も重要な「情報の探し出し」と「言い換え（パラフレーズ）」を見抜く力を養うこと。</p>
        </div>
        <div class="corner-item">
          <span class="clabel">学習のコツ</span>
          <p>TOEICは「いかに同じ意味の別の単語に気づけるか」のゲームです。本文の中から宝探しのようにキーワードを素早く見つける<strong>タイムアタック</strong>に挑戦してみてください。</p>
        </div>
      </div>
    </div>

    <!-- Story & Reading / Listening -->
    <div class="corner-card reveal">
      <div class="corner-header">
        <span class="cicon">📖</span>
        <h3>Story &amp; Reading / Listening</h3>
      </div>
      <div class="corner-body">
        <div class="corner-item">
          <span class="clabel">ねらい</span>
          <p>ビジネスの現場で使われる生きた英語、ビジネスシーンの疑似体験、英語で謎解き。</p>
        </div>
        <div class="corner-item">
          <span class="clabel">学習のコツ</span>
          <p>すべての単語を日本語に訳す必要はありません。「モモは今どんな気持ち？」「このトラブルをどう解決する？」と、<strong>物語の展開を楽しみながら</strong>英語の世界にダイブしましょう。</p>
        </div>
      </div>
    </div>

    <!-- Writing & Discussion -->
    <div class="corner-card reveal">
      <div class="corner-header">
        <span class="cicon">✍️</span>
        <h3>Writing Challenge &amp; Let's Discuss!</h3>
      </div>
      <div class="corner-body">
        <div class="corner-item">
          <span class="clabel">ねらい</span>
          <p>自分の意見や状況を、自分の言葉（英語）で相手に伝えるアウトプット力を鍛えること。</p>
        </div>
        <div class="corner-item">
          <span class="clabel">学習のコツ</span>
          <p>通常日本人のクラスメートに英語で伝えるのは不自然ですが、<strong>Momoの立場になってその文脈で書くなら</strong>自然と英語で書くしかない世界に入れます。上司に英語で謝罪メールを書いたり、文化の違いについて自分の意見をぶつけ合ったりして、「英語を使って仕事をする感覚」を味わってください！</p>
        </div>
      </div>
    </div>

  </div><!-- /.corner-grid -->

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
