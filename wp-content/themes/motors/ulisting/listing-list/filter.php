<?php
/**
 * Filter
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing/filter.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0.2
 */
use uListing\Classes\StmListingFilter;

$filter_field = "";
$search_fields = $args['listingType']->getSearchFields(\uListing\Classes\StmListingType::SEARCH_FORM_ADVANCED);
$data = StmListingFilter::build_data($args['listingType'], $search_fields, [], true);
$filter_panel = '<div '.\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element).'>[filter_panel_inner]</div>';
$filter = '
<div class="stm-c-f-filter-wrap">
<div class="head-wrap">
	<h2>' . esc_html__("Search options", "motors") . '</h2>
	<div class="ulisting-form-gruop">
		<a class="btn btn-default" href="' . esc_url($args['listingType']->getPageUrl()) . '">' . esc_html__('Reset all', "motors") .' </a>
	</div>
</div>
<div data-v-if="!filter.show" class="text-center">
	<div class="ulisting-preloader-ring"><div></div><div></div><div></div><div></div></div>
	<br />
	<br />
</div>
<stm-search-form-advanced
				class="ulisting-form"
				inline-template
				data-v-on_url-update="set_url"
				data-v-on_location-update="location_update"
				data-v-on_exists-filter="exists_filter"
				data-v-bind_show="filter.show"
				data-v-bind_url="url"
				data-v-bind_listing_type_id="listing_type_id"
				data-v-bind_search_form_type="search_form_type"
				data-v-bind_data="filter.field_data"
				data-v-bind_field_type="filter.field_type"
				data-v-bind_field_show="field_show"
				data-v-bind_search_fields="filter.search_fields">
					<div>
						<div data-v-if="show">
[filter_field]
						</div>
					</div>
			</stm-search-form-advanced>';
if(isset($element['params']['template']))
	echo \uListing\Classes\StmInventoryLayout::render_filter($element['params']['template'], $filter_panel, $filter, $data['content']);
?>

</div>