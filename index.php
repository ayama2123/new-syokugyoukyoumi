<?php
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

    // 結果解析
    $strong_interests = [];
    $low_interests = [];
    foreach ($responses as $index => $response) {
        if ($response === 'やりたい') {
            $strong_interests[] = $questions[$index];
        } elseif ($response === 'やりたくない') {
            $low_interests[] = $questions[$index];
        }
    }

    // 結果の表示
    echo "<h1>あなたに合う職業の提案</h1>";
    if (count($strong_interests) > 0) {
        echo "<h2>興味が強い分野:</h2><ul>";
        foreach ($strong_interests as $interest) {
            echo "<li>$interest</li>";
        }
        echo "</ul>";
    }
    if (count($low_interests) > 0) {
        echo "<h2>興味が低い分野:</h2><ul>";
        foreach ($low_interests as $interest) {
            echo "<li>$interest</li>";
        }
        echo "</ul>";
    }

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
