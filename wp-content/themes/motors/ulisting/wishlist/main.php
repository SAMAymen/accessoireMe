<?php

use uListing\Classes\StmListingTemplate;

if ( empty( $endpoint ) ) $endpoint = 'wishlist-list';
?>
<div class="wishlist-content-wrap">
	<?php echo StmListingTemplate::load_template( 'listing-list/breadcrumbs' ); ?>

    <h2><?php echo ( esc_attr($endpoint) == 'wishlist-list' ) ? esc_html__( 'Wishlist', 'motors' ) : esc_html__( 'Saved Search', 'motors' ); ?></h2>

    <ul class="nav nav-pills nav-fill">
        <li class="nav-item">
            <a href="<?php echo esc_url( $wishlist_page_url ) ?>/wishlist-list"
               class="nav-link <?php echo ( 'wishlist-list' == $endpoint ) ? 'active' : null ?> ">
                <span class="badge badge-dark ulisting-wishlist-total-count heading-font"><?php echo \uListing\UlistingWishlist\Classes\UlistingWishlist::get_total_count() ?></span>
				<?php _e( "Wishlist", 'motors' ) ?>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?php echo esc_url( $wishlist_page_url ) ?>/saved-searches-list"
               class="nav-link <?php echo ( 'saved-searches-list' == $endpoint ) ? 'active' : null ?>  ">
                <span class="badge badge-dark ulisting-saved-searches-total-count heading-font"><?php echo \uListing\Classes\UlistingSearch::get_total_count() ?></span>
				<?php _e( "Saved Search", 'motors' ) ?>
            </a>
        </li>

    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active">
			<?php
			switch ( $endpoint ) {
				case "wishlist-list":
					do_action( 'ulisting-wishlist-render-page' );
					break;
				case "saved-searches-list":
					do_action( 'ulisting-saved-searches-render-page' );
					break;
			}
			?>
        </div>
    </div>
</div>


