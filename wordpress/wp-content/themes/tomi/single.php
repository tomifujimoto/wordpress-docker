<?php
// 個別投稿の本文を表示する。
get_header();
?>
<main id="main-content" class="privacy" tabindex="-1"><div class="privacy__inner">
<?php while ( have_posts() ) : the_post(); ?>
<h1 class="privacy__title"><?php the_title(); ?></h1>
<div class="privacy__body"><?php the_content(); wp_link_pages(); ?></div>
<?php endwhile; ?>
</div></main>
<?php get_footer(); ?>
