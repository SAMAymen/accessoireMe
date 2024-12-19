<?php
$show_compare    = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' );
$compare_page_id = ( apply_filters( 'stm_is_listing_six', false ) ) ? \uListing\Classes\StmListingSettings::getPages( 'compare_page' ) : apply_filters( 'motors_vl_get_nuxy_mod', 156, 'compare_page' );
$compare_icon    = ( apply_filters( 'stm_is_listing_six', false ) ) ? 'stm-all-icon-listing-compare' : 'list-icon stm-boats-icon-compare-boats';
?>

<?php if ( $show_compare ) : ?>
	<?php
	if ( defined( 'ULISTING_VERSION' ) ) {
		$compare_cookie = ( ! empty( $_COOKIE['ulisting_compare'] ) ) ? (array) $_COOKIE['ulisting_compare'] : array();
		$compare_count  = ( ! empty( $compare_cookie ) ) ? count( (array) json_decode( stripslashes( $compare_cookie[0] ) ) ) : 0;
	}

		$compare_page_link = ( $compare_page_id ) ? esc_url( get_the_permalink( $compare_page_id ) ) : '#!';

	?>
	<div class="stm-compare">
		<a class="lOffer-compare" href="<?php echo esc_url( $compare_page_link ); ?>" title="<?php esc_attr_e( 'View compared items', 'motors' ); ?>">
			<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_compare_icon', $compare_icon ) ); ?>
			<?php if ( ! defined( 'ULISTING_VERSION' ) ) : ?>
				<span class="list-badge">
					<span class="stm-current-cars-in-compare">
						<?php echo count( apply_filters( 'stm_get_compared_items', array() ) ); ?>
					</span>
				</span>
			<?php else : ?>
				<span class="list-badge">
					<span class="stm-current-cars-in-compare">
						<?php
						if ( 0 !== $compare_count ) {
							echo esc_html( $compare_count );
						}
						?>
					</span>
				</span>
			<?php endif; ?>
		</a>
	</div>
<?php endif; ?>
