<?php

/**
 * Display WooCommerce Products professionally
 *
 * @package wpwebmaster0313.
 * @since 1.0.0
 */

/*
Plugin Name: Olek Products Pro
Plugin URI:
Description: Olek Products Pro plugin is to customize products layout and provide extensions for category and subcategory pages
Author: wpwebmaster0313.
Version: 1.0
Author URI:
Tags: woocommerce, products, categories, subcategories, product, category, subcategory
Text Domain: olek-products-pro
*/

// direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}
define( 'OLEK_PRO_URI', plugin_dir_url( __FILE__ ) );
define( 'OLEK_PRO_PATH', plugin_dir_path( __FILE__ ) );

class Olek_Products_Pro {

	/**
	 * Constructor
	 *
	 * @since 1.0
	 *
	*/
	public function __construct() {
		// plugin load
		add_action( 'plugins_loaded', array( $this, 'load' ) );

		// enqueue script & style
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ), 20 );

	}

	public function load() {
		require_once OLEK_PRO_PATH . '/elementor/init.php';
		require_once OLEK_PRO_PATH . '/functions/core-functions.php';
		require_once OLEK_PRO_PATH . '/add-on/init.php';
	}

	public function enqueue() {
		wp_enqueue_script( 'olek_products_pro_script', plugin_dir_url( __FILE__ ) . 'assets/script.js', array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'olek_products_pro_style', plugin_dir_url( __FILE__ ) . 'assets/style.css', null, '1.0', 'all' );
	}

}

new Olek_Products_Pro;
