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
<div class="row table-row">
	<div class="col-md-6 table-col col-no-padding">
		
		<aside class="secondary-nav">
			<a class="btn-toggle-menu " role="button" data-toggle="collapse" href="#nav-collapse" aria-expanded="false" aria-controls="nav-collapse">
				About Papa John's<i class='glyphicon glyphicon-triangle-bottom'></i>
			</a>
			<div class="collapse no-bullets" id="nav-collapse">
				<ul>
				
				

	<?php dynamic_sidebar( 'sidebar-1' ); ?>


				</ul>
			</div>
		</aside>
	</div>
	<div class="col-md-17 col-md-offset-1 table-col">
		<div class="content-right">
			<div class="title">
				<h1><?the_title();?></h1>
				<?=$this_page_excerpt?>
			</div>
			<? the_content();?>
		</div>
	</div>
</div>
<? else: ?>
<div class="row table-row">
	<div class="col-xs-24 content-full">
		<div class="title clearfix">
			<h1><?the_title();?></h1>
			<?=$this_page_excerpt?>
		</div>
		<? the_content();?>
	</div>
</div>
<?php endif; ?>




<?php
/* This section is for snippets that might be used on a page template. It doesn't display on the page and should be removed*/
$demoSection = true;
if (!$demoSection):

	/***
	Title
	***/
    the_title(); // Echoes the title of the page

	/***
	Excerpt
	***/
	the_excerpt(); // Echoes the post excerpt with filters applied

	/***
	Content
	***/
    the_content(); // Echoes the content of the page with filters applied

	/***
	Content section (more tags)
	***/
    _get_project_info('0', $post->post_content); // Gets a content section (seperated by a more tag)

    apply_filters('the_content', _get_project_info('0', $post->post_content)); // applies wordpress filter to content section

	/***
	Content section variable
	***/
    _get_project_info_class('1', $post->post_content); // Gets class name from more tag <!-- more [classname] -->

	/***
	Post variables
	***/
	$post->id;
	$post->name;
    $post->slug;


	/***
	Post meta values
	***/
	get_post_meta($post->ID, '[custom_field_name]', true); // Gets the value of a custom meta field by post id and name.


	/***
	Run shortcode
	***/
	do_shortcode('[name option="value"]'); // Run a shortcode

	/***
	URLs
	***/
    bloginfo( 'template_url' );// Echoes the url of the template

    get_bloginfo( 'template_url' ); // Returns url of the template as a string

	home_url(); // returns homepage url

	/***
	Date
	***/
	the_date('[format]'); // Echoes the date of the page. See formatting options: https://codex.wordpress.org/Formatting_Date_and_Times

	/***
	Get other files:
	***/
    get_template_part( 'template-parts/part', '[name]' ); // this is similar to using include 'part-[sub_part].php' but it retains $post

	file_get_contents('path/to/file'); // Gets the contents of a file as a string. Used to bring in content from svg files.

    get_sidebar('[name]'); // Gets the content of sidebar-[name].php

	/***
	Add dynamic sidebar by name
	***/
	dynamic_sidebar( 'sidebar-name' ); // sidebar with widgets

	/***
	Check if specific sidebar is active
			note: if you want to check for any sibebar you will need to run a loop through all registered sidebars in $wp_registered_sidebars.
	***/
	is_active_sidebar( 'sidebar-name' ); // Checks if the active sidebar is 'sidebar-name'


	/***
	Get image alt information
	***/
	$image_alt = get_post_meta( $image->id, '_wp_attachment_image_alt', true); // Gets the alt attribute for an image

	/***
	Add a file onto the page
	***/
	wp_enqueue_script('name-of-script'); // Register the script in inc/enqueue-resources.php

	wp_enqueue_style('name-of-style'); // Register the style in inc/enqueue-resources.php

	/***
	Remove file from page
	***/
	wp_dequeue_script('name-of-script'); // Prevents an enqueued script from being brought onto a page.

	wp_dequeue_style('name-of-stlye'); // Prevents an enqueued style from being brought onto a page.

endif; //end of snippet section ?>



<?php get_footer(); ?>
