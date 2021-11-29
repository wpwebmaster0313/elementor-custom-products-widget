/**
 * Olek Product Data Addons Admin Library
 */
 (function (wp, $) {
    'use strict';

    var file_frame, $btn;

    var productAddons = {
        init: function () {
            var self = this;
            this.onAddImage    = this.onAddImage.bind(this);
			this.onRemoveImage = this.onRemoveImage.bind(this);
			this.onSelectImage = this.onSelectImage.bind(this);

			$(document.body)
				.on('click', '#olek_data_addons .button_upload_image', this.onAddImage)
				.on('click', '#olek_data_addons .button_remove_image', this.onRemoveImage)

            $('#olek_data_addons .olek-data-addon-save').on('click', self.saveOptions);
        },

        /**
		 * Event handler on image selected
		 */
		onSelectImage: function () {
			var attachment = file_frame.state().get('selection').first().toJSON(),
				$img = $btn.closest('.form-field').find('img');
			$img.attr('src', attachment.url);
			$btn.closest('.form-field').find('input').val(attachment.id);
			file_frame.close();
		},

        /**
		 * Event handler on image added
		 */
		onAddImage: function (e) {
			$btn = $(e.currentTarget);

			// If the media frame already exists
			file_frame || (
				// Create the media frame.
				file_frame = wp.media.frames.downloadable_file = wp.media({
					title: 'Choose an image',
					button: {
						text: 'Use image'
					},
					multiple: false
				}),

				// When an image is selected, run a callback.
				file_frame.on('select', this.onSelectImage)
			);

			file_frame.open();
			e.preventDefault();
		},

		/**
		 * Event handler on image removed
		 */
		onRemoveImage: function (e) {
			var $btn = $(e.currentTarget),
				$img = $btn.closest('.form-field').find('img');
			$img.attr('src', olek_product_addon_vars.placeholder);
			$btn.closest('.form-field').find('input').val('');
			e.preventDefault();
		},

        /**
         * Event handler on save
         */
        saveOptions: function (e) {
            e.preventDefault();

            var $wrapper = $('#olek_data_addons');

            $wrapper.block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            $.ajax(
                {
                    type: 'POST',
                    dataType: 'json',
                    url: olek_product_addon_vars.ajax_url,
                    data: {
                        action: "olek_save_product_addon_options",
                        nonce: olek_product_addon_vars.nonce,
                        post_id: olek_product_addon_vars.post_id,
                        exclusive: $('#olek_exclusive').prop("checked"),
                        learn_more_link: $('#olek_learn_more_link').val(),
                        background_id: $('#olek_background_image_id').val(),
                        offer_id: $('#olek_offer_image_id').val()
                    },
                    success: function () {
                        $wrapper.unblock();
                    }
                }
            );
        },
    }
    /**
     * Product Data Addons Initializer
     */

    $(document).ready(function () {
        productAddons.init();
    });
})(wp, jQuery);
