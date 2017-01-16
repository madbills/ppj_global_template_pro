<?php
// This file enqueues all of the global styles and scripts for the site.

// Styles:
wp_enqueue_style('typeography-com', 'https://cloud.typography.com/7050374/6709372/css/fonts.css', NULL ); // Knockout Font


if (!is_rtl()){
	wp_enqueue_style('main-styles', get_bloginfo( 'template_url' ).'/style.css', array(), '1'); // Main style sheet
}

wp_enqueue_style('font-awesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array() ); // Font awesome

wp_enqueue_style('owl-carousel', get_bloginfo( 'template_url' ).'/css/lib/owl.carousel.css', array(), '1'); // Owl carousel main
wp_enqueue_style('owl-transitions', get_bloginfo( 'template_url' ).'/css/lib/owl.transitions.css', array(), '1'); // Owl carousel transitions

// Header JS:

// Footer JS:
wp_enqueue_script('jquery', get_bloginfo( 'template_url' ).'/js/jquery-1.11.3.min.js', array(), '1.11.3', true ); // jQuery
wp_enqueue_script('bootstrap', get_bloginfo( 'template_url' ).'/js/lib/bootstrap.min.js', array('jquery'), '1', true ); // Bootstrap JS
wp_enqueue_script('redballoon', get_bloginfo( 'template_url' ).'/js/custom.js', array('jquery'), '1', true ); // Custom scripts

wp_enqueue_script('owl-carousel', get_bloginfo( 'template_url' ).'/js/lib/owl.carousel.min.js', array('jquery'), '1', true ); // Bootstrap JS

wp_enqueue_script('form-validation-2', get_bloginfo( 'template_url' ).'/js/plugins/validate.js', array('jquery'), '1', true ); // Custom scripts



// Register Styles:
// Add these files to redballoon/css/
// wp_register_style('name', get_bloginfo( 'template_url' ).'/path/to/style.css', array('dependancies'), 'version number'); // Enqueued in page template (Update this)

// Register Scripts:
// Add these files to redballoon/js/
// wp_register_script('name', get_bloginfo( 'template_url' ).'/path/to/script.js', array('dependancies'), 'version number', [true = footer / false = header] ); // Enqueued in page template (Update this)

?>
