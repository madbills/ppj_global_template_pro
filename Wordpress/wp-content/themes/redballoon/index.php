<?php get_header(); ?>

<!-- html before loop (open ul element) -->

<?php
if ( have_posts() ) :
while ( have_posts() ) : the_post();
?>

<!-- The loop (li elements)-->

<?php endwhile; ?>
<?php else : ?>
<?php endif; ?>

<!-- html after loop (close ul element) -->

<?php get_footer(); ?>
