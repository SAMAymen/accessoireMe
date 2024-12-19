<?php
$sold             = get_post_meta( get_the_ID(), 'car_mark_as_sold', true );
$sold_badge_color = apply_filters( 'motors_vl_get_nuxy_mod', '', 'sold_badge_bg_color' );

// remove "special" if the listing is sold.
if ( ! empty( $sold ) ) {
	delete_post_meta( get_the_ID(), 'special_car' );
}

$special_car    = get_post_meta( get_the_ID(), 'special_car', true );
$badge_text     = get_post_meta( get_the_ID(), 'badge_text', true );
$badge_bg_color = get_post_meta( get_the_ID(), 'badge_bg_color', true );

$badge_style = '';
if ( ! empty( $badge_bg_color ) ) {
	$badge_style = 'style=background-color:' . $badge_bg_color . ';';
}

if ( empty( $badge_text ) ) {
	$badge_text = esc_html__( 'Special', 'motors' );
}

if ( ! empty( $sold ) && apply_filters( 'stm_sold_status_enabled', false ) ) {
	$badge_style = 'style=background-color:' . $sold_badge_color . ';';

	if ( apply_filters( 'stm_is_equipment', false ) || apply_filters( 'stm_is_motorcycle', false ) ) :
		?>
		<div class="special-label special-label-small h6" <?php echo esc_attr( $badge_style ); ?>>
			<?php esc_html_e( 'Sold', 'motors' ); ?>
		</div>
	<?php else : ?>
		<div class="stm-badge-directory heading-font 
		<?php
		if ( apply_filters( 'stm_is_car_dealer', false ) ) {
			echo 'stm-badge-dealer';
		}
		?>
		" <?php echo esc_attr( $badge_style ); ?>>
			<?php esc_html_e( 'Sold', 'motors' ); ?>
		</div>
		<?php
	endif;
} else {
	if ( ! empty( $special_car ) && 'on' === $special_car ) {
		if ( apply_filters( 'stm_is_equipment', false ) || apply_filters( 'stm_is_motorcycle', false ) ) :
			?>
			<div class="special-label special-label-small h6" <?php echo esc_attr( $badge_style ); ?>>
				<?php echo esc_html( $badge_text ); ?>
			</div>
		<?php else : ?>
			<div class="stm-badge-directory heading-font  <?php echo ( apply_filters( 'stm_is_car_dealer', false ) ) ? 'stm-badge-dealer' : ''; ?> " <?php echo esc_attr( $badge_style ); ?>>
				<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $badge_text, 'Special Badge Text' ) ); ?>
			</div>
			<?php
		endif;
	}
}
