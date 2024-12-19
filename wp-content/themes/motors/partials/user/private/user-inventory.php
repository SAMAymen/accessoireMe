<?php
$user_page = get_queried_object();
$user_id   = $user_page->data->ID;
?>
<div class="stm-delete-confirmation-popup stm-disabled">
	<i class="fas fa-times"></i>
	<div class="stm-confirmation-text heading-font">
		<span class="stm-danger"><?php esc_html_e( 'Delete', 'motors' ); ?></span>
		<span class="stm-car-title"></span>
	</div>
	<div class="actions">
		<a href="#" class="button stm-red-btn"><?php esc_html_e( 'Delete', 'motors' ); ?></a>
		<a href="#" class="button stm-grey-btn"><?php esc_html_e( 'Cancel', 'motors' ); ?></a>
	</div>
</div>
<div class="stm-delete-confirmation-overlay stm-disabled"></div>

<h4 class="stm-seller-title stm-main-title"><?php esc_html_e( 'My Inventory', 'motors' ); ?></h4>

<div class="stm-sort-private-my-cars">
	<?php if ( stm_is_multilisting() ) : ?>
		<?php
		$listings = stm_listings_multi_type_labeled( true );
		if ( ! empty( $listings ) ) :
			?>
			<div class="select-type select-listing-type" style="margin-right: 15px;">
				<div class="stm-label-type"><?php esc_html_e( 'Listing type', 'motors' ); ?></div>
				<select data-user="<?php echo esc_attr( $user_id ); ?>" data-user-private="1">
					<option value="all" selected><?php esc_html_e( 'All listing types', 'motors' ); ?></option>
					<?php foreach ( $listings as $slug => $label ) : ?>
						<option value="<?php echo esc_attr( $slug ); ?>" <?php echo ( isset( $_GET['listing_type'] ) && $_GET['listing_type'] === $slug ) ? 'selected' : ''; ?>><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<div class="select-type select-order-by">
		<div class="stm-label-type"><?php esc_html_e( 'Sort by', 'motors' ); ?></div>
		<select>
			<option value="all"><?php esc_html_e( 'All', 'motors' ); ?></option>
			<option value="pending"><?php esc_html_e( 'Pending', 'motors' ); ?></option>
			<option value="draft"><?php esc_html_e( 'Disabled', 'motors' ); ?></option>
		</select>
	</div>
</div>

<div class="clearfix"></div>

<?php //phpcs:disable ?>
<script>
	jQuery(document).ready(function () {
		var $ = jQuery;
		// order select
		$('.select-order-by select').select2().on('change', function () {
			var opt_val = $(this).val();
			if (opt_val == 'all') {
				$('.listing-list-loop-edit').removeClass('stm-invisible');
			} else if (opt_val == 'pending') {
				$('.listing-list-loop-edit').removeClass('stm-invisible');
				$('.listing-list-loop-edit:not(.' + opt_val + ')').addClass('stm-invisible');
			} else if (opt_val == 'draft') {
				$('.listing-list-loop-edit').removeClass('stm-invisible');
				$('.listing-list-loop-edit:not(.' + opt_val + ')').addClass('stm-invisible');
			}
		});
		<?php
		$current_url = apply_filters( 'stm_get_author_link', '' );
		$glue        = '?';
		if ( isset( $_GET ) && ! empty( $_GET ) ) {
			$url_array = $_GET;
			if ( isset( $url_array['listing_type'] ) ) {
				unset( $url_array['listing_type'] );
			}
			if ( ! empty( $url_array ) ) {
				$current_url = add_query_arg( $url_array, stm_get_author_link( '' ) );
				$glue        = '&';
			}
		}
		?>

        /*Stm confirmation before delete*/
        var urlToProceed = '';
        //Open confirmation
        $('.stm-delete-confirmation').on('click', function (e) {
            e.preventDefault();

			urlToProceed = $(this).attr('href');
			var carTitle = $(this).data('title');

			$('.stm-delete-confirmation-popup').removeClass('stm-disabled');
			$('.stm-delete-confirmation-overlay').removeClass('stm-disabled');

			$('.stm-confirmation-text .stm-car-title').text(carTitle);
		});

		//Delete
		$('.stm-delete-confirmation-popup .actions .stm-red-btn').on('click', function (e) {
			e.preventDefault();
			var del = confirm('<?php echo esc_html__( 'Do you want to delete Listing images permanently?', 'motors' ); ?>');
			if (del == true) {
				var date = new Date(new Date().getTime() + 10 * 1000);
				document.cookie = "deleteListingAttach=delete; path=/; expires=" + date.toUTCString();
			}
			window.location = urlToProceed;
		});

		//Cancel delete
		$('.stm-delete-confirmation-popup .actions .stm-grey-btn, .stm-delete-confirmation-overlay, .stm-delete-confirmation-popup .fa-times').on('click', function (e) {
			e.preventDefault();
			$('.stm-delete-confirmation-popup').addClass('stm-disabled');
			$('.stm-delete-confirmation-overlay').addClass('stm-disabled');
		});
	});
</script>
<?php //phpcs:enable ?>
