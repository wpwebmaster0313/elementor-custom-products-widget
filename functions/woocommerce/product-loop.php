<?php
/**
 * Woo Custom WooCommerce Product Loop Functions
 *
 * Functions used to display product loop.
 */
if ( ! function_exists( 'add_olek_custom_loop_content' ) ) {
	function add_olek_custom_loop_content() {
		add_action( 'woocommerce_before_shop_loop_item_title', 'olek_custom_details_open', 50 );
		add_action( 'woocommerce_shop_loop_item_title', 'olek_custom_display_categories', 5 );
		add_action( 'woocommerce_after_shop_loop_item_title', 'olek_custom_details_bottom_open', 5 );
		add_action( 'woocommerce_after_shop_loop_item', 'olek_custom_details_bottom_close', 20 );
		add_action( 'woocommerce_after_shop_loop_item', 'olek_custom_details_close', 15 );

		// Remove default AddToCart
		add_filter( 'woocommerce_product_add_to_cart_text', 'olek_custom_add_to_cart_text', 10, 2 );

		// Add custom link
		add_action( 'woocommerce_after_shop_loop_item_title', 'olek_custom_link', 15 );

		// Product Loop Media - Labels and Actions
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );
		add_action( 'woocommerce_before_shop_loop_item_title', 'olek_custom_show_product_loop_sale_flash', 15 );

		// Product thumbnail
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', 'olek_custom_loop_product_thumbnail', 15 );
		add_action( 'woocommerce_before_shop_loop_item', 'olek_custom_background_image', 2 );

		// Wrapper class
		add_filter( 'woocommerce_post_class', 'olek_custom_wrapper_class', 10, 2 );
	}
}

if ( ! function_exists( 'remove_olek_custom_loop_content' ) ) {
	function remove_olek_custom_loop_content() {
		// remove action and filter
		remove_action( 'woocommerce_before_shop_loop_item_title', 'olek_custom_details_open', 50 );
		remove_action( 'woocommerce_shop_loop_item_title', 'olek_custom_display_categories', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'olek_custom_details_bottom_open', 5 );
		remove_action( 'woocommerce_after_shop_loop_item', 'olek_custom_details_bottom_close', 20 );
		remove_action( 'woocommerce_after_shop_loop_item', 'olek_custom_details_close', 15 );

		remove_filter( 'woocommerce_product_add_to_cart_text', 'olek_custom_add_to_cart_text', 10 );

		remove_action( 'woocommerce_after_shop_loop_item_title', 'olek_custom_link', 15 );

		remove_action( 'woocommerce_before_shop_loop_item_title', 'olek_custom_show_product_loop_sale_flash', 15 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );

		remove_action( 'woocommerce_before_shop_loop_item_title', 'olek_custom_loop_product_thumbnail', 15 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		remove_action( 'woocommerce_before_shop_loop_item', 'olek_custom_background_image', 2 );

		remove_filter( 'woocommerce_post_class', 'olek_custom_wrapper_class', 10 );
	}
}

if ( ! function_exists( 'olek_custom_details_open' ) ) {
	function olek_custom_details_open() {
		echo '<div class="product-details">';
	}
}

if ( ! function_exists( 'olek_custom_display_categories' ) ) {
	function olek_custom_display_categories() {
		global $product;
		echo '<div class="product-cat">' . wc_get_product_category_list( $product->get_id(), ', ', '' ) . '</div>';
	}
}

if ( ! function_exists( 'olek_custom_details_bottom_open' ) ) {
	function olek_custom_details_bottom_open() {
		echo '<div class="product-details-bottom">';
	}
}

if ( ! function_exists( 'olek_custom_details_bottom_close' ) ) {
	function olek_custom_details_bottom_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'olek_custom_details_close' ) ) {
	function olek_custom_details_close() {
		echo '</div>';
	}
}

/**
 * change 'add to cart' to 'BUY'
 */
if ( ! function_exists( 'olek_custom_add_to_cart_text' ) ) {
	function olek_custom_add_to_cart_text( $text, $self ) {
		$text = $self->is_purchasable() && $self->is_in_stock() ? ( $self->is_type( 'simple' ) ? esc_html__( 'BUY', 'woo-custom-pro' ) : esc_html__( 'Select Options', 'woo-custom-pro' ) ) : esc_html__( 'MORE', 'woocommerce' );
		return $text;
	}
}

/**
 * display custom link
 */
