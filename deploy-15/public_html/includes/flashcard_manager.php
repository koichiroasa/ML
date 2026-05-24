<?php
/**
 * FlashcardManager - フラッシュカード進捗管理
 * 
 * 機能:
 * - CSV から単語データを読み込み
 * - ユーザーごと・CSV毎にJSONで進捗を保存
 * - 統一データ構造で「順番通り」「ランダム」「要復習のみ」を管理
 * - 前回の続きからの学習に対応
 * - 覚えたか要復習かの状態を保持
 * 
 * 保存先: momos_data/flashcard/{user_id}_{csv_name}.json
 */

class FlashcardManager {
    private $user_id;
    private $csv_filename;
    private $csv_path;
    private $json_file;

    public function __construct(string $user_id, string $csv_filename, string $csv_dir = null, string $data_dir = null) {
        $this->user_id = preg_replace('/[^a-zA-Z0-9_]/', '_', $user_id);
        $this->csv_filename = $csv_filename;

        // パス設定
        if ($csv_dir === null) {
            $csv_dir = dirname(dirname(__FILE__)) . '/flashcard';
        }
        if ($data_dir === null) {
            // momos_data/flashcard/ にまとめて保存
            $data_dir = dirname(dirname(dirname(__FILE__))) . '/momos_data/flashcard';
        }

        $this->csv_path = $csv_dir . '/' . basename($csv_filename);
        
        $csv_key = preg_replace('/[^a-zA-Z0-9_]/', '_', pathinfo($csv_filename, PATHINFO_FILENAME));
        $this->json_file = $data_dir . '/' . $this->user_id . '_' . $csv_key . '.json';

        // flashcard ディレクトリが存在しなければ作成
        if (!is_dir($data_dir)) {
            @mkdir($data_dir, 0755, true);
        }
    }

    /**
     * JSONデータを読み込む
     * なければ新規作成
     */
    public function loadData(): array {
        if (file_exists($this->json_file)) {
            $data = json_decode(file_get_contents($this->json_file), true);
            if ($data && is_array($data)) {
                // マイグレーション: 古い形式から新しい形式へ
                return $this->migrateOldFormat($data);
            }
        }

        // 新規作成
        return $this->initializeData();
    }

    /**
     * 古い形式のデータを新しい形式に変換
     * { "word": { "status": "ok|ng", "updated": "..." } }
     *   ↓
     * { "cards": [ {...}, ... ], "review_queue": [...] }
     */
    private function migrateOldFormat(array $data): array {
        // 既に新形式か確認（'cards' キーがあるか）
        if (isset($data['cards']) && is_array($data['cards'])) {
            return $data;
        }

        // 古い形式: "word" => { "status": "ok|ng", "updated": "..." }
        $cards = [];
        $review_queue = [];

        foreach ($this->getVocabFromCSV() as $index => $vocab) {
            $card = [
                'word' => $vocab['word'],
                'meaning' => $vocab['meaning'],
                'chunk' => $vocab['chunk'],
                'learned' => false,
                'need_review' => false,
                'review_count' => 0,
                'last_reviewed' => null
            ];

            // 古いデータから status を引き継ぐ
            if (isset($data[$vocab['word']])) {
                $old_status = $data[$vocab['word']]['status'];
                if ($old_status === 'ok') {
                    $card['learned'] = true;
                    $card['need_review'] = false;
                } elseif ($old_status === 'ng') {
                    $card['learned'] = false;
                    $card['need_review'] = true;
                    $review_queue[] = $vocab['word'];
                }
                $card['last_reviewed'] = $data[$vocab['word']]['updated'] ?? null;
                $card['review_count'] = 1;
            }

            $cards[] = $card;
        }

        return [
            'csv_filename' => $this->csv_filename,
            'last_accessed' => date('c'),
            'last_mode' => 'order',
            'last_index' => 0,
            'total_cards' => count($cards),
            'cards' => $cards,
            'review_queue' => $review_queue
        ];
    }

    /**
     * CSVから初期データを作成
     */
    private function initializeData(): array {
        $cards = [];
        
        foreach ($this->getVocabFromCSV() as $vocab) {
            $cards[] = [
                'word' => $vocab['word'],
                'meaning' => $vocab['meaning'],
                'chunk' => $vocab['chunk'],
                'learned' => false,
                'need_review' => false,
                'review_count' => 0,
                'last_reviewed' => null
            ];
        }

        return [
            'csv_filename' => $this->csv_filename,
            'last_accessed' => date('c'),
            'last_mode' => 'order',
            'last_index' => 0,
            'total_cards' => count($cards),
            'cards' => $cards,
            'review_queue' => []
        ];
    }

