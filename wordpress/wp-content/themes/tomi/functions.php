<?php

// WordPressを経由せず、このファイルへ直接アクセスされた場合は処理を終了する。
defined( 'ABSPATH' ) || exit;

/**
 * テーマで使用するWordPress標準機能を有効にする。
 */
function tomi_setup() {
    // SEO：ページごとのタイトルをWordPressに管理させる。
    add_theme_support( 'title-tag' );

    // 投稿や固定ページでアイキャッチ画像を使用できるようにする。
    add_theme_support( 'post-thumbnails' );

    // WordPressが出力する検索フォームなどをHTML5形式にする。
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'tomi_setup' );

// サイト表示側の管理バーを非表示にする。
add_filter( 'show_admin_bar', '__return_false' );

/**
 * 現在のサイトがローカル・開発環境かを判定する。
 *
 * アクセス解析、検索エンジンへの登録、メール送信の切り替えに使用する。
 *
 * @return bool ローカル・開発環境の場合はtrue。
 */
function tomi_is_local() {
    $host = wp_parse_url( home_url(), PHP_URL_HOST );

    return in_array( wp_get_environment_type(), array( 'local', 'development' ), true )
        || in_array( $host, array( 'localhost', '127.0.0.1', '::1' ), true )
        || str_ends_with( (string) $host, '.local' );
}

/**
 * スラッグから固定ページのURLを取得する。
 *
 * ページがまだ登録されていない場合は、同じスラッグのURLを組み立てて返す。
 *
 * @param string $slug 固定ページのスラッグ。
 * @return string 固定ページのURL。
 */
