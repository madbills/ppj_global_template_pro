<?php /* Template Name: MISO template */ ?>
<?php get_header(); ?>


<div class="container inner-content">


<?php
if (have_posts()) : while (have_posts()) : the_post();?>
<?php endwhile; else:?>
<?php
endif;
?>

<?
// Get the exerpt
$this_page_excerpt = get_the_excerpt();
if ( has_excerpt() ){
	$this_page_excerpt = '<p class="subhead">'.$this_page_excerpt.'</p>';
} else {
	$this_page_excerpt = '';
}

if ( is_active_sidebar( 'sidebar-1' ) ) : ?>

<div class="row table-row <?if (is_rtl()){echo 'rtl-title';};?>">
	<div class="content-full col-xs-24">
		<div class="title clearfix">
			<h1><? the_title();?></h1>
			<?=$this_page_excerpt?>
		</div>
		<div class="row">
			<div class="col-md-16">
				<? the_content();?>
			</div>
			<div class="col-md-8">
				<div class="sidebar">
					<?php dynamic_sidebar( 'sidebar-1' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<? else : ?>
<div class="row table-row">
	<div class="col-xs-24 content-full">
		<div class="title clearfix">
			<h1><? the_title();?></h1>
			<?=$this_page_excerpt?>
		</div>
		<? the_content();?>
	</div>
</div>
<? endif; ?>

<?php get_footer(); ?>