    /**
     * CSVをパース
     */
    private function getVocabFromCSV(): array {
        $vocab = [];

        if (!file_exists($this->csv_path)) {
            return $vocab;
        }

        if (($fh = fopen($this->csv_path, 'r')) === false) {
            return $vocab;
        }

        // BOM除去
        $bom = fread($fh, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($fh);
        }

        while (($row = fgetcsv($fh)) !== false) {
            if (count($row) < 2 || trim($row[0]) === '') {
                continue;
            }

            $vocab[] = [
                'word' => trim($row[0]),
                'meaning' => trim($row[1] ?? ''),
                'chunk' => trim($row[2] ?? '')
            ];
        }

        fclose($fh);
        return $vocab;
    }

    /**
     * データを保存
     */
    public function saveData(array $data): bool {
        $data['last_accessed'] = date('c');
        
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        return file_put_contents($this->json_file, $json, LOCK_EX) !== false;
    }

    /**
     * 単語を「覚えた」に更新
     */
    public function markAsLearned(string $word, array $data): array {
        $data = $this->updateCard($word, [
            'learned' => true,
            'need_review' => false,
            'last_reviewed' => date('c')
        ], $data);

        // 要復習リストから削除
        $data['review_queue'] = array_filter(
            $data['review_queue'],
            fn($w) => $w !== $word
        );

        return $data;
    }

    /**
     * 単語を「要復習」に更新
     */
    public function markAsReview(string $word, array $data): array {
        $data = $this->updateCard($word, [
            'learned' => false,
            'need_review' => true,
            'last_reviewed' => date('c')
        ], $data);

        // 要復習リストに追加（重複チェック）
        if (!in_array($word, $data['review_queue'], true)) {
            $data['review_queue'][] = $word;
        }

        return $data;
    }

    /**
     * カード情報を更新（内部用）
     */
    private function updateCard(string $word, array $updates, array $data): array {
        foreach ($data['cards'] as &$card) {
            if ($card['word'] === $word) {
                $card = array_merge($card, $updates);
                $card['review_count'] = ($card['review_count'] ?? 0) + 1;
                break;
            }
        }
        return $data;
    }

    /**
     * 学習方法に応じてカード一覧を取得
     * @param string $mode 'order' | 'random' | 'review_only'
     */
    public function getCards(array $data, string $mode = 'order'): array {
        $cards = $data['cards'];

        // 要復習のみモード
        if ($mode === 'review_only') {
            $review_words = array_flip($data['review_queue']);
            $cards = array_filter($cards, fn($c) => isset($review_words[$c['word']]));
        }

        // ランダムモード
        if ($mode === 'random') {
            shuffle($cards);
        }

        return array_values($cards);
    }

    /**
     * 統計情報を取得
     */
    public function getStats(array $data): array {
        $total = count($data['cards']);
        $learned = count(array_filter($data['cards'], fn($c) => $c['learned']));
        $review_count = count($data['review_queue']);

        return [
            'total' => $total,
            'learned' => $learned,
            'unlearned' => $total - $learned,
            'review_count' => $review_count,
            'progress' => $total > 0 ? round(($learned / $total) * 100, 1) : 0
        ];
    }

    /**
     * 進捗をリセット
     */
    public function resetProgress(array $data): array {
        return [
            'csv_filename' => $data['csv_filename'],
            'last_accessed' => date('c'),
            'last_mode' => 'order',
            'last_index' => 0,
            'total_cards' => count($data['cards']),
            'cards' => array_map(fn($c) => array_merge($c, [
                'learned' => false,
                'need_review' => false,
                'review_count' => 0,
                'last_reviewed' => null
            ]), $data['cards']),
            'review_queue' => []
        ];
    }

    /**
     * 前回のセッション情報を取得
     */
    public function getLastSession(array $data): array {
        return [
            'mode' => $data['last_mode'] ?? 'order',
            'index' => $data['last_index'] ?? 0,
            'accessed' => $data['last_accessed'] ?? null
        ];
    }

    /**
     * 前回のセッション情報を保存
     */
    public function saveLastSession(array $data, string $mode, int $index): array {
        $data['last_mode'] = $mode;
        $data['last_index'] = $index;
        return $data;
    }
}
?>
