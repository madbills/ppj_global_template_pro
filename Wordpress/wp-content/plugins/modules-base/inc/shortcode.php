<?php
function module_shortcode( $atts, $content = null)  {

    extract( shortcode_atts( array(
                'type' => '',               // Type of module eg 'forged_in_hiroshima'
                'id' => ''                 // ID of the parent element to bring in.
            ), $atts
        )
    );

	//$queried_post = get_page_by_path($type,OBJECT,'module');
	$queried_post = get_id_by_slug('locations');
    if ($type === 'locations') {
    		// Get the regions and their info
		   	global $post;
			$location_args = get_posts(array(
			    'showposts' => -1,
			    'order' => 'ASC',
          		'orderby' => 'menu_order',
			    'post_type' => 'module',
			    'post_parent' => $id)
			);
			$count = 0;
			$mapArea = '';
      $optionValue='';
      $regionListItem= '';

			foreach($location_args as $region) {
				$count++;
				if ($count != 1) {
					$hideClass = 'hidden';
				} else {
          $hideClass = '';
        }
				$regionName = $region->post_title;
				$regionSlug = $region->post_name;
				$regionList = _get_project_info('0', $region->post_content);
				$regionCoords = _get_project_info('1', $region->post_content);

				// Option Value
				$optionValue .= '<option value="'.$count.'">'.$regionName.'</option>';
				// Region Li
				$regionListItem .= '<li class="'.$hideClass.'" data-region="'.$count.'">';
				$regionListItem .= '<div class="region-title">';
				$regionListItem .= $regionName;
				$regionListItem .= '</div>';
				$regionListItem .= '<div class="region-opportunites">';
				$regionListItem .= $regionList;
				$regionListItem .= '</div>';
				$regionListItem .= "</li>";

				// Map Area
				// $mapArea =  $queried_post->ID ;
				$mapArea .= '<area id="region-'.$count.'" alt="'.$regionName.'" class="map-region '.$regionSlug.'" onmouseover="changeSRC('.$count.')" data-region="'.$count.'" href="#/" shape="poly" coords="'.$regionCoords.'" style="outline:none;" target="_self" />';
			}

    		// Build the output
      $output = '';
			$output .= '<div id="opportunites-list" class="col-lg-6 col-lg-offset-5 col-md-8 col-md-offset-4 col-sm-10 col-sm-offset-2 col-xs-24">';
			$output .= '<div id="region-selector" class="visible-xs">';
			$output .= '<select>'.$optionValue.'</select>';
			$output .= '<div class="arrows"></div>';
			$output .= '</div>';
			$output .= '<ul class="regions-list">';
			$output .= $regionListItem;
			$output .= '</ul>';
			$output .= '</div>';
			$output .= '<div class="col-lg-6 col-lg-offset-1 col-md-8 col-md-offset-1 col-sm-10 col-sm-offset-1 hidden-xs">';
			$output .= '<div id="opportunites-map-outer">';
			$output .= '<img id="opportunites-map-image" src="'.get_bloginfo('template_url').'/images/map/map-default.png" border="0" width="284" height="493" orgWidth="284" orgHeight="493" usemap="#opportunites-map" alt="" />';
			$output .= '<map name="opportunites-map" id="opportunites-image-map">';
			$output .= '<area shape="rect" coords="282,491,284,493" alt="Image Map" style="outline:none;" title="Image Map" href="#/" />';
			$output .= $mapArea;
			$output .= '</map>';
			$output .= '</div>';
			$output .= '</div>';


    }
    
    
    if ($type === 'email-form') {
    		// Get the regions and their info
		   	global $post;
			$emailArgs = get_posts(array(
			    'include' => $id,
			    'order' => 'ASC',
         		'orderby' => 'menu_order',
			    'post_type' => 'module'
			    )
			);

			foreach($emailArgs as $email) {
				$output = $email->post_content;
			
			}

    }
    
    if ($type === 'annotated-image') {
    		// Get the regions and their info
		 	global $post;
			$imgArgs = get_posts(array(
			    'include' => $id,
			    'order' => 'ASC',
         		'orderby' => 'menu_order',
			    'post_type' => 'module'
			    )
			);
		
			foreach ($imgArgs as $imgPost) {
				$count = 0;
				// Get the Featured image
				$slideImgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($imgPost->ID), 'full');
				
				// Get the content for each more tag
				$matchContent = preg_split("/<!--more(.*?)-->/", $imgPost->post_content);
				array_splice($matchContent, 0, 1);

				preg_match_all("/<!--more(.*?)-->/", $imgPost->post_content, $matchClass);
				$matchClass = $matchClass[1];

				foreach ($matchContent as $matchContentPart) {
					$singlePanel .= '<a href="javascript:void(0);" class="popover-common popover-'.($count+1).'" data-toggle="popover" data-placement="top" title="'.$matchClass[$count].'" data-content="'.$matchContentPart.'"><img src="images/plus-sign.png"></a>';
					$count++;
				}
                $panelWrap .= '<div class="img-banner"><img class="image" src="'.$slideImgSrc[0].'"><div class="pizza-popups">';
                $panelWrap .= $singlePanel;
				$panelWrap .= '</div></div>';
				
			}
			$output = $panelWrap;
    }    


    return $output  ;

}
