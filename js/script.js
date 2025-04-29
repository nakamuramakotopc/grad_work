//=================================================
// ハンバーガーメニューの作成 (レスポンシブ版のみ)
//=================================================
let startVal = 0;
let endVal = 0;

let btnGnavi = document.querySelector(".btn-gnavi");
let globalNavi = document.querySelector(".main-nav");

let options = {
    duration: 2000,
    easing: 'ease',
    fill: 'both'
};

btnGnavi.addEventListener("click", function(){
    // console.log("Enter btnGnavi!");

    let menuWidth = globalNavi.offsetWidth;

    if (btnGnavi.classList.contains("open")) {
        btnGnavi.classList.remove("open");
        startVal = 0;
        endVal = -menuWidth;
    } else {
        btnGnavi.classList.add("open");
        startVal = -menuWidth;
        endVal = 0;
    }

    let keyframes = [
        { left: `${startVal}px` },
        { left: `${endVal}px` }
    ];

    let animation = globalNavi.animate(keyframes, options);

    animation.onfinish = function () {
        globalNavi.style.left = `${endVal}px`;
    };
})


// メニューが開いている場合にリンククリックで閉じる処理
const menuLinks = document.querySelectorAll('.main-nav a');

menuLinks.forEach(link => {
    link.addEventListener('click', function () {
        if (btnGnavi.classList.contains("open")) {
            btnGnavi.classList.remove("open");

            let keyframes = [
                { left: `0px` },
                { left: `-390px` }
            ];

            let animation = globalNavi.animate(keyframes, options);

            animation.onfinish = function () {
                globalNavi.style.left = '-390px';
            };
        }
    });
});


//=================================================
// スムーススクロール
//=================================================
// a[href ^= "#"] :aタグの href属性の先頭が #で始まるもの
//
// ^ : ハット
// ^= : 属性セレクター
//
const links = document.querySelectorAll('a[href^="#"]');

for(let i = 0; i < links.length; i++){
    links[i].addEventListener('click', function(e) {
        //既定の動作をしない様に指示する
        e.preventDefault();

        //クリックされた要素の href属性の中身を取得
        const href = this.getAttribute("href");

        console.log(href);

        //三項演算子
        const target = document.querySelector(href === "#" || href === "" ? 'html' : href);

        //移動先の位置と現在のスクロール位置を足して、移動距離を求める
        const position = target.getBoundingClientRect().top + window.scrollY;

        console.log(position);

        //上記で求めた移動距離まで、実際に移動する
        window.scrollTo({
            top: position,
            behavior: 'smooth'
        });
    })
}


//=================================================
// トップへ戻るボタン
//=================================================
// ページトップボタンの要素を取得
const pageTop = document.querySelector("#page-top");

//初期状態では、非表示
pageTop.style.display = 'none';

//スクロールイベントのリスナーを追加
window.addEventListener('scroll', function(){
    
    //console.log(window.scrollY);

    if (window.scrollY > 500) {
        //ボタンを表示
        fadeIn(pageTop);
    } else {
        //ボタンを非表示
        fadeOut(pageTop);
    }
});

// フェードイン関数
function fadeIn(element) {
    element.style.display = 'block';
    element.animate (
        [
            { opacity: 0 },
            { opacity: 1 }
        ],
        {
            duration: 500,
            fill: 'forwards',
            easing: 'ease-in'
        }
    );
}

// フェードアウト関数
function fadeOut(element) {
    const animation = element.animate (
        [
            { opacity: 1 },
            { opacity: 0 }
        ],
        {
            duration: 500,
            fill: 'forwards',
            easing: 'ease-out'
        }
    );

    //アニメーション完了後に要素を非表示にする
    animation.onfinish = function() {
        element.style.display = 'none';
    };

}

//スムーズスクロール関数
function smoothScrollToTop() {
    const currentPosition = window.scrollY;
    
    if (currentPosition > 0) {
        window.requestAnimationFrame(smoothScrollToTop);
        // 現在位置の1/10ずつ減らしていく（最小値は1pxにする）
        const scrollStep = Math.max(currentPosition / 10, 1);
        window.scrollTo(0, currentPosition - scrollStep);
    } else {
        // 確実に0の位置に設定
        window.scrollTo(0, 0);
    }
}

//クリックイベントのリスナーを追加
pageTop.addEventListener('click', function(){
    //スムーズスクロールで、トップへ
    smoothScrollToTop(); 
});

//=================================================
// スクロール時にふわっと表示させる
//=================================================

// 監視オプションの設定
const ioOptions = {
    threshold: 0.1
};

// 監視設定
const fadeObserver = new IntersectionObserver(animateFade, ioOptions);

// 対象が範囲内に現れたら実行する関数
function animateFade(entries, obs) {
    for(let i = 0; i < entries.length; i++) {
        const entry = entries[i];

        // 対象が表示域に交差しているかを確認
        if(entry.isIntersecting){
            // スライドする動きを無くし、フェードインのみのアニメーション
            entry.target.animate(
                {
                    opacity: [0, 1],  // 最初は透明、最終的に不透明
                },
                {
                    duration: 800,  // アニメーションの時間を0.8秒に設定
                    easing: 'ease-out',  // 最後が滑らかに終わるように
                    fill: 'forwards',  // アニメーション後の状態を保持
                }
            );
            // 一度ふわっと表示されたら、監視をやめる
            obs.unobserve(entry.target);
        }
    }
}

// .fadeInクラスを監視する様に設定
const fadeElements = document.querySelectorAll('.fadeIn');

for (let i = 0; i < fadeElements.length; i++) {
    fadeObserver.observe(fadeElements[i]);
}