<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

define( 'OLEK_ADDON', OLEK_PRO_PATH . '/add-on' );
define( 'OLEK_ADDON_URI', OLEK_PRO_URI . '/add-on' );

if ( ! isset( $_GET['action'] ) || 'yith-woocompare-view-table' != $_GET['action'] ) {
	// $doing_ajax        = olek_doing_ajax();
	$customize_preview = is_customize_preview();
	$is_admin          = is_admin();

	/**
	 * Skeleton Screen
	 *
	 * @since 1.0.0
	 */
	// if ( ! $doing_ajax && ! $customize_preview && ! $is_preview && olek_get_option( 'skeleton_screen' ) ) {
	// 	require_once( OLEK_ADDON . '/skeleton/skeleton.php' );
	// }


	/**
	 * WooCommerce Add-ons
	 */
	if ( class_exists( 'WooCommerce' ) ) {
		global $pagenow;

		$product_edit_page = ( 'post-new.php' == $pagenow && isset( $_GET['post_type'] ) && 'product' == $_GET['post_type'] ) ||
							( 'post.php' == $pagenow && isset( $_GET['post'] ) && 'product' == get_post_type( $_GET['post'] ) );

		/**
		 * Product Custom Tabs
		 *
		 * @since 1.0.0
		 */
		if ( $is_admin && ( wp_doing_ajax() || $product_edit_page ) ) {
			// require_once( OLEK_ADDON . '/product-custom-tab/product-custom-tab-admin.php' );
			require_once( OLEK_ADDON . '/product-addon/product-addon.php' );
		}
	}
}
