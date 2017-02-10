<?php
/*
Plugin Name: Tabs
Plugin URI: http://redballoon.io
Description: Adds tabs to a page using a shortcode
Version: 0.0
Author: Red Balloon Design Ltd.
Author URI: http://redballoon.io
License: GPLv2
*/

// Custom Post Type
add_action( 'init', 'create_tab' );
function create_tab() {
    register_post_type( 'tab',
        array(
            'labels' => array(
                'name' => 'Tabs',
                'singular_name' => 'Tab',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Tab',
                'edit' => 'Edit',
                'edit_item' => 'Edit',
                'new_item' => 'New Tab',
                'view' => 'View',
                'view_item' => 'View Tab',
                'search_items' => 'Search Tab',
                'not_found' => 'No Tabs found',
                'not_found_in_trash' => 'No Tabs found in Trash',
                'parent' => 'Parent Tabs'
            ),

            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes', 'excerpt' ),
            'map_meta_cap' => true,
            'has_archive' => false,
            'capability_type' => 'page'
        )
    );
};
// Scripts
add_action( 'wp_enqueue_scripts', 'tabs_register_scripts' );
function tabs_register_scripts() {
    wp_register_script( 'tabs-script', plugins_url( 'js/tabs.js', __FILE__ ), array('jquery'), '1.0', true );
};

// Shortcodes
add_shortcode('tabs', 'tabs_shortcode');
include('inc/shortcode.php');
