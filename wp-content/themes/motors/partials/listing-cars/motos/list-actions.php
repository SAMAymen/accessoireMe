<?php
$links = array();

$show_listing_test_drive = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_test_drive' );
$show_listing_quote      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_quote' );
$show_listing_trade      = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_trade' );
$show_listing_calculate  = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_calculate' );
$show_listing_vin        = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_listing_vin' );

$history_link = get_post_meta( get_the_ID(), 'history_link', true );

$links['stm-moto-icon-angle-round'] = array(
	'link'   => get_permalink( get_the_ID() ),
	'target' => '_self',
	'text'   => esc_html__( 'View Details', 'motors' ),
);

if ( $show_listing_test_drive ) {
	$links['stm-moto-icon-helm'] = array(
		'link'  => '#test-drive',
		'modal' => 'data-toggle="modal" data-target="#test-drive"',
		'text'  => esc_html__( 'Test drive', 'motors' ),
	);
}

if ( $show_listing_calculate ) {
	$links['stm-moto-icon-cash'] = array(
		'link'  => '#calc',
		'modal' => 'data-toggle="modal" data-target="#get-car-calculator"',
		'text'  => esc_html__( 'Сalculate', 'motors' ),
	);
}

if ( $show_listing_trade ) {
	$links['stm-moto-icon-trade'] = array(
		'link'  => '#trade-offer',
		'modal' => 'data-toggle="modal" data-target="#trade-offer"',
		'text'  => esc_html__( 'Trade value', 'motors' ),
	);
}

if ( $show_listing_quote ) {
	$links['stm-moto-icon-phone-chat'] = array(
		'link'  => '#get-a-call',
		'modal' => 'data-toggle="modal" data-target="#get-car-price"',
		'text'  => esc_html__( 'Quote by Phone', 'motors' ),
	);
}

if ( $show_listing_vin && ! empty( $history_link ) ) {
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
					echo wp_kses_post( $lnk['modal'] ) . ' data-id ="' . get_the_ID() . '" data-title="' . apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) . '" class="stm-modal-action"';//phpcs:ignore
				}
				?>
			>
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
				<?php echo esc_html( $lnk['text'] ); ?>
			</a>
			<script>
				jQuery(document).ready(function(){
					var $ = jQuery;
					$('.stm-modal-action').on('click', function(){
						var $popup = $($(this).data('target'));
						var stm_price = $(this).closest('.listing-list-loop').data('price');
						var stm_id = $(this).data('id');
						var stm_title = $(this).data('title');

						$popup.find('.test-drive-car-name').text(stm_title);
						$popup.find('.vehicle_price').val(stm_price);
						$popup.find('input[name="vehicle_id"]').val(stm_id);
					});
				})
			</script>
		</div>
	<?php endforeach; ?>
</div>
