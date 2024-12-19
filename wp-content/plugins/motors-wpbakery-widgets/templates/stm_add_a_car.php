<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
$css_class = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';

$terms_args = array(
	'orderby'    => 'name',
	'order'      => 'ASC',
	'hide_empty' => false,
	'fields'     => 'all',
	'pad_counts' => true,
);

if ( ! empty( $taxonomy ) ) {
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$taxonomy = array_filter( array_unique( explode( ',', str_replace( ' ', '', $taxonomy ) ) ) );
}

if ( ! empty( $link ) ) {
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$link = vc_build_link( $link );
} else {
	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$link = array();
}

$data = apply_filters( 'stm_get_single_car_listings', array() );

if ( isset( $atts['items'] ) && strlen( $atts['items'] ) > 0 ) {
	$items = vc_param_group_parse_atts( $atts['items'] );
	if ( ! is_array( $items ) ) {
		$temp         = explode( ',', $atts['items'] );
		$param_values = array();
		foreach ( $temp as $value ) {
			$data                  = explode( '|', $value );
			$new_line              = array();
			$new_line['title']     = isset( $data[0] ) ? $data[0] : 0;
			$new_line['sub_title'] = isset( $data[1] ) ? $data[1] : '';
			if ( isset( $data[1] ) && preg_match( '/^\d{1,3}\%$/', $data[1] ) ) {
				$new_line['title']     = (float) str_replace( '%', '', $data[1] );
				$new_line['sub_title'] = isset( $data[2] ) ? $data[2] : '';
			}
			$param_values[] = $new_line;
		}
		$atts['items'] = rawurlencode( wp_json_encode( $param_values ) );
	}
}

if ( empty( $items ) ) {
	$items = array();
}

if ( empty( $stm_title_user ) ) {
	$stm_title_user = '';
}

if ( empty( $stm_text_user ) ) {
	$stm_text_user = '';
}

if ( empty( $stm_histories ) ) {
	$stm_histories = '';
}

if ( empty( $stm_phrases ) ) {
	$stm_phrases = '';
}

$vars = array(
	'id'                    => apply_filters( 'stm_listings_input', null, 'item_id' ),
	'taxonomy'              => $taxonomy,
	'link'                  => $link,
	'data'                  => $data,
	'items'                 => $items,
	'stm_title_user'        => $stm_title_user,
	'stm_text_user'         => $stm_text_user,
	'stm_histories'         => $stm_histories,
	'stm_phrases'           => $stm_phrases,
	'show_car_title'        => $show_car_title,
	'use_inputs'            => $use_inputs,
	'stm_title_desc'        => $stm_title_desc,
	'content'               => $content,
	'stm_title_price'       => $stm_title_price,
	'show_sale_price_label' => $show_sale_price_label,
	'show_custom_label'     => $show_custom_label,
	'price_label'           => $price_label,
	'sale_price_label'      => $sale_price_label,
);

$car_edit          = false;
$stm_edit_car_form = '';

if ( ! empty( $_GET['edit_car'] ) && $_GET['edit_car'] ) {// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$car_edit          = true;
	$stm_edit_car_form = 'stm_edit_car_form';
}

$restricted = false;

$user_id = '';

if ( is_user_logged_in() ) {
	$user    = wp_get_current_user();
	$user_id = $user->ID;
}

$restrictions = apply_filters(
	'stm_get_post_limits',
	array(
		'premoderation' => true,
		'posts_allowed' => 0,
		'posts'         => 0,
		'images'        => 0,
		'role'          => 'user',
	),
	$user_id,
	'publish'
);

if ( $restrictions['posts'] < 1 ) {
	$restricted = true;
}

$login_page = apply_filters( 'motors_vl_get_nuxy_mod', 1718, 'login_page' );
$login_page = apply_filters( 'stm_motors_wpml_is_page', $login_page );

