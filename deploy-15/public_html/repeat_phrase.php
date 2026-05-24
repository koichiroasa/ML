<?php
require_once __DIR__ . '/includes/auth_check.php';

// ヒアドキュメントでシングルクォート問題を回避
$raw = <<<'DATA'
①|気持ち・反応|Reaction|#B8960C
I see.|なるほど
That's right.|そうですね
Really?|本当ですか？
That's nice.|いいですね
That's great.|すばらしいですね
That's too bad.|残念ですね
Oh, I see.|ああ、なるほど
That makes sense.|納得できます
I get what you mean.|言いたいことはわかります
That's a good point.|いい指摘ですね
That's interesting to hear.|興味深いですね
I didn't expect that.|予想外でした
That's surprising.|驚きです
I can see why.|理由がわかります
That's something to think about.|考えさせられます
---
②|共感・サポート|Support|#4CAF85
I agree with you.|賛成です
You're right.|その通りです
Good idea.|いい考えですね
That sounds nice.|よさそうですね
No problem.|大丈夫です
That's okay.|大丈夫です
Don't worry.|心配しないで
I think so too.|私もそう思います
I feel the same way.|同じ気持ちです
That sounds like a great idea.|とても良い考えですね
That could be a good solution.|良い解決策かもしれません
I see your point.|意見は理解できます
That's a smart idea.|賢い考えですね
I think that will work.|うまくいくと思います
That sounds promising.|期待できそうですね
---
③|気持ち|Feeling|#C8102E
I'm happy.|うれしいです
I'm a bit tired.|少し疲れています
I'm busy.|忙しいです
I'm worried.|心配です
I feel good.|気分がいいです
I'm excited.|ワクワクしています
I'm nervous.|緊張しています
I'm a little nervous.|少し緊張しています
I feel stressed.|ストレスを感じています
I'm excited about it.|楽しみにしています
I feel better now.|今は気分がいいです
I'm not very confident.|自信がありません
I'm a bit worried about it.|少し心配です
I feel more relaxed now.|少し落ち着きました
I'm getting used to it.|慣れてきています
---
④|意見・考え|Opinion|#1F7A8C
I think so.|そう思います
I don't think so.|そうは思いません
I think it's nice.|いいと思います
I think it's difficult.|難しいと思います
I think it's fun.|楽しいと思います
I think it's okay.|大丈夫だと思います
I think it's important.|重要だと思います
I think it depends.|場合によると思います
I'm not sure about that.|どうでしょう
I think it's worth trying.|やる価値があります
I think it's not easy.|簡単ではない
I think it could be better.|もっと良くなる
I think it might be difficult.|難しいかもしれません
I think it will help.|役に立つと思います
I think it's a good idea overall.|全体として良いと思います
---
⑤|理由・説明|Reason|#8C5A0C
Because it's fun.|楽しいから
Because I like it.|好きだから
Because it's easy.|簡単だから
Because it's important.|重要だから
Because I'm busy.|忙しいから
Because I have time.|時間があるから
Because it's interesting.|おもしろいから
Because it helps me.|役に立つから
Because it takes time.|時間がかかるから
Because I need it.|必要だから
Because I want to improve.|上達したいから
Because it makes things easier.|楽になるから
Because I enjoy it.|楽しんでいるから
Because it's useful.|役に立つから
Because it works well.|うまくいくから
---
⑥|追加情報|Extra info|#6B4C9A
I like it.|好きです
I don't like it.|好きではありません
I do it every day.|毎日します
I do it on weekends.|週末にします
I want to try it.|やってみたい
I often do it.|よくします
I sometimes do it.|ときどきします
I'm working on it now.|今取り組んでいます
I tried it before.|前にやりました
I haven't decided yet.|まだ決めていません
I'm planning to do it.|予定です
I'm getting better at it.|上達しています
I've learned a lot.|多くを学びました
I want to learn more.|もっと学びたい
I'm still thinking about it.|まだ考えています
---
⑦|フィラー|Filler|#2E7D5E
Let me see.|えっと
Well...|ええと
Hmm...|うーん
Let me think.|考えます
Just a moment.|少し待って
How can I say this?|どう言えばいいかな
I'm not sure.|わかりません
It's hard to say.|言いにくい
I mean...|つまり
You know...|あの
Actually...|実は
To be honest...|正直に言うと
Well, I think...|ええと
Let me explain.|説明します
Give me a second.|少し時間ください
---
⑧|確認|Checking|#B8960C
You mean like this?|こういうこと？
Do you mean this?|これ？
What do you mean?|どういう意味？
Can you explain that?|説明して
Can you say that again?|もう一度
Did you say ~?|〜って言った？
So, you mean ~?|つまり〜？
Is that right?|合ってる？
Am I right?|これでいい？
What do you think?|どう思う？
Is that correct?|正しい？
Do you agree?|賛成？
Can you give an example?|例は？
What about you?|あなたは？
How about you?|あなたは？
---
⑨|フォローアップ質問|Follow-up|#1F7A8C
Why do you think so?|なぜ？
What kind of ~ do you like?|どんな〜？
When do you usually ~?|いつ？
Who do you ~ with?|誰と？
How often do you ~?|どのくらい？
Where do you usually ~?|どこで？
How do you do that?|どうやって？
What happened next?|その後どうなった？
Can you tell me more?|もっと教えて
Why is that?|なぜそれ？
What do you like about it?|何がいいの？
What's your favorite part?|どこが一番好き？
How was it?|どうだった？
What did you do?|何したの？
What will you do next?|次は何する？
---
⑩|具体化・つなぎ|Detail & Connecting|#4CAF85
For example, ~|例えば
Like ~|例えば
Such as ~|〜のような
And ~|そして
So ~|だから
But ~|しかし
Also ~|さらに
Then ~|それから
After that, ~|そのあと
In addition, ~|加えて
First, ~|まず
Next, ~|次に
Finally, ~|最後に
That's why ~|だから〜
Because of that, ~|そのため
In that case, ~|その場合は
In my case, ~|私の場合は
At the same time, ~|同時に
On the other hand, ~|一方で
Even so, ~|それでも
For me, ~|私にとっては
To be honest, ~|正直に言うと
Actually, ~|実は
---
⑪|感謝|Gratitude|#C8102E
Thanks.|ありがとう
Thank you.|ありがとうございます
Thanks a lot.|どうもありがとう
Thank you very much.|本当にありがとうございます
Thanks for your help.|手伝ってくれてありがとう
Thanks for your advice.|アドバイスありがとう
Thanks for telling me.|教えてくれてありがとう
I really appreciate it.|本当に感謝しています
I appreciate your help.|助けに感謝します
That was very helpful.|とても助かりました
Thanks, that helps a lot.|助かります
I'm grateful for that.|それに感謝しています
Thanks, I needed that.|ちょうど必要でした
I couldn't have done it without you.|あなたなしではできませんでした
Thanks for your support.|サポートありがとう
Thanks for mentioning that.|それを言ってくれてありがとう
DATA;

