<?php
use OlekProductsPro\Elementor\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Woo Custom Pro Widget
 *
 * Display products
 *
 * @since 1.0.0
 */

use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Breakpoints\Manager as Breakpoints_Manager;
use OlekProductsPro\Elementor\Classes\Products_Renderer as Products_Renderer;
use OlekProductsPro\Elementor\Classes\Current_Query_Renderer as Current_Query_Renderer;

class Olek_Custom_Products_Elementor_Widget extends \Elementor\Widget_Base {
	public function get_name() {
		return 'olek_custom_widget_products';
	}

	public function get_title() {
		return esc_html__( 'Olek Custom Products', 'woo-custom-pro' );
	}

	public function get_categories() {
		return array( 'olek_custom_widget' );
	}

	public function get_keywords() {
		return array( 'products', 'shop', 'woocommerce' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	protected function register_controls() {
		// products layout controls
		$this->start_controls_section(
			'section_products_layout',
			array(
				'label' => esc_html__( 'Content', 'woo-custom-pro' ),
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'               => __( 'Columns', 'olek-pro' ),
				'type'                => Controls_Manager::NUMBER,
				'prefix_class'        => 'elementor-grid%s-',
				'render_type'         => 'template',
				'min'                 => 1,
				'max'                 => 12,
				'default'             => 4,
				'tablet_default'      => '3',
				'mobile_default'      => '2',
				'required'            => true,
				'device_args'         => $this->get_devices_default_args(),
				'min_affected_device' => array(
					Controls_Stack::RESPONSIVE_DESKTOP => Controls_Stack::RESPONSIVE_TABLET,
					Controls_Stack::RESPONSIVE_TABLET  => Controls_Stack::RESPONSIVE_TABLET,
				),
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'Rows', 'olek-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 4,
				'render_type' => 'template',
				'range'       => array(
					'px' => array(
						'max' => 20,
					),
				),
			)
		);

		$this->add_control(
			'paginate',
			array(
				'label'   => __( 'Pagination', 'woo-custom-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'allow_order',
			array(
				'label'     => __( 'Allow Order', 'woo-custom-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->add_control(
			'wc_notice_frontpage',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Ordering is not available if this widget is placed in your front page. Visible on frontend only.', 'woo-custom-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'paginate'    => 'yes',
					'allow_order' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_result_count',
			array(
				'label'     => __( 'Show Result Count', 'woo-custom-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					'paginate' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		// products select control
		$this->start_controls_section(
			'section_query',
			array(
				'label' => __( 'Query', 'olek-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_group_control(
			'query-group',
			array(
				'name'           => 'query',
				'post_type'      => 'product',
				'presets'        => array( 'include', 'exclude', 'order' ),
				'fields_options' => array(
					'post_type' => array(
						'default' => 'product',
						'options' => array(
							'current_query' => __( 'Current Query', 'olek-pro' ),
							'product'       => __( 'Latest Products', 'olek-pro' ),
							'sale'          => __( 'Sale', 'olek-pro' ),
							'featured'      => __( 'Featured', 'olek-pro' ),
							'by_id'         => _x( 'Manual Selection', 'Posts Query Control', 'olek-pro' ),
						),
					),
					'orderby'   => array(
						'default' => 'date',
						'options' => array(
							'date'       => __( 'Date', 'olek-pro' ),
							'title'      => __( 'Title', 'olek-pro' ),
							'price'      => __( 'Price', 'olek-pro' ),
							'popularity' => __( 'Popularity', 'olek-pro' ),
							'rating'     => __( 'Rating', 'olek-pro' ),
							'rand'       => __( 'Random', 'olek-pro' ),
							'menu_order' => __( 'Menu Order', 'olek-pro' ),
						),
					),
					'exclude'   => array(
						'options' => array(
							'current_post'     => __( 'Current Post', 'olek-pro' ),
							'manual_selection' => __( 'Manual Selection', 'olek-pro' ),
							'terms'            => __( 'Term', 'olek-pro' ),
						),
					),
					'include'   => array(
						'options' => array(
							'terms' => __( 'Term', 'olek-pro' ),
						),
					),
				),
				'exclude'        => array(
					'posts_per_page',
					'exclude_authors',
					'authors',
					'offset',
					'related_fallback',
					'related_ids',
					'query_id',
					'avoid_duplicates',
					'ignore_sticky_posts',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function get_devices_default_args() {
		$devices_required = array();

		// Make sure device settings can inherit from larger screen sizes' breakpoint settings.
		foreach ( Breakpoints_Manager::get_default_config() as $breakpoint_name => $breakpoint_config ) {
			$devices_required[ $breakpoint_name ] = array(
				'required' => false,
			);
		}

		return $devices_required;
	}

	protected function get_shortcode_object( $settings ) {
		if ( 'current_query' === $settings[ OlekProductsPro\Elementor\Classes\Products_Renderer::QUERY_CONTROL_NAME . '_post_type' ] ) {
			$type = 'current_query';
			return new Current_Query_Renderer( $settings, $type );
		}
		$type = 'products';
		return new Products_Renderer( $settings, $type );
	}

	protected function render() {
		if ( WC()->session ) {
			wc_print_notices();
		}

		// For Products_Renderer.
		if ( ! isset( $GLOBALS['post'] ) ) {
			$GLOBALS['post'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}

		$settings = $this->get_settings();

		include OLEK_PRO_ELEMENTOR . '/render/widget-products-render.php';
	}

	protected function content_template() {}
}
