<?php
/* Template Name: 元デザインの固定ページ */
get_header();
?>
<main id="main-content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
</main>
<?php get_footer(); ?>
