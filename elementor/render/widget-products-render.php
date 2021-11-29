<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Woo Custom Products Widget Render
 */

if ( class_exists( 'WooCommerce' ) ) {
	require_once OLEK_PRO_PATH . '/functions/woocommerce/product-loop.php';
}

add_olek_custom_loop_content();

$shortcode = $this->get_shortcode_object( $settings );

$content = $shortcode->get_content();

if ( $content ) {
	$content = str_replace( '<ul class="products', '<ul class="products elementor-grid', $content );

	echo $content;
} elseif ( $this->get_settings( 'nothing_found_message' ) ) {
	echo '<div class="elementor-nothing-found elementor-products-nothing-found">' . esc_html( $this->get_settings( 'nothing_found_message' ) ) . '</div>';
}

remove_olek_custom_loop_content();
