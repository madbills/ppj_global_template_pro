<?php
/*
Plugin Name: RB Calls To Action
Plugin URI: http://redballoon.io
Description: Call to Action Link area for distribution cross site
Version: 1.0.2
Author: Red Balloon Design Ltd.
Author URI: http://redballoon.io
License: GPLv2
*/

/*

View the readme here:
https://docs.google.com/spreadsheets/d/1apC0th0X_rq8ybvTDsp40lWcHfthdna8WMbAywX7DBU/pubhtml?gid=1345923635&single=true

*/

/*
Updates to make:

* Update to how cta html is saved and created.
* Create styles
* Custom Meta is not in this version of the plugin however it may be required for Including specific CTA links on specific pags. See OITC Website for details.
* Add flex styles to allow for flexible number of CTA's
*/

/*
Changelog:
--  --  1.0.3  --  --
* Update to how files are enqueued. The files are registered here and enqueued in the shortcode file.

--  --  1.0.2  --  --
* Added the meta field to the bottom of pages to quickly add a CTA.
* Made compatible with multisites.
* added extra wrap to cta image to make it easier to add effects like zoom or fading between backgrounds
*/


// Custom Post Type
add_action( 'init', 'register_cpt_cta' );
function register_cpt_cta() {

        $labels = array(
            'name' => __( 'Call To Actions', 'cta' ),
            'singular_name' => __( 'CTA', 'cta' ),
            'add_new' => __( 'Add New', 'cta' ),
            'add_new_item' => __( 'Add New Module', 'cta' ),
            'edit_item' => __( 'Edit CTA', 'CTA' ),
            'new_item' => __( 'New CTA', 'cta' ),
            'view_item' => __( 'View CTA', 'cta' ),
            'search_items' => __( 'Search CTA', 'cta' ),
            'not_found' => __( 'No modules found', 'cta' ),
            'not_found_in_trash' => __( 'No CTA found in Trash', 'cta' ),
            'parent_item_colon' => __( 'Parent CTA:', 'cta' ),
            'menu_name' => __( 'CTAs', 'cta' ),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'description' => 'Call to Action Link area for distribution cross site',
            'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
            'taxonomies' => array( 'category', 'post_tag', 'video_categories' ),
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 6,
            'show_in_nav_menus' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'has_archive' => false,
            'query_var' => true,
            'can_export' => true,
            'rewrite' => true,
            'capability_type' => 'page'
        );

        register_post_type( 'cta', $args );
}

// Custom Meta Boxes
add_action( 'add_meta_boxes', 'add_cta_metaboxes' );
function add_cta_metaboxes() {
    add_meta_box('cta_link', 'URL to page', 'cta_link', 'cta', 'normal', 'low');
    add_meta_box('cta_to_use', 'CTA Shortcode', 'cta_to_use', 'page', 'normal', 'high');
}
// Add the meta box to WP Admin
function cta_link() {
	global $post;
    $blog_id = get_current_blog_id();

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="ctameta_noncename" id="ctameta_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
    echo '<input type="hidden" name="blog_id" value="'. $blog_id .'">';

	// Get the data if its already been entered
	$cta_url = get_post_meta($post->ID, '_cta_url', true);
	$cta_url = gettype($cta_url) === 'array' ? $cta_url['_cta_url']:$cta_url;

	// Echo out the field
	echo '<input type="text" name="_cta_url" value="' . $cta_url  . '" class="widefat" />';
}
// Save the Metabox Data
function wpt_save_cta_meta($post_id, $post) {
	$custom_meta = '';
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( isset($_POST['ctameta_noncename']) && !wp_verify_nonce( $_POST['ctameta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}

	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

        // Network compatibility. Our plugin should not be synchronized.
    if ( empty ( $_POST[ 'blog_id' ] ) )
       return FALSE;

    if ( (int) $_POST[ 'blog_id' ] !== get_current_blog_id() )
       return FALSE;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.

	// Add values of $events_meta as custom fields
	if( isset($_POST['ctameta_noncename'])){
        $custom_meta['_cta_url'] = $_POST['_cta_url'];
		foreach ($custom_meta as $key => $value) { // Cycle through the $events_meta array!
			if( $post->post_type == 'revision' ) return; // Don't store custom data twice
			$value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
			if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} else { // If the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
			if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
		}
	}


  update_post_meta( $post_id, '_cta_url', $custom_meta );

}
add_action('save_post', 'wpt_save_cta_meta', 1, 2); // save the custom fields

// Add ability to add the CTA shortcode to any page.
// Will be output to the theme footer.php template.
// Currently deosnt do this automatically.
function cta_to_use() {
	global $post;
	$blog_id = get_current_blog_id();
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="usectameta_noncename" id="usectameta_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<input type="hidden" name="blog_id" value="'. $blog_id .'">';

	// Get the location data if its already been entered
	$cta_to_use = get_post_meta($post->ID, '_use_cta_id', true);
	// Echo out the field
	echo '<input type="text" name="_use_cta_id" value="' . $cta_to_use  . '" class="widefat" />';
}
function wpt_save_cta_to_use_meta($post_id, $post) {
	$custom_meta = '';
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( isset($_POST['usectameta_noncename']) && !wp_verify_nonce( $_POST['usectameta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;

 // Network compatibility. Our plugin should not be synchronized.
        if ( empty ( $_POST[ 'blog_id' ] ) )
            return FALSE;

        if ( (int) $_POST[ 'blog_id' ] !== get_current_blog_id() )
            return FALSE;

	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	// Add values of $events_meta as custom fields
	if( isset($_POST['usectameta_noncename'])){
		$custom_meta = $_POST['_use_cta_id'];
	}
  update_post_meta( $post_id, '_use_cta_id', $custom_meta );
}
add_action('save_post', 'wpt_save_cta_to_use_meta', 1, 2); // save the custom fields


// Scripts
function register_cta_files(){
    if ( shortcode_exists('cta') ) {
        wp_register_style( 'cta-styles', plugins_url( 'css/cta.min.css', __FILE__ ) );
    };
};
add_action( 'wp_enqueue_scripts', 'register_cta_files' );

// Shortcodes
add_shortcode('cta', 'cta_shortcode');
include('inc/shortcode.php');


?>
