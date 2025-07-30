jQuery(document).ready(function ($) {
  /**
 * DOMに指定要素が出現するまで待機し、見つかったらコールバックを実行
 * WordPress（特にブロックエディタやスライダー系のブロック）は、ページ読み込み後にJSやDOMが遅れて初期化されることがある
 * @param {string} selector - CSSセレクタ
 * @param {Function} callback - 要素が見つかった時に実行される関数
 */
  function waitForElements(selector, callback) {
    //0.3秒ごとにその要素（DOM）が存在するかチェック
    const check = setInterval(() => {
      const $elements = $(selector);
      if ($elements.length > 0) {
        clearInterval(check);
        $elements.each(function () {
          callback($(this));
        });
      }
    }, 300);
  }

  /*--------------------------------------------------------------------------
  ライトニングテーマのcopyright削除
  ----------------------------------------------------------------------------*/
  $(".site-footer-copyright p").eq(1).hide();

  /*--------------------------------------------------------------------------
  楽ちんルビタグ変換コード
  ----------------------------------------------------------------------------*/
  $(".espo-ruby").each(function(){
      let html = $(this).html();
      html = html.replace(
          /\[([^\]]+)\]\{([^\}]+)\}/g,
          "<ruby>$1<rt>$2</rt></ruby>"
      );
      $(this).html(html);

      // [漢字]{ふりがな}
      // ↓↓ 例 ↓↓
      // [espo!エスポ長堀橋]{エスポながほりばし}
      // [一般就労]{いっぱんしゅうろう}
  })

  // バニラJSバージョン
  function convertRubyInElement(el){
    let html = el.innerHTML;
    html = html.replace(
        /\[([^\]]+)\]\{([^\}]+)\}/g,
        "<ruby>$1<rt>$2</rt></ruby>"
    );
    el.innerHTML = html;
  }
  document.querySelectorAll('.espo-ruby').forEach(convertRubyInElement);

  function convertRubySyntax(text){
    return text.replace(/\[([^\]]+)\]\{([^\}]+)\}/g,
          "<ruby>$1<rt>$2</rt></ruby>");
  }
  
  /*--------------------------------------------------------------------------
  TOP画像切り替え
  ----------------------------------------------------------------------------*/
  // 切り替え画像の配列
  const images = [
    'https://e-spo.org/espo_site02/wp-content/uploads/2024/12/IMG_1021-scaled.jpg',
    'https://e-spo.org/espo_site02/wp-content/uploads/2024/12/GYh8jNLasAITX_d-scaled.jpg',
    'https://e-spo.org/espo_site02/wp-content/uploads/2024/12/2GFJUUrfawAA-kwJ-scaled.jpg',
    'https://e-spo.org/espo_site02/wp-content/uploads/2024/12/espo_ホームページスライド軽作業01-scaled.jpg'
  ];

  // 画像のインデックス
  let currentImageIndex = 0;

  const startSlideshow = ($target) => {
    function changeBackgroundImage() {
      // フェードアウト
      $target.css('transition', 'opacity 1s ease-in-out');
      $target.css('opacity', 0);

      // 次の画像に切り替えてフェードイン
      setTimeout(() => {
        $target.css('background-image', `url(${images[currentImageIndex]})`);
        $target.css('opacity', 1);
        currentImageIndex = (currentImageIndex + 1) % images.length;
      }, 1000);
    }
    // 切り替える秒数
    setInterval(changeBackgroundImage, 4000);
  };

  // WordPress（特にブロックエディタやスライダー系のブロック）は、ページ読み込み後にJSやDOMが遅れて初期化されることがある
  waitForElements('.wp-block-cover__image-background.wp-image-3044.has-parallax', ($target) => {
    startSlideshow($target);
  });

  /*--------------------------------------------------------------------------
  画像のアニメーション
  ----------------------------------------------------------------------------*/
  function observeToggleClass($el, className) {
      const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        // 要素が見えたら実行
        if(entry.isIntersecting){
          console.log('intersected:', entry.target);
          entry.target.classList.add(className);
        } 
        //  見えなくなったらクラス削除
        else {
          entry.target.classList.remove(className);
        }
      });
      
    })
    //監視対象
    // $el.get(0) を使うことで、jQueryオブジェクトの$el がラップしている最初の生のDOM要素を取り出す。
    observer.observe($el.get(0));
  }

  // ひまわりちゃん
  waitForElements('.Irast_Himawari', ($el) => {
    observeToggleClass($el, 'animate');
  })

  // エスくん　シエンちゃん
  waitForElements('.es_shien_irast', ($el) => {
    observeToggleClass($el, 'visible');
  })

  // クローバーの回転
  waitForElements('.clover_dec', ($el) => {
    observeToggleClass($el, 'rotate');
  })
  waitForElements('.clover_dec_right', ($el) => {
    observeToggleClass($el, 'rotate');
  })

  // シエンちゃんヒーローセクション
  waitForElements('.espo-balloon', initializeBalloonAnimations);

  // テキストアニメーション
  waitForElements('.hero-text', animateTextWithRuby('.hero-text strong', 'fade-in-char', 200));

  /*--------------------------------------------------------------------------
  お問い合わせのチェックボックス表示切り替え
  ----------------------------------------------------------------------------*/
  const mainCheckbox = document.getElementById('mainCheckbox');
  const additionalCheckboxes = document.getElementById('additionalCheckboxes');

  mainCheckbox.addEventListener('change', () => {
      if (mainCheckbox.checked) {
          // チェックが入ったら表示
          additionalCheckboxes.classList.remove('kengaku_hidden');
      } else {
          // チェックが外れたら非表示
          additionalCheckboxes.classList.add('kengaku_hidden');
      }
  });

  /*--------------------------------------------------------------------------
  画像のアニメーション（3段チェーン）
  ----------------------------------------------------------------------------*/
  function initializeBalloonAnimations() {
    $('.espo-balloon').each(function () {
        const $el = $(this);
        const $text = $el.find('.content p');
        $el.addClass('fade-slide-in');
        $el.css('opacity', 1);

        $el.on('animationend', function (e) {
            const name = e.originalEvent.animationName;

            if (name === 'fadeSlideIn') {
                $el.removeClass('fade-slide-in').addClass('bounce-in');
            } else if (name === 'bounceIn') {
                $el.removeClass('bounce-in');
                $text.addClass('text-slide-in');
            }
        });
    });
  }

  /*--------------------------------------------------------------------------
  テキストアニメーション
  ----------------------------------------------------------------------------*/
  function animateText(selector, animationClass, delay){
    document.querySelectorAll(selector).forEach(el => {

      // このテキストをバラす。
      const text = el.textContent.trim();
      //一度空にする
      el.innerHTML = '';

      // スプレッド構文（1文字ずつ分解した配列に変換して処理）
      // ドット3つと変数
      [...text].forEach((char, i) => {

        // タグで囲む
        const span = document.createElement('span');
        span.textContent = char;
        span.style.animationDelay = `${i * delay}ms`;
        span.classList.add(animationClass);

        // セレクター要素の子要素として追加
        el.appendChild(span);
      })

    })
  }

  // ルビ対応の改良バージョン
  function animateTextWithRuby(selector, animationClass, delay) {
    document.querySelectorAll(selector).forEach(el => {

      // コピーした中身を編集するテクニック
      const original = el.innerHTML;
      const temp = document.createElement('div');
      temp.innerHTML = original;

      el.innerHTML = ''; // 一度空に

      let i = 0;

      temp.childNodes.forEach(node => {
        if (node.nodeType === Node.TEXT_NODE) {
          [...node.textContent].forEach(char => {
            const span = document.createElement('span');
            span.textContent = char;
            span.style.animationDelay = `${i * delay}ms`;
            span.classList.add(animationClass);
            el.appendChild(span);
            i++;
          });
        } else if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'RUBY') {
          const span = document.createElement('span');
          span.innerHTML = node.outerHTML;
          span.style.animationDelay = `${i * delay}ms`;
          span.classList.add(animationClass);
          el.appendChild(span);
          i++;
        } else {
          el.appendChild(node.cloneNode(true)); // その他はそのまま
        }
      });
    });
  }

  /*--------------------------------------------------------------------------
  お問い合わせ　クリックでひまわりジャンプアニメーション
  ----------------------------------------------------------------------------*/
  const button = document.getElementById("button_hima_butterfly");
  button.addEventListener("mousedown", () => {button.classList.add("jump");});
  button.addEventListener("animationend", () => {button.classList.remove("jump");});
  button.addEventListener("animationcancel", () => {button.classList.remove("jump");});

});


