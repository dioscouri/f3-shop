if (typeof(Shop) === 'undefined') {
    var Shop = {};
}

Shop.executeFunctionByName = function(functionName, context /*, args */) {
    var args = Array.prototype.slice.call(arguments, 2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for (var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }
    if (typeof context[func] == 'function') {
        return context[func].apply(context, args);
    }
    return null;
}

Shop.setupSelectVariantCallback = function() {
    var select = jQuery('select.select-variant');
    if (select.length) {
        select.on('change', function(){
            var selected = select.find("option:selected");
            var variant = jQuery.parseJSON( selected.attr('data-variant') );
            var callback = select.attr('data-callback');
            if (callback) {
                Shop.executeFunctionByName(callback, window, variant, select);
            }
        });
    }
}

Shop.selectVariant = function(variant, select) {
    // Update the product image
    if (variant && variant.image) {
    	new_image = variant.image;
        jqzoom = jQuery('.product-image a.zoom').data('jqzoom');
        if (jqzoom) {
            jqzoom.swapimage( jQuery('.zoom-thumbs #'+new_image+' a') );
        } else {
            jQuery('.product-image img').attr('src', './asset/thumb/'+new_image );
        }        
    }
    
    // update the product price
    if (variant && variant.price) {
    	jQuery('.price').text(variant.price);
    }
    
}

jQuery(document).ready(function() {
    Shop.setupSelectVariantCallback();
});