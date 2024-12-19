<?php

$prevNext = stm_custom_prev_next( get_the_ID() );

$prevImg   = '';
$prevDate  = '';
$prevTitle = '';
$prevLink  = '';

$nextImg   = '';
$nextDate  = '';
$nextTitle = '';
$nextLink  = '';


if ( isset( $prevNext['prev'] ) ) {
	$prevImg   = ( ! empty( get_the_post_thumbnail( $prevNext['prev']->ID, 'thumbnail' ) ) ) ? get_the_post_thumbnail( $prevNext['prev']->ID, 'thumbnail' ) : '';
	$prevDate  = get_the_date( 'd M Y', $prevNext['prev']->ID );
	$prevTitle = $prevNext['prev']->post_title;
	$prevLink  = get_the_permalink( $prevNext['prev']->ID );
}

if ( isset( $prevNext['next'] ) ) {
	$nextImg   = ( ! empty( get_the_post_thumbnail( $prevNext['next']->ID, 'thumbnail' ) ) ) ? get_the_post_thumbnail( $prevNext['next']->ID, 'thumbnail' ) : '';
	$nextDate  = get_the_date( 'd M Y', $prevNext['next']->ID );
	$nextTitle = $prevNext['next']->post_title;
	$nextLink  = get_the_permalink( $prevNext['next']->ID );
}


?>
<div class="stm_prev_next_pagination">
	<div class="left">
		<?php if ( ! empty( $prevLink ) ) : ?>
			<a href="<?php echo esc_url( $prevLink ); ?>">
				<div class="img">
					<?php echo wp_kses_post( $prevImg ); ?>
				</div>
				<div class="post-data">
					<div class="top">
					<span class="pagi-label heading-font">
						<?php echo esc_html__( 'PREVIOUS', 'motors' ); ?>
					</span>
						<span class="date normal_font">
						<?php echo esc_html( $prevDate ); ?>
					</span>
					</div>
					<div class="bottom heading-font">
						<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $prevTitle, 'Navigation Preview Title' ) ); ?>
					</div>
				</div>
			</a>
		<?php endif; ?>
	</div>
	<div class="right">
		<?php if ( ! empty( $nextLink ) ) : ?>
			<a href="<?php echo esc_url( $nextLink ); ?>">
				<div class="img">
					<?php echo wp_kses_post( $nextImg ); ?>
				</div>
				<div class="post-data">
					<div class="top">
					<span class="pagi-label heading-font">
						<?php echo esc_html__( 'NEXT', 'motors' ); ?>
					</span>
						<span class="date normal_font">
						<?php echo( esc_html( $nextDate ) ); ?>
					</span>
					</div>
					<div class="bottom heading-font">
						<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $nextTitle, 'Navigation Next Title' ) ); ?>
					</div>
				</div>
			</a>
		<?php endif; ?>
	</div>
</div>

