<?php
/**
 * Filter search form advanced
 *
 * Template can be modified by copying it to yourtheme/ulisting/filter/stm_search_form_advanced.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0.1
 */
use uListing\Classes\StmListingFilter;

$data = StmListingFilter::build_data($listingType, $search_fields, [], true);
?>

<stm-search-form-advanced
	class="ulisting-form"
	inline-template
	data-v-on_url-update="set_url"
	data-v-on_exists-filter="exists_filter"
	data-v-bind_show="filter.show"
	data-v-bind_url="url"
	data-v-bind_listing_type_id="listing_type_id"
	data-v-bind_search_form_type="search_form_type"
	data-v-bind_data="filter.field_data"
	data-v-bind_field_type="filter.field_type"
	data-v-bind_search_fields="filter.search_fields">
		<div>
			<div data-v-if="show">
				<?php foreach ($data['content'] as $item):?>
					<?php echo html_entity_decode($item)?>
				<?php endforeach;?>
			</div>
			<div class="ulisting-form-gruop">
				<a class="btn btn-default" href="<?php echo esc_url($listingType->getPageUrl())?>"><?php esc_html_e('Clear all', "motors")?></a>
			</div>
		</div>
</stm-search-form-advanced>




