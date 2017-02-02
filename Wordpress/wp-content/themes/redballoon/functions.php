<?php
/* Red Balloon's Functions  */
/*

Contents:
-- WP Admin chages
-- Extending Posts and Pages
-- Images
-- Extend Wordpress functions
-- Wordpress fixes
-- Scripts and Styles Enqueing
-- Woocommerce
-- Custom nav walker
-- Theme Support
*/


/////////////////////////////////////////////////
///////
/////// WP ADMIN CHANGES
///////
/////////////////////////////////////////////////

/*
Re orders the admin menu to put pages under posts.
Add other menu items in the order they should be displayed in.
All items not in the array will be output after the contents of the array in the default order.
https://code.tutsplus.com/articles/customizing-your-wordpress-admin--wp-24941
*/
function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
    return array(
        'index.php', // Dashboard
        'separator1', // First separator
        'edit.php', // Posts
		'edit.php?post_type=page' // Pages
    );
}
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
add_filter('menu_order', 'custom_menu_order');

/* Enqueue the resources for the theme: */
add_action('wp_enqueue_scripts', 'enqueue_resources');
function enqueue_resources(){
	include('inc/enqueue-resources.php');
}

/* Adds custom meta for Maplist Pro */
function mlp_location_metaboxes_addnew( $meta_box_fields ) {
       //Add a text-box field
       $meta_box_fields[] = array(
           'name' => 'Contact Number',
           'id' => 'maplist_telephone',
           'type' => 'text'
       );
       $meta_box_fields[] = array(
           'name' => 'Opening Hours',
           'id' => 'maplist_hours',
           'type' => 'text'
       );
        return $meta_box_fields;
}
add_filter( 'mlp_location_metaboxes', 'mlp_location_metaboxes_addnew' );




/////////////////////////////////////////////////
///////
/////// EXTENDING POSTS AND PAGES
///////
/////////////////////////////////////////////////

/////// Expands content in WYSIWYG by more tag position.
/////// e.g. content0<!--more-->Content1
///////
/////// echo _get_project_info('0', $post->post_content); // will output raw html
///////	echo apply_filters('the_content', _get_project_info('0', $post->post_content)); // will output content with wordpress additions (p tags, shortcodes etc.)
///////
/////// will return "content0"

function _get_project_info( $what, $info ) {
    /* Seperates the content of a post ($info) by more tags and outputs a specific section ($what). */
    $info = explode('<!--more-->', $info);
    $part = intval($what);
    if ( $part < count($info) ) {
        return $info[$part];
    } else {
        return '';
    }
}


// If you add a class to the more tag ie <!-- more class_here--> This function will get the class for that particular more tag.
function _get_project_info_class( $what, $info ) {
	preg_match_all('/<!--more(.*?)-->/s', $info, $matches);
    $part = intval($what);
    if ( $part < count($info) ) {
        if ($part == 0) {
            return $matches[0];
        } else {
            return $matches[1][$part];
        }
    } else {
        return '';
    }
}

// Setup a default widget/sidebar area for the plugin to replace
add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Primary Sidebar', 'theme-slug' ),
        'id' => 'sidebar-1',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
        'before_widget' => '<section id="pages-2" class="widget widget_pages">',
	'after_widget'  => '</section>',
	'before_title'  => '<h2 class="widget-title">',
	'after_title'   => '</h2>',
    ) ); 
}
// Setup a sidebar for the language switcher
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Language Switcher',
		'id' => 'language-switcher',
		'before_widget' => '<div id="language-selector">',
		'after_widget' => '</div>'
	));
}

/////////////////////////////////////////////////
///////
/////// Images
///////
/////////////////////////////////////////////////

