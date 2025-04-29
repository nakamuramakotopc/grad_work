<?php
    // カテゴリ名の配列
    $categoryNames = [
        1 => 'おすすめ',
        2 => '甲冑釣行',
        3 => '水中の戦士',
        4 => 'せっしゃ釣修行中',
        // 他のカテゴリーも追加
    ];

    // GETからカテゴリIDを取得
    $categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;

    // カテゴリ名を取得（存在しない場合はデフォルト）
    $categoryTitle = isset($categoryNames[$categoryId]) ? $categoryNames[$categoryId] : '不明なカテゴリ';

    // CSVファイルの読み込み
    $csvFile = fopen('data/videos.csv', 'r');
    $videos = [];

    if ($csvFile !== false) {
        fgetcsv($csvFile); // ヘッダー行

        $today = new DateTime();
        $oneWeekAgo = (new DateTime())->modify('-7 days');

        while (($row = fgetcsv($csvFile)) !== false) {
            list($videoId, $catId, $imageName, $title, $description, $location, $method, $species, $date, $isPro) = $row;

            if ((int)$catId === $categoryId) {
                $videoDate = new DateTime($date);
                $isNew = $videoDate >= $oneWeekAgo;
                $isProCourse = (int)$isPro === 1;

                $videos[] = [
                    'video_id' => $videoId,
                    'image' => $imageName,
                    'title' => $title,
                    'description' => $description,
                    'is_new' => $isNew,
                    'is_pro' => $isProCourse
                ];
            }
        }

        fclose($csvFile);
    }
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>釣り侍・魚の極み</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body id="top">

    <!-- ヘッダーを読み込む場所 -->
    <header class="wrapper" id="header">
        <!-- ロゴと会員登録・ログインボタン -->
        <div class="header-main">
            <h1 class="logo">
                <a href="index.php">
                    <img src="./img/logo.svg" alt="釣り侍・魚の極み">
                </a>
            </h1>
            <div class="auth-buttons">
                <button class="btn login-btn">ログイン</button>
                <button class="btn register-btn">会員登録</button>
            </div>
        </div>
    
        <!-- ハンバーガーメニュー3本線 -->
        <p class="btn-gnavi">
            <span></span>
            <span></span>
            <span></span>
        </p>
        <!-- ナビゲーションメニュー -->
        <div class="main-nav">
            <div class="auth-buttons">
                <button class="btn login-btn">ログイン</button>
                <button class="btn register-btn">会員登録</button>
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="index.php#new">新着</a></li>
                    <li><a href="index.php#recommend">おすすめ</a></li>
                    <li><a href="index.php#category">カテゴリ</a></li>
                    <li><a href="#how-to-watch">視聴方法</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- カテゴリセクション -->
        <section class="category">
            <h2 class="section-title"><?php echo htmlspecialchars($categoryTitle); ?></h2>
            <div class="wrapper">
                <?php if (count($videos) > 0): ?>
                    <ul class="category-list">
                        <?php foreach ($videos as $video): ?>
                            <li>
                                <a href="video.php?videoId=<?= urlencode($video['video_id']) ?>">
                                    <div class="image-wrapper">
                                        <?php if (!empty($video['is_new'])): ?>
                                            <span class="tag new-tag">NEW</span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($video['is_pro'])): ?>
                                            <span class="tag pro-tag">プロ</span>
                                        <?php endif; ?>
                                        
                                        <img src="./img/<?php echo htmlspecialchars($video['image']); ?>.jpg" alt="<?php echo htmlspecialchars($video['title']); ?>">
                                        <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                    </div>
                                    <p class="text"><?php echo htmlspecialchars($video['title']); ?></p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>このカテゴリには動画がありません。</p>
                <?php endif; ?>
            </div>
        </section>

        <!-- プロモーションセクション -->
        <section class="promotion">
            <p class="promotion-price">月額<span class="promotion-price-amount">550</span>円（税込み）で見放題！</p>
            <div class="promotion-details">
                <div class="promotion-image">
                    <img src="./img/logo.svg" alt="釣り侍・魚の極み" class="promotion-img">
                </div>
                <div class="promotion-info">
                    <div class="promotion-description">
                        <p>オリジナル動画配信！</p>
                        <p>まずは<span class="trial-days">14</span>日間お試し</p>
                    </div>
                    <button class="btn cta-btn">今すぐ登録</button>
                </div>
            </div>
        </section>
    </main>

    <!-- フッターを読み込む場所 -->
    <footer id="footer">
        <div class="footer-main wrapper">
            <h1 class="logo">
                <a href="index.php">
                    <img src="./img/logo.svg" alt="釣り侍・魚の極み">
                </a>
            </h1>
            <nav>
                <ul>
                    <li><a href="#">会社概要</a></li>
                    <li><a href="#">個人情報ポリシー</a></li>
                    <li><a href="#">よくある質問</a></li>
                    <li><a href="#">お問い合わせ</a></li>
                </ul>
            </nav>
        </div>
    
        <div class="copyright">
            <p>&copy; 2025 釣り侍・魚の極み</p>
        </div>
    </footer>

    <!-- トップへ戻りボタン -->
    <a id="page-top">TOP</a>

    <script src="js/script.js"></script>
</body>
</html>