// パース
$categories = [];
$current = null;
foreach (explode("\n", trim($raw)) as $line) {
    $line = trim($line);
    if ($line === '---') { if ($current) $categories[] = $current; $current = null; continue; }
    if ($current === null) {
        // ヘッダー行: num|title|en|color
        $parts = explode('|', $line, 4);
        $current = ['num'=>$parts[0], 'title'=>$parts[1], 'en'=>$parts[2], 'color'=>$parts[3], 'items'=>[]];
    } else {
        // フレーズ行: english|japanese
        $pos = strrpos($line, '|');
        if ($pos !== false) {
            $current['items'][] = [substr($line, 0, $pos), substr($line, $pos+1)];
        }
    }
}
if ($current) $categories[] = $current;

$total = array_sum(array_map(fn($c) => count($c['items']), $categories));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Repeat + 1 Phrase List | Momo's London</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
  :root{--navy:#0D1B2A;--cream:#F4EFE4;--gold:#B8960C;--mist:#D6CFC2;--fog:#8C8070;--gl:#4CAF85;--bg2:rgba(255,255,255,.03);--border:rgba(184,150,12,.18)}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{background-color:var(--navy);background-image:radial-gradient(ellipse 100% 40% at 50% 0%,#162030 0%,transparent 55%);font-family:'EB Garamond',Georgia,serif;color:var(--cream);min-height:100vh;font-size:1.05rem;line-height:1.8;overflow-x:hidden}

  /* ── CURTAIN ── */
  #curtain{position:fixed;inset:0;z-index:9000;background:var(--navy);display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;animation:curtainLift .9s 1.0s cubic-bezier(.76,0,.24,1) both}
  @keyframes curtainLift{from{transform:translateY(0)}to{transform:translateY(-102%)}}
  #curtain .c-icon{font-size:clamp(5rem,18vw,11rem);line-height:1;animation:cReveal .7s .15s cubic-bezier(.34,1.4,.64,1) both}
  @keyframes cReveal{from{opacity:0;transform:scale(1.5)}to{opacity:1;transform:scale(1)}}
  #curtain .c-line{width:0;height:1px;background:linear-gradient(to right,transparent,var(--gold),transparent);margin-top:1.2rem;animation:lineExp .6s .55s ease both}
  @keyframes lineExp{from{width:0;opacity:0}to{width:min(320px,60vw);opacity:1}}
  #curtain .c-label{font-size:.72rem;letter-spacing:.4em;text-transform:uppercase;color:var(--gold);margin-top:.9rem;animation:fsu .5s .7s ease both}
  @keyframes fsu{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}

  /* ── HEADER ── */
  header{border-bottom:1px solid var(--border);padding:1.1rem 3rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:200;background:rgba(13,27,42,.9);backdrop-filter:blur(12px);animation:hDrop .5s 1.7s ease both}
  @keyframes hDrop{from{opacity:0;transform:translateY(-100%)}to{opacity:1;transform:translateY(0)}}
  .site-title{font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:700;color:var(--cream);text-decoration:none}
  .site-title em{font-style:italic;color:var(--gold)}
  .hdr-r{display:flex;align-items:center;gap:1rem}
  .btn-sm{font-size:.72rem;letter-spacing:.15em;text-transform:uppercase;color:var(--fog);text-decoration:none;border:1px solid rgba(140,128,112,.4);padding:.28rem .75rem;border-radius:1px;transition:color .2s,border-color .2s}
  .btn-sm:hover{color:var(--cream);border-color:var(--mist)}

  /* ── HERO ── */
  .page-hero{text-align:center;padding:3rem 2rem 2.5rem;border-bottom:1px solid rgba(184,150,12,.08);overflow:hidden}
  .plabel{display:inline-block;font-size:.72rem;letter-spacing:.3em;text-transform:uppercase;background:rgba(184,150,12,.12);border:1px solid rgba(184,150,12,.28);color:var(--gold);padding:.2rem .75rem;border-radius:999px;margin-bottom:.9rem;animation:popIn .55s 1.2s cubic-bezier(.34,1.56,.64,1) both}
  @keyframes popIn{from{opacity:0;transform:scale(.6) translateY(8px)}to{opacity:1;transform:scale(1) translateY(0)}}
  .page-hero h1{font-family:'Playfair Display',serif;font-size:clamp(1.6rem,3.5vw,2.6rem);font-weight:700;line-height:1.2}
  .page-hero h1 .word{display:inline-block;animation:wRise .55s cubic-bezier(.22,1,.36,1) both}
  .page-hero h1 .word em{font-style:italic;color:var(--gold)}
  @keyframes wRise{from{opacity:0;transform:translateY(50px) rotate(1.5deg)}to{opacity:1;transform:translateY(0) rotate(0)}}
  .ch-rule{display:flex;align-items:center;justify-content:center;gap:1rem;margin:1rem auto .5rem;animation:fsu .5s 2.0s ease both}
  .ch-rule span{width:60px;height:1px}
  .ch-rule span:first-child{background:linear-gradient(to right,transparent,var(--gold))}
  .ch-rule span:last-child{background:linear-gradient(to left,transparent,var(--gold))}
  .ch-rule .d{width:7px;height:7px;background:var(--gold);transform:rotate(45deg)}
  .hero-meta{color:var(--fog);font-size:.88rem;animation:fsu .5s 2.1s ease both}

  /* ── REVEAL ── */
  .reveal{opacity:0;transform:translateY(22px);transition:opacity .6s ease,transform .6s cubic-bezier(.22,1,.36,1)}
  .reveal.visible{opacity:1;transform:translateY(0)}

  /* ── LAYOUT ── */
  .page-body{max-width:980px;margin:0 auto;padding:2rem 1.5rem 5rem}

  /* ── INTRO ── */
  .intro-box{background:rgba(184,150,12,.06);border-left:3px solid var(--gold);border-radius:0 2px 2px 0;padding:1rem 1.4rem;margin-bottom:1.5rem;color:var(--mist);font-size:.97rem}
  .intro-box strong{color:var(--gold)}

  /* ── STATS ROW ── */
  .stats-row{display:flex;gap:.7rem;margin-bottom:1.4rem;flex-wrap:wrap}
  .stat-pill{background:rgba(184,150,12,.1);border:1px solid rgba(184,150,12,.25);border-radius:999px;padding:.28rem .85rem;font-size:.8rem;color:var(--gold);white-space:nowrap}

  /* ── JUMP NAV ── */
  .jump-nav{display:flex;flex-wrap:wrap;gap:.45rem;margin-bottom:1.5rem}
  .jump-btn{display:inline-flex;align-items:center;gap:.3rem;background:var(--bg2);border:1px solid var(--border);border-radius:2px;padding:.25rem .7rem;font-size:.8rem;color:var(--mist);text-decoration:none;cursor:pointer;transition:background .18s,border-color .18s,color .18s}
  .jump-btn:hover{background:rgba(184,150,12,.1);border-color:rgba(184,150,12,.35);color:var(--gold)}
  .jump-btn .jnum{font-weight:bold;color:var(--gold)}

  /* ── EXPAND ROW ── */
  .expand-row{display:flex;gap:.6rem;margin-bottom:1.2rem}
  .expand-btn{background:var(--bg2);border:1px solid var(--border);border-radius:2px;padding:.32rem .85rem;font-family:'EB Garamond',serif;font-size:.85rem;color:var(--mist);cursor:pointer;transition:background .18s,border-color .18s}
  .expand-btn:hover{background:rgba(184,150,12,.1);border-color:rgba(184,150,12,.3);color:var(--gold)}

  /* ── CATEGORY BLOCK ── */
  .cat-block{margin-bottom:1rem;border:1px solid var(--border);border-radius:3px;overflow:hidden}
  .cat-trigger{width:100%;background:rgba(255,255,255,.03);border:none;cursor:pointer;display:flex;align-items:center;gap:1rem;padding:.85rem 1.3rem;text-align:left;transition:background .2s}
  .cat-trigger:hover{background:rgba(255,255,255,.06)}
  .ct-num{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;min-width:1.8rem;flex-shrink:0}
  .ct-titles{flex:1}
  .ct-ja{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--cream);display:block;line-height:1.2}
  .ct-en{font-size:.78rem;letter-spacing:.15em;text-transform:uppercase;color:var(--fog);display:block;margin-top:.1rem}
  .ct-count{font-size:.75rem;color:var(--fog);flex-shrink:0}
  .ct-arr{color:var(--gold);font-size:.9rem;flex-shrink:0;transition:transform .3s}
  .cat-trigger.open .ct-arr{transform:rotate(180deg)}

  /* ── PHRASE GRID ── */
  .cat-body{display:none;padding:.8rem 1rem 1rem}
  .cat-body.open{display:block}
  .phrase-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:.42rem}
  .phrase-card{background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:2px;padding:.48rem .85rem;transition:background .15s,border-color .15s}
  .phrase-card:hover{background:rgba(255,255,255,.05);border-color:rgba(184,150,12,.2)}
  .phrase-en{color:var(--cream);font-size:.97rem;font-weight:500;display:block}
  .phrase-ja{color:var(--fog);font-size:.82rem;display:block;margin-top:.03rem}

  /* ── FOOTER ── */
  footer{border-top:1px solid rgba(184,150,12,.1);text-align:center;padding:1.5rem;font-size:.77rem;letter-spacing:.12em;color:var(--fog)}

  @media(max-width:600px){
    header{padding:1rem 1.2rem}
    .page-body{padding:1.5rem .8rem 4rem}
    .phrase-grid{grid-template-columns:1fr}
  }
