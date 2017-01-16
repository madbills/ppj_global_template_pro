<?php
/*
Plugin Name: Module Base
Plugin URI: http://redballoon.io
Description: Website Modular system to allow content to be distributed cross site whilst allowing ultimate customisation.
Version: 0.1
Author: Red Balloon Design Ltd
Author URI: http://redballoon.io
License: GPLv2
*/
add_action( 'init', 'register_cpt_modules' );
function register_cpt_modules() {
        $labels = array(
            'name' => __( 'Modules', 'modules' ),
            'singular_name' => __( 'Module', 'module' ),
            'add_new' => __( 'Add New', 'module' ),
            'add_new_item' => __( 'Add New Module', 'module' ),
            'edit_item' => __( 'Edit Module', 'module' ),
            'new_item' => __( 'New Module', 'module' ),
            'view_item' => __( 'View Module', 'module' ),
            'search_items' => __( 'Search Module', 'module' ),
            'not_found' => __( 'No modules found', 'module' ),
            'not_found_in_trash' => __( 'No module found in Trash', 'module' ),
            'parent_item_colon' => __( 'Parent module:', 'module' ),
            'menu_name' => __( 'Modules', 'modules' ),
        );

        $args = array(
            'labels' => $labels,
            'description' => 'Website Modular system to allow content to be distributed cross site whilst allowing ultimate customisation.',
            'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'page-attributes' ),
            'taxonomies' => array( 'category', 'post_tag', 'video_categories' ),
            'hierarchical' => true,            
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_nav_menus' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => true,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'page',
            'page-attributes' => true
        );

        register_post_type( 'module', $args );
}

// Scripts
function add_module_base_files(){
}
add_action( 'wp_enqueue_scripts', 'add_module_base_files' );

// Shortcode
include('inc/shortcode.php');
add_shortcode('module', 'module_shortcode');
?>