function tomi_page_url( $slug ) {
    $page = get_page_by_path( $slug );

    return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * テーマで使用するCSSとJavaScriptを読み込む。
 */
function tomi_assets() {
    // ブラウザ用URLと、更新日時を調べるためのサーバー上のパスを用意する。
    $uri = get_template_directory_uri();
    $dir = get_template_directory();

    // サイト全体で使用するCSSを読み込む。更新日時をバージョンにしてキャッシュを更新する。
    wp_enqueue_style( 'tomi-main', $uri . '/assets/css/style.min.css', array(), filemtime( $dir . '/assets/css/style.min.css' ) );
    wp_enqueue_style( 'tomi-wordpress', get_stylesheet_uri(), array( 'tomi-main' ), filemtime( $dir . '/style.css' ) );

    // メニューやFAQなど、サイト全体で使用するJavaScriptを遅延読み込みする。
    wp_enqueue_script( 'tomi-script', $uri . '/assets/js/script.js', array(), filemtime( $dir . '/assets/js/script.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );

    // スクロール表示アニメーションはトップページだけで読み込む。
    if ( is_front_page() ) {
        wp_enqueue_script( 'tomi-reveal-init', $uri . '/assets/js/reveal-init.js', array(), filemtime( $dir . '/assets/js/reveal-init.js' ), false );
        wp_enqueue_script( 'tomi-animation', $uri . '/assets/js/animation.js', array( 'tomi-script' ), filemtime( $dir . '/assets/js/animation.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
    }

    // GA4・Clarity用の解析コードは、本番環境だけで読み込む。
    if ( ! tomi_is_local() ) {
        wp_enqueue_script( 'tomi-analytics', $uri . '/assets/js/analytics.js', array(), filemtime( $dir . '/assets/js/analytics.js' ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'tomi_assets' );

// SEO：トップページとお問い合わせ確認画面のtitleタグを設定する。
function tomi_title( $title ) {
    // PHP工房の確認画面では、確認画面専用のタイトルを使用する。
    if ( ! empty( $GLOBALS['tomi_contact_title'] ) ) {
        return $GLOBALS['tomi_contact_title'] . ' | アトリエ トミ';
    }

    // トップページでは、サイトの内容が分かる専用タイトルを使用する。
    if ( is_front_page() ) {
        return 'アトリエ トミ | 人材採用に強いWeb制作';
    }

    // その他のページは、WordPressが生成したタイトルを変更せず使用する。
    return $title;
}
add_filter( 'pre_get_document_title', 'tomi_title' );

// 投稿・固定ページのタイトル末尾に表示するサイト名を統一する。
add_filter( 'document_title_parts', function ( $parts ) {
    $parts['site'] = 'アトリエ トミ';
    return $parts;
} );

// SEO：description、OGP、canonical、構造化データをhead内へ出力する。
function tomi_metadata() {
    $uri = get_template_directory_uri();

    // SEO：トップページは専用の説明文、それ以外は投稿・固定ページの抜粋を使用する。
    $description = is_front_page()
        ? '大阪拠点のWebデザイナー・コーダー、藤本富大による人材採用に強いホームページ・ランディングページ制作サービス。'
        : wp_strip_all_tags( get_the_excerpt() );

    // OGPで使用するページURLを取得する。
    $url = is_singular() ? get_permalink() : home_url( '/' );

    // 管理画面でサイトアイコンが未設定の場合は、テーマ内のアイコンを使用する。
    if ( ! has_site_icon() ) {
        echo '<link rel="icon" href="' . esc_url( $uri . '/favicon.ico' ) . '" sizes="any">' . "\n";
        echo '<link rel="apple-touch-icon" href="' . esc_url( $uri . '/assets/img/apple-touch.png' ) . '">' . "\n";
    }
    if ( ! empty( $GLOBALS['tomi_contact_title'] ) || is_404() ) {
        // SEO：確認画面と404ページを検索結果へ登録させない。
        echo '<meta name="robots" content="noindex,nofollow">' . "\n";
        return;
    }

    // SEO：検索結果などに使用されるページの説明文を出力する。
    echo '<meta name="description" content="' . esc_attr( wp_trim_words( $description, 90, '' ) ) . '">' . "\n";

    // SNS：ページが共有されたときのタイトル、説明文、画像を設定する。
    $meta = array(
        'og:type' => 'website', 'og:site_name' => 'アトリエ トミ',
        'og:title' => wp_get_document_title(), 'og:description' => $description,
        'og:url' => $url, 'og:image' => $uri . '/assets/img/ogp.png', 'og:locale' => 'ja_JP',
    );
    foreach ( $meta as $property => $content ) {
        echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    if ( is_front_page() ) {
        // SEO：同じ内容のURLが複数ある場合に、正規URLを検索エンジンへ伝える。
        // 固定ページや投稿のcanonicalはWordPress本体が出力する。
        if ( ! is_singular() ) {
            echo '<link rel="canonical" href="' . esc_url( home_url( '/' ) ) . '">' . "\n";
        }

        // 表示速度：ファーストビュー画像を先読みする。
        echo '<link rel="preload" href="' . esc_url( $uri . '/assets/img/mv-sp.webp' ) . '" as="image" type="image/webp" media="(max-width: 1023px)">' . "\n";
        echo '<link rel="preload" href="' . esc_url( $uri . '/assets/img/mv.webp' ) . '" as="image" type="image/webp" media="(min-width: 1024px)">' . "\n";

        // SEO：business.jsonの事業者情報をJSON-LD構造化データとして出力する。
        // business.jsonはJSON仕様上コメントを書けないため、説明はここに記載する。
        $schema = json_decode( file_get_contents( get_template_directory() . '/data/business.json' ), true );
        $schema['@id'] = home_url( '/#business' );
        $schema['url'] = home_url( '/' );
        $schema['logo'] = $uri . '/assets/img/logo.svg';
        $schema['image'] = $uri . '/assets/img/ogp.png';
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'tomi_metadata', 5 );

// SEO：ローカル環境と送信完了ページを検索結果へ登録させない。
add_filter( 'wp_robots', function ( $robots ) {
    if ( tomi_is_local() || is_page( 'thanks' ) ) {
        $robots['noindex'] = true;
    }
    return $robots;
} );

// 旧静的サイトの.html URLを、対応するWordPressページへ301転送する。
add_action( 'template_redirect', function () {
    // 現在のアクセス先と、WordPressが設置されている基準パスを取得する。
    $path = wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
    $root = trailingslashit( wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ?: '/' );

    // privacy.htmlなどの旧URLを、現在の固定ページURLへ転送する。
    foreach ( array( 'privacy', 'legal', 'thanks' ) as $slug ) {
        if ( $path === $root . $slug . '.html' ) {
            wp_safe_redirect( tomi_page_url( $slug ), 301 );
            exit;
        }
    }

    // index.htmlへのアクセスはWordPressのトップページへ転送する。
    if ( $path === $root . 'index.html' ) {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
} );
