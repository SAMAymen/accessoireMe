<?php
$_id = apply_filters( 'stm_listings_input', null, 'item_id' );
$pey_per_listing = get_post_meta( $_id, 'pay_per_listing', true );

if ( false === apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_plans' ) || false === apply_filters( 'stm_is_multiple_plans', false ) || ! empty( $pey_per_listing ) ) {
	return false;
}
$plans         = \MotorsVehiclesListing\Features\MultiplePlan::getPlans();
$selected_plan = \MotorsVehiclesListing\Features\MultiplePlan::getCurrentPlan( $__vars['id'] );
$is_editing    = ( ! empty( $_GET['edit_car'] ) && ! empty( $_GET['item_id'] ) ) ? true : false;//phpcs:ignore

$allSlotsUsed = true;

foreach ( $plans['plans'] as $plan ) {
	if ( $plan['total_quota'] > $plan['used_quota'] ) {
		$allSlotsUsed = false;
		break;
	}
}

if ( $allSlotsUsed ) {
	return;
}

?>
<div class="stm-form-plans">
	<div class="stm-car-listing-data-single stm-border-top-unit ">
		<div class="title heading-font"><?php esc_html_e( 'Choose plan', 'motors' ); ?></div>
		<span class="step_number step_number_5 heading-font"><?php esc_html_e( 'step', 'motors' ); ?> 7</span>
	</div>
	<div id="user_plans_select_wrap">
		<?php if ( is_user_logged_in() ) { ?>
			<div class="user-plans-list" >
				<select name="selectedPlan">
					<option value=""><?php echo esc_html__( 'Select Plan', 'motors' ); ?></option>
					<?php
					$count = 0;
					foreach ( $plans['plans'] as $plan ) :
						$selected = '';
						if ( 1 === count( $plans['plans'] ) - $count ) {
							$selected = 'selected';
						}
						if ( (string) $plan['plan_id'] === (string) $selected_plan && $plan['used_quota'] < $plan['total_quota'] ) {
							$selected = 'selected';
						} elseif ( (string) $plan['used_quota'] >= (string) $plan['total_quota'] ) {
							$selected = 'disabled';
							$count++;
						}

						if ( $is_editing && (string) $plan['plan_id'] === (string) $selected_plan && $plan['used_quota'] <= $plan['total_quota'] ) {
							$selected = 'selected';
						}
						?>

						<option value="<?php echo esc_attr( $plan['plan_id'] ); ?>" <?php echo esc_attr( $selected ); ?>>
							<?php echo wp_kses_post( sprintf( ( '%s %s / %s' ), $plan['label'], $plan['used_quota'], $plan['total_quota'] ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php } else { ?>
			<p style="color: #888888; font-size: 13px;"><?php echo esc_html__( 'Please, log in to view your available plans', 'motors' ); ?></p>
		<?php } ?>
	</div>
</div>