function filter_ptags_on_images($content){
   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

add_filter('the_content', 'filter_ptags_on_images');

/*
Add additional thumbnails to a page or post.
////// add code examples below, its about 3 lines.
*/
if (class_exists('MultiPostThumbnails')) {
	new MultiPostThumbnails(array(
		'label' => 'Second Featured Image',
		'id' => 'second-thumb',
		'post_type' => 'page'
	 ) );
     /* new MultiPostThumbnails(array(
 		'label' => 'Second Featured Image',
 		'id' => 'second-thumb',
 		'post_type' => 'page'
    ) );*/
}

/*
Docs for srcset in images:
https://www.smashingmagazine.com/2015/12/responsive-images-in-wordpress-core/
https://make.wordpress.org/core/2015/11/10/responsive-images-in-wordpress-4-4/
https://developer.wordpress.org/reference/functions/add_image_size/

To remove srcset from images: add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );

*/

/*
Add a new image size:
add_image_size( $name, $width, $height, $crop);

$name (string)      = name of the image size
$width (int)        = width of the image in pixels
$height (int)       = height of the image in pixels
$crop (bool|array)  = Whether to crop images to specified width and height or resize. An array can specify positioning of the crop area.

$crop explination:
true : the image will be set to the $width value and the top and bottom of the image will be cropped off. (similar to css "background-size:cover")

false : the image will be as large as possible while fitting within the height and width values.  (similar to css "background-size:contain")

array : an array that allows you to customize where the image is cropped from.

        array($x_crop_position, $y_crop_position)
        $x_crop_position = (string) 'left' || 'center' || 'right'
        $y_crop_position = (string) 'top' || 'center' || 'bottom'

$crop = true is the same as $crop = array('center', 'center');


example image size:
add_image_size('mobile-square', 320, 320, array('center', 'top') );
This will create a new image size which is 320px by 320px and is cropped from the middle and top of the image


New image sizes will only output for images added after the new size is defined and only if they are the same aspect ratio as the other sizes. The default image sizes are squared: 1024px x 1024px and 300px x 300px. These default sizes can be changed in: wp-admin > settings > media

EXMPLE OF THIS: https://www.developersq.com/add-custom-srcset-values-for-responsive-images-wordpress/



function add_image_sizes() {
    // Create new image sizes
    add_image_size( 'test-size', 600, 500, false ); // force crop true
	add_image_size( 'second-test-size', 800, 667, false ); // force crop true
}// end
add_action('init', 'add_image_sizes');


function display_image_sizes($sizes) {
    // Add custom image sizes to dropdown menu
	$sizes['test-size'] = __( 'Test size' );
	$sizes['second-test-size'] = __( 'Second Test Size' );
	return $sizes;
}
add_filter('image_size_names_choose', 'display_image_sizes');

function dq_add_custom_image_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ){	
	// image base name		
	$image_basename = wp_basename( $image_meta['file'] );
	// upload directory info array
	$upload_dir_info_arr = wp_upload_dir(null, false);
	// base url of upload directory
	$baseurl = $upload_dir_info_arr['baseurl'];
	
	// Uploads are (or have been) in year/month sub-directories.
	if ( $image_basename !== $image_meta['file'] ) {
		$dirname = dirname( $image_meta['file'] );
		
		if ( $dirname !== '.' ) {
			$image_baseurl = trailingslashit( $baseurl ) . $dirname; 
		}
	}
	$image_baseurl = trailingslashit( $image_baseurl );
	// check whether our custom image size exists in image meta	
	if( array_key_exists('test-size', $image_meta['sizes'] ) ){

		// add source value to create srcset
		$sources[ $image_meta['sizes']['test-size']['width'] ] = array(
				 'url'        => $image_baseurl .  $image_meta['sizes']['test-size']['file'],
				 'descriptor' => 'w',
				 'value'      => $image_meta['sizes']['test-size']['width'],
		);
	}
	if( array_key_exists('second-test-size', $image_meta['sizes'] ) ){

		// add source value to create srcset
		$sources[ $image_meta['sizes']['second-test-size']['width'] ] = array(
				 'url'        => $image_baseurl .  $image_meta['sizes']['second-test-size']['file'],
				 'descriptor' => 'w',
				 'value'      => $image_meta['sizes']['second-test-size']['width'],
		);
	}
	//return sources with new srcset value
	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'dq_add_custom_image_srcset', 10, 5 );


function custom_theme_content_image_sizes_attr( $sizes, $size ) {
    // Change the content of this function to include the correct widths of the image at different screen sizes and on different pages.
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'custom_theme_content_image_sizes_attr', 10 , 2 );

*/



/////////////////////////////////////////////////
///////
/////// EXTENDING WORDPRESS FUNCTIONS
///////
/////////////////////////////////////////////////

// Checks if page or it's ancestors are in a wordpress menu.
// Returns an int value: 0 if not in menu, > 0 if the page or it's parents are in menu.
// $menu is the name of the WP menu and is required.
// $object_id is the id of the page to check. Defaults to current page is not set.
// $checkparents is booleen.
function page_in_menu( $menu = null, $object_id = null, $checkparents = false ) {
    $menu_object = wp_get_nav_menu_items( esc_attr( $menu ) );
    if( ! $menu_object ){
		// If no menu set just returns false.
        return false;
	}
    $menu_items = wp_list_pluck( $menu_object, 'object_id' );
    if( !$object_id OR $checkparents ) {
		// Gets global post variable
        global $post;
		// Finds post id if non is set.
        if( !$object_id){
		 	$object_id = get_queried_object_id();
		}
		// Gets post ancestors in an array
		$pageAndAncestors = get_post_ancestors($post->id);
		// Adds post id to array
		array_push($pageAndAncestors, $object_id);
		// returns number of matching page id's in $menu_items and $pageAndAncestors
		$result = (count(array_intersect($pageAndAncestors, $menu_items)));
		return $result;
    }
	// Returns 1 if in menu, 0 if not.
    return in_array( (int) $object_id, $menu_items );
}

/**
 * Add Excerpt to pages
 *
 */
add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

/**
 * Extend the default WordPress body classes.
 *
 */
function rbd_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'homepage';
	}

	return $classes;
}
add_filter( 'body_class', 'rbd_body_classes' );



function get_id_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} else {
		return null;
	}
}


