/**
 * This JS file is for the custom tabs plugin made by Red Balloon Design.
 * When clicking a tab it removes the active tab class from all the tabs and tab
 * selectors and then adds the class to the correct selector and tab.
 * This also works with multiple tabs on the same page.
 */

jQuery(document).ready(function($) {
    $('.tabs-outer').on('click', '.tab-selector li a', function(event) {
        var $outerWrap = $(this).closest('.tabs-outer');
        var targetNo = $(this).attr('data-target');

        $outerWrap.find('.tab-selector li a').removeClass('active-tab');
        $(this).addClass('active-tab');

        $outerWrap.find('.tab').removeClass('active-tab');
        $outerWrap.find('.tab-' + targetNo).addClass('active-tab');
    });
});