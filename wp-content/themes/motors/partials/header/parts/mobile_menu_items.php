<?php
$shopping_cart_boats     = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_cart_show' );
$compare_page            = apply_filters( 'motors_vl_get_nuxy_mod', 156, 'compare_page' );
$show_compare_page       = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' );
$header_listing_btn_link = apply_filters( 'stm_me_get_nuxy_mod', '/add-a-car', 'header_listing_btn_link' );
$header_listing_btn_text = apply_filters( 'stm_me_get_nuxy_mod', esc_html__( 'Add your item', 'motors' ), 'header_listing_btn_text' );
$header_profile          = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_profile' );
$compared_items_count    = apply_filters( 'stm_get_compared_items', array() );

if ( function_exists( 'WC' ) ) {
	$woocommerce_shop_page_id = wc_get_cart_url();
}

?>
<?php if ( apply_filters( 'stm_me_get_nuxy_mod', false, 'header_show_add_car_button' ) && boolval( apply_filters( 'is_listing', array() ) ) && ! empty( $header_listing_btn_link ) && ! empty( $header_listing_btn_text ) ) : ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php echo esc_url( $header_listing_btn_link ); ?>">
			<span>
				<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $header_listing_btn_text, 'Listing Button Text' ) ); ?>
			</span>
		</a>
	</li>
<?php endif; ?>
<?php if ( boolval( apply_filters( 'is_listing', array() ) ) && $header_profile ) : ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', 'myself-view' ) ); ?>">
			<span>
				<?php esc_html_e( 'Profile', 'motors' ); ?>
			</span>
		</a>
	</li>
<?php endif; ?>
<?php if ( $shopping_cart_boats && ! empty( $woocommerce_shop_page_id ) ) : ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<?php $items = WC()->cart->cart_contents_count; ?>
		<!--Shop archive-->
		<a href="<?php echo esc_url( $woocommerce_shop_page_id ); ?>" title="<?php esc_attr_e( 'Watch shop items', 'motors' ); ?>" >
			<span><?php esc_html_e( 'Cart', 'motors' ); ?></span>
			<?php
			if ( $items > 0 ) :
				?>
				<span class="list-badge">
					<span class="stm-current-items-in-cart">
						<?php echo esc_html( $items ); ?>
					</span>
				</span>
				<?php
			endif;
			?>
		</a>
	</li>
<?php endif; ?>

<?php if ( ! empty( $compare_page ) && $show_compare_page ) : ?>
	<li class="menu-item menu-item-type-post_type menu-item-object-page">
		<a href="<?php echo esc_url( get_the_permalink( $compare_page ) ); ?>" title="<?php esc_attr_e( 'View compared items', 'motors' ); ?>">
			<span><?php esc_html_e( 'Compare', 'motors' ); ?></span>
			<span class="list-badge">
				<span class="stm-current-cars-in-compare">
					<?php echo count( $compared_items_count ); ?>
				</span>
			</span>
		</a>
	</li>
	<?php
	endif;

echo apply_filters( 'stm_vin_decoder_mobile_menu', '' );//phpcs:ignore
