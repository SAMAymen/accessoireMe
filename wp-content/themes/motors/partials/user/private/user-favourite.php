<?php
$list      = 'active';
$grid      = '';
$user_page = get_queried_object();
$user_id   = $user_page->data->ID;
$active    = 'list';

if ( ! empty( $_GET['view'] ) and $_GET['view'] == 'grid' ) { //phpcs:ignore
	$list   = '';
	$grid   = 'active';
	$active = 'grid';
}
?>

<div class="stm-car-listing-sort-units stm-car-listing-directory-sort-units clearfix">
	<input type="hidden" id="stm_dealer_view_type" value="<?php echo esc_attr( $active ); ?>">
	<div class="stm-listing-directory-title">
		<h4 class="stm-seller-title"><?php esc_html_e( 'My Favorites', 'motors' ); ?></h4>
	</div>
	<div class="stm-directory-listing-top__right">
		<div class="clearfix">
			<?php if ( stm_is_multilisting() ) : ?>
				<div class="multilisting-select">
					<?php
					$listings = stm_listings_multi_type_labeled( true );
					if ( ! empty( $listings ) ) :
						?>
						<div class="select-type select-listing-type" style="margin-right: 15px;">
							<div class="stm-label-type"><?php esc_html_e( 'Listing type', 'motors' ); ?></div>
							<select data-user="<?php echo esc_attr( $user_id ); ?>" data-user-favourite="1">
								<option value="all"
										selected><?php esc_html_e( 'All listing types', 'motors' ); ?></option>
								<?php foreach ( $listings as $slug => $label ) : ?>
									<option value="<?php echo esc_attr( $slug ); ?>" <?php echo ( ! empty( $_GET['listing_type'] ) && $_GET['listing_type'] === $slug ) ? 'selected' : ''; //phpcs:ignore ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<div class="stm-view-by">
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'page' => 'favourite',
							'view' => 'grid',
						),
						apply_filters( 'stm_get_author_link', '' )
					)
				);
				?>
				" class="view-grid view-type <?php echo esc_attr( $grid ); ?>">
					<i class="stm-icon-grid"></i>
				</a>
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'page' => 'favourite',
							'view' => 'list',
						),
						apply_filters( 'stm_get_author_link', '' )
					)
				);
				?>
				" class="view-list view-type <?php echo esc_attr( $list ); ?>">
					<i class="stm-icon-list"></i>
				</a>
			</div>
		</div>
	</div>
</div>

<?php
$user = wp_get_current_user();
if ( ! empty( $user->ID ) ) {
	$favourites = get_the_author_meta( 'stm_user_favourites', $user->ID );
	if ( ! empty( $favourites ) ) {
		$fav_type = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );
		if ( isset( $_GET['listing_type'] ) && ! empty( $_GET['listing_type'] ) ) {//phpcs:ignore
			$fav_type = $_GET['listing_type'];//phpcs:ignore
		}
		$args       = array(
			'post_type'      => $fav_type,
			'post_status'    => 'any',
			'posts_per_page' => - 1,
			'post__in'       => array_unique( explode( ',', $favourites ) ),
		);
		$fav        = new WP_Query( $args );
		$exist_adds = array();
		if ( $fav->have_posts() ) :
			?>
			<div class="
			<?php
			if ( 'active' === $grid ) {
				echo 'row';
			}
			?>
			car-listing-row clearfix">
				<?php
				while ( $fav->have_posts() ) :
					$fav->the_post();
					?>
					<?php
					$exist_adds[] = get_the_id();
					if ( 'active' === $list ) {
						get_template_part( 'partials/listing-cars/listing-list-directory-loop', 'fav' );
					} else {
						get_template_part( 'partials/listing-cars/listing-grid-directory', 'loop' );
					}
					?>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>

		<!--Get deleted adds-->
		<?php
		$my_adds      = array_unique( explode( ',', $favourites ) );
		$deleted_adds = array_diff( $my_adds, $exist_adds );
		if ( ! empty( $deleted_adds ) && ! isset( $_GET['listing_type'] ) ) : //phpcs:ignore?>
			<div class="stm-deleted-adds">
				<?php foreach ( $deleted_adds as $deleted_add ) : ?>
					<?php if ( 0 !== $deleted_add ) : ?>
						<div class="stm-deleted-add">
							<div class="heading-font">
								<i class="fas fa-times stm-listing-favorite" data-id="<?php echo esc_attr( $deleted_add ); ?>"></i>
								<?php esc_html_e( 'Item has been removed', 'motors' ); ?>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<?php
		endif;
		if ( empty( $my_adds ) && empty( $deleted_adds ) ) :
			?>
			<h4><?php esc_html_e( 'You have not added favorites yet', 'motors' ); ?>.</h4>
			<?php
		endif;
	} else {
		?>
		<h4><?php esc_html_e( 'You have not added favorites yet', 'motors' ); ?>.</h4>
		<?php
	}
}

$current_url = apply_filters( 'stm_get_author_link', '' );
$glue        = '?';

$url_array = $_GET;//phpcs:ignore
if ( isset( $url_array['listing_type'] ) ) {
	unset( $url_array['listing_type'] );
}

if ( ! empty( $url_array ) ) {
	$current_url = add_query_arg( $url_array, apply_filters( 'stm_get_author_link', '' ) );
	$glue        = '&';
}
?>
<?php // @codingStandardsIgnoreStart ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        var $ = jQuery;
        $('.stm-deleted-adds .stm-deleted-add .heading-font .fa-times').on('click', function () {
            $(this).closest('.stm-deleted-add').slideUp();
        });
</script>
