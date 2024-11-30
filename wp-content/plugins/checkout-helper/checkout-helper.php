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
            'billing_first_name' => 'Test',
            'billing_last_name' => 'User',
            'billing_address_1' => '123 Test St',
            'billing_address_2' => 'Apt 4B',
            'billing_city' => 'San Francisco',
            'billing_state' => 'CA',
            'billing_postcode' => '94107',
            'billing_phone' => '555-0123',
            'billing_email' => 'test@example.com'
        ],
        [
            'billing_first_name' => 'Sample',
            'billing_last_name' => 'Customer',
            'billing_address_1' => '456 Demo Ave',
            'billing_address_2' => 'Suite 789',
            'billing_city' => 'Los Angeles',
            'billing_state' => 'CA',
            'billing_postcode' => '90001',
            'billing_phone' => '555-0456',
            'billing_email' => 'sample@example.com'
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
        if (!is_checkout()) {
            return;
        }

        wp_enqueue_script(
            'checkout-helper',
            plugins_url('js/autofiller.js', __FILE__),
            ['jquery', 'wc-checkout'],
            filemtime(plugin_dir_path(__FILE__) . 'js/autofiller.js'),
            true
        );

        wp_localize_script(
            'checkout-helper',
            'checkoutHelperData',
            [
                'addresses' => $this->test_addresses,
                'nonce' => wp_create_nonce('checkout_helper'),
                'ajaxurl' => admin_url('admin-ajax.php')
            ]
        );
    }

    public function add_autofill_button() {
        ?>
        <div id="checkout-helper-buttons" class="checkout-helper-section" style="margin-bottom: 20px;">
            <button type="button" id="fill-test-address-1" class="button alt" style="margin-right: 10px;">
                Fill Test Address 1
            </button>
            <button type="button" id="fill-test-address-2" class="button alt" style="margin-right: 10px;">
                Fill Test Address 2
            </button>
            <small style="color: #666;">Click to automatically fill the form with test data</small>
        </div>
        <?php
    }
}

new Checkout_Helper();