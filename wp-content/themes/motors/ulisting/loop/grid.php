<?php
/**
 * Loop grid
 *
 * Template can be modified by copying it to yourtheme/ulisting/loop/grid.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

?>
<div class="<?php echo esc_html($item_class)?>">
<?php
    $explode = '';
    if(!empty($item_class)) {
        $explode = explode(' ', $item_class);
		$explode = (!empty($explode[1])) ? $explode[1] : '';
    }

	echo \uListing\Classes\Builder\UListingBuilder::render($listing_item_card_layout, "ulisting_item_card_".$listingType->ID."_grid", [
		'model' => $model,
		'listingType' => $listingType,
        'item_position' => (!empty($explode) && strpos($explode, 'stm-featured') !== false) ? $explode : ''
	]);
?>
</div>

