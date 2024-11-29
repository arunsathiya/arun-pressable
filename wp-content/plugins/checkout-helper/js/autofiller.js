jQuery(document).ready(function($) {
    console.log('Checkout Helper loaded');

    const testAddress = {
        'billing_first_name': 'Test',
        'billing_last_name': 'User',
        'billing_address_1': '123 Test St',
        'billing_address_2': 'Apt 4B',
        'billing_city': 'San Francisco',
        'billing_state': 'CA',
        'billing_postcode': '94107',
        'billing_phone': '555-0123',
        'billing_email': 'test@example.com'
    };

    // Add a button to the page
    $('<button>', {
        id: 'fill-test-address',
        text: 'Fill Test Address',
        class: 'button alt',
        css: {
            'margin': '10px 0'
        },
        click: function(e) {
            e.preventDefault();
            console.log('Filling form...');
            
            // Fill each field and log the operation
            Object.keys(testAddress).forEach(field => {
                const $field = $(`input[name="${field}"]`);
                console.log(`Setting ${field} to ${testAddress[field]}`);
                if ($field.length) {
                    $field.val(testAddress[field]).trigger('change');
                } else {
                    console.log(`Field ${field} not found`);
                }
            });

            // Set state specifically since it might be a select
            $('#billing_state, select[name="billing_state"]').val('CA').trigger('change');

            // Force form update
            $('form.checkout').trigger('update');
        }
    }).prependTo('form.checkout');
});