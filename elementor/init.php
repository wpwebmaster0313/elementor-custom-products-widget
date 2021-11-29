<?php

// direct load is not allowed
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
	return;
}

define( 'OLEK_PRO_ELEMENTOR', OLEK_PRO_PATH . '/elementor' );

use Elementor\Core\Files\CSS\Global_CSS;

class Olek_Elementor {
	/**
	 * Constructor
	 *
	 * @since 1.0
	 *
	*/
	public function __construct() {
		$this->register_class();
		// Register widget
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widget' ) );
	}

	public function register_class() {
		include_once OLEK_PRO_ELEMENTOR . '/classes/base-products-renderer.php';
		include_once OLEK_PRO_ELEMENTOR . '/classes/current-query-renderer.php';
		include_once OLEK_PRO_ELEMENTOR . '/classes/products-renderer.php';
	}

	// Register widget
	public function register_widget( $self ) {
		$widgets = array();

		if ( class_exists( 'WooCommerce' ) ) {
			$widgets = array_merge(
				$widgets,
				array(
					'products',
				)
			);
		}

		foreach ( $widgets as $widget ) {
			include_once OLEK_PRO_ELEMENTOR . '/widgets/widget-' . $widget . '.php';
			$class_name = 'Olek_Custom_' . ucwords( str_replace( '-', '_', $widget ) ) . '_Elementor_Widget';
			$self->register_widget_type(
				new $class_name(
					array(),
					array(
						'widget_name' => $class_name,
					)
				)
			);
		}
	}

}

new Olek_Elementor;
