<?php
/**
 * Listing list order
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing-list/listing-order.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0.3
 */

$element['params']['class'] = $element['params']['class'] . ' stm-listing-sort';

wp_enqueue_script('stm-listing-order', ULISTING_URL . '/assets/js/frontend/stm-listing-order.js', array('vue'), ULISTING_VERSION, true);
$list = '<ul data-v-if="view_type==\'list\'" class="list-inline sort-list">
			<li class="list-inline-item" data-v-for=" (item, key)  in listing_order">
				<span style="cursor: pointer" data-v-on_click="selected=item; change()">{{item.label}}</span>
			</li>
		 </ul>';

$select = '<ulisting-select2 v-if="selected" data-v-bind_options="listing_order" v-model="selected" text="label" theme="bootstrap4"></ulisting-select2>';

$sort_panel = '<div '.\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element).' >
					<span>' . esc_html__('SORT BY:', 'motors') . '</span>
					<stm-listing-order inline-template
										v-on:set-order="set_order"
								       :listing_order="listing_order_data.listing_order"
								       :order_by_default="listing_order_data.order_by_default"
								       :view_type="listing_order_data.view_type">
										<div> [sort_pane_inner] </div>
					</stm-listing-order>
				</div>';

echo \uListing\Classes\StmInventoryLayout::render_sort($element['params']['template'], $sort_panel, $list, $select)
?>
