<?php
function tabs_shortcode( $atts, $content = null ) {
    wp_enqueue_script('tabs-script');
    extract( shortcode_atts( array(
                'include' => '', // comma seperated list of ints
                'exclude' => '', // comma seperated list of ints
                'parent' => 'none', // int
                'selector_width' => '' // equal
            ), $atts ) );

    // Unique number for each of the Tabs shortcodes on the page.
    static $numberOfTabWraps = 0;
    $numberOfTabWraps++;

    // If parent isn't set then revert value to default: -1
    if ($parent === 'none') {
        $parent_id = -1;
    } else {
        $parent_id = intval($parent);
    }

    // Get tabs.
    $args = array(
        'sort_order' => 'ASC',
        'sort_column' => 'menu_order',
        'include' => $include,
        'exclude' => $exclude,
        'post_type' => 'tab',
        'post_status' => 'publish',
        'parent' => $parent_id,
        'orderby' => 'menu_order', 
        'order' => 'ASC'
    );
    $tabs = get_posts($args);

    // Outout variables.
    $output = '';
    $output .= '<div id="tabs'.$numberOfTabWraps.'" class="tabs-outer">'; // Outer wrap

    $tabSelector = '<div class="tabs-wrap"><ul class="nav nav-tabs no-bullets">'; // Wrap around the selectors

    $tabsContent = '<div class="tab-content tab-data">'; // wrap around tab content

    // If selector_width is set to equal then make the selecors an equal width. Combine this with display inline block or floating the elements to get them to appear next to each other.
    if ($selector_width === 'equal') {
        $set_width = 100 / count($tabs);
        $width_style = 'style="width:'.$set_width.'%;"';
    } else {
        $width_style = '';
    }

    $numberOfTabs = 0;
    foreach ($tabs as $tab) {
        // Increment number of tabs
        $numberOfTabs++;

        // Active tab class
        if ($numberOfTabs == 1) {
            $activeTab = 'active';
        } else {
            $activeTab = '';
        }

        // Title of tab
        $tabTitle = $tab->post_title;
        
        // Tab Excerpt
        $tabExcerpt = $tab->post_excerpt;

        // Tab Slug
        $tabSlug = $tab->post_name;

        // Content from tab
        $tabContent = apply_filters( 'the_content', $tab->post_content);

        // Image for tab
        $tabImage = wp_get_attachment_image_src( get_post_thumbnail_id($tab->ID), 'large' )[0];

        // HTML for the selector:
        $tabSelector .= '<li class="'.$activeTab.'"><a data-toggle="tab" href="#'.$tabSlug.'">'.$tabExcerpt.'</a></li>';

        // HTML for the content:
        $tabsContent .= '<div id="'.$tabSlug.'" class="tab-pane fade in '.$activeTab.'">';
        $tabsContent .= '<h2>'.$tabTitle.'</h2>';
        $tabsContent .= $tabContent;
        $tabsContent .= '</div>';
    }

    // Close off elements:
    $tabSelector .= '</ul>';
    $tabsContent .= '</div></div>';

    $output .= $tabSelector;
    $output .= $tabsContent;
    $output .= '</div><!-- Tab number '.$numberOfTabWraps.' -->';

    return $output;
}

?>