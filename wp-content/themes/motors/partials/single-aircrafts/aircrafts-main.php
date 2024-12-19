<div class="row">
    <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="single-listing-car-inner">
            <?php the_content(); ?>
        </div>
    </div>

    <div class="col-md-3 col-sm-12 col-xs-12">
        <?php if ( is_active_sidebar( 'stm_listing_car' )) { ?>
            <div class="stm-single-listing-car-sidebar">
                <?php dynamic_sidebar( 'stm_listing_car' ); ?>
            </div>
        <?php } ?>
    </div>
</div>