    <section class="contact" id="contact">
      <div class="contact__inner">
        <div class="section-head centered js-reveal">
          <span class="section-head__label">Contact</span>
          <h2 class="section-head__title">お問い合わせ</h2>
        </div>

        <div class="contact__box js-reveal">
          <!-- **************************** -->
          <!-- **************************** -->
          <?php if ( tomi_is_local() ) : ?><p class="contact__text">ローカル確認用：メールは外部へ送信されません。</p><?php endif; ?>
          <form class="contact__form" action="<?php echo esc_url( get_template_directory_uri() . '/mail.php' ); ?>" method="post">
            <?php wp_nonce_field( 'tomi_contact', 'tomi_nonce' ); ?>
            <div class="contact__field">
              <label class="contact__label" for="contact-name">
                <span>お名前</span>
                <span class="contact__required">必須</span>
              </label>
              <!-- **************************** -->
              <!-- **************************** -->
              <input class="contact__input" type="text" name="お名前" id="contact-name" autocomplete="name" placeholder="例）山田　太郎" required>
            </div>

            <div class="contact__field">
              <label class="contact__label" for="contact-tel">電話番号</label>
              <input class="contact__input" type="tel" name="電話番号" id="contact-tel" autocomplete="tel" placeholder="例）0120-200-177">
            </div>

            <div class="contact__field">
              <label class="contact__label" for="contact-email">
                <span>メールアドレス</span>
                <span class="contact__required">必須</span>
              </label>
              <!-- **************************** -->
              <!-- **************************** -->
              <input class="contact__input" type="email" name="Email" id="contact-email" autocomplete="email" placeholder="例）sample@xxx.com"
                required>
            </div>

            <div class="contact__field">
              <label class="contact__label" for="contact-message">お問い合わせ内容</label>
              <textarea class="contact__textarea" name="お問い合わせ内容" id="contact-message"
                placeholder="こちらにご記入ください。"></textarea>
            </div>

            <button class="contact__submit" type="submit">送信する</button>
          </form>
        </div>
      </div>
    </section>