if ( ! function_exists( 'olek_custom_link' ) ) {
	function olek_custom_link() {
		global $product;
		if ( get_post_meta( $product->get_ID(), 'olek_learn_more_link', true ) ) {
			$link_url = get_post_meta( $product->get_ID(), 'olek_learn_more_link', true );
			echo '<a href="' . esc_url( $link_url ) . '" class="custom-link">' . esc_attr( 'LEARN MORE' ) . '</a>';
		}
	}
}

/**
 * display sale labels
 */
if ( ! function_exists( 'olek_custom_show_product_loop_sale_flash' ) ) {
	function olek_custom_show_product_loop_sale_flash() {
		global $product;
		$html = '';

		// Out of Stock
		if ( get_post_meta( $product->get_ID(), 'olek_exclusive', true ) == 'true' ) {
			$top_label = 'EXCLUSIVE';
			$html     .= '<label class="product-label label-top" title="' . esc_html__( 'Exclusive Product', 'woo-custom-pro' ) . '">' . $top_label . '</label>';
		} elseif ( 'outofstock' == $product->get_stock_status() ) {
			$stock_label = 'OUT OF STOCK';
			$html       .= '<label class="product-label label-stock" title="' . esc_html__( 'Out-of-Stock Product', 'woo-custom-pro' ) . '">' . $stock_label . '</label>';
		}

		// Sale Product
		if ( get_post_meta( $product->get_ID(), 'olek_offer_image', true ) ) {
			$olek_offer_image = wp_get_attachment_image_src( get_post_meta( $product->get_ID(), 'olek_offer_image', true ), 'thumbnail' )[0];
			$html            .= '<label class="product-label label-sale" title="' . esc_html__( 'Offer Product', 'olek' ) . '"> <img src="' . esc_url( $olek_offer_image ) . '" width="96" height="96" /></label>';
		} elseif ( $product->is_on_sale() ) {
			$reg_p = floatval( $product->get_regular_price() );
			if ( $reg_p ) {
				$percentage = round( ( ( $reg_p - $product->get_sale_price() ) / $reg_p ) * 100 );
			} elseif ( 'variable' == $product->get_type() && $product->get_variation_regular_price() ) {
				$percentage = round( ( ( $product->get_variation_regular_price() - $product->get_variation_sale_price() ) / $product->get_variation_regular_price() ) * 100 );
			}

			$sale_label = '%percent% OFF';
			if ( ! empty( $percentage ) && ! empty( $sale_label ) && false !== strpos( $sale_label, '%percent%' ) ) {
				$percentage .= '%';
				$sale_html   = str_replace( '%percent%', $percentage, $sale_label );
				$html       .= '<label class="product-label label-sale" title="' . esc_html__( 'On-Sale Product', 'olek' ) . '">' . $sale_html . '</label>';
			}
		}

		if ( $html ) {
			$html = '<div class="product-label-group' . esc_attr( apply_filters( 'olek_product_label_group_class', '' ) ) . '">' . $html . '</div>';

			echo apply_filters( 'woocommerce_sale_flash', $html, $product );
		}

	}
}

/**
 * Background Image
 *
 */
if ( ! function_exists( 'olek_custom_background_image' ) ) {
	function olek_custom_background_image() {
		global $product;
		if ( get_post_meta( $product->get_ID(), 'olek_background_image', true ) ) {
			$olek_background_image = wp_get_attachment_image_src( get_post_meta( $product->get_ID(), 'olek_background_image', true ), 'full' )[0];
			echo '<div class="product-background-style" style="' . sprintf( 'background-image:url(%s);', esc_url( $olek_background_image ) ) . '"></div>';
		}
	}
}

/**
 * product thumbnail
 *
 */
if ( ! function_exists( 'olek_custom_loop_product_thumbnail' ) ) {
	function olek_custom_loop_product_thumbnail() {
		global $product;
		if ( ! get_post_meta( $product->get_ID(), 'olek_background_image', true ) ) {
			echo woocommerce_get_product_thumbnail();
		}
	}
}

/**
 * products wrapper class
 *
 */

if ( ! function_exists( 'olek_custom_wrapper_class' ) ) {
	function olek_custom_wrapper_class( $classes, $product ) {
		if ( wc_get_loop_prop( 'product_type' ) && 'full' == wc_get_loop_prop( 'product_type' ) ) {
			$classes[] = 'products-full-img';
		}

		return $classes;
	}
}
