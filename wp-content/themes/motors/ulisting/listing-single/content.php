<?php
/**
 * Listing single content
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing-single/content.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0.3
 */

use uListing\Classes\StmListingTemplate;
use uListing\Classes\Builder\UListingBuilder;

$listing_type = $model->getType();
$layout = $listing_type->getSinglePageLayout();

if (isset($_GET['layout'])) {
    foreach ($listing_type->getAllSinglePageLayout() as $key => $_layout) {
        if ($_layout['name'] == 'Layout ' . $_GET['layout']) {
            $layout = $_layout;
            $layout['id'] = $key;
        }
    }
}

if (isset($layout['id'])) {
    $model->generation_attribute_elements('ulisting_' . $layout['id'] . '_element_data', $listing_type);

    $builder = new UListingBuilder();
    \uListing\Classes\UlistingPageStatistics::page_statistics_for_listing($model->ID);
    if (isset($layout['section']) AND isset($layout['id'])) {
        echo \uListing\Classes\Builder\UListingBuilder::render($layout['section'], $layout['id'], [
            'model' => $model,
            'listing_type' => $listing_type,
        ]);
    }
}
?>