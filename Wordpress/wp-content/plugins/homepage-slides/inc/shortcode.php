<?php
function slides_shortcode( $atts, $content = null)  {
    // slides_enqueue_scripts();
    extract( shortcode_atts( array(
                'carousel_class' => '', // Class name that gets added to the carousel
                'carousel_id' => '',   // ID that gets given to the carousel. Creates an incremental one if multiple are used.
				'parent' => '',	    	// Carousel  to be used (The parent page ID - only in use for more than one carousel)
				'include' => '',	    	// Include ID's for specific slides (overrides parent)
    			'indicators' => 'show',		// Show/Hide - Display the carousel indicators
                'arrows' => 'show',         // Show/Hide - Display the carousel arrows
                'exclude' => '',		   		// List of slides to exclude
                'interval' => '5000',           // Delay for the slideshow
                'swipe' => 'true',             // Set active if swipe effect is used.
                'include' => ''				 // Includes certain CTA's, overriding Parent and Exclude values.
            ), $atts
        )
    );
    
	$slideIndicator = '';
	$outputIndicators = '';
	$slideContent = '';

    static $number_of_carousels=0;
    $number_of_carousels++;
    if ($carousel_id == ''){
        $carousel_id = 'carousel_'.$number_of_carousels;
    }

    $swipeClass = 'noswipe';
    if ($swipe === 'true'){
        $swipeClass = 'swipe-enabled';
        // wp_enqueue_script('slides-swipe-script'); // Registered in homepage-slides.php
    };

	// When the shortcode runs, get the custom posts as slides. If Carousel is a value it will select only slides from under that parent.
	$homepageSlides = get_posts(array(
	    // 'showposts' => -1,
        'orderby' => 'menu_order',
	    'order' => 'ASC',
        'include' => $include,
	    'exclude' => $exclude,
	    'post_type' => 'slide',
	    'post_parent' => $parent)
	);
	$count = -1; // Count set to minus 1 so first post is counted as 0 (for bootstrap carousel).
		// Run through each slide individually and get the data from WP.
	foreach ($homepageSlides as $slide) {
		$count++;

		$homeid = $slide->ID;	// Slide ID to get the Featured Image
		$slideImgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($slide->ID), 'full'); // Get the Featured Image
        $slideImgSrc = gettype($slideImgSrc) === 'array' ? $slideImgSrc[0] : $slideImgSrc;

        $slideMobileImg = MultiPostThumbnails::get_the_post_thumbnail(get_post_type($slide->ID), 'mobile-image', $slide->ID, 'full');

        $slideMainClass = get_post_meta($slide->ID, '_slidemainclass', true);
		$slideBGClass = get_post_meta($slide->ID, '_slidebgclass', true);
		$slideLink = get_post_meta($slide->ID, '_slidelink', true);

		$title = $slide->post_title;
		$content = $slide->post_content;

		// Set the first slide to "active"
		if ($count === 0) {
			$activeSlideIndicator = 'class="active"';
			$activeSlideContent = 'active';
		} else {
			$activeSlideIndicator = '';
			$activeSlideContent = '';
		}

		// Display code for individual indicators
		$slideIndicator .= '<li data-target="#'.$carousel_id.'" data-slide-to="'.$count.'" '. $activeSlideIndicator .'></li> ';

		// Display code for individual slides
		$slideContent	.= '<div class="item '. $activeSlideContent . ' ' . $slideMainClass.'">';
        if (!empty($slideLink)) {
            // Checks if internal or external link:
            if ( mb_substr($slideLink, 0, 4) === 'http' ) {
                // external
                $slideContent	.= '<a href="'.$slideLink.'">';
            } else {
                // internal
                $slideContent	.= '<a href="'.get_bloginfo('url').$slideLink.'">';
            }
        }
        if (!empty($slideMobileImg)) {
            $slideContent	.= '<img src="'.$slideImgSrc.'" alt="hero-banner" class="hidden-xs"/>';
            $slideContent	.= $slideMobileImg;
        } else {
            $slideContent	.= '<img src="'.$slideImgSrc.'" alt="hero-banner"/>';
        }
        if (!empty($slideLink)) {
            $slideContent 	.= '</a>';
        }
        $slideContent	.= '</div><!--item-->';
    }

    if ($indicators != 'hide') {
		// do the code to show indicators
		$outputIndicators .= '<ol class="carousel-indicators">';
		$outputIndicators .= $slideIndicator;
		$outputIndicators .= '</ol>';
    }
    $carouselArrows = '';
    if ($arrows === 'show') {
        $carouselArrows .= '<!-- Carousel controls -->';

        $carouselArrows .= '<a class="carousel-control left" href="#'.$carousel_id.'" data-slide="prev">';
        $carouselArrows .= '<span class="glyphicon glyphicon-chevron-left"></span>';
        $carouselArrows .= '</a>';

        $carouselArrows .= '<a class="carousel-control right" href="#'.$carousel_id.'" data-slide="next">';
        $carouselArrows .= '<span class="glyphicon glyphicon-chevron-right"></span>';
        $carouselArrows .= '</a>';
    }

    // Main output of the slider:
    $output  = '';
    $output .= '<div id="'.$carousel_id.'" class="carousel slide '.$carousel_class.' '.$swipeClass.'" data-ride="carousel">';
    $output .= $outputIndicators;
    $output .= $carouselArrows;
    $output	.= '<div class="carousel-inner" role="listbox">';
    $output	.= $slideContent;
    $output	.= '</div>';
    $output	.= '</div>';

    return $output ;

}
?>
