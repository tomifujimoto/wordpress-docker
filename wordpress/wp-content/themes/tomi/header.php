<?php defined( 'ABSPATH' ) || exit; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content">本文へ移動</a>
<?php if ( empty( $args['hide_header'] ) ) : ?>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="<?php echo esc_url( home_url( '/#top' ) ); ?>" aria-label="アトリエ トミ ホーム">
        <img class="header__logo-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo.svg' ); ?>" alt="Atelier Tomi アトリエトミ" width="200" height="54"
          fetchpriority="high">
      </a>
      <button class="header__toggle" type="button" aria-controls="header-menu" aria-expanded="false"
        aria-label="メニューを開く">
        <span class="header__toggle-line"></span>
        <span class="header__toggle-line"></span>
        <span class="header__toggle-line"></span>
      </button>
      <div class="header__menu" id="header-menu">
        <div class="header__menu-inner">
          <nav class="header__nav" aria-label="メインメニュー">
            <a class="header__nav-link" href="<?php echo esc_url( home_url( '/#about' ) ); ?>">私について</a>
            <a class="header__nav-link" href="<?php echo esc_url( home_url( '/#works' ) ); ?>">制作実績</a>
            <a class="header__nav-link" href="<?php echo esc_url( home_url( '/#service' ) ); ?>">サービス内容</a>
            <a class="header__nav-link" href="<?php echo esc_url( home_url( '/#price' ) ); ?>">料金表</a>
            <a class="header__nav-link" href="<?php echo esc_url( home_url( '/#process' ) ); ?>">制作の流れ</a>
            <a class="header__nav-link" href="<?php echo esc_url( home_url( '/#faq' ) ); ?>">よくあるご質問</a>
          </nav>
          <a class="header__cta" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">
            <span class="header__cta-text">お問い合わせ</span>
            <img class="header__cta-icon mail-icon" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/mail.svg' ); ?>" alt="" aria-hidden="true" width="20"
              height="16">
          </a>
          <nav class="header__social" aria-label="SNSリンク">
            <a class="header__social-link" href="https://www.instagram.com/web_atelier_tomi/?hl=ja" target="_blank"
              rel="noopener noreferrer" aria-label="Instagram">
              <img class="header__social-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/Instagram.svg' ); ?>" alt="" aria-hidden="true" width="28"
                height="28">
            </a>
            <a class="header__social-link" href="https://www.threads.com/@web_atelier_tomi" target="_blank"
              rel="noopener noreferrer" aria-label="Threads">
              <img class="header__social-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/threads.svg' ); ?>" alt="" aria-hidden="true" width="28"
                height="28">
            </a>
          </nav>
        </div>
      </div>
    </div>
  </header>
<?php endif; ?>
