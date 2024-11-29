// Save this as js/autofiller.js in your plugin directory
jQuery(document).ready(function($) {
    console.log('Checkout Helper loaded');
    console.log('Available addresses:', checkoutHelperAddresses);
    
    let currentAddressIndex = 0;

    $('#fill-test-address').on('click', function() {
        console.log('Fill address button clicked');
        const address = checkoutHelperAddresses[currentAddressIndex];
        console.log('Using address:', address);
        
        // Fill billing fields
        $('#billing_first_name').val(address.first_name).trigger('change');
        $('#billing_last_name').val(address.last_name).trigger('change');
        $('#billing_address_1').val(address.address_1).trigger('change');
        $('#billing_address_2').val(address.address_2).trigger('change');
        $('#billing_city').val(address.city).trigger('change');
        $('#billing_state').val(address.state).trigger('change');
        $('#billing_postcode').val(address.postcode).trigger('change');
        $('#billing_phone').val(address.phone).trigger('change');

        // Force WooCommerce to update
        $(document.body).trigger('update_checkout');
        
        // Move to next address in rotation
        currentAddressIndex = (currentAddressIndex + 1) % checkoutHelperAddresses.length;
        
        console.log('Address filled, moved to index:', currentAddressIndex);
    });
});