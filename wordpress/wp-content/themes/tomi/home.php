<?php
// 投稿一覧ページ。
get_header();
?>
<main id="main-content" class="privacy" tabindex="-1"><div class="privacy__inner">
<h1 class="privacy__title">お知らせ</h1>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article class="privacy__section">
<h2 class="privacy__heading"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<?php the_excerpt(); ?>
</article>
<?php endwhile; the_posts_pagination(); else : ?>
<p class="privacy__text">記事はまだありません。</p>
<?php endif; ?>
</div></main>
<?php get_footer(); ?>
