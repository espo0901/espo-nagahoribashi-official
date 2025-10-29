<?php

/**
 * Template Part: LG Hero (CSS制御版)
 * Path: template-parts/espo-hero.php
 *
 * $args:
 * - img      : 背景画像URL（必須）
 * - alt      : 代替テキスト
 * - animate  : アニメ種別 'fade' | 'kenburns' | 'parallax'
 * - content  : 中央テキストHTML（任意）
 * - class    : 追加クラス（任意）
 */
$imgPath = get_stylesheet_directory_uri() . '/assets/images/';
$a = wp_parse_args( $args ?? [], [
    'img'     => $imgPath . 'espo-hero-image.png',
    'alt'     => 'ヒーローセクション背景画像',
    'animate' => 'fade',
    'content' => '',
    'class'   => '',
]);

if (empty($a['img'])) return;

$img     = esc_url($a['img']);
$alt     = esc_attr($a['alt']);
$animate = esc_attr($a['animate']);
$class   = esc_attr($a['class']);
$inner   = $a['content'] ? wp_kses_post($a['content']) : '';
?>

<section class="lg-hero animate-fade"
    style="--hero-bg:url('<?php echo esc_url($a['img']); ?>');"
    role="img" aria-label="<?php echo esc_attr($a['alt']); ?>">

    <div class="lg-hero__overlay"></div>

    <div class="lg-hero__content">

        <!-- メインコピー -->
        <div class="hero-text">
            <p class="espo-ruby"><strong>あなたのペースで</strong></p>
            <p class="espo-ruby"><strong>[一般就労]{いっぱんしゅうろう}へ</strong></p>
            <p class="espo-ruby"><strong>[見学]{けんがく}・[体験受付中]{たいけんうけつけちゅう}！</strong></p>
        </div>

        <!-- バルーン -->
        <div class="espo-balloon right">
            <div class="icon">
                <img src="https://e-spo.org/espo_site02/wp-content/uploads/2024/12/シエン立ち絵-2-1.png" alt="シエン">
            </div>
            <div class="content">
                <p class="espo-ruby"><strong>とりあえず[見学]{けんがく}だけでもOK</strong></p>
            </div>
        </div>

    </div>
</section>