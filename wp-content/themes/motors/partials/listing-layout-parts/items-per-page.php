<?php
$listing_grid_choices = explode( ',', apply_filters( 'motors_vl_get_nuxy_mod', '9,12,18,27', 'listing_grid_choices' ) );
$view_type            = sanitize_file_name( apply_filters( 'stm_listings_input', apply_filters( 'stm_me_get_nuxy_mod', 'list', 'listing_view_type' ), 'view_type' ) );
$archive_page_id      = stm_get_listing_archive_page_id();
$archive_posts_ppp    = get_post_meta( $archive_page_id, ( 'grid' === $view_type ) ? 'ppp_on_grid' : 'ppp_on_list', true );
$listings_per_page    = ( ! empty( $archive_posts_ppp ) ) ? $archive_posts_ppp : get_option( 'posts_per_page' );

$posts_per_page = apply_filters( 'stm_listings_input', null, 'posts_per_page' );
if ( ! empty( $posts_per_page ) ) {
	$listing_grid_choices[] = absint( $posts_per_page );
} else {
	$posts_per_page = $listings_per_page;
}

$listing_grid_choices[] = $listings_per_page;

sort( $listing_grid_choices );

$listing_grid_choices = array_unique( $listing_grid_choices );

if ( ! empty( $listing_grid_choices ) ) : ?>
	<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
		<span class="stm_label heading-font"><?php esc_html_e( 'Vehicles per page:', 'motors' ); ?></span>
	<?php endif; ?>
	<span class="first"><?php esc_html_e( 'Show', 'motors' ); ?></span>
	<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
		<div class="stm_motorcycle_pp">
	<?php endif; ?>
	<ul>
		<?php
		foreach ( $listing_grid_choices as $listing_grid_choice_single ) :
			$is_active = absint( $listing_grid_choice_single ) === absint( $posts_per_page );
			$active    = $is_active ? 'class=active' : '';
			$_label    = sprintf( /* translators: %s page number */ __( 'Page %s', 'motors' ), absint( $listing_grid_choice_single ) );
			$_link     = add_query_arg( array( 'posts_per_page' => absint( $listing_grid_choice_single ) ), get_the_permalink( $archive_page_id ) );
			$_link     = preg_replace( '/\/page\/\d+/', '', remove_query_arg( array( 'paged', 'ajax_action' ), $_link ) );
			?>

			<li <?php echo esc_attr( $active ); ?>>
				<?php if ( $is_active && ! apply_filters( 'stm_is_boats', false ) ) : ?>
					<span aria-current="page" aria-label="<?php echo esc_attr( $_label ); ?>">
						<?php echo esc_html( absint( $listing_grid_choice_single ) ); ?>
					</span>
				<?php else : ?>
					<a href="<?php echo esc_url( apply_filters( 'paginate_links', $_link ) ); ?>" aria-label="<?php echo esc_attr( $_label ); ?>">
						<?php echo esc_html( absint( $listing_grid_choice_single ) ); ?>
					</a>
				<?php endif; ?>
			</li>

		<?php endforeach; ?>
	</ul>
	<?php if ( apply_filters( 'stm_is_motorcycle', false ) ) : ?>
		</div>
	<?php endif; ?>
	<span class="last"><?php esc_html_e( 'items per page', 'motors' ); ?></span>
<?php endif; ?>
