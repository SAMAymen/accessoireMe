
<?php
/**
 * Listing list pagination
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing-list/pagination.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.5.5
 */
wp_enqueue_script('stm-listing-pagination', get_template_directory_uri() . '/assets/js/ulisting/frontend/stm-listing-pagination.js', array('vue'), ULISTING_VERSION, true);
use uListing\Classes\StmListingType;
$current_page = ulisting_listing_input('current_page');
$paged        = ( $current_page ) ? $current_page : 1;
$data         = array(
		        'paged'            => $paged,
		        'total_pages'      => $args['total_pages'],
		        'listing_type_id'  => $args['listingType']->ID,
		        'search_form_type' => StmListingType::SEARCH_FORM_ADVANCED,
			  );
wp_add_inline_script('stm-search-form-advanced', " var stm_listing_pagination_data =  json_parse('".ulisting_convert_content(json_encode($data))."') ", 'before');
$element['params']['class'] .= " stm-listing-pagination heading-font";


$pagination = '<stm-listing-pagination
					prev="'. __('Prev', "motors") .'"
					next="'. __('Next', "motors") .'"
					:url="url"
					:page="paginate.page"
					v-on:url-update="set_url"
					v-on:pagination-update="pagination_update"
					:page_count="paginate.pageCount">
				</stm-listing-pagination>';

$pagination_panel = '<div '.\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element).' >[pagination_panel_inner]</div>';

if(isset($element['params']['template']))
	echo \uListing\Classes\StmInventoryLayout::render_pagination($element['params']['template'], $pagination_panel, $pagination);
?>