</style>
</head>
<body>

<div id="curtain" aria-hidden="true">
  <div class="c-icon">💬</div>
  <div class="c-line"></div>
  <div class="c-label">Repeat + 1 Phrase List</div>
</div>

<header>
  <a href="/index.php" class="site-title">Momo's <em>London</em></a>
  <div class="hdr-r">
    <a href="/index.php" class="btn-sm">← Contents</a>
    <a href="/logout.php" class="btn-sm">Logout</a>
  </div>
</header>

<div class="page-hero">
  <span class="plabel">List</span>
  <h1>
    <span class="word" style="animation-delay:1.3s">Repeat</span>
    <span class="word" style="animation-delay:1.4s"><em>+ 1</em></span>
    <span class="word" style="animation-delay:1.5s">Phrase</span>
    <span class="word" style="animation-delay:1.6s">List</span>
  </h1>
  <div class="ch-rule"><span></span><div class="d"></div><span></span></div>
  <p class="hero-meta">スピーキング活動を豊かにする <?= $total ?>フレーズ — 11カテゴリ</p>
</div>

<div class="page-body">

  <div class="intro-box reveal">
    会話の中に<strong>「+ 1フレーズ」</strong>を加えることで、自然な英会話のキャッチボールを続ける練習をしましょう。下記のフレーズは、Speaking の各ロールプレイや Repeat + 1 活動で自由に使えます。カテゴリを選んで、自分の気持ちや状況に合ったフレーズを見つけてください。
  </div>

  <div class="stats-row reveal">
    <span class="stat-pill">💬 全<?= $total ?>フレーズ</span>
    <span class="stat-pill">📂 <?= count($categories) ?>カテゴリ</span>
    <span class="stat-pill">📖 Speaking 全章対応</span>
  </div>

  <div class="jump-nav reveal">
    <?php foreach ($categories as $ci => $cat): ?>
    <a class="jump-btn" href="#cat-<?= $ci ?>">
      <span class="jnum"><?= htmlspecialchars($cat['num']) ?></span>
      <span><?= htmlspecialchars($cat['title']) ?></span>
    </a>
    <?php endforeach; ?>
  </div>

  <div class="expand-row reveal">
    <button class="expand-btn" onclick="expandAll()">▼ すべて開く</button>
    <button class="expand-btn" onclick="collapseAll()">▲ すべて閉じる</button>
  </div>

  <?php foreach ($categories as $ci => $cat): ?>
  <div class="cat-block reveal" id="cat-<?= $ci ?>">
    <button class="cat-trigger" id="cat-btn-<?= $ci ?>" onclick="toggleCat(<?= $ci ?>)" type="button">
      <span class="ct-num" style="color:<?= htmlspecialchars($cat['color']) ?>"><?= htmlspecialchars($cat['num']) ?></span>
      <span class="ct-titles">
        <span class="ct-ja"><?= htmlspecialchars($cat['title']) ?></span>
        <span class="ct-en"><?= htmlspecialchars($cat['en']) ?></span>
      </span>
      <span class="ct-count"><?= count($cat['items']) ?>フレーズ</span>
      <span class="ct-arr">▼</span>
    </button>
    <div class="cat-body" id="cat-body-<?= $ci ?>">
      <div class="phrase-grid">
        <?php foreach ($cat['items'] as $item): ?>
        <div class="phrase-card">
          <span class="phrase-en"><?= htmlspecialchars($item[0]) ?></span>
          <span class="phrase-ja">（<?= htmlspecialchars($item[1]) ?>）</span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

</div>

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
const TOTAL = <?= count($categories) ?>;
function toggleCat(i){
  const b=document.getElementById('cat-btn-'+i), d=document.getElementById('cat-body-'+i);
  const o=d.classList.toggle('open'); b.classList.toggle('open',o);
}
function expandAll(){for(let i=0;i<TOTAL;i++){document.getElementById('cat-body-'+i).classList.add('open');document.getElementById('cat-btn-'+i).classList.add('open');}}
function collapseAll(){for(let i=0;i<TOTAL;i++){document.getElementById('cat-body-'+i).classList.remove('open');document.getElementById('cat-btn-'+i).classList.remove('open');}}
(function(){
  const els=document.querySelectorAll('.reveal');
  if(!('IntersectionObserver' in window)){els.forEach(el=>el.classList.add('visible'));return;}
  const obs=new IntersectionObserver(entries=>{entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('visible');obs.unobserve(e.target);}});},{threshold:0.06,rootMargin:'0px 0px -20px 0px'});
  els.forEach(el=>obs.observe(el));
})();
document.getElementById('curtain').addEventListener('animationend',function(){this.style.display='none';});
</script>
</body>
</html>
