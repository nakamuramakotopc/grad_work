<?php
// CSVファイルのパス
$csvFile = 'data/videos.csv';

// CSVファイルを開く
if (($handle = fopen($csvFile, 'r')) !== FALSE) {
    // 最初の行を読み飛ばす（ヘッダ行）
    fgetcsv($handle);

    // データを格納する配列
    $videos = [];

    // 現在の日付を取得
    $currentDate = new DateTime();

    // 1週間前の日付を取得
    $oneWeekAgo = $currentDate->modify('-1 week')->format('Y-m-d');

    // CSVファイルの各行を処理
    while (($data = fgetcsv($handle)) !== FALSE) {
        // CSVの各列に対応するデータを連想配列に格納
        $video = [
            'Video_ID' => $data[0],
            'Category_ID' => $data[1],
            'Image_Name' => $data[2],
            'Video_Title' => $data[3],
            'Video_Description' => $data[4],
            'Location' => $data[5],
            'Fishing_Method' => $data[6],
            'Fish_Species' => $data[7],
            'Broadcast_Date' => $data[8],
            'Is_Pro_Course' => $data[9],
        ];

        // 日付が1週間以内のものだけ新着として格納
        if ($video['Broadcast_Date'] >= $oneWeekAgo) {
            $video['is_new'] = true; // 新着としてフラグを立てる
        } else {
            $video['is_new'] = false; // 新着ではない
        }

        $videos[] = $video;
    }
    fclose($handle);
}

// 必要に応じてカテゴリー名を取得する処理を追加（ここでは仮の例として配列を使用）
$categoryNames = [
    1 => 'おすすめ',
    2 => '甲冑釣行',
    3 => '水中の戦士',
    4 => 'せっしゃ釣修行中',
    // 他のカテゴリーも追加
];

// カテゴリーIDを基にカテゴリー名を取得する関数
function getCategoryName($categoryID, $categoryNames) {
    return isset($categoryNames[$categoryID]) ? $categoryNames[$categoryID] : 'Unknown';
}

// カテゴリ1とカテゴリ2に分けて格納
$category1Videos = [];
$category2Videos = [];
$category3Videos = [];
$category4Videos = [];
$newVideos = []; // 新着動画の配列

