<?php
/**
 * Builder attribute price
 *
 * Template can be modified by copying it to yourtheme/ulisting/builder/attribute/price.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.4.1
 */
use uListing\Classes\StmListingAttribute;

$model = $args['model'];
$attr_data = $model->getAttributeValue(StmListingAttribute::TYPE_PRICE);

$disc = (!empty($attr_data['old_price'])) ? 'has-discount' : '';
?>
<div class="item-price heading-font <?php echo esc_attr($disc); ?>">
    <div <?php echo \uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element) ?>>
        <?php echo StmListingAttribute::render_attribute($args['model'], $element)?>
    </div>
</div>
