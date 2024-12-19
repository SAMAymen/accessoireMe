<?php
$history_link       = get_post_meta( get_the_ID(), 'history_link', true );
$car_price_form     = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_offer_price' );
$show_share         = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_share' );
$show_test_drive    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_test_drive' );
$stm_car_link_quote = apply_filters( 'motors_vl_get_nuxy_mod', '#1471332454395-0e51ff9f-8682', 'stm_car_link_quote' );
$show_trade_in      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_trade_in' );
$show_calculator    = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_calculator' );
$show_report        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_history' );


$links = array();

if ( ! empty( $stm_car_link_quote ) ) {
	$links['stm-moto-icon-chat'] = array(
		'link'   => $stm_car_link_quote,
		'target' => '_self',
		'text'   => esc_html__( 'Request a quote', 'motors' ),
	);
}

if ( $show_test_drive ) {
	$links['stm-moto-icon-helm'] = array(
		'link'  => '#test-drive',
		'modal' => 'data-toggle="modal" data-target="#test-drive"',
		'text'  => esc_html__( 'Schedule test drive', 'motors' ),
	);
}

if ( $car_price_form ) {
	$links['stm-moto-icon-phone-chat'] = array(
		'link'  => '#get-a-call',
		'modal' => 'data-toggle="modal" data-target="#get-car-price"',
		'text'  => esc_html__( 'Quote by Phone', 'motors' ),
	);
}

if ( $show_trade_in ) {
	$links['stm-moto-icon-trade'] = array(
		'link'  => '#trade-in',
		'modal' => 'data-toggle="modal" data-target="#trade-in"',
		'text'  => esc_html__( 'Trade In', 'motors' ),
	);
}

if ( $show_calculator ) {
	$links['stm-moto-icon-cash'] = array(
		'link'  => '#calc',
		'modal' => 'data-toggle="modal" data-target="#get-car-calculator"',
		'text'  => esc_html__( 'Сalculate Payment', 'motors' ),
	);
}

if ( $show_share ) {
	$links['stm-moto-icon-share'] = array(
		'link' => '#calculator',
		'text' => esc_html__( 'Share this', 'motors' ),
	);
}

if ( ! empty( $history_link ) && $show_report ) {
	$links['stm-moto-icon-report'] = array(
		'link' => esc_url( $history_link ),
		'text' => esc_html__( 'History report', 'motors' ),
	);
}

?>

<div class="stm-single-car-links">
	<?php foreach ( $links as $icon => $lnk ) : ?>
		<?php
			$target = '_blank';
		if ( ! empty( $lnk['target'] ) ) {
			$target = $lnk['target'];
		}
		?>
		<div class="stm-single-car-link unit-<?php echo esc_attr( $icon ); ?> heading-font">
			<a href="<?php echo esc_url( $lnk['link'] ); ?>" target="<?php echo esc_attr( $target ); ?>" 
				<?php
				if ( ! empty( $lnk['modal'] ) ) {
					echo wp_kses_post( $lnk['modal'] );
				}
				?>
				<?php
				if ( 'stm-moto-icon-share' === $icon ) {
					echo 'class="stm-share"';}
				?>
				>
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
				<?php echo esc_html( $lnk['text'] ); ?>
			</a>
			<?php if ( 'stm-moto-icon-share' === $icon && function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) && ! get_post_meta( get_the_ID(), 'sharing_disabled', true ) ) : ?>
				<div class="stm-a2a-popup">
					<?php echo apply_filters( 'stm_add_to_any_shortcode', get_the_ID() );//phpcs:ignore ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
