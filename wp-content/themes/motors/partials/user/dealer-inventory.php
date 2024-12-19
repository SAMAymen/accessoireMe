<?php
$user_page     = get_queried_object();
$user_id       = $user_page->data->ID;
$query         = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id, 'publish', 6, false, 0, false, true ) : null;
$query_popular = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( $user_id, 'publish', 6, true, 0, false, true ) : null;

$row    = 'row row-3';
$active = 'grid';
$list   = '';
$grid   = 'active';
if ( ! empty( $_GET['view_type'] ) && 'list' === $_GET['view_type'] ) {
	$list   = 'active';
	$grid   = '';
	$active = 'list';
	$row    = 'row-no-border-last';
}
?>

<div class="stm_listing_tabs_style_2 stm-car-listing-sort-units stm-car-listing-directory-sort-units clearfix">
	<input type="hidden" id="stm_dealer_view_type" value="<?php echo esc_attr( $active ); ?>"/>
	<ul role="tablist" class="hidden">
		<li role="presentation">
			<a href="#popular" aria-controls="popular" role="tab" data-toggle="tab">p</a>
		</li>
		<li role="presentation">
			<a href="#recent" aria-controls="recent" role="tab" data-toggle="tab" class="active">r</a>
		</li>
	</ul>
	<h4 class="stm-seller-title"><?php esc_html_e( 'Dealer Inventory', 'motors' ); ?></h4>

	<div class="stm-directory-listing-top__right">
		<div class="clearfix">
			<div class="stm-view-by">
				<a href="?view_type=grid#stm_d_inv" class="stm-modern-view view-grid view-type <?php echo esc_attr( $grid ); ?>">
					<i class="stm-icon-grid"></i>
				</a>
				<a href="?view_type=list#stm_d_inv" class="stm-modern-view view-list view-type <?php echo esc_attr( $list ); ?>">
					<i class="stm-icon-list"></i>
				</a>
			</div>
			<div class="stm-sort-by-options clearfix">
				<span><?php esc_html_e( 'Sort by', 'motors' ); ?>:</span>
				<div class="stm-select-sorting">
					<select id="stm-dealer-view-type">
						<option value="popular"><?php esc_html_e( 'Popular items', 'motors' ); ?></option>
						<option value="recent" selected=""><?php esc_html_e( 'Recent items', 'motors' ); ?></option>
					</select>
				</div>
			</div>
			<?php if ( stm_is_multilisting() ) : ?>
				<div class="multilisting-select">
					<?php
					$listings = stm_listings_multi_type_labeled( true );
					if ( ! empty( $listings ) ) :
						?>
						<div class="select-type select-listing-type" style="margin-right: 15px;">
							<div class="stm-label-type"><?php esc_html_e( 'Listing type', 'motors' ); ?></div>
							<select>
								<option value="all" selected><?php esc_html_e( 'All Listings', 'motors' ); ?></option>
								<?php foreach ( $listings as $slug => $label ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php echo ( isset( $_GET['listing_type'] ) && ! empty( $_GET['listing_type'] ) && $_GET['listing_type'] === $slug ) ? 'selected' : ''; ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php if ( null !== $query && $query->have_posts() ) : ?>

	<div class="tab-content">
		<div class="tab-pane fade active in" role="tabpanel" id="recent">
			<?php if ( $query->have_posts() ) : ?>
				<div class="car-listing-row <?php echo esc_attr( $row ); ?>">
					<?php
					$counter = 0;
					while ( $query->have_posts() && $counter < 6 ) :
						$query->the_post();
						get_template_part( 'partials/listing-cars/listing-' . $active . '-directory-loop', 'animate' );
						$counter ++;
					endwhile;
					?>
				</div>
				<?php if ( $query->found_posts > 6 ) : ?>
					<div class="stm-load-more-dealer-cars">
						<a data-offset="6" data-user="<?php echo esc_attr( $user_id ); ?>" data-popular="no" href="#" class="heading-font"><span><?php esc_html_e( 'Show more', 'motors' ); ?></span></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<div class="tab-pane fade" role="tabpanel" id="popular">
			<?php if ( null !== $query_popular && $query_popular->have_posts() ) : ?>
				<div class="car-listing-row <?php echo esc_attr( $row ); ?>">
					<?php
					while ( $query_popular->have_posts() ) :
						$query_popular->the_post();
						?>
						<?php get_template_part( 'partials/listing-cars/listing-' . $active . '-directory-loop', 'animate' ); ?>
					<?php endwhile; ?>
				</div>
				<?php if ( $query->found_posts > 6 ) : ?>
					<div class="stm-load-more-dealer-cars">
						<a data-offset="6" data-user="<?php echo esc_attr( $user_id ); ?>" data-popular="yes" href="#" class="heading-font"><span><?php esc_html_e( 'Show more', 'motors' ); ?></span></a>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
<?php else : ?>
	<h4 class="stm-seller-title"
		style="color:#aaa; margin-top:44px"><?php esc_html_e( 'No Inventory added yet.', 'motors' ); ?></h4>
<?php endif; ?>

<?php
$current_url = apply_filters( 'stm_get_author_link', '' );
$glue        = '?';

if ( isset( $_GET ) && ! empty( $_GET ) ) {
	$url_array = $_GET;
	if ( isset( $url_array['listing_type'] ) ) {
		unset( $url_array['listing_type'] );
	}

	if ( ! empty( $url_array ) ) {
		$current_url = add_query_arg( $url_array, apply_filters( 'stm_get_author_link', '' ) );
		$glue        = '&';
	}
}
?>
