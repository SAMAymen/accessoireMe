<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function stm_wcmap_attr_image() {
	$atts = wc_get_attribute_taxonomies();

	if ( ! empty( $atts ) ) {
		foreach ( $atts as $k => $attr ) {
			/** Add Custom Field To Form */
			add_action( 'pa_' . $attr->attribute_name . '_add_form_fields', 'stm_attr_wcmap_add_field', 10 );
			add_action( 'pa_' . $attr->attribute_name . '_edit_form_fields', 'stm_attr_wcmap_edit_field', 10, 2 );
			/** Save Custom Field Of Form */
			add_action( 'created_pa_' . $attr->attribute_name, 'stm_attr_wcmap_image_save', 10, 2 );
			add_action( 'edited_pa_' . $attr->attribute_name, 'stm_attr_wcmap_image_save', 10, 2 );
		}
	}
}

add_action( 'init', 'stm_wcmap_attr_image' );

/*Add field*/
if ( ! function_exists( 'stm_attr_wcmap_add_field' ) ) {
	function stm_attr_wcmap_add_field( $taxonomy ) {
		$default_image = STM_WCMAP_URL . '/assets/img/placeholder.png';
		?>
		<div class="form-field">
			<label for="stm_attr_wcmap_image">
				<?php esc_html_e( 'Attribute Image', 'stm-woocommerce-motors-auto-parts' ); ?>
			</label>
			<div class="stm-choose-wcmap-image">
				<input
					type="hidden"
					name="stm_attr_wcmap_image"
					id="stm_attr_wcmap_image"
					value=""
					size="40"
					aria-required="true"/>

				<img class="stm_attr_wcmap_image_chosen" src="<?php echo esc_url( $default_image ); ?>"/>

				<input type="button" class="button-primary" value="<?php echo esc_attr__( 'Choose image', 'stm-woocommerce-motors-auto-parts' ); ?>"/>
			</div>
			<script type="text/javascript">
				jQuery(function ($) {
					$(".stm-choose-wcmap-image .button-primary").click(function () {
						var custom_uploader = wp.media({
							title: "<?php echo esc_js( __( 'Select image', 'stm-woocommerce-motors-auto-parts' ) ); ?>",
							button: {
								text: "<?php echo esc_js( __( 'Attach', 'stm-woocommerce-motors-auto-parts' ) ); ?>"
							},
							multiple: false
						}).on("select", function () {
							var attachment = custom_uploader.state().get("selection").first().toJSON();
							$('#stm_attr_wcmap_image').val(attachment.id);
							$('.stm_attr_wcmap_image_chosen').attr('src', attachment.url);
						}).open();
					});
				});
			</script>
		</div>
		<?php
	}
}

/*Edit field*/
if ( ! function_exists( 'stm_attr_wcmap_edit_field' ) ) {
	function stm_attr_wcmap_edit_field( $tag, $taxonomy ) {
		$current_image             = get_term_meta( $tag->term_id, 'stm_attr_wcmap_image', true );
		$current_banner_link       = get_term_meta( $tag->term_id, 'stm_attr_banner_link', true );
		$default_image_placeholder = STM_WCMAP_URL . '/assets/img/placeholder.png';
		$default_image             = STM_WCMAP_URL . '/assets/img/placeholder.png';
		if ( ! empty( $current_image ) ) {
			$default_image = wp_get_attachment_image_src( $current_image, 'thumbnail' );
			if ( ! empty( $default_image[0] ) ) {
				$default_image = $default_image[0];
			}
		}

		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="stm_attr_wcmap_image">
					<?php esc_html_e( 'Attribute Image', 'stm-woocommerce-motors-auto-parts' ); ?>
				</label>
			</th>
			<td>
				<div class="stm-choose-wcmap-image">
					<input
						type="hidden"
						name="stm_attr_wcmap_image"
						id="stm_attr_wcmap_image"
						value="<?php echo esc_attr( $current_image ); ?>"
						size="40"
						aria-required="true"/>

					<img class="stm_attr_wcmap_image_chosen" src="<?php echo esc_url( $default_image ); ?>"/>

					<input type="button" class="button-primary" value="<?php echo esc_attr__( 'Choose image', 'stm-woocommerce-motors-auto-parts' ); ?>"/>
					<input type="button" class="button-primary-delete" value="<?php echo esc_attr__( 'Remove image', 'stm-woocommerce-motors-auto-parts' ); ?>"/>
				</div>
			</td>
			<script type="text/javascript">
				jQuery(function ($) {
					$(".stm-choose-wcmap-image .button-primary").click(function () {
						var custom_uploader = wp.media({
							title: "<?php echo esc_js( __( 'Select image', 'stm-woocommerce-motors-auto-parts' ) ); ?>",
							button: {
								text: "<?php echo esc_js( __( 'Attach', 'stm-woocommerce-motors-auto-parts' ) ); ?>"
							},
							multiple: false
						}).on("select", function () {
							var attachment = custom_uploader.state().get("selection").first().toJSON();
							$('#stm_attr_wcmap_image').val(attachment.id);
							$('.stm_attr_wcmap_image_chosen').attr('src', attachment.url);
						}).open();
					});

					$(".stm-choose-wcmap-image .button-primary-delete").click(function () {
						$('#stm_attr_wcmap_image').val('');
						$('.stm_attr_wcmap_image_chosen').attr('src', '<?php echo esc_url( $default_image_placeholder ); ?>');
					})
				});
			</script>
		</tr>
		<?php
	}
}

/*Save value*/
if ( ! function_exists( 'stm_attr_wcmap_image_save' ) ) {
	function stm_attr_wcmap_image_save( $term_id, $tt_id ) {
		if ( isset( $_POST['stm_attr_wcmap_image'] ) ) {
			update_term_meta( $term_id, 'stm_attr_wcmap_image', sanitize_text_field( $_POST['stm_attr_wcmap_image'] ) );
		}

		if ( isset( $_POST['stm_attr_wcmap_link'] ) ) {
			update_term_meta( $term_id, 'stm_attr_banner_link', sanitize_text_field( $_POST['stm_attr_wcmap_link'] ) );
		}
	}
}
