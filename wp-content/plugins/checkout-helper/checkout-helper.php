<?php
/**
 * Plugin Name: Simple Checkout Helper
 * Description: Automatically fills checkout forms with test address
 * Version: 1.0
 * Author: arunsathiya
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * WC requires at least: 3.0
 * WC tested up to: 8.5
 */

if (!defined('ABSPATH')) {
    exit;
}

class Simple_Checkout_Helper {
    private $test_data = [
        'billing_first_name' => 'Test',
        'billing_last_name'  => 'User',
        'billing_company'    => 'Test Company',
        'billing_address_1'  => '123 Test St',
        'billing_address_2'  => 'Apt 4B',
        'billing_city'       => 'San Francisco',
        'billing_state'      => 'CA',
        'billing_postcode'   => '94107',
        'billing_country'    => 'US',
        'billing_phone'      => '555-0123',
        'billing_email'      => 'test@example.com'
    ];

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
        
        // Add HPOS and Remote Logging compatibility
        add_action('before_woocommerce_init', function() {
            if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('remote_logging', __FILE__, true);
            }
        });
    }

    public function init() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p>Checkout Helper requires WooCommerce to be installed and active.</p></div>';
            });
            return;
        }

        // Add filter for each checkout field
        foreach ($this->test_data as $field => $value) {
            add_filter("woocommerce_checkout_get_value_{$field}", [$this, 'pre_fill_checkout_fields'], 10, 1);
        }
    }

    public function pre_fill_checkout_fields($input) {
        // Only fill if the field is empty
        if (empty($input)) {
            $field = str_replace('woocommerce_checkout_get_value_', '', current_filter());
            return isset($this->test_data[$field]) ? $this->test_data[$field] : $input;
        }
        return $input;
    }
}

new Simple_Checkout_Helper();