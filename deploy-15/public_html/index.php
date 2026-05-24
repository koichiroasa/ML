<?php
require_once __DIR__ . '/includes/auth_check.php';

$student_pages = [
    ['file' => 'intro_student.php',  'label' => 'Guide', 'title' => '学生の皆さんへ：このテキストの歩き方'],
    ['file' => 'intro_teacher.php',  'label' => 'Note',  'title' => '先生方へ：本書の教育的デザインと教授法'],
    ['file' => 'spiral.php',         'label' => 'List',  'title' => '一度覚えた語彙を再度使っているリスト（スパイラル学習）'],
    ['file' => 'repeat_phrase.php',  'label' => 'List',  'title' => 'Repeat + 1 Phrase List'],
    ['file' => 'flashcard/',         'label' => 'Study', 'title' => '🃏 Flashcard — 単語学習'],
];

$chapters = [
    ['num'=>'01','title'=>'A Leap Across the Globe'],
    ['num'=>'02','title'=>'An Elevator of Confusion'],
    ['num'=>'03','title'=>'A Different Rhythm of Work'],
    ['num'=>'04','title'=>'Finding Her Voice'],
    ['num'=>'05','title'=>'A Test in the Tower'],
    ['num'=>'06','title'=>'A Digital Voyage to Italy'],
    ['num'=>'07','title'=>'First Taste of Italy'],
    ['num'=>'08','title'=>'The Universal Language'],
    ['num'=>'09','title'=>'Grounded by Fog'],
    ['num'=>'10','title'=>"The Artifact's Secret"],
    ['num'=>'11','title'=>'A Call Across Time'],
    ['num'=>'12','title'=>'Beneath the Sea to the City of Light'],
    ['num'=>'13','title'=>'A Passport with Limits'],
    ['num'=>'14','title'=>'Taking Ownership'],
    ['num'=>'15','title'=>'The True Navigator'],
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Momo's London — Contents</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=EB+Garamond:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
<style>
  :root{--navy:#0D1B2A;--cream:#F4EFE4;--red:#C8102E;--gold:#B8960C;--mist:#D6CFC2;--fog:#8C8070;--green:#2E7D5E;--gl:#4CAF85}
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
  body{min-height:100vh;background-color:var(--navy);background-image:radial-gradient(ellipse 100% 50% at 50% 0%,#162030 0%,transparent 55%),url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.015'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/svg%3E");font-family:'EB Garamond',Georgia,serif;color:var(--cream)}
  header{border-bottom:1px solid rgba(184,150,12,.2);padding:1.2rem 3rem;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;background:rgba(13,27,42,.88);backdrop-filter:blur(12px)}
  .site-title{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--cream);text-decoration:none}
  .site-title em{font-style:italic;color:var(--gold)}
  .hdr-r{display:flex;align-items:center;gap:1.2rem;font-size:.85rem}
  .badge{font-size:.68rem;letter-spacing:.2em;text-transform:uppercase;padding:.25rem .65rem;border-radius:999px}
  .badge.student{background:rgba(184,150,12,.15);color:var(--gold);border:1px solid rgba(184,150,12,.3)}
  .badge.teacher{background:rgba(46,125,94,.2);color:var(--gl);border:1px solid rgba(46,125,94,.4)}
  .uname{color:var(--mist)}
  .logout{font-size:.73rem;letter-spacing:.15em;text-transform:uppercase;color:var(--fog);text-decoration:none;border:1px solid rgba(140,128,112,.4);padding:.3rem .8rem;border-radius:1px;transition:color .2s,border-color .2s}
  .logout:hover{color:var(--cream);border-color:var(--mist)}
  .hero{text-align:center;padding:4.5rem 2rem 3rem;animation:fi .8s ease both}
  @keyframes fi{from{opacity:0;transform:translateY(18px)}to{opacity:1}}
  .eye{font-size:.75rem;letter-spacing:.35em;text-transform:uppercase;color:var(--gold);margin-bottom:.9rem}
  .hero h2{font-family:'Playfair Display',serif;font-size:clamp(2.2rem,5vw,3.4rem);font-weight:700;line-height:1.1;margin-bottom:.8rem}
  .hero h2 em{font-style:italic;color:var(--gold)}
  .rule{display:flex;align-items:center;justify-content:center;gap:1rem;margin:.8rem auto 1rem}
  .rule span{width:70px;height:1px;background:linear-gradient(to right,transparent,var(--gold))}
  .rule span:last-child{background:linear-gradient(to left,transparent,var(--gold))}
  .rule .d{width:8px;height:8px;background:var(--gold);transform:rotate(45deg)}
  .hero p{color:var(--fog);font-size:1rem}
  .wrap{max-width:820px;margin:0 auto;padding:0 2rem 5rem}
  .sh{display:flex;align-items:center;gap:1rem;margin:2.8rem 0 1.2rem}
  .sh h3{font-family:'Playfair Display',serif;font-size:.95rem;letter-spacing:.15em;text-transform:uppercase;white-space:nowrap}
  .sh h3.gc{color:var(--mist)} .sh h3.tc{color:var(--gl)}
  .sl{flex:1;height:1px}
  .sl.gold{background:linear-gradient(to right,rgba(184,150,12,.4),transparent)}
  .sl.green{background:linear-gradient(to right,rgba(76,175,133,.35),transparent)}
  .igrid{display:grid;grid-template-columns:1fr 1fr;gap:.65rem;margin-bottom:.65rem}
  .row{display:flex;align-items:center;gap:1.2rem;background:rgba(255,255,255,.03);border:1px solid rgba(184,150,12,.12);border-radius:2px;padding:1rem 1.3rem;text-decoration:none;color:var(--cream);position:relative;overflow:hidden;transition:background .22s,border-color .22s,transform .15s;margin-bottom:.55rem}
  .row::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;opacity:0;transition:opacity .2s}
  .row.si::before{background:var(--gold)}
  .row.ti{border-color:rgba(76,175,133,.13)}
  .row.ti::before{background:var(--gl)}
  .row:hover{background:rgba(255,255,255,.06);transform:translateX(4px)}
  .row:hover::before{opacity:1}
  .row.si:hover{border-color:rgba(184,150,12,.3)}
  .row.ti:hover{border-color:rgba(76,175,133,.3)}
  .lbl{font-size:.62rem;letter-spacing:.18em;text-transform:uppercase;padding:.14rem .45rem;border-radius:999px;flex-shrink:0;min-width:2.6rem;text-align:center}
  .lbl.g{background:rgba(184,150,12,.14);color:var(--gold);border:1px solid rgba(184,150,12,.24)}
  .lbl.t{background:rgba(76,175,133,.1);color:var(--gl);border:1px solid rgba(76,175,133,.23)}
  .rtitle{flex:1;font-size:.97rem;line-height:1.35}
  .arr{color:var(--fog);font-size:1.1rem;transition:transform .2s,color .2s;flex-shrink:0}
  .row:hover .arr{transform:translateX(3px);color:var(--gold)}
  .row.ti:hover .arr{color:var(--gl)}
  .cnum{font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:rgba(184,150,12,.38);min-width:2rem;text-align:center;transition:color .2s;flex-shrink:0}
  .row:hover .cnum{color:var(--gold)}
  .tnum{font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:rgba(76,175,133,.32);min-width:2rem;text-align:center;transition:color .2s;flex-shrink:0}
  .row:hover .tnum{color:var(--gl)}
  /* Teacher section wrapper */
  .tsec{background:rgba(46,125,94,.045);border:1px solid rgba(76,175,133,.14);border-radius:3px;padding:1.6rem 1.6rem .8rem;margin-top:2.8rem}
  .thead{display:flex;align-items:center;gap:.8rem;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid rgba(76,175,133,.12)}
  .thead .ico{font-size:1.3rem}
  .thead h3{font-family:'Playfair Display',serif;font-size:1rem;letter-spacing:.12em;text-transform:uppercase;color:var(--gl)}
  .thead p{font-size:.78rem;color:rgba(76,175,133,.55);margin-top:.1rem}
  .tmgrid{display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;margin-bottom:.7rem}
  footer{border-top:1px solid rgba(184,150,12,.1);text-align:center;padding:1.5rem;font-size:.78rem;letter-spacing:.12em;color:var(--fog)}
  @media(max-width:700px){header{padding:1rem 1.2rem}.igrid,.tmgrid{grid-template-columns:1fr}.wrap{padding:0 1rem 4rem}.hero{padding:3rem 1.2rem 2rem}}
  @media(max-width:480px){.uname{display:none}}
</style>
</head>
<body>
<header>
  <a href="/index.php" class="site-title">Momo's <em>London</em></a>
  <div class="hdr-r">
    <span class="badge <?= $current_role ?>"><?= $is_teacher ? '教員' : '学生' ?></span>
    <span class="uname"><?= htmlspecialchars($current_name) ?></span>
    <a href="/logout.php" class="logout">Logout</a>
  </div>
</header>

<div class="hero">
  <p class="eye">English Learning Materials</p>
  <h2>Momo's <em>London</em></h2>
  <div class="rule"><span></span><div class="d"></div><span></span></div>
  <p>Table of Contents &nbsp;·&nbsp; ようこそ、<?= htmlspecialchars($current_name) ?>さん</p>
</div>

<div class="wrap">

  <!-- はじめに -->
  <div class="sh"><h3 class="gc">はじめに</h3><div class="sl gold"></div></div>
  <div class="igrid">
    <?php foreach ($student_pages as $p): ?>
    <a href="/<?= $p['file'] ?>" class="row si" style="margin-bottom:0">
      <span class="lbl g"><?= $p['label'] ?></span>
      <span class="rtitle"><?= htmlspecialchars($p['title']) ?></span>
      <span class="arr">›</span>
    </a>
    <?php endforeach; ?>
  </div>

  <!-- Chapters -->
  <div class="sh"><h3 class="gc">Chapters</h3><div class="sl gold"></div></div>
  <?php foreach ($chapters as $i => $ch): ?>
  <a href="/ch<?= $ch['num'] ?>.php" class="row si">
    <span class="cnum"><?= $ch['num'] ?></span>
    <span class="rtitle" style="font-family:'Playfair Display',serif;font-style:italic"><?= htmlspecialchars($ch['title']) ?></span>
    <span class="arr">›</span>
  </a>
  <?php endforeach; ?>

  <!-- Teacher section -->
  <?php if ($is_teacher): ?>
  <div class="tsec">
    <div class="thead">
      <span class="ico">🔒</span>
      <div><h3>教員専用コンテンツ</h3><p>Teacher Access Only</p></div>
    </div>
    <div class="tmgrid">
      <?php for ($n = 1; $n <= 15; $n++):
        $nn = sprintf('%02d', $n); ?>
      <a href="/tm<?= $nn ?>.php" class="row ti" style="margin-bottom:0;padding:.85rem 1rem">
        <span class="tnum"><?= $nn ?></span>
        <span class="rtitle" style="font-size:.88rem">Manual<br><span style="color:var(--fog);font-size:.78rem">Chapter <?= $nn ?></span></span>
        <span class="arr" style="font-size:.95rem">›</span>
      </a>
      <?php endfor; ?>
    </div>
    <a href="/zenyaku.php" class="row ti" style="margin-top:.3rem">
      <span class="lbl t">全訳</span>
      <span class="rtitle">全訳</span>
      <span class="arr">›</span>
    </a>
  </div>
  <?php endif; ?>

</div>
<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>
</body>
</html>
