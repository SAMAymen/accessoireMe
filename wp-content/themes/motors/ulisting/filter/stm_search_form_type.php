<?php
/**
 * Filter search form type
 *
 * Template can be modified by copying it to yourtheme/ulisting/filter/stm_search_form_type.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.3.0
 */
use uListing\Classes\StmListingType;
use uListing\Classes\StmListingTemplate;

ulisting_field_components_enqueue_scripts_styles();
wp_enqueue_script('stm-search-form-type', ULISTING_URL . '/assets/js/frontend/stm-search-form-type.js', array('vue'), ULISTING_VERSION, true);

$id = wp_rand( 1, 99999 ).time();
$listing_type_data = [];
$listing_type_component = [];

?>

<?php foreach ($listingsTypes as $listingsType): $prefix = 'attribute.listing_type_'.$listingsType->ID;?>
<?php
	if(!isset($listing_type_component[$listingsType->ID]))
		$listing_type_component[$listingsType->ID] = "";

	$data['listung_types'][$listingsType->ID] = array(
		"id"  => $listingsType->ID,
		"url" => $listingsType->getPageUrl()
	);

	if($search_fields = $listingsType->getSearchFields(StmListingType::SEARCH_FORM_TYPE)) {

		foreach ($search_fields as $field) {
			$field_type          = key($field);
			$field               = current($field);
			$field['field_type'] = $field_type;

			if(!isset($data['listung_types'][$listingsType->ID]['fields_types'] ))
				$data['listung_types'][$listingsType->ID]['fields_types'] = [];

			if(isset($field['attribute_name']))
				$data['listung_types'][$listingsType->ID]['fields_types'][$field['attribute_name']] = $field;

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_SEARCH){
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = '';
				$listing_type_component[$listingsType->ID] .="<div class='stm-col-12 stm-col-md-3'>".StmListingTemplate::load_template( 'components/fields/'.$field['type'],
					array(
						"model"           => $prefix.".{$field['attribute_name']}",
						"placeholder"     => (isset($field['placeholder'])) ? "{$field['placeholder']}" : "",
						"callback_change" => "change",
					))."</div>";
			}

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_LOCATION){
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = array(
																										 'address' => "",
																										 'lat' =>  0,
																										 'lng' => 0
																										);
				$listing_type_component[$listingsType->ID] .="<div class='stm-col-12 stm-col-md-3'>".StmListingTemplate::load_template( 'components/fields/'.$field['type'],
						array(
							"model"           => $prefix.".{$field['attribute_name']}",
							"placeholder"     => (isset($field['placeholder'])) ? "{$field['placeholder']}" : "",
							"callback_change" => "change",
						))."</div>";
			}

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_PROXIMITY){
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = (int) $field['default'];
				$listing_type_component[$listingsType->ID] .="<div class='stm-col-12 stm-col-md-3'>".StmListingTemplate::load_template( 'components/fields/'.$field['type'],
						array(
							"model" => $prefix.".{$field['attribute_name']}",
							"callback_change" => "change",
							"units" => (isset($field['units'])) ? "{$field['units']}" : "",
							"min" => (isset($field['min'])) ? "{$field['min']}" : "",
							"max" => (isset($field['max'])) ? "{$field['max']}" : "",
						))."</div>";
			}

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_RANGE AND isset($field['attribute_name'])){
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = array( $field['min'], $field['max'] );
				$listing_type_component[$listingsType->ID] .="<div class='stm-col-12 stm-col-md-3'>".StmListingTemplate::load_template( 'components/fields/'.$field['type'],
						array(
							"model"           => $prefix.".{$field['attribute_name']}",
							"callback_change" => "change",
							"suffix" => (isset($field['suffix'])) ? "{$field['suffix']}" : "",
							"prefix" => (isset($field['prefix'])) ? "{$field['prefix']}" : "",
							"min" => (isset($field['min'])) ? "{$field['min']}" : "",
							"max" => (isset($field['max'])) ? "{$field['max']}" : "",
						))."</div>";
			}

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_DROPDOWN) {
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = '';
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name']."_items" ] = isset($field['items']) ? $field['items'] : [];
				$listing_type_component[ $listingsType->ID ].= "<div class='stm-col-12 stm-col-md-3'>" . StmListingTemplate::load_template( 'components/fields/' . $field['type'], array(
							"model"           => $prefix . ".{$field['attribute_name']}",
							"order_by"        => (isset($field['order_by'])) ? "{$field['order_by']}" : "",
							"order"           => (isset($field['order'])) ? "{$field['order']}" : "",
							"placeholder"     => (isset($field['placeholder'])) ? "{$field['placeholder']}" : "" ,
							"callback_change" => "change",
							"items"           => $prefix . ".{$field['attribute_name']}_items",
							"hide_empty"      => (isset($field['hide_empty'])) ? "{$field['hide_empty']}" : "",
							"attribute_name"  => $field['attribute_name']
						) ) . "</div>";
			}

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_DATE){
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = '';
				$listing_type_component[ $listingsType->ID ].= "<div class='stm-col-12 stm-col-md-3'>" . StmListingTemplate::load_template( 'components/fields/' . $field['type'], array(
						"model"           => $prefix . ".{$field['attribute_name']}",
						"callback_change" => "change",
						"name"            => (isset($field['attribute_name'])) ? "{$field['attribute_name']}" : "",
						"date_type"       => (isset($field['date_type'])) ? "{$field['date_type']}" : "",
						"placeholder"     => (isset($field['placeholder'])) ? "{$field['placeholder']}" : "",
					)) . "</div>";
			}

			if($field['field_type'] == StmListingType::SEARCH_FORM_TYPE_CHECKBOX){
				$listing_type_data['listing_type_'.$listingsType->ID][$field['attribute_name']] = array();
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name']."_items" ] = $field['items'];
				$listing_type_component[ $listingsType->ID ].= "<div class='stm-col-12 stm-col-md-3'>" . StmListingTemplate::load_template( 'components/fields/' . $field['type'], array(
						"model"           => $prefix . ".{$field['attribute_name']}",
						"order_by"        => (isset($field['order_by'])) ? "{$field['order_by']}" : "",
						"order"           => (isset($field['order'])) ? "{$field['order']}" : "",
						"callback_change" => "change",
						"items"           => $prefix . ".{$field['attribute_name']}_items",
						"hide_empty"      => (isset($field['hide_empty'])) ? "{$field['hide_empty']}" : "",
					) ) . "</div>";

			}
		}
	}

