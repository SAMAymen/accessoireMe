<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Loop thumbnail box
 *
 * Template can be modified by copying it to yourtheme/ulisting/loop/thumbnail-box.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.5.6
 */

$top = "";
$bottom = "";
$template = '';
$thumbnail_panel = "";

$element['params']['class'] .= " ulisting-thumbnail-box";
$element['params']['data-id'] = $args['model']->ID;
$size = isset($element['params']['image_size']) && strpos($element['params']['image_size'], 'x') !== false ? $element['params']['image_size'] : '500x500';
$feature_image = $args['model']->getfeatureImage(explode('x', $size));

if(!empty($args['item_position']) && strpos($args['item_position'], 'stm-featured') !== false) {
	$size = ($args['item_position'] == 'stm-featured-0') ? '398-696' : '330-205';
	$feat_image = wp_get_attachment_image_src(get_post_thumbnail_id($args['model']->ID), $size);
	$feature_image = (isset($feat_image[0])) ? $feat_image[0] : $feature_image;
}

$feature_background_image = ($feature_image ) ? $feature_image : ulisting_get_placeholder_image_url();

$style = " background-image: url('".$feature_background_image."');
	       background-repeat: no-repeat;
	       background-position: center center;
	       background-size: cover;";

if(isset($element['elements_top'])) {
	foreach ($element['elements_top'] as  $element_top) {

		if($element_top['type'] == 'basic')
			$template = 'builder/'.$element_top['type'].'/'.$element_top['params']['type'];

		if($element_top['type'] == 'attribute' || $element_top['type'] == 'element')
			$template = \uListing\Classes\StmListingItemCardLayout::get_element_template($element_top);

		if(isset($element_top['params']['template_path'])){
			$template = $element_top['params']['template_path'];
		}

		$top.= \uListing\Classes\StmListingTemplate::load(
			$template,
			[
				"args" => $args,
				"element" => $element_top,
			],
			"ulisting/",
			(isset($element_top['params']['default_path'])) ? ABSPATH.$element_top['params']['default_path'] : ""
		);
	}
}

if(isset($element['elements_bottom'])) {
	foreach ($element['elements_bottom'] as  $element_bottom) {

		if($element_bottom['type'] == 'basic')
			$template = 'builder/'.$element_bottom['type'].'/'.$element_bottom['params']['type'];

		if($element_bottom['type'] == 'attribute' || $element_bottom['type'] == 'element')
			$template = \uListing\Classes\StmListingItemCardLayout::get_element_template($element_bottom);

		if(isset($element_bottom['params']['template_path'])){
			$template = $element_bottom['params']['template_path'];
		}

		$bottom.= \uListing\Classes\StmListingTemplate::load(
			$template,
			[
				"args" => $args,
				"element" => $element_bottom,
			],
			"ulisting/",
			(isset($element_bottom['params']['default_path'])) ?  ABSPATH.$element_bottom['params']['default_path']  : ""
		);
	}
}

$topBadge = '';
if(!empty($args['model']->featured)) {
	$topBadge = '<div class="stm-top-badge">' . esc_html__('Featured', 'motors') . '</div>';
}

$thumbnail_panel = '<a href="'.get_permalink( $args['model']->ID ).'" class="ulisting-thumbnail-box-link"></a><div style="'.$style.'" '.\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element).'>[thumbnail_panel_inner]' . $topBadge . '</div>';

if(isset($element['params']['template']))
 	echo \uListing\Classes\StmListingItemCardLayout::render_thumbnail_box($element['params']['template'], $thumbnail_panel ,$top, $bottom, $args['model']->ID);
?>



