<?php
require 'vendor/autoload.php'; // Guzzleを読み込む

use GuzzleHttp\Client;

$client = new Client();

// OpenAI APIキーの設定
$openai_api_key = 'YOUR_OPENAI_API_KEY';

// 質問リスト
$questions = [
    "人と話すことが好きですか？",
    "データの分析が得意ですか？",
    "創造的な作業が好きですか？",
    "新しい技術を学ぶことが好きですか？",
    "リーダーシップを発揮することが好きですか？",
    "細かい作業を集中して行うことが得意ですか？",
    "困難な問題を解決することが好きですか？",
    "チームで働くことが好きですか？",
    "自分のアイデアを発表することが得意ですか？",
    "計画を立てて実行することが得意ですか？"
];

// POSTリクエストの場合、回答を処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $responses = [];
    foreach ($questions as $index => $question) {
        $responses[] = $_POST["q$index"];
    }

    // 回答データを基に診断プロンプトを生成
    $prompt = "以下は職業に関する質問とユーザーの回答です。これに基づいてユーザーの職業興味診断を行ってください。\n\n";
    foreach ($questions as $index => $question) {
        $prompt .= $question . ": " . $responses[$index] . "\n";
    }

    // OpenAI APIへのリクエスト
    $response = $client->post('https://api.openai.com/v1/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $openai_api_key,
            'Content-Type'  => 'application/json',
        ],
        'json' => [
            'model' => 'gpt-4o mini',
            'prompt' => $prompt,
            'max_tokens' => 150,
        ]
    ]);

    $result = json_decode($response->getBody(), true);
    $advice = $result['choices'][0]['text'];

    // 結果を表示
    echo "<h1>あなたに合う職業の提案</h1>";
    echo "<p>$advice</p>";
    echo '<a href="index.php">もう一度</a>';
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>職業適性検査</title>
</head>
<body>
    <h1>職業適性検査</h1>
    <form method="post">
        <?php foreach ($questions as $index => $question): ?>
            <label><strong><?php echo ($index + 1) . ". " . $question; ?></strong></label><br>
            <input type="radio" name="q<?php echo $index; ?>" value="やりたい"> やりたい
            <input type="radio" name="q<?php echo $index; ?>" value="どちらともいえない" checked> どちらともいえない
            <input type="radio" name="q<?php echo $index; ?>" value="やりたくない"> やりたくない
            <br><br>
        <?php endforeach; ?>
        <input type="submit" value="結果を見る">
    </form>
</body>
</html>