/////// Is it that page or one of the siblings? This function finds both.
function is_tree($pid)
{
  global $post;

  $ancestors = get_post_ancestors($post->$pid);
  $root = count($ancestors) - 1;
  $parent = $ancestors[$root];

  if(is_page() && (is_page($pid) || $post->post_parent == $pid || in_array($pid, $ancestors)))
  {
    return true;
  }
  else
  {
    return false;
  }
};

/////// Another way to get the content if the usual ways dont work for some reason? Or maybe to show only before the more tag.. prob need to test this!
function get_the_content_with_formatting ($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}

/* Generate a random alphanumeric string with a defined length. */
function generateString($length)
{
    $charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

    for($i=0; $i<$length; $i++)
        $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];

    return $key;
}

/////////////////////////////////////////////////
///////
/////// WORDPRESS FIXES
///////
/////////////////////////////////////////////////

/**
 * Add the ID to the Admin Area pages/posts list
 *
 */
add_filter( 'manage_posts_columns', 'revealid_add_id_column', 5 );
add_action( 'manage_posts_custom_column', 'revealid_id_column_content', 5, 2 );
add_filter( 'manage_pages_columns', 'revealid_add_id_column', 5 );
add_action( 'manage_pages_custom_column', 'revealid_id_column_content', 5, 2 );

$custom_post_types = get_post_types(
   array(
      'public'   => true,
      '_builtin' => false
   ),
   'names'
);

foreach ( $custom_post_types as $post_type ) {
	add_action( 'manage_edit-'. $post_type . '_columns', 'revealid_add_id_column' );
	add_filter( 'manage_'. $post_type . '_custom_column', 'revealid_id_column_content' );
}

function revealid_add_id_column( $columns ) {
	$checkbox = array_slice( $columns , 0, 1 );
	$columns = array_slice( $columns , 1 );

	$id['revealid_id'] = 'ID';

	$columns = array_merge( $checkbox, $id, $columns );
	return $columns;
}

function revealid_id_column_content( $column, $id ) {
  if( 'revealid_id' == $column ) {
    echo $id;
  }
}


///// Remove P tags from around images.
function img_unautop($pee) {
    $pee = preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '$1', $pee);
    return $pee;
}
add_filter( 'the_content', 'img_unautop', 30 );


/////// Hides the admin bar
function remove_admin_bar(){
  return false;
}
add_filter( 'show_admin_bar' , 'remove_admin_bar');



/**
 * Manage Contact Form 7 Scripts (only display on contact form pages)
 * Set pages below where the Contact Form 7 Scripts sit.
 * if ( ! is_page('register') && ! is_page('contact') ) {
 * add_filter( 'wpcf7_do_enqueue_scripts', '__return_empty_array' );
 * }
 * couldnt get working??
 */


function disable_wp_emojicons() {
  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}

/*
/////////////////////////////////////////////////
///////
/////// Custom nav walker
///////
/////////////////////////////////////////////////
*/
class Custom_Nav_Walker extends Walker_Nav_Menu {
    // HTML that gets added at the start of a new level. This outputs after the parents start_el() and before the end_el().
    function start_lvl(&$output, $depth = 0, $args = array())  {
        $output .= "\n<ul id=\"roles-menu\">\n";
    }
    // HTML that gets added at the end of a level.
    function end_lvl(&$output, $depth=0, $args=array()) {
        $output .= "</ul>\n";
    }

    // HTML that gets added to the begining of each element in the list.
    function start_el(&$output, $item, $depth=0, $args=array()) {
        $itemClasses = $item->classes;
        if (gettype($itemClasses) === 'array'){
            $itemClasses = implode(' ', $itemClasses );
        }
        $output .= '<li class="' . $itemClasses . '">';
        $output .= '<a href="' . $item->url . '" rel="' . $item->title . '" class="' . $itemClasses . '"">';
        $output .= '<span>' . $item->title . '</span></a>';
    }
    // HTML that gets added to the end of each element in the list.
    function end_el(&$output, $item, $depth=0, $args=array()) {
        $output .= "</li>\n";
    }
};
// Output with this in the templates:
/*
<?php
wp_nav_menu( array(
    'menu'=>'Main Menu',//Which wordpress menu to output (set in wp-admin>appearance>menus)
    'depth' => 2,// How many levels deep to go when creating the menu
    'container' => false,// If true adds a div around the ul
    'walker'=> new Custom_Nav_Walker()// Name of nav walker class
));
?>
*/

/////////////////////////////////////////////////
///////
/////// Theme Support
// ///////
// /////////////////////////////////////////////////
function register_my_menus() {
  register_nav_menus(
    array(
      'primary' => __( 'Primary Menu' ),
      'secondary' => __( 'Secondary Menu' ),
      'footer' => __( 'Footer Menu' )
    )
  );
}
add_action( 'init', 'register_my_menus' );

add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('widgets');


if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(
        array(
            'label' => 'Mobile Image',
            'id' => 'mobile-image',
            'post_type' => 'slide'
        )
    );
}