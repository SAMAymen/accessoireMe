<?php
$post_id = get_the_ID();

$badge_text        = get_post_meta( $post_id, 'badge_text', true );
$badge_bg_color    = get_post_meta( $post_id, 'badge_bg_color', true );
$show_pdf          = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_pdf' );
$car_brochure      = get_post_meta( $post_id, 'car_brochure', true );
$show_print        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_print_btn' );
$show_compare      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_compare' );
$show_share        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_share' );
$show_featured_btn = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_featured_btn' );

$asSold           = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );

// remove "special" if the listing is sold
if ( ! empty( $asSold ) ) {
	delete_post_meta( get_the_ID(), 'special_car' );
}

$special_car = get_post_meta( $post_id, 'special_car', true );

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'motors' );
}

$badge_style = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}
?>
<div class="actions-wrap">
	<!--Actions-->
	<div class="stm-gallery-actions">
		<ul>
			<!--Print button-->
			<?php if ( ! empty( $show_print ) && $show_print ) : ?>
				<li>
					<a href="javascript:window.print()" class="car-action-unit stm-car-print">
						<i class="fas fa-print"></i>
						<?php echo esc_html__( 'Print page', 'motors' ); ?>
					</a>
				</li>
			<?php endif; ?>
			<?php if ( ! empty( $show_featured_btn ) ) : ?>
				<li class="stm-gallery-action-unit stm-listing-favorite-action car-action-unit"
					data-id="<?php echo esc_attr( get_the_id() ); ?>">
					<i class="stm-service-icon-staricon"></i>
					<?php echo esc_html__( 'Featured', 'motors' ); ?>
				</li>
			<?php endif; ?>
			<?php if ( ! empty( $show_compare ) && $show_compare ) : ?>
				<li data-compare-id="<?php echo esc_attr( get_the_ID() ); ?>">
					<a href="#" class="car-action-unit add-to-compare stm-added" style="display: none;" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-action="remove"
					data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>">
						<i class="stm-icon-added stm-unhover"></i>
						<span class="stm-unhover"><?php esc_html_e( 'in compare list', 'motors' ); ?></span>
						<div class="stm-show-on-hover">
							<i class="stm-icon-remove"></i>
							<?php esc_html_e( 'Remove from list', 'motors' ); ?>
						</div>
					</a>
					<a href="#" class="car-action-unit add-to-compare" data-id="<?php echo esc_attr( get_the_ID() ); ?>" data-action="add" data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>">
						<i class="stm-icon-add"></i>
						<?php esc_html_e( 'Add to compare', 'motors' ); ?>
					</a>
				</li>
			<?php endif; ?>
			<!--Share-->
			<?php if ( ! empty( $show_share ) && $show_share ) : ?>
				<li class="stm-shareble">
					<a href="#" class="car-action-unit stm-share" title="<?php esc_attr_e( 'Share this', 'motors' ); ?>" download>
						<i class="stm-icon-share"></i>
						<?php esc_html_e( 'Share this', 'motors' ); ?>
					</a>

					<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) : ?>
						<div class="stm-a2a-popup">
							<?php echo apply_filters( 'stm_add_to_any_shortcode', get_the_ID() );//phpcs:ignore ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endif; ?>
			<!--PDF-->
			<?php if ( ! empty( $show_pdf ) && $show_pdf ) : ?>
				<?php if ( ! empty( $car_brochure ) && ! empty( wp_get_attachment_url( $car_brochure ) ) ) : ?>
					<li>
						<a
								href="<?php echo esc_url( wp_get_attachment_url( $car_brochure ) ); ?>"
								class="car-action-unit stm-brochure"
								title="<?php esc_attr_e( 'Download brochure', 'motors' ); ?>"
								download>
							<i class="stm-icon-brochure"></i>
							<?php ( apply_filters( 'stm_is_listing_five', false ) ) ? esc_html_e( 'PDF brochure', 'motors' ) : esc_html_e( 'Car brochure', 'motors' ); ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endif; ?>
		</ul>
	</div>
	<?php if ( empty( $asSold ) && ! empty( $special_car ) && 'on' === $special_car ) : ?>

		<div class="special-wrap">
			<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
				<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $badge_text, 'Special Badge Text' ) ); ?>
			</div>
		</div>

	<?php elseif ( apply_filters( 'stm_sold_status_enabled', false ) && ! empty( $asSold ) ) : ?>
		<?php $badge_style = 'style=background-color:' . $sold_badge_color . ';'; ?>
		<div class="special-wrap">
			<div class="special-label h5" <?php echo esc_attr( $badge_style ); ?>>
				<?php esc_html_e( 'Sold', 'motors' ); ?>
			</div>
		</div>

	<?php endif; ?>
</div>
