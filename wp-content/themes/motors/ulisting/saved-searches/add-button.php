<?php
/**
 * Listing save-search
 *
 * Template can be modified by copying it to yourtheme/ulisting/listing-list/save-search.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */
if(ulisting_wishlist_active()){
	$save_search_title = __("Save Search", "motors");
	$element['params']['class'] .= " ulisting-save-search";
	if(is_user_logged_in()){
		$listing_type_id = (isset($args['listingType']) AND isset($args['listingType']->ID)) ? $args['listingType']->ID : null;
		$save_search_panel = '<div onclick="save_search(this, '.get_current_user_id().', '.$listing_type_id.')"  '.\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element).' >
							<span class="inner">[save_search_panel_inner]</span>
						  </div>';

		if(isset($element['params']['template']))
			echo \uListing\Classes\UlistingSearch::render_save_search($element['params']['template'] ,$save_search_panel, $save_search_title);
	}else{
		$save_search_panel = '<a target="_blank" '.\uListing\Classes\Builder\UListingBuilder::generation_html_attribute($element).' href="'.\uListing\Classes\StmUser::getProfileUrl().'">
							<span class="inner">[save_search_panel_inner]</span>
						  </a>';
		if(isset($element['params']['template']))
			echo \uListing\Classes\UlistingSearch::render_save_search($element['params']['template'] ,$save_search_panel, $save_search_title);
	}
}
?>




