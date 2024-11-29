// Save this as js/autofiller.js in your plugin directory
jQuery(document).ready(function($) {
    let currentAddressIndex = 0;

    $('#fill-test-address').on('click', function() {
        const address = checkoutHelperAddresses[currentAddressIndex];
        
        // Fill billing fields
        $('#billing_first_name').val(address.first_name);
        $('#billing_last_name').val(address.last_name);
        $('#billing_address_1').val(address.address_1);
        $('#billing_address_2').val(address.address_2);
        $('#billing_city').val(address.city);
        $('#billing_state').val(address.state);
        $('#billing_postcode').val(address.postcode);
        $('#billing_phone').val(address.phone);

        // Trigger change events to update form state
        $('#billing_state').trigger('change');
        
        // Move to next address in rotation
        currentAddressIndex = (currentAddressIndex + 1) % checkoutHelperAddresses.length;
    });
});