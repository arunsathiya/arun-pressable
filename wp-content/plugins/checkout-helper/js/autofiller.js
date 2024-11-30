jQuery(function($) {
    'use strict';
    
    // Make sure WooCommerce checkout is available
    if (typeof wc_checkout_params === 'undefined') {
        console.error('WooCommerce checkout params not found');
        return;
    }

    const addresses = checkoutHelperData.addresses;

    function fillAddress(addressIndex) {
        if (!addresses[addressIndex]) {
            console.error('Address index not found');
            return;
        }

        const address = addresses[addressIndex];
        
        // Disable form updates temporarily
        $(document.body).trigger('update_checkout');
        
        // Fill each field
        Object.keys(address).forEach(field => {
            const $field = $(`#${field}, [name="${field}"]`);
            
            if ($field.length) {
                $field.val(address[field]).trigger('change');
                
                // Handle select fields differently
                if ($field.is('select')) {
                    $field.trigger('select2:select');
                }
            } else {
                console.warn(`Field ${field} not found in the form`);
            }
        });

        // Re-enable form updates
        $(document.body).trigger('update_checkout');
        
        // Show success message
        const message = $('<div>')
            .addClass('woocommerce-message')
            .text('Test address filled successfully')
            .insertBefore('#checkout-helper-buttons');
            
        setTimeout(() => message.fadeOut(400, function() { $(this).remove(); }), 3000);
    }

    // Bind click events to buttons
    $('#fill-test-address-1').on('click', function(e) {
        e.preventDefault();
        fillAddress(0);
    });

    $('#fill-test-address-2').on('click', function(e) {
        e.preventDefault();
        fillAddress(1);
    });

    // Remove any duplicate buttons that might have been added
    $('.checkout-helper-section').not(':first').remove();
});