foreach ($videos as $video) {
    // カテゴリ1の動画
    if ($video['Category_ID'] == 1) {
        $category1Videos[] = $video;
    // カテゴリ2の動画
    } elseif ($video['Category_ID'] == 2) {
        $category2Videos[] = $video;
    // カテゴリ3の動画
    } elseif ($video['Category_ID'] == 3) {
        $category3Videos[] = $video;
    // カテゴリ4の動画
    } elseif ($video['Category_ID'] == 4) {
        $category4Videos[] = $video;
    }

    // 新着動画を抽出
    if ($video['is_new']) {
        $newVideos[] = $video;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico">
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
                    <li><a href="#new">新着</a></li>
                    <li><a href="#recommend">おすすめ</a></li>
                    <li><a href="#category">カテゴリ</a></li>
                    <li><a href="">視聴方法</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div id="mainvisual">
            <video autoplay muted loop playsinline webkit-playsinline class="mainvisual_video">
                <source src="videos/samurai_battle_1.mp4" type="video/mp4">
                <img src="img/mainvisual.jpg" alt="メインビジュアル">
            </video>
            <div id="video-text" class="video-text"></div>
        </div>

        <section class="reason fadeIn">
            <h2 class="section-title">選ばれる理由</h2>
            <div class="reason-content wrapper">
                <ul class="reason-list">
                    <li class="reason-item">
                        <h3 class="reason-title">歴史とエンタメの融合</h3>
                        <img src="img/history-entertainment.jpg" alt="歴史とエンタメの融合" class="reason-image">
                        <div class="reason-text">
                            <p>侍の文化と釣りの技術を学べる</p>
                            <p>ユニークなコンテンツ</p>
                        </div>
                    </li>
                    <li class="reason-item">
                        <h3 class="reason-title">初心者からマニアまで対応</h3>
                        <img src="img/beginner-expert.jpg" alt="初心者からマニアまで対応" class="reason-image">
                        <div class="reason-text">
                            <p>初心者向けのやさしい解説から</p>
                            <p>プロ向けの深い技術まで</p>
                        </div>
                    </li>
                    <li class="reason-item">
                        <h3 class="reason-title">美しい映像体験</h3>
                        <img src="img/beautiful-scenes.jpg" alt="美しい映像体験" class="reason-image">
                        <div class="reason-text">
                            <p>甲冑を着た武士のリアルな</p>
                            <p>釣りシーンを高画質で</p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        
        <section class="services fadeIn">
            <h2 class="section-title">サービス内容</h2>
            <div class="service-content wrapper">
                <ul class="service-list">
                    <li class="service-item">
                        <h3 class="service-title">入門者向けセレクション</h3>
                        <img src="img/beginner-course.jpg" alt="入門者向けセレクション" class="service-image">
                        <p class="service-text">釣りの基本を楽しく学べるコンテンツ</p>
                    </li>
                    <li class="service-item">
                        <h3 class="service-title">上級者向けコンテンツ</h3>
                        <img src="img/pro-course.jpg" alt="上級者向けコンテンツ" class="service-image">
                        <p class="service-text">釣りの高度なテクニックや</p>
                        <p class="service-text">侍文化に触れらるコース</p>
                    </li>
                </ul>
            </div>
        </section>
        
        <section class="customer-reviews fadeIn">
            <h2 class="section-title">お客様の声</h2>
            <div class="wrapper">
                <div class="customer-reviews__item">
                    <div class="review-avatar">
                        <img src="img/user-a.jpg" alt="ユーザーA" class="customer-reviews__image">
                    </div>
                    <div class="review-content">
                        <p class="reviewer-name">ユーザーA</p>
                        <p class="review-text">侍が釣りを教えてくれるなんて、他では体験できない！</p>                        
                    </div>
                </div>
                <div class="customer-reviews__item">
                    <div class="review-avatar">
                        <img src="img/user-b.jpg" alt="ユーザーB" class="customer-reviews__image">
                    </div>
                    <div class="review-content">
                        <p class="reviewer-name">ユーザーB</p>
                        <p class="review-text">甲冑を着て釣りをするシーンが迫力満点！釣り初心者でも簡単に理解でき、楽しく学べました。</p>
                    </div>
                </div>
                <div class="customer-reviews__item">
                    <div class="review-avatar">
                        <img src="img/user-c.jpg" alt="ユーザーC" class="customer-reviews__image">
                    </div>
                    <div class="review-content">
                        <p class="reviewer-name">ユーザーC</p>
                        <p class="review-text">映像のクオリティが非常に高く、甲冑の細部や風景の美しさに圧倒されました！</p>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="subscription-cta fadeIn">
            <p class="cta-headline"><span class="cta-emphasis">初月無料</span>でお試しできます！</p>
            <p class="cta-description">今すぐ初めて</p>
            <p class="cta-description">釣りと侍の世界を学びながら楽しもう</p>
            <button class="btn cta-btn">今すぐ無料でお試し</button>
        </section>

        <div class="content fadeIn">
            <h2 class="section-title">動画コンテンツ</h2>

            <!-- 新着動画の表示 -->
            <section class="video-slider-section" id="new">
                <?php if (!empty($newVideos)): ?>
                    <h3 class="category-name">新着動画</h3>
                    <div class="carousel">
                        <ul class="carousel-list" id="slideList">
                            <?php foreach ($newVideos as $video): ?>
                                <li class="carousel-item">
                                    <div class="image-wrapper">
                                        <?php if ($video['is_new']): ?>
                                            <span class="new-tag">NEW</span>
                                        <?php endif; ?>
                                        <?php if (isset($video['Is_Pro_Course']) && $video['Is_Pro_Course'] == 1): ?>
                                            <span class="pro-tag">プロ</span>
                                        <?php endif; ?>
                                        <a href="video.php?videoId=<?= urlencode($video['Video_ID']) ?>">
                                            <img src="img/<?= htmlspecialchars($video['Image_Name']) ?>.jpg" alt="<?= htmlspecialchars($video['Video_Title']) ?>" class="carousel-image">
                                            <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                        </a>
                                    </div>
                                    <p class="carousel-description"><?= htmlspecialchars($video['Video_Title']) ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="carousel-controls">
                    <div class="carousel-button" id="prevButton">&lt;</div>
                    <div class="page-number" id="pageNumber">1 / 5</div>
                    <div class="carousel-button" id="nextButton">&gt;</div>
                </div>
            </section>

            <!-- カテゴリ1 -->
            <section class="video-slider-section" id="recommend">
                <?php if (!empty($category1Videos)): ?>
                    <h3 class="category-name"><?= getCategoryName(1, $categoryNames) ?></h3>
                    <div class="carousel">
                        <ul class="carousel-list" id="slideList2">
                            <?php foreach ($category1Videos as $video): ?>
                                <li class="carousel-item">
                                    <div class="image-wrapper">
                                        <?php if ($video['is_new']): ?>
                                            <span class="new-tag">NEW</span>
                                        <?php endif; ?>
                                        <?php if (isset($video['Is_Pro_Course']) && $video['Is_Pro_Course'] == 1): ?>
                                            <span class="pro-tag">プロ</span>
                                        <?php endif; ?>
                                        <a href="video.php?videoId=<?= urlencode($video['Video_ID']) ?>">
                                            <img src="img/<?= htmlspecialchars($video['Image_Name']) ?>.jpg" alt="<?= htmlspecialchars($video['Video_Title']) ?>" class="carousel-image">
                                            <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                        </a>
                                    </div>
                                    <p class="carousel-description"><?= htmlspecialchars($video['Video_Title']) ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="carousel-controls">
                    <div class="carousel-button" id="prevButton2">&lt;</div>
                    <div class="page-number" id="pageNumber2">1 / 5</div>
                    <div class="carousel-button" id="nextButton2">&gt;</div>
                </div>
            </section>

            <!-- カテゴリ2 -->
            <section class="video-slider-section">
                <?php if (!empty($category2Videos)): ?>
                    <h3 class="category-name"><?= getCategoryName(2, $categoryNames) ?></h3>
                    <div class="carousel">
                        <ul class="carousel-list" id="slideList3">
                            <?php foreach ($category2Videos as $video): ?>
                                <li class="carousel-item <?= $video['is_new'] ? 'new-video' : '' ?>">
                                    <div class="image-wrapper">
                                        <?php if ($video['is_new']): ?>
                                            <span class="new-tag">NEW</span>
                                        <?php endif; ?>
                                        <?php if (isset($video['Is_Pro_Course']) && $video['Is_Pro_Course'] == 1): ?>
                                            <span class="pro-tag">プロ</span>
                                        <?php endif; ?>
                                        <a href="video.php?videoId=<?= urlencode($video['Video_ID']) ?>">
                                            <img src="img/<?= htmlspecialchars($video['Image_Name']) ?>.jpg" alt="<?= htmlspecialchars($video['Video_Title']) ?>" class="carousel-image">
                                            <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                        </a>
                                    </div>
                                    <p class="carousel-description"><?= htmlspecialchars($video['Video_Title']) ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="carousel-controls">
                    <div class="carousel-button" id="prevButton3">&lt;</div>
                    <div class="page-number" id="pageNumber3">1 / 5</div>
                    <div class="carousel-button" id="nextButton3">&gt;</div>
                </div>
            </section>

            <!-- カテゴリ3 -->
            <section class="video-slider-section">
                <?php if (!empty($category3Videos)): ?>
                    <h3 class="category-name"><?= getCategoryName(3, $categoryNames) ?></h3>
                    <div class="carousel">
                        <ul class="carousel-list" id="slideList4">
                            <?php foreach ($category3Videos as $video): ?>
                                <li class="carousel-item <?= $video['is_new'] ? 'new-video' : '' ?>">
                                    <div class="image-wrapper">
                                        <?php if ($video['is_new']): ?>
                                            <span class="new-tag">NEW</span>
                                        <?php endif; ?>
                                        <?php if (isset($video['Is_Pro_Course']) && $video['Is_Pro_Course'] == 1): ?>
                                            <span class="pro-tag">プロ</span>
                                        <?php endif; ?>
                                        <a href="video.php?videoId=<?= urlencode($video['Video_ID']) ?>">
                                            <img src="img/<?= htmlspecialchars($video['Image_Name']) ?>.jpg" alt="<?= htmlspecialchars($video['Video_Title']) ?>" class="carousel-image">
                                            <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                        </a>
                                    </div>
                                    <p class="carousel-description"><?= htmlspecialchars($video['Video_Title']) ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="carousel-controls">
                    <div class="carousel-button" id="prevButton4">&lt;</div>
                    <div class="page-number" id="pageNumber4">1 / 5</div>
                    <div class="carousel-button" id="nextButton4">&gt;</div>
                </div>
            </section>

            <!-- カテゴリ4 -->
            <section class="video-slider-section">
                <?php if (!empty($category4Videos)): ?>
                    <h3 class="category-name"><?= getCategoryName(4, $categoryNames) ?></h3>
                    <div class="carousel">
                        <ul class="carousel-list" id="slideList5">
                            <?php foreach ($category4Videos as $video): ?>
                                <li class="carousel-item <?= $video['is_new'] ? 'new-video' : '' ?>">
                                    <div class="image-wrapper">
                                        <?php if ($video['is_new']): ?>
                                            <span class="new-tag">NEW</span>
                                        <?php endif; ?>
                                        <?php if (isset($video['Is_Pro_Course']) && $video['Is_Pro_Course'] == 1): ?>
                                            <span class="pro-tag">プロ</span>
                                        <?php endif; ?>
                                        <a href="video.php?videoId=<?= urlencode($video['Video_ID']) ?>">
                                            <img src="img/<?= htmlspecialchars($video['Image_Name']) ?>.jpg" alt="<?= htmlspecialchars($video['Video_Title']) ?>" class="carousel-image">
                                            <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                        </a>
                                    </div>
                                    <p class="carousel-description"><?= htmlspecialchars($video['Video_Title']) ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <div class="carousel-controls">
                            <div class="carousel-button" id="prevButton5">&lt;</div>
                            <div class="page-number" id="pageNumber5">1 / 5</div>
                            <div class="carousel-button" id="nextButton5">&gt;</div>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        </div>

        <!-- カテゴリ -->
        <section class="recommended-categories fadeIn" id="category">
            <h2 class="section-title">カテゴリ一覧</h2>
            <div class="category-grid-container wrapper">
            <ul class="category-grid">
                    <li class="category-card">
                        <a href="category.php?categoryId=1">
                            <img src="img/samurai_jump_1.jpg" alt="おすすめ" class="category-image">
                            <p class="category-description">おすすめ</p>
                        </a>
                    </li>
                    <li class="category-card">
                        <a href="category.php?categoryId=2">
                            <img src="img/golden_armor_fight.jpg" alt="甲冑釣行" class="category-image">
                            <p class="category-description">甲冑釣行</p>
                        </a>
                    </li>
                    <li class="category-card">
                        <a href="category.php?categoryId=3">
                            <img src="img/piranha_swarm.jpg" alt="水中の戦士" class="category-image">
                            <p class="category-description">水中の戦士</p>
                        </a>
                    </li>
                    <li class="category-card">
                        <a href="category.php?categoryId=4">
                            <img src="img/sessha_dojo.jpg" alt="せっしゃ釣修行中" class="category-image">
                            <p class="category-description">せっしゃ釣修行中</p>
                        </a>
                    </li>
                    <li class="category-card">
                        <img src="img/category5.jpg" alt="coming soon" class="category-image">
                        <p class="category-description">coming soon</p>
                    </li>
                    <li class="category-card">
                        <img src="img/category6.jpg" alt="coming soon" class="category-image">
                        <p class="category-description">coming soon</p>
                    </li>
                </ul>
            </div>
        </section>

        <!-- プロモーションセクション -->
        <section class="promotion fadeIn">
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

    <script>
        let currentIndex = 0;
        const videos = [
            { src: 'videos/samurai_battle_1.mp4', text: '心を静め、釣竿を手に取る' },
            { src: 'videos/golden_armor_fight.mp4', text: '一匹の魚に、修行を込める' },
            { src: 'videos/samurai_jump_1.mp4', text: 'この瞬間を、映像で体感せよ' }
        ];
        const videoElement = document.querySelector('.mainvisual_video');
        const videoTextElement = document.getElementById('video-text');

        // 最初の動画とテキストを設定
        videoElement.src = videos[currentIndex].src;
        videoTextElement.textContent = videos[currentIndex].text;
        videoTextElement.style.opacity = 1;

        // 動画を切り替える関数
        function changeVideo() {
            // フェードアウト
            videoTextElement.style.opacity = 0;

            // 少し待ってから動画とテキストを更新し、フェードイン
            setTimeout(() => {
                currentIndex = (currentIndex + 1) % videos.length;
                videoElement.src = videos[currentIndex].src;
                videoTextElement.textContent = videos[currentIndex].text;
                videoTextElement.style.opacity = 1;
            }, 1000); // テキストのフェードアウト時間（1秒）と合わせる
        }

        // 5秒ごとに動画を切り替える
        setInterval(changeVideo, 5000);

        //=================================================
        // 各カテゴリごとのスライダー処理
        //=================================================
        function initializeSlider(categoryId, prevButtonId, nextButtonId, pageNumberId) {

            // DOM要素を取得
            const slideList = document.getElementById(categoryId);
            const items = document.querySelectorAll('#' + categoryId + ' .carousel-item');
            const prevButton = document.getElementById(prevButtonId);
            const nextButton = document.getElementById(nextButtonId);
            const pageNumber = document.getElementById(pageNumberId);

            // 基本的な変数設定
            const totalItems = items.length;  // アイテムの総数
            let itemsToMove = getItemsToMove();  // 一度に移動するアイテム数（レスポンシブ対応）
            let isAnimating = false;         // アニメーション中かどうか

            // スライドの状態管理
            let currentIndex = 0;            // 実際の表示位置（DOM上の位置）
            let currentPage = 0;             // 論理的なページ番号（0から始まる）

            // 画面幅に応じて移動するアイテム数を決定する関数
            function getItemsToMove() {
                if (window.innerWidth <= 480) {
                    return 1; // モバイル（小）
                } else if (window.innerWidth <= 768) {
                    return 3; // モバイル（大）・タブレット
                } else {
                    return 5; // デスクトップ
                }
            }

            // クローン用に作成する前後の余分なアイテム数を決定
            // 循環スクロールを確実にするため、移動単位以上を確保
            let cloneCountBefore = Math.max(5, Math.ceil(5 * 1.5)); // 最大の移動数(5)を基準に設定
            let cloneCountAfter = Math.max(5, Math.ceil(5 * 1.5));

            // アイテムの複製と配置
            function setupSlider() {
                // 元のアイテムを保存
                const originalItems = Array.from(items);
                
                // スライドリストを空にする
                slideList.innerHTML = '';
                
                // 前方にクローンを追加
                for (let i = 0; i < cloneCountBefore; i++) {
                    const index = (totalItems - cloneCountBefore + i + totalItems) % totalItems;
                    const clone = originalItems[index].cloneNode(true);
                    clone.setAttribute('data-position', 'clone-before');
                    clone.setAttribute('data-original-index', index);
                    slideList.appendChild(clone);
                }
                
                // オリジナルのアイテムを追加
                for (let i = 0; i < totalItems; i++) {
                    const item = originalItems[i].cloneNode(true);
                    item.setAttribute('data-position', 'original');
                    item.setAttribute('data-index', i);
                    slideList.appendChild(item);
                }
                
                // 後方にクローンを追加
                for (let i = 0; i < cloneCountAfter; i++) {
                    const index = i % totalItems;
                    const clone = originalItems[index].cloneNode(true);
                    clone.setAttribute('data-position', 'clone-after');
                    clone.setAttribute('data-original-index', index);
                    slideList.appendChild(clone);
                }
                
                // 初期位置設定 - 必ず最初のスライドを表示
                currentIndex = cloneCountBefore;
                currentPage = 0;
                updateSlidePosition(false);
                updatePageNumber();
                
                // トランジション終了時のイベントハンドラを設定
                slideList.addEventListener('transitionend', handleTransitionEnd);
            }

            // スライドの位置を更新する関数
            function updateSlidePosition(animate = true) {
                // スライドの幅を取得（リサイズ対応のため毎回計算）
                const itemWidth = slideList.querySelector('.carousel-item').offsetWidth;
                const translateX = -currentIndex * itemWidth;
                
                slideList.style.transition = animate ? 'transform 0.5s ease-in-out' : 'none';
                slideList.style.transform = `translateX(${translateX}px)`;
            }

            // ページ番号を更新する関数
            function updatePageNumber() {
                // 1から始まるページ番号を表示
                pageNumber.textContent = `${currentPage + 1} / ${totalItems}`;
            }

            // トランジション終了時のハンドラ
            function handleTransitionEnd() {
                if (!isAnimating) return;
                
                // 末尾を超えた場合
                if (currentIndex >= cloneCountBefore + totalItems) {
                    // 先頭の実際の位置に調整
                    const overflowAmount = currentIndex - (cloneCountBefore + totalItems);
                    currentIndex = cloneCountBefore + (overflowAmount % totalItems);
                    updateSlidePosition(false);
                }
                // 先頭より前に移動した場合
                else if (currentIndex < cloneCountBefore) {
                    // 末尾の実際の位置に調整
                    const underflowAmount = cloneCountBefore - currentIndex;
                    currentIndex = (cloneCountBefore + totalItems) - (underflowAmount % totalItems);
                    if (currentIndex === cloneCountBefore + totalItems) {
                        currentIndex = cloneCountBefore;
                    }
                    updateSlidePosition(false);
                }
                
                isAnimating = false;
            }

            // 前のスライドに移動する関数
            function goToPrevSlide() {
                if (isAnimating) return;
                
                isAnimating = true;
                
                // 現在の画面幅に基づいて移動アイテム数を取得
                itemsToMove = getItemsToMove();
                
                // 実際に表示するページ数を計算
                const moveAmount = Math.min(itemsToMove, totalItems);
                
                // moveAmount分前に移動
                currentIndex -= moveAmount;
                // ページ番号も更新（循環させる）
                currentPage = (currentPage - moveAmount + totalItems) % totalItems;
                
                updateSlidePosition(true);
                updatePageNumber();
            }

            // 次のスライドに移動する関数
            function goToNextSlide() {
                if (isAnimating) return;
                
                isAnimating = true;
                
                // 現在の画面幅に基づいて移動アイテム数を取得
                itemsToMove = getItemsToMove();
                
                // 実際に表示するページ数を計算
                const moveAmount = Math.min(itemsToMove, totalItems);
                
                // moveAmount分後に移動
                currentIndex += moveAmount;
                // ページ番号も更新（循環させる）
                currentPage = (currentPage + moveAmount) % totalItems;
                
                updateSlidePosition(true);
                updatePageNumber();
            }

            // 特定のページにジャンプする関数（外部から呼び出し可能）
            function jumpToPage(pageNum) {
                if (isAnimating) return;
                
                isAnimating = true;
                
                // 現在の画面幅に基づいて移動アイテム数を更新
                itemsToMove = getItemsToMove();
                
                // ページ番号を0からtotalItems-1の範囲に制限
                pageNum = Math.max(0, Math.min(totalItems - 1, pageNum));
                
                // 現在のページからの差分を計算
                const pageDiff = pageNum - currentPage;
                
                // 現在のインデックスを更新
                currentIndex += pageDiff;
                currentPage = pageNum;
                
                updateSlidePosition(true);
                updatePageNumber();
            }

            // デバッグ情報表示（問題診断用）
            function logState() {
                console.log({
                    totalItems,
                    currentIndex,
                    currentPage,
                    itemsToMove: getItemsToMove(),
                    cloneCountBefore,
                    cloneCountAfter,
                    slideItems: slideList.children.length,
                    windowWidth: window.innerWidth
                });
            }

            // イベントリスナーを設定
            prevButton.addEventListener('click', goToPrevSlide);
            nextButton.addEventListener('click', goToNextSlide);

            // ウィンドウリサイズ時の処理
            window.addEventListener('resize', () => {
                // 画面サイズに合わせて移動アイテム数を更新
                itemsToMove = getItemsToMove();
                updateSlidePosition(false);
            });

            // スライダーのセットアップを実行
            setupSlider();

            // 外部から制御できるようにするための公開メソッド
            return {
                goToNext: goToNextSlide,
                goToPrev: goToPrevSlide,
                jumpToPage: jumpToPage,
                debug: logState
            };
            }

            // 各カテゴリごとのスライダー初期化
            const slider1 = initializeSlider('slideList', 'prevButton', 'nextButton', 'pageNumber');
            const slider2 = initializeSlider('slideList2', 'prevButton2', 'nextButton2', 'pageNumber2');
            const slider3 = initializeSlider('slideList3', 'prevButton3', 'nextButton3', 'pageNumber3');
            const slider4 = initializeSlider('slideList4', 'prevButton4', 'nextButton4', 'pageNumber4');
            const slider5 = initializeSlider('slideList5', 'prevButton5', 'nextButton5', 'pageNumber5');

    </script>

    <!-- トップへ戻りボタン -->
    <a id="page-top">TOP</a>

    <script src="js/script.js" defer></script>
</body>
</html>