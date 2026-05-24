<?php
require_once __DIR__ . '/includes/auth_check.php';

/* ── データ定義 ───────────────────────────────────────────
   各カテゴリ: [icon, title, items[]]
   items: [no, word, reading, meaning, chapters, collocation, synonym]
──────────────────────────────────────────────────────── */
$categories = [
  [
    'icon'  => '✈️',
    'title' => '1. 出張・交通機関 (Travel & Transportation)',
    'items' => [
      [1,  'itinerary',              '(名) /aɪtínərèri/',  '旅程、旅行計画',        'Ch 6, 7, 10, 15🎧',       'travel itinerary（旅行日程）',              'travel plan, schedule, travel arrangements'],
      [2,  'passenger',              '(名) /pǽsəndʒər/',   '乗客、旅客',            'Ch 1, 7, 9, 12🎧',        'passenger terminal（旅客ターミナル）',       'traveler, commuter, flyer, customer'],
      [3,  'departure',              '(名) /dɪpάːrtʃər/',  '出発',                  'Ch 1, 7, 12',             'departure gate（出発ゲート）',               'leaving, taking off, exit'],
      [4,  'destination',            '(名) /dèstənéɪʃən/', '目的地、行き先',        'Ch 5, 9, 13',             'popular tourist destination（人気の観光地）', 'arrival point, final stop, location'],
      [5,  'luggage / baggage',      '(名) /lʌ́gɪdʒ/ /bǽgɪdʒ/', '手荷物',          'Ch 1, 7, 12🎧',           'baggage claim（手荷物受取所）',              'suitcases, bags, belongings, items'],
      [6,  'accommodation',          '(名) /əkὰmədéɪʃən/', '宿泊施設',              'Ch 6, 9',                 'arrange accommodation（宿泊施設を手配する）', 'lodging, place to stay, hotel'],
      [7,  'alternative',            '(形/名) /ɔːltə́ːrnətɪv/', '代わりの、代替案',  'Ch 9🎧, 14',              'alternative route（代替ルート）',            'substitute, backup, different option'],
      [8,  'route / detour',         '(名) /rúːt/ /díːtʊər/', 'ルート / 迂回路',    'Ch 13, 14',               'take a detour（迂回する）',                  'path, direction / alternate route'],
      [9,  'boarding',               '(名) /bɔ́ːrdɪŋ/',    '搭乗',                  'Ch 1, 7, 12',             'boarding pass（搭乗券）',                    'getting on, embarking'],
      [10, 'terminal',               '(名) /tə́ːrmənl/',   'ターミナル、終点',       'Ch 1, 7',                 'bus terminal（バスターミナル）',              'airport building, station'],
      [11, 'transfer',               '(動/名) /trænsfə́ːr/', '乗り換える、転勤',     'Ch 1, 10',                'transfer to a new branch（新しい支店へ転勤する）', 'change trains, move, relocate'],
      [12, 'available',              '(形) /əvéɪləbl/',    '利用可能な、空いている', 'Ch 9, 11',                'available seats（空席）',                   'free, open, ready to use'],
      [13, 'delayed',                '(形) /dɪléɪd/',      '遅延した',              'Ch 7, 9, 12🎧',           'the flight is delayed（フライトが遅れている）', 'behind schedule, late'],
      [14, 'proceed',                '(動) /proʊsíːd/',    '進む、向かう',          'Ch 12🎧',                 'proceed to gate 3（3番ゲートへ進む）',        'go to, head for, continue'],
      [15, 'customs',                '(名) /kʌ́stəmz/',    '税関',                  'Ch 1',                    'clear customs（税関を通過する）',             'border control, inspection'],
      [16, 'declare',                '(動) /dɪkléər/',     '申告する',              'Ch 1',                    'nothing to declare（申告するものはない）',     'state, announce'],
      [17, 'stranded',               '(形) /strǽndɪd/',    '足止めされた',          'Ch 9',                    'left stranded（足止めされる）',               'stuck, unable to leave'],
      [18, 'immigration',            '(名) /ìməgréɪʃən/',  '入国審査',              'Ch 1, 12',                'immigration officer（入国審査官）',           'border control, passport control'],
      [19, 'overhead',               '(形/副) /óʊvərhèd/', '頭上の',               'Ch 7',                    'overhead compartment（頭上の荷物入れ）',      'above, upper'],
      [20, 'guide',                  '(動/名) /gάɪd/',     '導く、ガイド',          'Ch 5, 11, 15🎧',          'tour guide（ツアーガイド）',                 'lead, direct, escort'],
      [21, 'board',                  '(動/名) /bɔ́ːrd/',   '搭乗する、掲示板',      'Ch 12',                   'board the train（列車に乗り込む）',           'get on, enter / panel, sign'],
      [22, 'transit',                '(名) /trǽnsɪt/',     '乗り継ぎ、輸送',        'Ch 1',                    'public transit（公共交通機関）',              'transportation, transfer'],
      [23, 'explore',                '(動) /ɪksplɔ́ːr/',   '探検する、調査する',    'Ch 1, 4',                 'explore the city（街を散策する）',            'discover, look around, investigate'],
    ],
  ],
  [
    'icon'  => '🏢',
    'title' => '2. オフィス業務・実務 (Office & Business Operations)',
    'items' => [
      [24, 'client',                 '(名) /klάɪənt/',     '顧客、依頼人',          'Ch 5, 6, 14, 15🎧',       'potential client（見込み客）',               'customer, patron, guest, account'],
      [25, 'colleague',              '(名) /kάliːg/',      '同僚',                  'Ch 2, 3, 4',              'former colleague（かつての同僚）',            'coworker, associate, team member, peer'],
      [26, 'procedure',              '(名) /prəsíːdʒər/',  '手順、手続き',          'Ch 2, 3, 6',              'standard procedure（標準的な手順）',          'process, method, step, policy'],
      [27, 'facility / equipment',   '(名) /fəsíləti/ /ɪkwípmənt/', '施設 / 機器',  'Ch 2, 6, 10',             'research facility（研究施設）',              'building, space, amenity / gear, machinery'],
      [28, 'submit',                 '(動) /səbmít/',      '提出する',              'Ch 3, 4, 10',             'submit a report（報告書を提出する）',         'hand in, turn in, provide, present'],
      [29, 'approve / approval',     '(動/名) /əprúːv/ /əprúːvəl/', '承認する / 承認', 'Ch 2, 7, 10',           'get approval（承認を得る）',                 'accept, authorize, grant, agree to'],
      [30, 'implement',              '(動) /ímpləmènt/',   '実行する、導入する',    'Ch 4, 14',                'implement a new system（新システムを導入する）', 'carry out, put into practice, apply'],
      [31, 'deadline / schedule',    '(名) /dédlὰɪn/ /skédʒuːl/', '締め切り / 予定', 'Ch 6, 14, 15🎧',         'ahead of schedule（予定より早く）',           'due date, time limit / timeline, timetable'],
      [32, 'behalf (on ~ of)',       '(名) /bɪhǽf/',       '代表（～を代表して）',  'Ch 1, 11, 15🎧',          'on behalf of the company（会社を代表して）',  'as a representative of, speaking for'],
      [33, 'appointment',            '(名) /əpɔ́ɪntmənt/', '約束、予約',            'Ch 7, 8',                 'make an appointment（面会の約束をする）',     'meeting, engagement, booking'],
      [34, 'assignment',             '(名) /əsάɪnmənt/',   '任務、課題',            'Ch 5, 15',                'complete an assignment（任務を完了する）',    'task, duty, project, job'],
      [35, 'finalize',               '(動) /fάɪnəlὰɪz/',  '最終決定する、仕上げる', 'Ch 10, 15🎧',             'finalize the contract（契約を最終決定する）', 'complete, settle, finish up'],
      [36, 'brief',                  '(形) /bríːf/',       '短い、簡潔な',          'Ch 10, 14',               'a brief meeting（短い会議）',                'short, concise, quick'],
      [37, 'identify',               '(動) /aɪdéntəfὰɪ/', '特定する、確認する',    'Ch 10, 14',               'identify the cause（原因を特定する）',        'recognize, determine, pinpoint'],
      [38, 'organized',              '(形) /ɔ́ːrgənàɪzd/', '整理された、几帳面な',  'Ch 6, 15',                'highly organized（非常によく整理された）',    'well-planned, efficient, structured'],
      [39, 'branch',                 '(名) /brǽntʃ/',      '支店、支社',            'Ch 1, 2, 11🎧',           'branch manager（支店長）',                  'regional office, local office'],
      [40, 'document / material',    '(名) /dάkjəmənt/ /mətíəriəl/', '文書 / 資料', 'Ch 6, 10, 11',            'attached document（添付書類）',              'file, record, paperwork, data'],
      [41, 'participate',            '(動) /pɑːrtísəpèɪt/', '参加する',             'Ch 3, 4',                 'participate in a meeting（会議に参加する）',  'take part in, join, attend'],
      [42, 'reception',              '(名) /rɪsépʃən/',    '受付、歓迎',            'Ch 2',                    'reception desk（受付）',                    'front desk, welcome'],
      [43, 'annual',                 '(形) /ǽnjuəl/',      '年1回の、例年の',       'Ch 3',                    'annual leave（年次休暇）',                  'yearly, once a year'],
      [44, 'corporate',              '(形) /kɔ́ːrpərət/',  '企業の',               'Ch 1, 2',                 'corporate culture（企業文化）',              'business, company, organizational'],
      [45, 'draft',                  '(名/動) /drǽft/',    '下書き、草案',          'Ch 14',                   'draft an email（メールの下書きをする）',      'outline, rough copy'],
      [46, 'wrap up',                '(動) /rǽp ʌ́p/',     '締めくくる',            'Ch 15',                   'wrap up the meeting（会議を終える）',         'finish, complete, conclude'],
      [47, 'long-term',              '(形) /lɔ́ŋtə́ːrm/',  '長期的な',             'Ch 15🎧',                 'long-term vision（長期的なビジョン）',        'extended, lasting, future'],
      [48, 'article',                '(名) /άːrtɪkl/',     '記事、品物',            'Ch 4, 7',                 'newspaper article（新聞記事）',              'story, report, piece'],
      [49, 'detail',                 '(名) /díːteɪl/',     '詳細',                  'Ch 6, 8',                 'in detail（詳細に）',                        'specifics, particulars, facts'],
      [50, 'environment',            '(名) /ɪnvάɪrənmənt/', '環境',                 'Ch 2, 12',                'work environment（職場環境）',               'setting, surroundings, atmosphere'],
      [51, 'share',                  '(動) /ʃéər/',        '共有する',              'Ch 10, 11🎧',             'share the screen（画面を共有する）',          'distribute, split, communicate'],
      [52, 'officially',             '(副) /əfíʃəli/',     '正式に',               'Ch 1, 12',                'officially announce（正式に発表する）',       'formally, authorized, legally'],
      [53, 'presentation',           '(名) /prèzəntéɪʃən/', 'プレゼン',             'Ch 3, 15🎧',              'give a presentation（プレゼンを行う）',       'speech, talk, demonstration'],
      [54, 'headquarter',            '(名) /hédkwɔ̀ːrtərz/', '本社、本部',          'Ch 1',                    'corporate headquarters（本社）',             'head office, main office'],
    ],
  ],
  [
    'icon'  => '💡',
    'title' => '3. 顧客対応・問題解決 (Customer Service & Problem Solving)',
    'items' => [
      [55, 'overwhelmed',            '(形) /òʊvərhwélmd/', '圧倒された',            'Ch 2, 4🎧, 6',            'feel overwhelmed（圧倒される）',             'swamped, overloaded, stressed'],
      [56, 'struggle',               '(動/名) /strʌ́gl/',   '苦労する、奮闘',        'Ch 6🎧, 13',              'struggle to understand（理解に苦労する）',   'have a hard time, face difficulty'],
      [57, 'responsibility',         '(名) /rɪspὰnsəbíləti/', '責任',               'Ch 5, 14🎧, 15',          'take full responsibility（全責任を負う）',   'duty, task, obligation'],
      [58, 'apologize / apology',    '(動/名) /əpάlədʒὰɪz/', '謝罪する',            'Ch 5, 14🎧',              'sincerely apologize（心から謝罪する）',       'express regret, say sorry'],
      [59, 'prevent recurrence',     '(動/名) /prɪvént/',  '再発防止する',          'Ch 14🎧, 15',             'prevent recurrence（再発を防止する）',        'stop, avoid / happening again'],
      [60, 'oversight / error',      '(名) /óʊvərsὰɪt/',  '見落とし / ミス',       'Ch 14🎧, 15',             'due to an oversight（見落としにより）',       'mistake, slip, careless fault'],
      [61, 'confirm / confirmation', '(動/名) /kənfə́ːrm/', '確認する',             'Ch 6, 14',                'confirmation email（確認メール）',           'verify, make sure, validate'],
      [62, 'clarify / clarification','(動/名) /klǽrəfὰɪ/', '明確にする',           'Ch 4, 13🎧',              'ask for clarification（明確な説明を求める）', 'explain, make clear, specify'],
      [63, 'solution',               '(名) /səlúːʃən/',    '解決策、解決法',        'Ch 9, 14🎧',              'find a solution（解決策を見つける）',         'answer, fix, resolution, remedy'],
      [64, 'inquiry',                '(名) /ɪnkwάɪəri/',   '問い合わせ、質問',      'Ch 4, 13',                'make an inquiry（問い合わせをする）',         'question, request for info'],
      [65, 'resolve',                '(動) /rɪzάlv/',      '解決する',              'Ch 14, 15',               'resolve an issue（問題を解決する）',          'fix, settle, solve, deal with'],
      [66, 'negotiate',              '(動) /nɪgóʊʃièɪt/',  '交渉する',              'Ch 14, 15🎧',             'negotiate a contract（契約を交渉する）',      'discuss terms, bargain'],
      [67, 'satisfaction',           '(名) /sætisfǽkʃən/', '満足',                  'Ch 10, 15🎧',             'customer satisfaction（顧客満足度）',         'pleasure, contentment, approval'],
      [68, 'urgent',                 '(形) /ə́ːrdʒənt/',   '緊急の、急ぎの',        'Ch 5, 14',                'urgent request（緊急の依頼）',               'pressing, immediate, critical'],
      [69, 'complaint',              '(名) /kəmpléɪnt/',   '苦情、クレーム',        'Ch 9, 14',                'file a complaint（苦情を申し立てる）',        'grievance, criticism, objection'],
      [70, 'investigate / look into','(動) /ɪnvéstəgèɪt/', '調査する',             'Ch 10, 11, 14',           'look into the matter（その件を調査する）',    'examine, check into, explore'],
      [71, 'handle',                 '(動) /hǽndl/',       '対処する',              'Ch 5, 14🎧',              'handle a complaint（苦情に対処する）',        'manage, deal with, take care of'],
      [72, 'crisis',                 '(名) /krάɪsɪs/',     '危機',                  'Ch 14',                   'face a crisis（危機に直面する）',             'emergency, disaster, trouble'],
      [73, 'recovery',               '(名) /rɪkʌ́vəri/',   '回復、取り戻すこと',    'Ch 14🎧',                 'quick recovery（迅速なリカバリー）',          'retrieval, improvement, comeback'],
      [74, 'request',                '(動/名) /rɪkwést/',  '要請する、リクエスト',  'Ch 11, 12',               'upon request（要望に応じて）',               'ask for, demand, appeal'],
      [75, 'search',                 '(動/名) /sə́ːrtʃ/',   '探す、捜索',            'Ch 5, 12',                'search for the item（品物を探す）',           'look for, seek, hunt'],
      [76, 'calm',                   '(形/動) /kάːm/',     '冷静な、落ち着かせる',  'Ch 5',                    'stay calm（冷静さを保つ）',                  'relaxed, peaceful, composed'],
      [77, 'relieved',               '(形) /rɪlíːvd/',     'ほっとした',            'Ch 5, 14🎧',              'feel relieved（ほっとする）',                'comforted, reassured, glad'],
      [78, 'attention',              '(名) /əténʃən/',     '注意、注目',            'Ch 12🎧',                 'pay attention to（～に注意を払う）',          'focus, notice, awareness'],
      [79, 'feedback',               '(名) /fíːdbæ̀k/',    'フィードバック',        'Ch 11',                   'positive feedback（肯定的な意見）',           'response, comments, review'],
      [80, 'response',               '(名) /rɪspάns/',     '返答、対応',            'Ch 11, 14',               'quick response（迅速な対応）',               'answer, reply, reaction'],
      [81, 'replace',                '(動) /rɪpléɪs/',     '取り替える、交換する',  'Ch 1, 5',                 'replace the item（品物を交換する）',          'substitute, change, exchange'],
    ],
  ],
  [
    'icon'  => '💰',
    'title' => '4. 財務・条件・専門用語 (Finance, Terms & Logistics)',
    'items' => [
      [82,  'budget',                '(名) /bʌ́dʒɪt/',     '予算',                  'Ch 6, 7, 15🎧',           'within the budget（予算内で）',              'financial plan, funds, limit'],
      [83,  'quote / estimate',      '(名) /kwóʊt/',       '見積もり',              'Ch 6🎧, 7, 10',           'price quote（価格見積もり）',                'price offer, cost estimate, pricing'],
      [84,  'charge / fee',          '(名) /tʃάːrdʒ/',     '料金 / 手数料',         'Ch 6, 7',                 'cancellation fee（キャンセル料）',            'cost, price, rate, payment'],
      [85,  'refund / non-refundable','(名/形) /ríːfʌnd/',  '返金',                  'Ch 6🎧, 9, 12',           'full refund（全額返金）',                    'repayment, money back'],
      [86,  'currency / exchange',   '(名) /kə́ːrənsi/',   '通貨 / 両替',           'Ch 6, 9, 12',             'currency exchange（両替所）',                'money, cash / swap, trade'],
      [87,  'expense / cost',        '(名) /ɪkspéns/',     '費用 / コスト',         'Ch 6🎧, 14',              'travel expenses（出張経費）',                'expenditure, spending, outlay'],
      [88,  'requirement',           '(名) /rɪkwάɪərmənt/', '必要条件',             'Ch 6, 10',                'meet the requirements（要件を満たす）',       'necessity, condition, prerequisite'],
      [89,  'policy',                '(名) /pάləsi/',      '規定、方針',            'Ch 7, 12',                'company policy（社内規定）',                 'rules, regulations, guidelines'],
      [90,  'valid',                 '(形) /vǽlɪd/',       '有効な',               'Ch 6, 7',                 'a valid passport（有効なパスポート）',        'effective, current, in force'],
      [91,  'inclusive',             '(形) /ɪnklúːsɪv/',   '全て含んだ',            'Ch 6🎧, 14',              'tax inclusive（税込みの）',                  'comprehensive, including all'],
      [92,  'exclusive',             '(形) /ɪksklúːsɪv/',  '高級な、含まない',      'Ch 6🎧, 14',              'exclusive interview（独占インタビュー）',     'luxury, private, not including'],
      [93,  'compensate',            '(動) /kάmpənsèɪt/',  '補償する',              'Ch 9, 14',                'compensate for the loss（損失を補償する）',   'make up for, reimburse, repay'],
      [94,  'rate',                  '(名) /réɪt/',        '割合、料金、レート',    'Ch 6',                    'exchange rate（為替レート）',                'price, pace, percentage'],
      [95,  'measure',               '(動/名) /méʒər/',    '測る、対策',            'Ch 7',                    'safety measure（安全対策）',                 'calculate, weigh / step, action'],
      [96,  'strict',                '(形) /stríkt/',      '厳しい',               'Ch 7',                    'strict rules（厳しい規則）',                 'rigid, stern, rigorous'],
      [97,  'compare',               '(動) /kəmpéər/',     '比較する',              'Ch 6',                    'compare prices（価格を比較する）',            'contrast, evaluate against'],
      [98,  'figure',                '(名) /fígjər/',      '数字、人物',            'Ch 11🎧',                 'sales figures（売上数字）',                  'number, amount, individual'],
      [99,  'standard',              '(形/名) /stǽndərd/', '標準、基準',            'Ch 12, 13',               'standard shipping（通常配送）',              'normal, regular, baseline'],
      [100, 'admission',             '(名) /ədmíʃən/',     '入場（料）',            'Ch 4',                    'free admission（入場無料）',                 'entrance fee, entry, access'],
      [101, 'evaluate',              '(動) /ɪvǽljuèɪt/',  '評価する',              'Ch 11🎧',                 'evaluate performance（成績を評価する）',      'assess, judge, appraise'],
      [102, 'property',              '(名) /prάpərti/',    '不動産、所有物',        'Ch 8',                    'commercial property（商業用不動産）',         'real estate, building, belongings'],
      [103, 'capacity',              '(名) /kəpǽsəti/',    '容量、収容能力',        'Ch 7',                    'seating capacity（座席数）',                 'volume, size, role'],
      [104, 'inventory',             '(名) /ínvəntɔ̀ːri/', '在庫、棚卸し',          'Ch 14',                   'inventory check（在庫確認）',                'stock, supply, goods'],
      [105, 'condition',             '(名) /kəndíʃən/',    '状態、条件',            'Ch 6',                    'working conditions（労働条件）',              'state, terms, situation'],
    ],
  ],
  [
    'icon'  => '🧠',
    'title' => '5. 評価・マインドセット・歴史 (Qualities & Identity)',
    'items' => [
      [106, 'authentic',             '(形) /ɔːθéntɪk/',   '本物の',               'Ch 10🎧, 11, 15🎧',       'authentic experience（本物の体験）',          'real, genuine, true, original'],
      [107, 'artifact',              '(名) /άːrtəfækt/',   '遺物、工芸品',          'Ch 10🎧, 11, 14',         'historical artifact（歴史的な遺物）',         'historical object, ancient item, relic'],
      [108, 'descendant / successor','(名) /dɪséndənt/',   '子孫',                  'Ch 10, 11',               'chosen successor（選ばれし後継者）',          'offspring, heir / next in line'],
      [109, 'proactive',             '(形) /proʊǽktɪv/',   '積極的な',             'Ch 4, 5, 9',              'proactive approach（積極的なアプローチ）',    'self-starting, taking initiative'],
      [110, 'professionalism',       '(名) /prəféʃənəlìzəm/', 'プロ意識',           'Ch 5, 8, 14🎧',           'with great professionalism（見事なプロ意識で）', 'competence, expertise, skill'],
      [111, 'adapt / adaptability',  '(動/名) /ədǽpt/',    '適応する',              'Ch 2, 11, 13',            'adapt to a new culture（新しい文化に適応する）', 'adjust, get used to / flexibility'],
      [112, 'dedication',            '(名) /dèdɪkéɪʃən/',  '献身、熱意',            'Ch 15🎧',                 'dedication to work（仕事への献身）',          'commitment, hard work, devotion'],
      [113, 'vision',                '(名) /víʒən/',       '展望、ビジョン',        'Ch 15🎧',                 'long-term vision（長期的なビジョン）',        'long-term plan, foresight, goal'],
      [114, 'extraordinary',         '(形) /ɪkstrɔ́ːrdənèri/', '驚くべき',          'Ch 11, 15',               'extraordinary experience（並外れた経験）',    'amazing, exceptional, remarkable'],
      [115, 'genuine',               '(形) /dʒénjuɪn/',    '本物の、心からの',      'Ch 11, 15🎧',             'genuine care（心からの配慮）',               'real, sincere, honest'],
      [116, 'confident',             '(形) /kάnfədənt/',   '自信がある',            'Ch 4, 9, 11',             'feel confident（自信を感じる）',              'assured, certain, self-reliant'],
      [117, 'fluent',                '(形) /flúːənt/',     '流暢な',               'Ch 8🎧, 9',               'fluent in English（英語が流暢な）',           'articulate, smooth-spoken'],
      [118, 'anxious',               '(形) /ǽŋkʃəs/',      '心配して、切望して',    'Ch 8, 12',                'anxious about the result（結果を心配して）',  'nervous, worried, eager'],
      [119, 'patient',               '(形) /péɪʃənt/',     '忍耐強い',             'Ch 8, 13🎧',              'be patient with ~（～に対して忍耐強くある）',  'tolerant, understanding, calm'],
      [120, 'universal',             '(形) /jùːnəvə́ːrsəl/', '世界共通の',          'Ch 8, 13🎧',              'universal language（世界共通語）',            'global, worldwide, general'],
      [121, 'exhausted',             '(形) /ɪgzɔ́ːstɪd/',  '疲れ果てた',           'Ch 1, 14🎧',              'feel exhausted（疲れ果てる）',               'extremely tired, worn out'],
      [122, 'energetic',             '(形) /ènərdʒétɪk/',  '活気のある',           'Ch 2',                    'energetic atmosphere（活気ある雰囲気）',      'active, lively, dynamic'],
      [123, 'diverse',               '(形) /dɪvə́ːrs/',    '多様な',               'Ch 13',                   'diverse backgrounds（多様な背景）',           'various, different, varied'],
      [124, 'historical',            '(形) /hɪstɔ́ːrɪkəl/', '歴史的な',            'Ch 1, 10',                'historical building（歴史的建造物）',         'classic, traditional, old'],
      [125, 'creative',              '(形) /kriéɪtɪv/',    '創造的な',              'Ch 4',                    'creative thinking（創造的な思考）',           'inventive, innovative, imaginative'],
      [126, 'courage',               '(名) /kə́ːrɪdʒ/',    '勇気',                  'Ch 11, 13',               'have the courage to（～する勇気がある）',     'bravery, boldness, nerve'],
      [127, 'philosophy',            '(名) /fəlάsəfi/',    '哲学、理念',            'Ch 15',                   'company philosophy（企業理念）',              'belief, principles, ideology'],
      [128, 'familiar',              '(形) /fəmíljər/',    '見慣れた、おなじみの',  'Ch 7, 12, 13🎧',          'look familiar（見覚えがある）',              'recognizable, known, common'],
      [129, 'global',                '(形) /glóʊbəl/',     '世界的な',             'Ch 13',                   'global market（グローバル市場）',             'worldwide, international'],
      [130, 'iconic',                '(形) /aɪkάnɪk/',     '象徴的な',             'Ch 1, 13',                'iconic landmark（象徴的なランドマーク）',     'symbolic, famous, legendary'],
    ],
  ],
  [
    'icon'  => '⚙️',
    'title' => '6. コアビジネス動詞・形容詞 (Core Business Actions & Descriptors)',
    'items' => [
      [131, 'state-of-the-art',      '(形) /stéɪt əv ði άːrt/', '最新式の',         'Ch 2',                    'state-of-the-art facility（最先端の施設）',   'cutting-edge, latest, most modern'],
      [132, 'propose / proposal',    '(動/名) /prəpóʊz/',  '提案する',              'Ch 3, 10',                'submit a proposal（提案書を提出する）',       'suggest, put forward / offer'],
      [133, 'restriction',           '(名) /rɪstríkʃən/',  '制限、制約',            'Ch 4',                    'lifting of restrictions（制限の解除）',       'limit, constraint, rule'],
      [134, 'accessible',            '(形) /æksésəbl/',    'アクセスできる',        'Ch 4',                    'easily accessible（簡単にアクセスできる）',   'available, reachable, open to all'],
      [135, 'expert / expertise',    '(名) /ékspərt/',     '専門家',               'Ch 5, 14',                'financial expert（財務の専門家）',            'specialist, professional'],
      [136, 'interpret',             '(動) /ɪntə́ːrprət/', '通訳する、解釈する',    'Ch 5',                    'interpret the data（データを解釈する）',       'translate, explain, understand'],
      [137, 'conduct',               '(動) /kəndʌ́kt/',    '行う、実施する',        'Ch 8',                    'conduct a survey（アンケート調査を実施する）', 'carry out, perform, direct'],
      [138, 'display',               '(動/名) /dɪspléɪ/',  '表示する、展示する',    'Ch 5, 9, 13',             'on display（展示されている）',               'show, exhibit, present'],
      [139, 'praise',                '(動/名) /préɪz/',    '褒める、称賛する',      'Ch 9🎧, 14',              'highly praised（高く評価される）',            'commend, compliment, approve'],
      [140, 'review',                '(動/名) /rɪvjúː/',   '見直す、再調査する',    'Ch 7, 10, 12🎧',          'review the document（書類を見直す）',         'examine, check, go over'],
      [141, 'connect / connection',  '(動/名) /kənékt/',   '繋ぐ',                 'Ch 8, 11',                'strong connection（強い繋がり）',             'link, join, attach / relationship'],
      [142, 'emphasize',             '(動) /émfəsὰɪz/',    '強調する',              'Ch 13',                   'emphasize the importance（重要性を強調する）', 'stress, highlight, underline'],
      [143, 'convince',              '(動) /kənvíns/',     '説得する、納得させる',  'Ch 14',                   'convince the client（顧客を説得する）',       'persuade, win over, satisfy'],
      [144, 'automatic',             '(形) /ɔ̀ːtəmǽtɪk/',  '自動の',               'Ch 14',                   'automatic renewal（自動更新）',              'self-operating, mechanized'],
      [145, 'divide',                '(動) /dɪvάɪd/',      '分割する、分ける',      'Ch 15',                   'divide into groups（グループに分ける）',      'separate, split, part'],
      [146, 'applaud',               '(動) /əplɔ́ːd/',     '拍手する、称賛する',    'Ch 15',                   'applaud the decision（その決定を称賛する）',  'clap, praise, commend'],
      [147, 'ensure',                '(動) /ɪnʃʊ́ər/',     '確実にする、保証する',  'Ch 10, 15🎧',             'ensure safety（安全を確保する）',            'make sure, guarantee, secure'],
      [148, 'smooth / smoothly',     '(形/副) /smúːð/',    'スムーズに',            'Ch 1',                    'run smoothly（スムーズに進行する）',          'seamlessly, easily, flat'],
      [149, 'massive',               '(形) /mǽsɪv/',       '巨大な',               'Ch 12',                   'massive building（巨大な建物）',              'huge, enormous, large'],
      [150, 'exactly',               '(副) /ɪgzǽktli/',    '正確に、まさに',        'Ch 10, 15',               'match exactly（完全に一致する）',             'precisely, accurately, perfectly'],
    ],
  ],
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>スパイラル学習語彙リスト | Momo's London</title>
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
    font-size: 1.05rem;
    line-height: 1.8;
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
  .page-hero { text-align: center; padding: 3rem 2rem 2.5rem; border-bottom: 1px solid rgba(184,150,12,.08); overflow: hidden; }
  .plabel { display: inline-block; font-size: .72rem; letter-spacing: .3em; text-transform: uppercase; background: rgba(184,150,12,.12); border: 1px solid rgba(184,150,12,.28); color: var(--gold); padding: .2rem .75rem; border-radius: 999px; margin-bottom: .9rem; animation: popIn 0.55s 1.2s cubic-bezier(0.34,1.56,0.64,1) both; }
  @keyframes popIn { from { opacity:0; transform:scale(0.6) translateY(8px); } to { opacity:1; transform:scale(1) translateY(0); } }
  .page-hero h1 { font-family: 'Playfair Display', serif; font-size: clamp(1.4rem,3vw,2.2rem); font-weight: 700; line-height: 1.25; }
  .page-hero h1 .word { display: inline-block; animation: wRise 0.55s cubic-bezier(0.22,1,0.36,1) both; }
  @keyframes wRise { from { opacity:0; transform:translateY(50px) rotate(1.5deg); } to { opacity:1; transform:translateY(0) rotate(0); } }
  .ch-rule { display: flex; align-items: center; justify-content: center; gap: 1rem; margin: 1rem auto .5rem; animation: fsu 0.5s 2.0s ease both; }
  .ch-rule span { width: 60px; height: 1px; }
  .ch-rule span:first-child { background: linear-gradient(to right, transparent, var(--gold)); }
  .ch-rule span:last-child  { background: linear-gradient(to left,  transparent, var(--gold)); }
  .ch-rule .d { width: 7px; height: 7px; background: var(--gold); transform: rotate(45deg); }
  .hero-meta { color: var(--fog); font-size: .88rem; animation: fsu 0.5s 2.1s ease both; }

  /* ── REVEAL ── */
  .reveal { opacity:0; transform:translateY(22px); transition: opacity 0.6s ease, transform 0.6s cubic-bezier(0.22,1,0.36,1); }
  .reveal.visible { opacity:1; transform:translateY(0); }

  /* ── LAYOUT ── */
  .page-body { max-width: 1060px; margin: 0 auto; padding: 2rem 1.5rem 5rem; }

  /* ── INTRO TEXT ── */
  .intro-text { color: var(--mist); margin-bottom: 1rem; max-width: 820px; }
  .intro-text strong { color: var(--gold); }

  /* ── STATS ROW ── */
  .stats-row { display: flex; gap: 1rem; margin: 1.2rem 0 2rem; flex-wrap: wrap; }
  .stat-pill { background: rgba(184,150,12,.1); border: 1px solid rgba(184,150,12,.25); border-radius: 999px; padding: .3rem .9rem; font-size: .82rem; color: var(--gold); white-space: nowrap; }

  /* ── ACCORDION ── */
  .acc-wrap { display: flex; flex-direction: column; gap: .7rem; }
  .acc-item { border: 1px solid var(--border); border-radius: 3px; overflow: hidden; }
  .acc-trigger {
    width: 100%; background: rgba(184,150,12,.06);
    border: none; cursor: pointer;
    display: flex; align-items: center; gap: 1rem;
    padding: .9rem 1.3rem;
    font-family: 'Playfair Display', serif;
    text-align: left; transition: background .2s;
  }
  .acc-trigger:hover { background: rgba(184,150,12,.13); }
  .acc-trigger .t-icon { font-size: 1.2rem; flex-shrink: 0; }
  .acc-trigger .t-title { flex: 1; font-size: 1.05rem; font-weight: 700; color: var(--cream); }
  .acc-trigger .t-count { font-size: .75rem; letter-spacing: .12em; color: var(--fog); flex-shrink: 0; }
  .acc-trigger .t-arr { color: var(--gold); font-size: .9rem; flex-shrink: 0; transition: transform .3s; }
  .acc-trigger.open .t-arr { transform: rotate(180deg); }
  .acc-body { display: none; }
  .acc-body.open { display: block; }

  /* ── TABLE ── */
  .vocab-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
  .vocab-table thead th {
    font-size: .65rem; letter-spacing: .2em; text-transform: uppercase;
    color: var(--fog); border-bottom: 1px solid var(--border);
    padding: .5rem .8rem; text-align: left; background: rgba(255,255,255,.02);
    white-space: nowrap;
  }
  .vocab-table tbody tr { border-bottom: 1px solid rgba(255,255,255,.035); transition: background .15s; }
  .vocab-table tbody tr:last-child { border-bottom: none; }
  .vocab-table tbody tr:hover { background: rgba(255,255,255,.025); }
  .vocab-table td { padding: .55rem .8rem; vertical-align: top; }
  .td-no   { color: rgba(184,150,12,.4); font-size: .8rem; width: 2.2rem; text-align: right; padding-right: 1rem; }
  .td-word { color: var(--cream); font-weight: 500; min-width: 130px; }
  .td-word .pron { display: block; font-size: .76rem; color: var(--fog); font-weight: 400; }
  .td-mean { color: var(--mist); min-width: 100px; }
  .td-ch   { color: var(--teal); font-size: .82rem; min-width: 120px; }
  .td-col  { color: var(--fog); font-size: .82rem; min-width: 160px; }
  .td-col strong { color: var(--mist); }
  .td-syn  { color: rgba(76,175,133,.75); font-size: .8rem; }
  .listening-badge { font-size: .65rem; color: #5bc4d8; }

  /* ── FOOTER ── */
  footer { border-top: 1px solid rgba(184,150,12,.1); text-align: center; padding: 1.5rem; font-size: .77rem; letter-spacing: .12em; color: var(--fog); }

  @media (max-width: 700px) {
    header { padding: 1rem 1.2rem; }
    .page-body { padding: 1.5rem .8rem 4rem; }
    .td-col, .td-syn { display: none; }
  }
</style>
</head>
<body>

<div id="curtain" aria-hidden="true">
  <div class="c-icon">📈</div>
  <div class="c-line"></div>
  <div class="c-label">Spiral Vocabulary</div>
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
    <?php
    $words = ['一度覚えた語彙を', '再度使っているリスト'];
    foreach ($words as $i => $w) {
        $delay = 1.3 + $i * 0.18;
        echo '<span class="word" style="animation-delay:' . $delay . 's">' . htmlspecialchars($w) . '</span> ';
    }
    ?>
  </h1>
  <div class="ch-rule"><span></span><div class="d"></div><span></span></div>
  <p class="hero-meta">スパイラル学習（Spiral Learning）— TOEIC頻出語彙 150語</p>
</div>

<div class="page-body">

  <p class="intro-text reveal">
    TOEIC頻出語彙や重要表現が、章をまたいで意図的に何度も登場（リサイクル）するように設計されており、自然な反復学習（スパイラル学習）が可能です。本書の語彙設計は、単なる「単語の出現」ではなく、<strong>「ビジネスの文脈（実務）」と「物語の謎解き（ミステリー）」の両輪</strong>で単語を再登場させることで、学習者の記憶に深く刻み込むように作られています。
  </p>

  <div class="stats-row reveal">
    <span class="stat-pill">📊 全150語収録</span>
    <span class="stat-pill">📂 6カテゴリ</span>
    <span class="stat-pill">🎧 リスニング登場語あり</span>
    <span class="stat-pill">📝 TOEIC頻出コロケーション付き</span>
    <span class="stat-pill">🔄 類義語（パラフレーズ）付き</span>
  </div>

  <!-- ── アコーディオン ── -->
  <div class="acc-wrap">
  <?php foreach ($categories as $ci => $cat): ?>
    <div class="acc-item reveal">
      <button class="acc-trigger" id="acc-btn-<?= $ci ?>" onclick="toggleAcc(<?= $ci ?>)" type="button">
        <span class="t-icon"><?= $cat['icon'] ?></span>
        <span class="t-title"><?= htmlspecialchars($cat['title']) ?></span>
        <span class="t-count"><?= count($cat['items']) ?>語</span>
        <span class="t-arr">▼</span>
      </button>
      <div class="acc-body" id="acc-body-<?= $ci ?>">
        <table class="vocab-table">
          <thead>
            <tr>
              <th class="td-no">#</th>
              <th>語彙 / 発音</th>
              <th>意味</th>
              <th>登場 Chapter</th>
              <th>TOEIC頻出コロケーション</th>
              <th>類義語・言い換え</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach ($cat['items'] as $item):
            [$no, $word, $pron, $mean, $chapters, $collocation, $synonym] = $item;
            // 🎧 をspanに変換
            $chapters_html = str_replace('🎧', '<span class="listening-badge">🎧</span>', htmlspecialchars($chapters));
          ?>
            <tr>
              <td class="td-no"><?= $no ?></td>
              <td class="td-word">
                <?= htmlspecialchars($word) ?>
                <span class="pron"><?= htmlspecialchars($pron) ?></span>
              </td>
              <td class="td-mean"><?= htmlspecialchars($mean) ?></td>
              <td class="td-ch"><?= $chapters_html ?></td>
              <td class="td-col"><strong><?= htmlspecialchars($collocation) ?></strong></td>
              <td class="td-syn"><?= htmlspecialchars($synonym) ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endforeach; ?>
  </div>

</div>

<footer>&copy; <?= date('Y') ?> Momo's London &nbsp;·&nbsp; All Rights Reserved</footer>

<script>
function toggleAcc(i) {
  const btn  = document.getElementById('acc-btn-'  + i);
  const body = document.getElementById('acc-body-' + i);
  const open = body.classList.toggle('open');
  btn.classList.toggle('open', open);
}

(function () {
  const els = document.querySelectorAll('.reveal');
  if (!('IntersectionObserver' in window)) { els.forEach(el => el.classList.add('visible')); return; }
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
  }, { threshold: 0.06, rootMargin: '0px 0px -20px 0px' });
  els.forEach(el => obs.observe(el));
})();
document.getElementById('curtain').addEventListener('animationend', function () { this.style.display = 'none'; });
</script>
</body>
</html>
