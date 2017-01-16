/*
Javascript for the backend of the miso plugin.
*/

var $;

$ = jQuery;
var $outer_wrap;
var $inner_wrap;


function createButton(text, action, cssClass) {
    // Create buttons
    var cssClass = cssClass || 'add_more_button';
    var output = '';
    text = text || 'Add another';
    output += '<div class="miso_button ' + cssClass + '" data-action="' + action + '"><span>' + text + '</span></div>';
    return output;
}

function createSize(n1, n2, name, amount) {
    // Create sizes
    var n1 = n1 || 0;
    var n2 = n2 || 0;

    name = name || '';
    amount = amount || '';
    var output = '<div class="size-variables clearfix"> ';
    output += '<label for="_variations[' + n1 + '][sizes][' + n2 + '][name]" class="clearfix">Size Name<input type="text" name="_variations[' + n1 + '][sizes][' + n2 + '][name]" value="' + name + '" class="name"></input></label>';
    output += '<label for="_variations[' + n1 + '][sizes][' + n2 + '][value]" class="clearfix">Size Value<input type="text" name="_variations[' + n1 + '][sizes][' + n2 + '][value]" value="' + amount + '" class="value"></input></label>';
    output += '</div> ';
    return output;
}

function createVaraition(n, value, sizes) {
    // Create variations
    var value = value || '';
    var n = n || 0;
    var sizes = sizes || [];

    var output = '';
    output += '<div class="single_variation clearfix" data-n="' + n + '">';
    output += '<label for="_variations[' + n + '][variation_name]" class="clearfix">Variation Name<input type="text" name="_variations[' + n + '][variation_name]" value="' + value + '" class="variation"></input></label>';
    if (sizes.length > 0) {
        for (var i = 0; i < sizes.length; i++) {
            var sizeHtml = createSize(n, i, sizes[i].name, sizes[i].value);
            output += sizeHtml;
        }
    }
    var addSizeButton = '';
    addSizeButton = createButton('Add size', 'add_size');
    removeSizeButton = createButton('Remove size', 'remove_size', 'remove_button');
    output += addSizeButton;
    output += removeSizeButton;
    output += '</div>';
    return output;
}

$(window).load(function() {
    // Settup
    $outer_wrap = $('#variations');
    var $inner_wrap = $('<div class="variations-inner-wrap clearfix"></div>');
    $outer_wrap.append($inner_wrap);

    // Click events for the buttons
    $outer_wrap.on('click', '.miso_button', function(event) {
        event.stopPropagation();
        var action = $(this).attr('data-action');
        if (action === 'add_variation') {
            var noVaraitions = $('.single_variation', '#variations').length;
            console.log(noVaraitions);
            var returnHtml = '';
            returnHtml += createVaraition(noVaraitions);
            $(this).before(returnHtml);
        } else if (action === 'add_size') {
            var $parent = $(this).parent();
            var noSizes = $parent.find('.name').length;
            var noVariations = $parent.attr('data-n');
            var returnHtml = '';
            returnHtml += createSize(noVariations, noSizes);
            $(this).before(returnHtml);
        } else if (action === 'remove_size') {
            $(this).prevAll('.size-variables').eq(0).remove();
        } else if (action === 'remove_variaition') {
            $(this).prevAll('.single_variation').eq(0).remove();
        }
    })

    // Create the form
    var combinedOutput = '';
    if (typeof current_variation_info == 'object' && current_variation_info.length > 0) {
        for (var i = 0; i < current_variation_info.length; i++) {
            var variation = '';
            variation = createVaraition(i, current_variation_info[i].variation_name, current_variation_info[i].sizes);
            combinedOutput += variation;
        };
    }
    var addNewVariation = createButton('Add a Variation', 'add_variation');
    var removeVariaition = createButton('Remove a Variation', 'remove_variaition', 'remove_button');
    $inner_wrap.append(combinedOutput);
    $inner_wrap.append(addNewVariation + removeVariaition);


});