if ( $restricted && ! $car_edit && ! apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_pay_per_listing' ) ) : ?>
	<div class="stm-no-available-adds-overlay"></div>
	<div class="stm-no-available-adds">
		<h3><?php esc_html_e( 'Posts Available', 'motors-wpbakery-widgets' ); ?>: <span>0</span></h3>
		<p><?php esc_html_e( 'You ended the limit of free classified ads. Please select one of the following', 'motors-wpbakery-widgets' ); ?></p>
		<div class="clearfix">
			<?php if ( apply_filters( 'stm_pricing_enabled', false ) ) : ?>
				<?php
				$stm_pricing_link = stm_pricing_link();
				if ( ! empty( $stm_pricing_link ) ) :
					?>
					<a href="<?php echo esc_url( $stm_pricing_link ); ?>" class="button stm-green">
						<?php esc_html_e( 'Upgrade Plan', 'motors-wpbakery-widgets' ); ?>
					</a>
				<?php endif; ?>
			<?php else : ?>
				<?php if ( 'user' === $restrictions['role'] ) : ?>
					<a href="<?php echo esc_url( add_query_arg( array( 'become_dealer' => 1 ), apply_filters( 'stm_get_author_link', '' ) ) ); ?>" class="button stm-green">
						<?php esc_html_e( 'Become a Dealer', 'motors-wpbakery-widgets' ); ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>" class="button stm-green-dk"><?php esc_html_e( 'My inventory', 'motors-wpbakery-widgets' ); ?></a>
			<?php elseif ( $login_page ) : ?>
				<a href="<?php echo esc_url( get_permalink( $login_page ) ); ?>" class="button stm-green-dk"><?php esc_html_e( 'Registration', 'motors-wpbakery-widgets' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'stm_listings_load_template', 'add_car/binding' ); ?>

<div class="stm_add_car_form 
<?php
echo esc_attr( $stm_edit_car_form );
echo esc_attr( $css_class );
?>
">
	<?php
	if ( $car_edit ) {
		if ( ! is_user_logged_in() ) {
			echo '<h4>' . esc_html__( 'Please login.', 'motors-wpbakery-widgets' ) . '</h4></div>';
			return false;
		}

		if ( ! empty( $_GET['item_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$item_id = intval( $_GET['item_id'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$car_user = get_post_meta( $item_id, 'stm_car_user', true );

			if ( intval( $user_id ) !== intval( $car_user ) ) {
				echo '<h4>' . esc_html__( 'You are not the owner of this car.', 'motors-wpbakery-widgets' ) . '</h4></div>';
				return false;
			}
		} else {
			echo '<h4>' . esc_html__( 'No car to edit.', 'motors-wpbakery-widgets' ) . '</h4></div>';
			return false;
		}
	}
	?>

	<form method="POST" action="" enctype="multipart/form-data" id="stm_sell_a_car_form">
		<?php wp_nonce_field(); ?>
		<?php if ( $car_edit ) { ?>
			<input type="hidden" value="<?php echo intval( $vars['id'] ); ?>" name="stm_current_car_id"/>
			<input type="hidden" value="update" name="stm_edit"/>
		<?php } else { ?>
			<input type="hidden" value="adding" name="stm_edit"/>
		<?php } ?>

		<?php do_action( 'stm_vin_auto_complete_require_template' ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/title', $vars ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/step_1', $vars ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/step_2', $vars ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/step_3', $vars ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/step_4', $vars ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/step_5', $vars ); ?>

		<?php do_action( 'stm_listings_load_template', 'add_car/step_6', $vars ); ?>

		<?php
		if ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) {
			do_action( 'stm_listings_load_template', 'add_car/step_7', $vars );
		}
		?>
	</form>

	<?php do_action( 'stm_listings_load_template', 'add_car/progress-bar', $vars ); ?>

	<?php do_action( 'stm_listings_load_template', 'add_car/check_user', $vars ); ?>

</div>
