jQuery(document).ready(function ($) {
  // ヒーローセクションスライダー処理
  // ＄変数は中身は jQueryオブジェクト っていう目印
  let $silides = $('.p-hero-slider__item');
  let current = 0;
  const changeTime = 4000; 

  function showNextSlide(){
    $silides.eq(current).removeClass('active');
    // 最後のスライドから最初のスライドへループするために使われるテンプレ的な計算方法
    current = (current + 1) % $silides.length;
    $silides.eq(current).addClass('active');
  }
  $silides.eq(0).addClass('active');
  // setInterval…一定時間ごとに特定の処理を繰り返す
  setInterval(showNextSlide, changeTime);

  // アコーディオンメニュー（よくある質問）
  $(".accordion-toggle").on("click", function () {
    $(this).next(".accordion-content").slideToggle();
  });

  // スムーススクロール
  $('a[href^="#"]').click(function(event){
        event.preventDefault(); // デフォルトの動作（ページジャンプ）を防ぐ

        // $(...):HTML要素をjQueryオブジェクトとして取得
        let href = $(this).attr("href");
        let target = $(href == "#" || href == "" ? 'html' : href);
        let position = target.offset().top;
        $("html, body").animate({scrollTop:position}, 600, "swing");
    })

  // ライトニングテーマのcopyright削除
  $(".site-footer-copyright p").eq(1).hide();

  // 謎のスタイルタグ削除
  $("#egf-frontend-styles").remove();

  // ポップアップカード（規定値以上スクロールでポップアップ）
  $(window).scroll(function(){

    //PC画面の場合のみ処理
    if(window.innerWidth >= 768){

      if($(this).scrollTop() > 700){
        $("#popup-card").addClass("active");
      }else{
        $("#popup-card").removeClass("active");
      }
    }
  })

  // AOS初期化（忘れがち！）
  AOS.init();
});
