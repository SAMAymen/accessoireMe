<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

if ( empty( $title ) ) {
	$title = esc_html__( 'Contact Information', 'motors-wpbakery-widgets' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
}

?>

	<div class="stm-boats-contact-information">
		<h4 class="title"><?php echo esc_html( $title ); ?></h4>
		<ul class="stm-list-duty heading-font">
			<?php if ( ! empty( $address ) ) : ?>
				<li class="widget_contacts_address">
					<div class="icon"><i class="stm-icon-pin"></i></div>
					<div class="text"><?php echo esc_attr( $address ); ?></div>
				</li>
			<?php endif; ?>
			<?php if ( ! empty( $phone ) ) : ?>
				<li class="widget_contacts_phone">
					<div class="icon"><i class="stm-service-icon-sales_phone"></i></div>
					<div class="text">
						<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
							<div class="stm_label"><?php esc_html_e( 'Phone:', 'motors-wpbakery-widgets' ); ?></div>
						<?php endif; ?>
						<?php echo esc_attr( $phone ); ?>
					</div>
				</li>
			<?php endif; ?>

			<?php if ( ! empty( $mail ) ) : ?>
				<li class="widget_contacts_mail">
					<div class="icon"><i class="stm-icon-mail"></i></div>
					<div class="text">
						<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
							<div class="stm_label"><?php esc_html_e( 'Email:', 'motors-wpbakery-widgets' ); ?></div>
						<?php endif; ?>
						<a href="mailto:<?php echo esc_attr( $mail ); ?>">
							<?php echo esc_attr( $mail ); ?>
						</a>
					</div>
				</li>
			<?php endif; ?>

			<?php if ( ! empty( $hours ) ) : ?>
				<li class="widget_contacts_hours">
					<div class="icon"><i class="stm-service-icon-sales_hours"></i></div>
					<div class="text"><?php echo esc_attr( $hours ); ?></div>
				</li>
			<?php endif; ?>
		</ul>
	</div>

