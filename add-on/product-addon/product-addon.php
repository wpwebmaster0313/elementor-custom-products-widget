<?php

/**
 * Olek_Product_Data_Addons class
 *
 * @version 1.0
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Olek_Product_Data_Addons' ) ) {
	class Olek_Product_Data_Addons {

		public function __construct() {
			add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_data_tab' ), 101 );
			add_action( 'woocommerce_product_data_panels', array( $this, 'add_product_data_panel' ), 99 );

			// Save 'Olek Extra Options'
			add_action( 'wp_ajax_olek_save_product_addon_options', array( $this, 'save_extra_options' ) );
			add_action( 'wp_ajax_nopriv_olek_save_product_addon_options', array( $this, 'save_extra_options' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 1001 );
		}

		public function add_product_data_tab( $tabs ) {
			$tabs['olek_data_addon'] = array(
				'label'    => esc_html__( 'Olek Extra Options', 'olek' ),
				'target'   => 'olek_data_addons',
				'priority' => 90,
			);
			return $tabs;
		}

		public function add_product_data_panel() {
			global $thepostid;

			?>
			<div id="olek_data_addons" class="panel woocommerce_options_panel wc-metaboxes-wrapper hidden">
				<div class="wc-metaboxes">
					<div class="options-group">
						<?php
						// Exclusive
						$olek_exclusive = get_post_meta( $thepostid, 'olek_exclusive', true );
						?>
						<p class="form-field">
							<label><?php esc_html_e( 'Exclusive', 'olek' ); ?></label>
							<input type="checkbox" class="checkbox" style="" name="olek_exclusive" id="olek_exclusive" <?php echo esc_attr( $olek_exclusive ) == 'true' ? 'checked' : ''; ?>>
						</p>

						<?php
						// Learn more Link
						$olek_learn_more_link = get_post_meta( $thepostid, 'olek_learn_more_link', true );
						?>
						<p class="form-field">
							<label><?php esc_html_e( 'Learn More Link', 'olek' ); ?></label>
							<input type="text" id="olek_learn_more_link" name="olek_learn_more_link" value="<?php echo esc_attr( $olek_learn_more_link ); ?>" />
							<?php echo wc_help_tip( esc_html__( 'Add custom link for each product', 'olek' ) ); ?>
						</p>

						<?php
						// Background Image
						$olek_background_image_id = get_post_meta( $thepostid, 'olek_background_image', true );
						if ( $olek_background_image_id ) {
							$olek_background_image = wp_get_attachment_image_src( $olek_background_image_id, 'medium' )[0];
						} else {
							$olek_background_image_id = '';
							$olek_background_image    = wc_placeholder_img_src( 'medium' );
						}
						?>
						<p class="form-field">
							<label><?php esc_html_e( 'Background Image', 'olek' ); ?></label>
							<img src="<?php echo esc_url( $olek_background_image ); ?>" alt="<?php esc_attr_e( 'Thumbnail Preview', 'olek' ); ?>" width="300" height="300" />
							<input class="upload_image_url" id="olek_background_image_id" type="hidden" value="<?php echo esc_attr( $olek_background_image_id ); ?>" />
							<span style="display: block;">
								<button class="button_upload_image button"><?php esc_html_e( 'Upload/Add image', 'olek' ); ?></button>
								<button class="button_remove_image button"><?php esc_html_e( 'Remove image', 'olek' ); ?></button>
							</span>
						</p>

						<?php
						// Background Image
						$olek_offer_image_id = get_post_meta( $thepostid, 'olek_offer_image', true );
						if ( $olek_offer_image_id ) {
							$olek_offer_image = wp_get_attachment_image_src( $olek_offer_image_id, 'thumbnail' )[0];
						} else {
							$olek_offer_image_id = '';
							$olek_offer_image    = wc_placeholder_img_src( 'thumbnail' );
						}
						?>
						<p class="form-field">
							<label><?php esc_html_e( 'Offer Image', 'olek' ); ?></label>
							<img src="<?php echo esc_url( $olek_offer_image ); ?>" alt="<?php esc_attr_e( 'Thumbnail Preview', 'olek' ); ?>" width="150" height="150" />
							<input class="upload_image_url" id="olek_offer_image_id" type="hidden" value="<?php echo esc_attr( $olek_offer_image_id ); ?>" />
							<span style="display: block;">
								<button class="button_upload_image button"><?php esc_html_e( 'Upload/Add image', 'olek' ); ?></button>
								<button class="button_remove_image button"><?php esc_html_e( 'Remove image', 'olek' ); ?></button>
							</span>
						</p>
					</div>
					<div class="toolbar clear">
						<button type="submit" class="button-primary olek-data-addon-save"><?php esc_html_e( 'Save changes', 'olek' ); ?></button>
					</div>
				</div>
			</div>
			<?php
		}

		public function enqueue_scripts() {
			wp_enqueue_script( 'olek-product-addon', OLEK_ADDON_URI . '/product-addon/product-addon.js', array(), 1, true );
			wp_localize_script(
				'olek-product-addon',
				'olek_product_addon_vars',
				array(
					'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
					'post_id'  => get_the_ID(),
					'nonce'    => wp_create_nonce( 'olek-product-editor' ),
				)
			);
		}

		public function save_extra_options() {
			if ( ! check_ajax_referer( 'olek-product-editor', 'nonce', false ) ) {
				wp_send_json_error( 'invalid_nonce' );
			}
			$post_id         = $_POST['post_id'];
			$exclusive       = isset( $_POST['exclusive'] ) ? olek_strip_script_tags( $_POST['exclusive'] ) : '';
			$learn_more_link = isset( $_POST['learn_more_link'] ) ? olek_strip_script_tags( $_POST['learn_more_link'] ) : '';
			$background_id   = isset( $_POST['background_id'] ) ? olek_strip_script_tags( $_POST['background_id'] ) : '';
			$offer_id        = isset( $_POST['offer_id'] ) ? $_POST['offer_id'] : '';

			if ( $exclusive ) {
				update_post_meta( $post_id, 'olek_exclusive', $exclusive );
			} else {
				delete_post_meta( $post_id, 'olek_exclusive' );
			}

			if ( $learn_more_link ) {
				update_post_meta( $post_id, 'olek_learn_more_link', $learn_more_link );
			} else {
				delete_post_meta( $post_id, 'olek_learn_more_link' );
			}

			if ( $background_id ) {
				update_post_meta( $post_id, 'olek_background_image', $background_id );
			} else {
				delete_post_meta( $post_id, 'olek_background_image' );
			}

			if ( $offer_id ) {
				update_post_meta( $post_id, 'olek_offer_image', $offer_id );
			} else {
				delete_post_meta( $post_id, 'olek_offer_image' );
			}

			wp_send_json_success();
			die();
		}
	}
}

new Olek_Product_Data_Addons;
