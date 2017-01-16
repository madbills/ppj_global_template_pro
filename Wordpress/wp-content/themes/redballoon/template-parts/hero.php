<?php // This is the html for the hero of a page. It will get brought in on most pages and posts
global $post;
/* For pages with single thumbnails (or just using thumbnail 1): */
$post_thumbnail_id = get_post_thumbnail_id( $post->id );
$featuredimageAlt = '';
if (empty($post_thumbnail_id))
	$post_thumbnail_id = 41;// Default hero image. Change this value

$post_thumbnail_array = wp_get_attachment_image_src($post_thumbnail_id, 'full', true);
$post_thumbnail_src = $post_thumbnail_array[0];
$featuredimageAlt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true );
?>

<div class="hero hero-default">
	<div>
        <img class="hero-bg object-fit" src="<?=$post_thumbnail_src?>" alt="<?=$featuredimageAlt?>" />
    </div><?// This div will be given a background image of the child img element if object fit is not supported. ?>
	<div class="hero-text">
        <h1><?php the_title(); ?></h1>
        <div class="clearfix">
            <div class="hero-text-inner">
                <?php echo $post->post_excerpt; ?>
            </div>
        </div>
    </div><!--/.herp-text-->
</div><!--/.hero-->
