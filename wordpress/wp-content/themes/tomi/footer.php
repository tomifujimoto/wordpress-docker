<?php defined( 'ABSPATH' ) || exit; ?>
<?php if ( empty( $args['minimal'] ) ) : ?>
  <footer class="footer">
    <div class="footer__inner">
      <div class="footer__profile">
        <h2 class="footer__logo">
          <a class="footer__logo-link" href="<?php echo esc_url( home_url( '/#top' ) ); ?>" aria-label="トップへ戻る">
            <img class="footer__logo-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/footer-logo.svg' ); ?>" alt="Atelier Tomi" width="200" height="54"
              loading="lazy" decoding="async">
          </a>
        </h2>
        <p class="footer__text">人材採用に強いWeb制作<br>Webデザイナー・コーダー／大阪拠点・全国対応</p>
      </div>
      <nav class="footer__nav footer__nav--menu" aria-label="フッターメニュー">
        <h3 class="footer__heading">メニュー</h3>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#about' ) ); ?>">私について</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#works' ) ); ?>">制作実績</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#service' ) ); ?>">サービス内容</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#price' ) ); ?>">料金表</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#process' ) ); ?>">制作の流れ</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#faq' ) ); ?>">よくあるご質問</a>
      </nav>
      <nav class="footer__nav footer__nav--service" aria-label="サービス">
        <h3 class="footer__heading">サービス</h3>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#service' ) ); ?>">ランディングページ制作</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#service' ) ); ?>">ホームページ制作</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#service' ) ); ?>">採用サイト制作</a>
        <a class="footer__nav-link" href="<?php echo esc_url( home_url( '/#service' ) ); ?>">WordPress保守・運用</a>
      </nav>
      <div class="footer__contact">
        <h3 class="footer__contact-heading">ご相談・お見積りは無料です</h3>
        <p class="footer__contact-text">Webに関するお悩みやご希望を<br>丁寧にヒアリングし、<br>最適なプランをご提案します。</p>
        <div class="footer__sns">
          <a class="footer__sns-link" href="https://www.instagram.com/web_atelier_tomi/?hl=ja" target="_blank"
            rel="noopener noreferrer" aria-label="Instagram">
            <img class="footer__sns-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/footer-Instagram.svg' ); ?>" alt="" width="28" height="28"
              loading="lazy" decoding="async">
          </a>
          <a class="footer__sns-link" href="https://www.threads.com/@web_atelier_tomi" target="_blank"
            rel="noopener noreferrer" aria-label="Threads">
            <img class="footer__sns-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/footer-threads.svg' ); ?>" alt="" width="28" height="28"
              loading="lazy" decoding="async">
          </a>
        </div>
      </div>
    </div>
    <div class="footer__bottom">
      <span>&copy; 2026 アトリエ トミ, All Rights Reserved.</span>
      <a class="footer__bottom-link" href="<?php echo esc_url( tomi_page_url( 'privacy' ) ); ?>">プライバシーポリシー</a>
      <a class="footer__bottom-link" href="<?php echo esc_url( tomi_page_url( 'legal' ) ); ?>">特定商取引法に基づく表記</a>
    </div>
  </footer>
<?php endif; ?>
<?php wp_footer(); ?>
</body>
</html>
