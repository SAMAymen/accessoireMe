<?php
$show_compare = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' );

if ( $show_compare ) :
	$compare_page_id   = apply_filters( 'motors_vl_get_nuxy_mod', false, 'compare_page' );
	$compare_page_link = ( $compare_page_id ) ? esc_url( get_the_permalink( $compare_page_id ) ) : '#!';
	?>
	<div class="pull-right hdn-767">
		<a class="lOffer-compare" href="<?php echo esc_url( $compare_page_link ); ?>" title="<?php esc_attr_e( 'View compared items', 'motors' ); ?>">
			<?php if ( ! boolval( apply_filters( 'is_listing', array() ) ) ) : ?>
				<span class="heading-font"><?php esc_html_e( 'Compare', 'motors' ); ?></span>
			<?php endif; ?>

			<?php echo wp_kses_post( stm_me_get_wpcfto_icon( 'header_compare_icon', 'stm-boats-icon-compare-boats', 'list-icon' ) ); ?>

			<span class="list-badge">
				<span class="stm-current-cars-in-compare">
					<?php echo count( apply_filters( 'stm_get_compared_items', array() ) ); ?>
				</span>
			</span>
		</a>
	</div>
<?php endif; ?>
