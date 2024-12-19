<?php
/**
 * Listing
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing/listing.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 2.0.0
 */
$model                          = $args['model']->getType();
$region                         = $args['model']->getRegion();
$category                       = $args['model']->getCategory();
$element['params']['data-id']   = $args['model']->ID;

$models = \uListing\Classes\StmListingType::get_similar_listings(
    [
        "type_id"       => $model->ID,
        "listing_id"    => $args['model']->ID,
        "region"        => isset($region[0]->term_id) ? $region[0]->term_id : null,
        "category"      => isset($category[0]->term_id) ? $category[0]->term_id : null,
    ]
);
?>
<div class="ulisting-similar-listings" <?php echo esc_attr(\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element));?>>
	<h3><?php echo esc_html__( 'Similar Listings', 'motors' ); ?></h3>
    <?php if( count($models) > 0 ): ?>
        <div class="motors-ulisting" id="stm-listing-list-panel">
			<div class="stm-row">
	            <?php
	            foreach ($models as $_model)
	                echo apply_filters('uListing-listing-view', $_model);
	            ?>
			</div>
        </div>
    <?php else:; ?>
		<p class="ulisting-no-similar-listing"><?php echo __("No similar listings found", "motors")?></p>
    <?php endif; ?>
</div>

