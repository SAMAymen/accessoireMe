<?php
/**
 * @var $position
 * */

$enable_keywords_search = apply_filters( 'motors_vl_get_nuxy_mod', true, 'enable_keywords_search', false );
if ( $enable_keywords_search ) :

	$selected_position = apply_filters( 'motors_vl_get_nuxy_mod', 'bottom', 'position_keywords_search', false );
	$position          = $position ?? '';

	if ( $selected_position !== $position ) {
		return;
	}

	$searched = '';
	if ( ! empty( $_GET['stm_keywords'] ) ) { // phpcs:ignore WordPress.Security
		$searched = sanitize_text_field( $_GET['stm_keywords'] ); // phpcs:ignore WordPress.Security
	}
	?>
	<div class="col-md-12 col-sm-12 stm-search_keywords">
		<div class="form-group type-text">
			<?php
				$placeholder = __( 'Search...', 'motors' );
			if ( apply_filters( 'stm_is_boats', false ) || apply_filters( 'stm_is_motorcycle', false ) ) :
				$placeholder = __( 'Search by keywords...', 'motors' );
				else :
					?>
				<h5><?php esc_html_e( 'Search by keywords', 'motors' ); ?></h5>
			<?php endif; ?>
			<input type="text" class="form-control" name="stm_keywords" placeholder="<?php echo esc_attr( $placeholder ); ?>" id="stm_keywords" value="<?php echo esc_attr( $searched ); ?>" aria-label="<?php esc_attr_e( 'Search by keywords in listings', 'motors' ); ?>">
		</div>
	</div>
<?php endif; ?>
