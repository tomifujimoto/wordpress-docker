<?php get_header(); ?>

  <main id="main-content" class="thanks" tabindex="-1">
    <section class="thanks__section">
      <div class="thanks__inner">
        <div class="section-head centered">
          <span class="section-head__label">Thanks</span>
          <h1 class="section-head__title">送信完了</h1>
        </div>
        <div class="thanks__box">
          <p class="thanks__text"><?php echo tomi_is_local() ? 'ローカルでのフォーム動作確認が完了しました。' : 'お問い合わせありがとうございます。送信が完了しました。'; ?></p>
          <p class="thanks__text"><?php echo tomi_is_local() ? 'メールは外部へ送信されていません。' : '内容を確認のうえ、折り返しご連絡いたします。'; ?></p>
          <a class="thanks__button" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページへ戻る</a>
        </div>
      </div>
    </section>
  </main>
<?php get_footer( null, array( 'minimal' => true ) ); ?>
