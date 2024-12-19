<?php
$id     = get_the_ID();
$body   = get_the_terms( $id, 'body' );
$body   = ( ! is_wp_error( $body ) ) ? $body[0]->name : '';
$serial = get_post_meta( $id, 'serial_number', true );
$reg    = get_post_meta( $id, 'registration_number', true );
?>

<div class="stm-aircraft-title-box heading-font">
	<div class="left">
		<h1>
			<?php
				$as_label = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_generated_title_as_label' );
				echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), $as_label ) );
			?>
		</h1>
		<div class="stm-air-category">
			<?php echo esc_html__( 'Type: ', 'motors' ); ?>
			<span><?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $body, 'Category Body Type' ) ); ?></span>
		</div>
	</div>
	<div class="right">
		<div class="stm-air-category">
			<?php echo esc_html__( 'Serial Number: ', 'motors' ); ?>
			<span><?php echo esc_html( $serial ); ?></span>
		</div>
		<div class="stm-air-category">
			<?php echo esc_html__( 'Reg Number: ', 'motors' ); ?>
			<span><?php echo esc_html( $reg ); ?></span>
		</div>
	</div>
</div>
