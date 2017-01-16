/* This is the core JS file for redballoon projects. This is the uncompiled JS that is loaded onto all pages of a site. If this file is large then minify it and load the minified version on the production site */
var $;
$ = jQuery;


/* ABOUT US */
$(document).ready(function() {
    $('.btn-toggle-menu').find('span').html($('#nav-collapse').find('li.active a').text());
    $('#nav-collapse').on('show.bs.collapse', function() {
        $(this).prev().find('i').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top');
    });
    $('#nav-collapse').on('hide.bs.collapse', function() {
        $(this).prev().find('i').removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');
    });
});
/* EMAIL */
$(document).ready(function() {
    $("input[type=radio]:checked").addClass("checked");
    $("input[type=radio]").on("change", function() {
        if ($(this).is(":checked")) {
            $(this).addClass("checked");
        } else {
            $(this).removeClass("checked");
        }
    });


    $("#subscribeForm").validate();
});
/* HOME */
$(document).ready(function() {
    var owlSlider = $("#slider");
    if (!owlSlider.hasClass('no-owl')) {
        owlSlider.owlCarousel({

            // autoPlay: 3000, //Set AutoPlay to 3 seconds   

            items: 3, // set number of items in the bottom carousel
            itemsMobile: [767, 1],
            itemsTablet: [991, 2],
            itemsDesktopSmall: [1170, 3],
            itemsDesktopSmall: [1217, 3]

        });
        $(".feature-secondary-carousel .right-arrow").click(function(e) {

            owlSlider.trigger('owl.next');

            /*..................For left arrow................*/

        });
        $(".feature-secondary-carousel .left-arrow").click(function(e) {

            owlSlider.trigger('owl.prev');

        });
    }
});

/* OUR PIZZA */


$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('.popover-common').on('shown.bs.popover', function() {
        $(this).addClass('close-sign rotateIn').removeClass('rotateOut');
    });
    $('.popover-common').on('hidden.bs.popover', function() {
        $(this).removeClass('close-sign rotateIn').addClass('rotateOut');
    });
    $('body').on('click', function(e) {
        $('[data-toggle="popover"]').each(function() {
            if (!$(this).is(e.target) && $(this).hasClass('close-sign')) {
                $(this).popover('hide');
            }
        });
    });
});

/* SIDENAV */
$(document).ready(function() {
    $('.btn-toggle-menu').find('span').html($('#nav-collapse').find('li.active a').text());
    $('#nav-collapse').on('show.bs.collapse', function() {
        $(this).prev().find('i').removeClass('glyphicon-triangle-bottom').addClass('glyphicon-triangle-top');
    });
    $('#nav-collapse').on('hide.bs.collapse', function() {
        $(this).prev().find('i').removeClass('glyphicon-triangle-top').addClass('glyphicon-triangle-bottom');
    });
});