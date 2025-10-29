<?php
/**
 * Lightning Child theme functions
 *
 * @package lightning
 */

/************************************************
 * 独自CSSファイルの読み込み処理
 *
 * 主に CSS を SASS で 書きたい人用です。 素の CSS を直接書くなら style.css に記載してかまいません.
 */

// 独自のCSSファイル（assets/css/）を読み込む場合は true に変更してください.
$my_lightning_additional_css = true;

if ( $my_lightning_additional_css ) {
	// 公開画面側のCSSの読み込み.
	add_action(
		'wp_enqueue_scripts',
		function() {
			wp_enqueue_style(
				'my-lightning-custom',
				get_stylesheet_directory_uri() . '/assets/css/style.css',
				array( 'lightning-design-style' ),
				filemtime( dirname( __FILE__ ) . '/assets/css/style.css' )
			);

            // 2025/7/8 自前JS読み込み追加
            wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/main.js', array('jquery'), filemtime(get_stylesheet_directory() . '/assets/js/main.js'), true);
		}
	);
	// 編集画面側のCSSの読み込み.
	add_action(
		'enqueue_block_editor_assets',
		function() {
			wp_enqueue_style(
				'my-lightning-editor-custom',
				get_stylesheet_directory_uri() . '/assets/css/editor.css',
				array( 'wp-edit-blocks', 'lightning-gutenberg-editor' ),
				filemtime( dirname( __FILE__ ) . '/assets/css/editor.css' )
			);
		}
	);
}

/************************************************
 * 独自の処理を必要に応じて書き足します
 */
/* add_filter('walker_nav_menu_start_el', 'description_in_nav_menu', 10, 4);
function description_in_nav_menu($item_output, $item){
return preg_replace('/(<a.*?>[^<]*?)</',"<br /><span>{$item->attr_title}</span><", $item_output); }
*/

/*ページが存在しない場合はホーム画面に遷移*/
function redirect_404() {
        if (is_404()) {
            wp_redirect(home_url());
            exit();
        }
    }
    add_action('template_redirect', 'redirect_404');


/*OGP設定*/
function add_og_image_meta_tag() {
    if (has_post_thumbnail()) {
        echo '<meta property="og:image" content="' . get_the_post_thumbnail_url() . '" />' . "\n";
    } else {
        echo '<meta property="og:image" content="http://e-spo.org/espo_site02/wp-content/uploads/2024/09/cropped-espo長堀橋03.png" />' . "\n";
    }
}
add_action('wp_head', 'add_og_image_meta_tag');


function hook_google_search_console() {
    echo <<<EOF

<meta name="google-site-verification" content="3KRuXFcKyeY5CD55OAD3O6slTfAM6_e5DKbREzdAa0I">

EOF;
}

add_action('wp_head', 'hook_google_search_console');


/*メニュータイトル下に説明を入れる　外観 ー＞ メニュー　ー＞各メニュー名クリック ー＞ タイトル属性に説明を入れる*/
add_filter('walker_nav_menu_start_el', 'description_in_nav_menu', 10, 4);

function description_in_nav_menu($item_output, $item, $depth, $args) {
    if (!empty($item->attr_title)) {
        $item_output = preg_replace('/(<a.*?>)(.*?)(<\/a>)/', "$1$2<br /><span class='menu-description'>{$item->attr_title}</span>$3", $item_output);
    }
    return $item_output;
}


//自動更新を無効にする 2025 2 11
add_filter( 'automatic_updater_disabled', '__return_true' );

// 投稿ページのサムネイル画像 有効化
add_theme_support('post-thumbnails');//両方

// カスタム投稿（職員のつぶやき）の追加
function create_custom_post_type() {
    register_post_type('news', [
    'labels' => [
        'name' => '職員つぶやき',
        'singular_name' => 'お知らせ',
    ],
    'public' => true, // 管理画面だけではなくサイト上でも表示
    'has_archive' => true, // アーカイブページの有無
    'menu_position' => 5, // メニューの位置
    'menu_icon' => 'dashicons-portfolio',
    'supports' => ['title', 'editor', 'thumbnail'],
    'rewrite' => ['slug' => 'news', 'with_front' => false],
    'show_in_rest' => true, // ブロックエディタ対応
    ]);
}
add_action('init', 'create_custom_post_type');

// カスタム投稿（職員のつぶやき）の表示用ショートコード
function custom_post_shortcode($atts) {
    ob_start();

    $query = new WP_Query(array(
        'post_type'      => 'news',
        'posts_per_page' => 3,
    ));

    if ($query->have_posts()) {
        // データをテンプレートに渡す
        include locate_template('custom-post-list.php');
    } else {
        echo '投稿がありません。';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('custom_posts', 'custom_post_shortcode');

// 定着支援ページ（固定ページID：4828）の表示用ショートコード
function teichaku_page_shortcode(){
    // 出力バッファリング開始
    ob_start();
    
    // ファイルを探して読み込む
    include locate_template('teichaku.php');

    // バッファリングしてた内容を返す（HTMLとして出力される）
    return ob_get_clean();
}
add_shortcode('teichaku_page', 'teichaku_page_shortcode');

// 定着支援ページ（固定ページID：4828）のCSS・JS読み込み
function teichaku_page_scripts(){
    if(is_page(4828)){
        wp_enqueue_style('aos-css', 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css');
        wp_enqueue_style('teichaku-css', get_stylesheet_directory_uri() . '/teichaku-assets/css/style.css', array());

        wp_enqueue_script('aos-js', 'https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js', '2.3.4', true);
        wp_enqueue_script('teichaku-js', get_stylesheet_directory_uri() . '/teichaku-assets/js/script.js', array('aos-js'), true);
    }
}
add_action('wp_enqueue_scripts', 'teichaku_page_scripts');

/* ===============================================================================
footerウィジェット　カスタマイズ　ショートコード埋め込み対応 20250723
=============================================================================== */
function footer_widget_shortcode(){
    // 出力バッファリング開始
    ob_start();
    $template = locate_template('LG_footer-widget.php');

    // ファイルを探して読み込む
    if($template){
        include $template;
    } else {
        echo '<!-- LG_footer-widget.php が見つかりません -->';
    }
    // バッファリングしてた内容を返す（HTMLとして出力される）
    return ob_get_clean();
}
add_shortcode('LG_footer-widget', 'footer_widget_shortcode');

// === LG Hero Shortcode ===
// 呼び出し側: [lg_hero_template]
function lg_hero_template_shortcode() {
    // 出力バッファリング開始
    ob_start();

    // テンプレートパーツを呼び出し
    get_template_part('assets/template-parts/espo-hero');

    // バッファリングしていた内容を返す（HTMLとして出力される）
    return ob_get_clean();
}
add_shortcode( 'lg_hero_template', 'lg_hero_template_shortcode' );