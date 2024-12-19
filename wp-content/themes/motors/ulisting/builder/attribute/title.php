<?php
/**
 * Builder attribute title
 *
 * Template can be modified by copying it to yourtheme/ulisting/builder/attribute/title.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

?>
<div <?php echo \uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element) ?>>
	<a class="item-ulisting-title heading-font" href="<?php echo get_permalink($args['model']->ID)?>">
		<?php echo esc_html($args['model']->post_title); ?>
	</a>
</div>
