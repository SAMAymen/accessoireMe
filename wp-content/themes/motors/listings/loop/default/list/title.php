<div class="title heading-font">
	<a href="<?php the_permalink(); ?>" class="rmv_txt_drctn">
		<?php
		$as_label = apply_filters( 'motors_vl_get_nuxy_mod', false, 'show_generated_title_as_label' );
		echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), $as_label ) );
		?>
	</a>
</div>
