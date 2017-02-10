<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post();?>

	<?
	/* Gets content from content */
	/* If post format is set this will get other post formats */
	get_template_part( 'template-parts/content', get_post_format() );
	?>

<?php endwhile; else: ?>
	<h1>Sorry, no posts matched your criteria.</h1>
<?php endif; ?>

<!-- see page.php for snippets that may speed up development -->

<?php get_footer(); ?>
