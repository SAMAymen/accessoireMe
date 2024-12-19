<?php
/**
 * @var $type
 * @var $navigation_type
*/

$query = $GLOBALS['wp_query'];

if ( have_posts() ) :
	$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' );

	if ( wp_is_mobile() ) {
		$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', 'listing_view_type_mobile' );
	}

	if ( function_exists( 'stm_is_multilisting' ) && stm_is_multilisting() && wp_is_mobile() ) {
		$nuxy_mod_option = apply_filters( 'motors_vl_get_nuxy_mod', 'grid', get_query_var( 'post_type' ) . '_view_type_mobile' );
	}
	$view_type = apply_filters( 'stm_listings_input', $nuxy_mod_option, 'view_type' );

	$template_args = array();
	if ( ! empty( $custom_img_size ) && has_image_size( $custom_img_size ) ) {
		$template_args['custom_img_size'] = $custom_img_size;
	}

	/*Filter Badges*/
	do_action( 'stm_listings_load_template', 'filter/badges' );

	if ( apply_filters( 'is_listing', array() ) && 'sold_car' !== $type ) {
		do_action( 'stm_listings_load_template', 'classified/filter/featured' );
	}
	?>

	<div class="stm-isotope-sorting stm-isotope-sorting-<?php echo esc_attr( $view_type ); ?>">
		<?php
		if ( 'grid' === $view_type ) :
			?>
		<div class="row row-3 car-listing-row car-listing-modern-grid">
			<?php
		endif;

			$template = 'partials/listing-cars/listing-' . $view_type . '-loop';

		if ( apply_filters( 'stm_is_ev_dealer', false ) || apply_filters( 'is_listing', array( 'listing', 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor', 'listing_one_elementor' ) ) || apply_filters( 'stm_is_dealer_two', false ) || apply_filters( 'stm_is_listing_five', false ) || apply_filters( 'stm_is_listing_six', false ) ) {
			$template = 'partials/listing-cars/listing-' . $view_type . '-directory-loop';
		} elseif ( apply_filters( 'stm_is_listing_four', false ) ) {
			$template = 'partials/listing-cars/listing-four-' . $view_type . '-loop';
		} elseif ( apply_filters( 'stm_is_boats', false ) && 'list' === $view_type ) {
			$template = 'partials/listing-cars/listing-' . $view_type . '-loop-boats';
		} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
			$template = 'partials/listing-cars/motos/' . $view_type;
		} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
			$template = 'partials/listing-cars/listing-aircrafts-' . $view_type;
		}

		while ( have_posts() ) :
			the_post();
			get_template_part( $template, null, $template_args );
			endwhile;
		if ( 'grid' === $view_type ) :
			?>
		</div>
	<?php endif; ?>

	</div>
	<?php
	if ( is_null( $navigation_type ) || 'pagination' === $navigation_type ) :
		stm_listings_load_pagination( $__vars['posts_per_page'] );
	else :
		$ppp   = $query->query['posts_per_page'];
		$paged = ( ! empty( $query->query['paged'] ) && 1 !== $query->query['paged'] ) ? $query->query['paged'] + 1 : 2;

		if ( $ppp < $query->found_posts ) {
			echo "<a class='btn stm-inventory-load-more-btn' href='#' data-ppp='" . esc_attr( $ppp ) . "' data-page='" . esc_attr( $paged ) . "' data-nav='load_more' data-offset='1'>" . esc_html__( 'Load More', 'motors' ) . '</a>';
		}
	endif;

	wp_reset_query(); //phpcs:ignore
	?>
<?php else : ?>

	<div class="stm-listings-empty">
		<span class="stm-icon-search-list"></span>
		<span class="stm-listings-empty__not-found"><?php esc_html_e( 'Not found any vehicle based on your filter', 'motors' ); ?></span>
		<span class="stm-listings-empty__another"><?php esc_html_e( 'Try another filter, location or keywords', 'motors' ); ?></span>
		<?php
		$reset_url = ( ! apply_filters( 'motors_vl_get_nuxy_mod', false, 'friendly_url' ) ) ? strtok( $_SERVER['REQUEST_URI'], '?' ) : apply_filters( 'stm_inventory_page_url', '', get_query_var( 'post_type' ) );
		?>
		<a href="<?php echo esc_url( $reset_url ); ?>" class="stm-listings-empty__button">
			<span><?php esc_html_e( 'Reset filters', 'motors' ); ?></span>
		</a>
	</div>

	<?php
	if ( ! apply_filters( 'motors_vl_get_nuxy_mod', true, 'enable_distance_search' ) && apply_filters( 'motors_vl_get_nuxy_mod', false, 'recommend_items_empty_result' ) ) {
		do_action( 'stm_listings_load_template', 'filter/inventory/items-empty' );
	}

	endif;
?>
<?php if ( apply_filters( 'stm_is_aircrafts', false ) ) : ?>
	<script>
		jQuery(document).ready(function (){
			var showing = '<?php echo esc_html( $query->found_posts ); ?>';

			jQuery('.ac-total').text('<?php echo esc_html( $query->found_posts ); ?>');

			if(showing === '0') {
				jQuery('.ac-showing').text('0');
			}
		});
	</script>
<?php endif; ?>
