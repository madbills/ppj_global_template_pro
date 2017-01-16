<?php

function cta_shortcode( $atts, $content = null)  {

    extract( shortcode_atts( array(
                'parent' => '',               // ID of the list of the CTA's top bring in (If blank fetches all CTA posts
                'exclude' => '',			 // ID's of CTA's to exclude from the output
                'include' => ''				 // Includes certain CTA's, overriding Parent and Exclude values.
            ), $atts
        )
    );
	$ctaImgsrc = '';
	$output = '';
	$cTAoutput = '';

	$CTAcount = 0;

	global $post;
    $cta_pages = get_pages(array(
	    'sort_order' => 'ASC',
	    'sort_column' => 'menu_order',
	    'exclude' => $exclude,
	    'include' => $include,
	    'post_type' => 'cta',
	    'parent' => $parent)
	);

	// Run through each CTA and extract the data as needed and place it in the HTML, then place that element in an array.
	foreach($cta_pages as $cta) {

				$cta_url = get_post_meta($cta->ID, '_cta_url', true);
				$cta_url = gettype($cta_url) === 'array' ? $cta_url['_cta_url']:$cta_url;
				$img = get_the_post_thumbnail($cta->ID, 'full');

				$img = gettype($img) === 'array' ? $img[0] : $img;

				$title = $cta->post_title;	 // Post title
				$excerpt = $cta->post_excerpt;	 // Post excerpt


				$cTAoutput .= '<div class="item">';

				if (!empty($cta_url)) {
					$cta_url = gettype($cta_url) === 'array' ? $cta_url[0] : $cta_url;
                    // Checks if internal or external link:
                    if ( mb_substr($cta_url, 0, 4) === 'http' ) {
                        // external
                        $cTAoutput	.= '<a href="'.$cta_url.'">';
                    } else {
                        // internal
                        $cTAoutput	.= '<a href="'.get_bloginfo('url').$cta_url.'">';
                    }
                }

				$cTAoutput .= $img;
				$cTAoutput .= '<div class="item-text">';
				$cTAoutput .= '<h3>'.$title.'</h3>';
				$cTAoutput .= $excerpt;
                $cTAoutput .= '</div>';

				if (!empty($cta_url)) {
					$cTAoutput .= '</a>';
				}
				
				$cTAoutput .= '</div><!-- Item -->';
				$CTAcount++;
	}

	$parentVariable ='';
	if ($CTAcount <= 3) {
		$parentVariable = 'no-owl';// If a parent has been selected, we may need to write some custom code to display those CTA's in a wrapper or with custom classes
	}

	// Output our CTAs in our pre-determined wrapper
	$output .= '<div class="feature-secondary-carousel">';
	if ($CTAcount > 3){
		$output .= '<a href="javascript:void(0)" class="left-arrow"><img src="'.get_bloginfo( 'template_url' ).'/images/owl-left.png"></a>';
	}
	$output .= '<div id="slider" class="cta-wrap '.$parentVariable.'" data-slides="'.$CTAcount.'">';	
	$output .= $cTAoutput;
	$output .= '</div>';
	if ($CTAcount > 3){
		$output .= '<a href="javascript:void(0)" class="right-arrow"><img src="'.get_bloginfo( 'template_url' ).'/images/owl-right.png"></a>';
	}
	$output .= '</div>';

    wp_enqueue_style('cta-styles');

	return $output;
}
?>
