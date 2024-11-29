<?php
/**
 * Plugin Name: Checkout Helper
 * Description: Automatically fills checkout forms with test addresses
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

class Checkout_Helper {
    private $test_addresses = [
        [
            'first_name' => 'Test',
            'last_name' => 'User',
            'address_1' => '123 Test St',
            'address_2' => 'Apt 4B',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postcode' => '94107',
            'phone' => '555-0123'
        ],
        [
            'first_name' => 'Sample',
            'last_name' => 'Customer',
            'address_1' => '456 Demo Ave',
            'address_2' => 'Suite 789',
            'city' => 'Los Angeles',
            'state' => 'CA',
            'postcode' => '90001',
            'phone' => '555-0456'
        ]
    ];

    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
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

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('woocommerce_before_checkout_form', [$this, 'add_autofill_button']);
    }

    public function enqueue_scripts() {
        if (!function_exists('is_checkout') || !is_checkout()) {
            return;
        }

        wp_enqueue_script(
            'checkout-helper',
            plugins_url('js/autofiller.js', __FILE__),
            ['jquery', 'wc-checkout'],
            time(), // Use time() for development to prevent caching
            true
        );

        wp_localize_script(
            'checkout-helper',
            'checkoutHelperAddresses',
            $this->test_addresses
        );
    }

    public function add_autofill_button() {
        ?>
        <div style="margin-bottom: 20px;">
            <button type="button" id="fill-test-address" class="button alt" style="margin-right: 10px;">
                Fill Test Address
            </button>
            <small style="color: #666;">Click to automatically fill the form with test data</small>
        </div>
        <?php
    }
}

new Checkout_Helper();