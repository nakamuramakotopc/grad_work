<?php
    $categoryNames = [
        1 => 'おすすめ',
        2 => '甲冑釣行',
        3 => '水中の戦士',
        4 => 'せっしゃ釣修行中',
        // 他のカテゴリーも追加
    ];

    $videoId = isset($_GET['videoId']) ? $_GET['videoId'] : null;
    $videoData = null;
    $allVideos = [];

    if ($videoId !== null && ($csv = fopen('data/videos.csv', 'r')) !== false) {
        fgetcsv($csv); // ヘッダー行スキップ
        while (($row = fgetcsv($csv)) !== false) {
            list($id, $categoryId, $image, $title, $description, $location, $method, $species, $date, $isPro) = $row;

            $video = [
                'video_id' => $id,
                'category_id' => $categoryId,
                'image' => $image,
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'method' => $method,
                'species' => $species,
                'date' => $date,
                'is_pro' => ($isPro === '1')
            ];
            
            // 新着判定
            $videoDate = strtotime($date);
            $oneWeekAgo = strtotime('-7 days');
            $video['is_new'] = $videoDate >= $oneWeekAgo;

            if ($id === $videoId) {
                $videoData = array_merge($video, ['videoFile' => "videos/{$id}.mp4"]);
            }

            $allVideos[] = $video;
        }
        fclose($csv);
    }

    // 関連動画を抽出（同カテゴリ・動画IDが異なる）→日付順で最大10件
    $relatedVideos = [];
    if ($videoData) {
        $sameCategoryVideos = array_filter($allVideos, function ($v) use ($videoData, $videoId) {
            return $v['category_id'] === $videoData['category_id'] && $v['video_id'] !== $videoId;
        });

        usort($sameCategoryVideos, function ($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']); // 新しい順
        });

        $relatedVideos = array_slice($sameCategoryVideos, 0, 10);
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
        <?php if ($videoData): ?>
            <section class="video-content wrapper">
                <!-- サムネイル画像・再生ボタン -->
                <div class="video-thumbnail-container">
                    <video id="videoPlayer"
                        src="videos/<?php echo htmlspecialchars($videoData['image']); ?>.mp4"
                        poster="img/<?php echo htmlspecialchars($videoData['image']); ?>.jpg"
                        playsinline></video>
                    <div id="videoOverlay" class="video-overlay"></div> <!-- クリック用の透明オーバーレイ -->
                    <button id="playBtn" class="play-button">
                        <img src="img/video_btn.png" alt="再生ボタン">
                    </button>
                </div>

                <!-- 動画タグ・カテゴリ情報 -->
                <div class="video-meta">
                    <ul class="video-meta_list">
                        <?php
                            // 1週間以内か判定
                            $isNew = false;
                            if (!empty($videoData['date'])) {
                                $videoDate = strtotime($videoData['date']);
                                $oneWeekAgo = strtotime('-7 days');
                                $isNew = ($videoDate >= $oneWeekAgo);
                            }

                            // プロ判定
                            $isPro = !empty($videoData['is_pro']);
                        ?>

                        <?php if ($isNew): ?>
                            <li class="video-tag video-tag-new">New</li>
                        <?php endif; ?>

                        <li class="video-tag video-tag-category">
                            <?php
                                $catId = $videoData['category_id'] ?? null;
                                echo htmlspecialchars($categoryNames[$catId] ?? '不明なカテゴリ');
                            ?>
                        </li>

                        <?php if ($isPro): ?>
                            <li class="video-tag video-tag-pro">プロ</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- 動画情報 -->
                <div class="video-info">
                    <h2 class="video-title"><?php echo htmlspecialchars($videoData['title']); ?></h2>
                    <p class="video-description"><?php echo nl2br(htmlspecialchars($videoData['description'])); ?></p>
                    <dl>
                        <dt>場所</dt>
                        <dd><?php echo htmlspecialchars($videoData['location']); ?></dd>
                        <dt>釣り方</dt>
                        <dd><?php echo htmlspecialchars($videoData['method']); ?></dd>
                        <dt>魚種</dt>
                        <dd><?php echo htmlspecialchars($videoData['species']); ?></dd>
                        <dt>放送日</dt>
                        <dd><?php echo htmlspecialchars($videoData['date']); ?></dd>
                    </dl>
                </div>
            </section>

            <section class="related-episodes">
                <h2 class="section-title">他にもこんな動画があります</h2>
                <div class="wrapper">
                    <?php foreach ($relatedVideos as $related): ?>
                        <a href="video.php?videoId=<?= urlencode($related['video_id']) ?>" class="video-item-link">
                            <article class="video-item">
                                <figure class="video-thumbnail image-wrapper">
                                    <img src="img/<?php echo htmlspecialchars($related['image']); ?>.jpg" alt="<?php echo htmlspecialchars($related['title']); ?>">
                                    <?php if (!empty($related['is_new'])): ?>
                                        <span class="tag new-tag">NEW</span>
                                    <?php endif; ?>
                                    <?php if (!empty($related['is_pro'])): ?>
                                        <span class="tag pro-tag">プロ</span>
                                    <?php endif; ?>
                                    <img src="img/video_btn.png" alt="ビデオボタン" class="play-icon">
                                </figure>
                                <div class="video-info">
                                    <h3 class="video-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                                    <p class="video-description"><?php echo htmlspecialchars($related['description']); ?></p>
                                </div>
                            </article>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

        <?php else: ?>
            <div class="wrapper">
                <p class="no-video-message">指定された動画が見つかりませんでした。</p>
            </div>
        <?php endif; ?>

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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('videoPlayer');
            const playBtn = document.getElementById('playBtn');
            const overlay = document.getElementById('videoOverlay');

            playBtn.addEventListener('click', () => {
                video.play();
                playBtn.classList.add('hidden');
                overlay.style.display = 'block'; // 動画クリックで制御できるようにする
            });

            overlay.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    playBtn.classList.add('hidden');
                } else {
                    video.pause();
                    playBtn.classList.remove('hidden');
                }
            });

            video.addEventListener('ended', () => {
                playBtn.classList.remove('hidden');
            });
        });
    </script>
    <script src="js/script.js"></script>
</body>
</html>