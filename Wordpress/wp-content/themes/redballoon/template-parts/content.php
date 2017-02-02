<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 */
?>
<? get_template_part( 'template-parts/hero' ); ?>

<div class="container">
	<div class="row">
		<div class="content ">
				<?php the_content(); ?>
		</div><!-- content -->
		<?php get_sidebar(); ?>
	</div>
</div>
