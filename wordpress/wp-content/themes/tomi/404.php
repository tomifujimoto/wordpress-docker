<?php get_header( null, array( 'hide_header' => true ) ); ?>

    <main id="main-content" class="thanks" tabindex="-1">
        <section class="thanks__section">
            <div class="thanks__inner">
                <div class="section-head centered">
                    <span class="section-head__label">404 Not Found</span>
                    <h1 class="section-head__title">ページが見つかりません</h1>
                </div>
                <div class="thanks__box">
                    <p class="thanks__text">
                        お探しのページは、削除されたか、URLが変更された可能性があります。
                    </p>
                    <a class="thanks__button" href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページへ戻る</a>
                </div>
            </div>
        </section>
    </main>
<?php get_footer( null, array( 'minimal' => true ) ); ?>