?>
<?php endforeach;?>

<div id="search_form_type_<?php echo esc_attr($id)?>">
	<stm-search-form-type key="<?php echo esc_attr($id)?>" :stm_search_form_type_data="stm_search_form_type_data" inline-template>
		<div>
			<ul class="nav nav-tabs" role="tablist">
				<?php $i = 0; foreach ($listingsTypes as $listingsType): if($i == 0) $data['active_tab'] = $listingsType->ID?>
					<li class="nav-item">
						<a class="nav-link stm-cursor-pointer" data-v-on_click="set_active_tab(<?php echo esc_attr($listingsType->ID)?>)"  data-v-bind_class="{ active: active_tab == <?php echo esc_attr($listingsType->ID)?>}"  ><?php echo esc_html($listingsType->post_title)?></a>
					</li>
					<?php $i++; endforeach;?>
			</ul>
			<div class="tab-content" id="stm_search_form_type_tab_content">
				<?php $i = 0; foreach ($listingsTypes as $listingsType):?>
					<div data-v-if="active_tab == <?php echo esc_html($listingsType->ID)?>" class="tab-pane fade show active p-t-15">
						<div class="stm-row">
							<?php if(isset($listing_type_component[$listingsType->ID])):?>
								<?php echo html_entity_decode($listing_type_component[$listingsType->ID]);?>
							<?php endif;?>
							<div class="stm-col-12 stm-col-md-12 p-t-15">
								<a  data-v-bind_href="listung_types[<?php echo esc_attr($listingsType->ID);?>].url_params" class="btn btn-primary w-full"><?php _e("Search", "motors")?></a>
							</div>
						</div>
					</div>
				<?php $i++; endforeach;?>
			</div>
		</div>
	</stm-search-form-type>
</div>

<?php
	$data['data'] = $listing_type_data;
	wp_add_inline_script('stm-search-form-type', " new VueW3CValid({ el: '#search_form_type_".$id."' }); new Vue({el:'#search_form_type_".$id."',data:{stm_search_form_type_data:json_parse('". ulisting_convert_content(json_encode($data)) ."')}}) ");
?>



