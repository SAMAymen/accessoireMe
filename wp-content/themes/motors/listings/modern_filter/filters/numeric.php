<?php
/**
 *
 * @var $terms
 * @var $modern_filter
 * @var $unit
 */

?>

<div class="stm-accordion-single-unit <?php echo esc_attr( $unit['slug'] ); ?>">
	<a class="title collapsed"
		data-toggle="collapse"
		href="#<?php echo esc_attr( $unit['slug'] ); ?>"
		aria-expanded="false">
		<h5><?php echo esc_html( $unit['single_name'] ); ?></h5>
		<span class="minus"></span>
	</a>
	<div class="stm-accordion-content">
		<div class="collapse content" id="<?php echo esc_attr( $unit['slug'] ); ?>">
			<div class="stm-accordion-content-wrapper">

			</div>
		</div>
	</div>
</div>
