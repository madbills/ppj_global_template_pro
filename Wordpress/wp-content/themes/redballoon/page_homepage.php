<?php /* Template Name: Homepage */ ?>

<?php get_header(); ?>

<?php
if (have_posts()) : while (have_posts()) : the_post();?>
<?php endwhile; else:?>
<?php
endif;
?>

<div class="container ingredients" id="content">
    <div class="row">
        <h2><?=get_the_excerpt();?></h2>
        <?=the_content();?>
        <?
        // Output the calls to action here
        $cta_ids = get_post_meta($post->ID, '_use_cta_id', true); // Gets the value of a custom meta field by post id and name.
        if ( strlen($cta_ids) > 0){
            echo do_shortcode( '[cta include="'.$cta_ids.'"]' );
        };
        ?>
    </div>
</div>

<?php get_footer(); ?>
