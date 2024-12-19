<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );

if ( empty( $contact_info_link ) ) {
	$contact_info_link = '#!';
}

if ( empty( $block_bg_color ) ) {
	$block_bg_color = '#f0f2f5';
}

$show_price          = true;
$show_sale_price     = true;
$price               = get_post_meta( get_the_ID(), 'price', true );
$sale_price          = get_post_meta( get_the_ID(), 'sale_price', true );
$regular_price_label = get_post_meta( get_the_ID(), 'regular_price_label', true );
$special_price_label = get_post_meta( get_the_ID(), 'special_price_label', true );

if ( empty( $price ) ) {
	$show_price = false;
}

if ( empty( $sale_price ) ) {
	$show_sale_price = false;
}

if ( ! empty( $price ) && empty( $sale_price ) ) {
	$show_sale_price = false;
}

if ( ! empty( $price ) && ! empty( $sale_price ) ) {
	if ( intval( $price ) === intval( $sale_price ) ) {
		$show_sale_price = false;
	}
}

if ( empty( $price ) && ! empty( $sale_price ) ) {
	$price           = $sale_price;
	$show_price      = true;
	$show_sale_price = false;
}

$trade_in   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_trade_in' );
$make_offer = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_offer_price' );


?>

<div class="stm_all_in_one_price_block <?php echo esc_attr( $css_class ); ?>" style="background-color: <?php echo esc_attr( $block_bg_color ); ?>">
	<div class="row">
		<div class="col-sm-6 col-xs-6">
			<div class="prices_wrap">
				<?php if ( $show_price && ! $show_sale_price ) : ?>
					<?php if ( ! empty( $regular_price_label ) ) : ?>
						<span class="h3 price_label"><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_label, 'Regular Price Label' ) ); ?></span>
					<?php endif; ?>
					<span class="h3 heading-font">
						<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
					</span>
				<?php elseif ( $show_price && $show_sale_price ) : ?>
					<span class="regular_price h3 heading-font">
						<?php if ( ! empty( $regular_price_label ) ) : ?>
							<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $regular_price_label, 'Special Price Label' ) ); ?>
						<?php endif; ?>
						<span class="crossed">
							<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $price ) ); ?>
						</span>
					</span>
					<span class="h3 heading-font">
						<?php echo esc_html( apply_filters( 'stm_filter_price_view', '', $sale_price ) ); ?>
					</span>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-sm-6 col-xs-6 action-links">
			<?php if ( $trade_in ) : ?>
				<a href="#!" data-toggle="modal" data-target="#trade-in">
					<?php esc_html_e( 'Trande in Form', 'motors-wpbakery-widgets' ); ?>
				</a>
			<?php endif; ?>
			<?php if ( $make_offer ) : ?>
				<a href="#!" data-toggle="modal" data-target="#trade-offer">
					<?php esc_html_e( 'Make an Offer Price', 'motors-wpbakery-widgets' ); ?>
				</a>
			<?php endif; ?>            
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<a href="<?php echo esc_attr( $contact_info_link ); ?>" class="button button-sm contact-btn heading-font">
				<?php esc_html_e( 'Contact Information', 'motors-wpbakery-widgets' ); ?>
			</a>
		</div>
	</div>
</div>
