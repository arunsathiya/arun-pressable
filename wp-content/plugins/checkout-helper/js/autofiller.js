// Save this as js/autofiller.js in your plugin directory
jQuery(document).ready(function($) {
    console.log('Checkout Helper loaded');
    console.log('Available addresses:', checkoutHelperAddresses);
    
    let currentAddressIndex = 0;

    // Make sure button exists and is clickable
    $(document).on('click', '#fill-test-address', function(e) {
        e.preventDefault();
        console.log('Fill address button clicked');
        const address = checkoutHelperAddresses[currentAddressIndex];
        console.log('Using address:', address);
        
        // Try both billing_ and billing- prefixes
        const fields = {
            'billing_first_name': address.first_name,
            'billing-first-name': address.first_name,
            'billing_last_name': address.last_name,
            'billing-last-name': address.last_name,
            'billing_address_1': address.address_1,
            'billing-address-1': address.address_1,
            'billing_address_2': address.address_2,
            'billing-address-2': address.address_2,
            'billing_city': address.city,
            'billing-city': address.city,
            'billing_state': address.state,
            'billing-state': address.state,
            'billing_postcode': address.postcode,
            'billing-postcode': address.postcode,
            'billing_phone': address.phone,
            'billing-phone': address.phone
        };

        // Fill all possible field variations
        Object.keys(fields).forEach(function(fieldId) {
            // Try with # prefix
            $(`#${fieldId}`).val(fields[fieldId]).trigger('change');
            // Try with name attribute
            $(`[name="${fieldId}"]`).val(fields[fieldId]).trigger('change');
        });

        // Force WooCommerce to update
        $(document.body).trigger('update_checkout');
        
        // Move to next address in rotation
        currentAddressIndex = (currentAddressIndex + 1) % checkoutHelperAddresses.length;
        console.log('Address filled, moved to index:', currentAddressIndex);
    });
});