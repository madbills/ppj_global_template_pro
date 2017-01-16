<?php
/*
Plugin Name: MISO
Plugin URI: http://redballoon.io
Description: Plugin to add custom post types that will be displayed as menus and ingredients.
Version: 0.1
Author: Red Balloon Design Ltd.
Author URI: http://redballoon.io
License: GPLv2
*/

// Include class for creating new custom post type with a name: https://github.com/jjgrainger/wp-custom-post-type-class
include('inc/CPT.php');

// Add custom fields:
class miso_posts extends CPT{

}

// This array exists to store the plugin pages info. This could be moved into a wordpress option but that is beyond the scope of the development here.
// If changing this also change the array within set_variations_meta.
$miso_types = array(
    array(
        'miso_menu_item', 
        'Menu Items', 
        'Menu Item'
    ), 
    array(
        'miso_offer', 
        'Special Offers', 
        'Special Offer'
    ),
    array(
        'miso_ingredient', 
        'Ingredients', 
        'Ingredient'
    )
);

$ppj_MISO_posts = array();

$typeCount = 0;

foreach ($miso_types as $type){

    // Create post type
    $ppj_MISO_posts[$typeCount] = new miso_posts(
        $type[0],
        array(
            'labels' => array(
                'name' => $type[1],
                'singular_name' => $type[2],
                'add_new' => 'Add New',
                'add_new_item' => 'Add New '.$type[2],
                'edit' => 'Edit',
                'edit_item' => 'Edit',
                'new_item' => 'New '.$type[2],
                'view' => 'View',
                'view_item' => 'View '.$type[1],
                'search_items' => 'Search '.$type[1],
                'not_found' => 'No '.$type[1].' found',
                'not_found_in_trash' => 'No '.$type[1].' found in Trash',
                'parent' => 'Parent '.$type[2]
            ),
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'page-attributes' ),
            'taxonomies' => array('faqs_cat'), // For custom taxonomies
            'map_meta_cap' => true,
            'has_archive' => false,
            'capability_type' => 'page'
        )
    );

    // Register taxonimies
    $tax_name_raw = $type[0];
    $tax_name_raw = str_replace('miso_', '', $tax_name_raw);
    $tax_name = $tax_name_raw.'_category';
    $tax_plural = $tax_name_raw.'_categories';
    $tax_name_raw = str_replace('_', ' ', $tax_name_raw);
    
    $ppj_MISO_posts[$typeCount]->register_taxonomy(array(
        'taxonomy_name' => $tax_name,
        'singular' => ucwords($tax_name_raw . ' category'),
        'plural' => ucwords($tax_name_raw . ' categories'),
        'slug' => $tax_name
    ));

    $typeCount++;
}

add_action('add_meta_boxes', 'set_variations_meta', 10, 1 );

function set_variations_meta(){
    // This needs to be the same as the variable above. Due to the scope of the two variables it isn't possible to just use the same variaible.
    $miso_types = array(
        array(
            'miso_menu_item', 
            'Menu Items', 
            'Menu Item'
        ), 
        array(
            'miso_offer', 
            'Special Offers', 
            'Special Offer'
        ),
        array(
            'miso_ingredient', 
            'Ingredients', 
            'Ingredient'
        )
    ); 
    foreach( $miso_types as $post_type ){
        add_meta_box(
            'variations', // $id
            'Variations', // $title 
            'variations_meta_callback', // $callback
             $post_type[0],
            'normal', // $context
            'high' // $priority
        );

        add_meta_box(
            'display_price', // $id
            'Display Price', // $title 
            'display_price_meta_callback', // $callback
             $post_type[0],
            'normal', // $context
            'high' // $priority
        );
        add_meta_box(
            'show_image', // $id
            'Show Image?', // $title 
            'show_image_meta_callback', // $callback
             $post_type[0],
            'normal', // $context
            'high' // $priority
        );
    }

}

// Front end for admin variation box
function variations_meta_callback(){

    wp_enqueue_script('miso_admin');

    global $post;
	$blog_id = get_current_blog_id();
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="usemisometa_noncename" id="usemisometa_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<input type="hidden" name="blog_id" value="'. $blog_id .'">';

	// Get the location data if its already been entered
	$current_variation_info = get_post_meta($post->ID, '_variations', true);
    ?>
    <? if (strlen($current_variation_info)>= 1):?>
    <script>
        var current_variation_info = <?=$current_variation_info;?>;
    </script>
    <?php endif;?>
    <noscript>
        Unfortunatly this page requires javascript to be loaded.
    </noscript>
    <?

}

function display_price_meta_callback(){

    global $post;
	$blog_id = get_current_blog_id();
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="usemisometa_noncename" id="usemisometa_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<input type="hidden" name="blog_id" value="'. $blog_id .'">';

	// Get the location data if its already been entered
	$display_price = get_post_meta($post->ID, '_display_price', true);
	// Echo out the field
	echo '<input type="text" name="_display_price" value="' . $display_price  . '" class="widefat" />';

}

function show_image_meta_callback(){

    global $post;
	$blog_id = get_current_blog_id();
	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="usemisometa_noncename" id="usemisometa_noncename" value="' .
	wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	echo '<input type="hidden" name="blog_id" value="'. $blog_id .'">';

	// Get the location data if its already been entered
	$show_image = get_post_meta($post->ID, '_show_image', true);
    echo '<label for="_show_image"><input name="_show_image" type="checkbox" id="_show_image" value="true"';
    if ($show_image === 'true'){
        echo 'checked';
    }
    echo '>Display the items featured image in the modal content.</label>';
}


add_action('save_post', 'save_miso_meta', 1, 2);

function save_miso_meta(){
    global $post;
    $custom_meta = '';
    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if ( isset($_POST['usemisometa_noncename']) && !wp_verify_nonce( $_POST['usemisometa_noncename'], plugin_basename(__FILE__) )) {
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
    
    if( isset($_POST['usemisometa_noncename'])){
		$custom_meta['_variations'] = $_POST['_variations'];
		$custom_meta['_display_price'] = $_POST['_display_price'];
		$custom_meta['_show_image'] = $_POST['_show_image'];

		foreach ($custom_meta as $key => $value) { // Cycle through the $events_meta array!
			if( $post->post_type == 'revision' ) return; // Don't store custom data twice

            if (gettype($value) === 'array'){
                $value = json_encode($value); // If $value is an array, make it a JSON string
            }

			if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
				update_post_meta($post->ID, $key, $value);
			} else { // If the custom field doesn't have a value
				add_post_meta($post->ID, $key, $value);
			}
			if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
		}

	}

}

add_shortcode('miso', 'miso_plugin_shortcode');
include('inc/shortcode.php');


function miso_register_admin_script(){
    wp_enqueue_style( 'miso_admin', plugins_url('/resources/miso_admin.css', __FILE__) );
    wp_enqueue_script( 'miso_admin', plugins_url('/resources/miso_admin.js', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'miso_register_admin_script' );
