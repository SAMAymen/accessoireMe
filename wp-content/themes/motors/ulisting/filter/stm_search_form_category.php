<?php
/**
 * Filter search form category
 *
 * Template can be modified by copying it to yourtheme/ulisting/filter/stm_search_form_category.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 1.0.6
 */
//phpcs:disable
ulisting_field_components_enqueue_scripts_styles();
wp_enqueue_script( 'stm-search-form-category', ULISTING_URL . '/assets/js/frontend/stm-search-form-category.js', array( 'vue' ), ULISTING_VERSION, true );

use uListing\Classes\StmListingType;
use uListing\Classes\StmListingTemplate;

$id                    = wp_rand( 1, 99999 ) . time();
$data                  = array();
$listing_type_data     = array();
$listing_type_form     = array();
$category_listing_type = array();

foreach ( $categories as $category ) {
	$data['categories'][ $category->term_id ] = array(
		'id'            => $category->term_id,
		'slug'          => $category->slug,
		'url'           => array(),
		'name'          => $category->name,
		'types'         => array(),
		'listing_types' => $category->getListingTypes(),
		'type_selected' => 0,
	);

	if ( isset( $data['categories'][ $category->term_id ]['listing_types'] ) && $data['categories'][ $category->term_id ]['listing_types'] ) {
		$category_listing_type = array_merge( $category_listing_type, $data['categories'][ $category->term_id ]['listing_types'] );
	}
}
?>

<?php foreach ( $listingsTypes as $listingsType ) : ?>
	<?php
	if ( ! in_array( $listingsType->ID, $category_listing_type, true ) ) {
		continue;
	}
	$prefix                                 = 'attribute.listing_type_' . $listingsType->ID;
	$listing_type_form[ $listingsType->ID ] = '';

	$data['listung_type'][ $listingsType->ID ] = array(
		'id'   => $listingsType->ID,
		'name' => $listingsType->post_title,
		'url'  => $listingsType->getPageUrl(),
	);

	$search_fields = $listingsType->getSearchFields( StmListingType::SEARCH_FORM_CATEGORY )
	if ( $search_fields ) {
		foreach ( $search_fields as $field ) {
			$field_type          = key( $field );
			$field               = current( $field );
			$field['field_type'] = $field_type;

			if ( ! isset( $field['attribute_name'] ) ) {
				$field['attribute_name'] = '';
			}

			$data['listung_type'][ $listingsType->ID ]['fields_types'][ $field['attribute_name'] ] = $field;

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_SEARCH and ! empty( $field['attribute_name'] ) ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ] = '';
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-on_input='searchAutoComplete(event)' data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 '>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'placeholder'     => "{$field['placeholder']}",
						'callback_change' => 'change',
					)
				) . '</div>';
			}

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_LOCATION ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ] = array(
					'address' => '',
					'lat'     => 0,
					'lng'     => 0,
				);
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 '>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'placeholder'     => "{$field['placeholder']}",
						'callback_change' => 'change',
					)
				) . '</div>';
			}

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_PROXIMITY ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ] = (int) $field['default'];
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 '>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'callback_change' => 'change',
						'units'           => "{$field['units']}",
						'min'             => "{$field['min']}",
						'max'             => "{$field['max']}",
					)
				) . '</div>';
			}

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_RANGE and ! empty( $field['attribute_name'] ) ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ] = array(
					$field['min'],
					$field['max'],
				);
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 '>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'callback_change' => 'change',
						'suffix'          => "{$field['suffix']}",
						'prefix'          => "{$field['prefix']}",
						'min'             => "{$field['min']}",
						'max'             => "{$field['max']}",
					)
				) . '</div>';
			}

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_DROPDOWN and ! empty( $field['attribute_name'] ) && isset( $field['items'] ) ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ]            = '';
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] . '_items' ] = $field['items'];
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 '>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'order_by'        => ( isset( $field['order_by'] ) ) ? "{$field['order_by']}" : '',
						'order'           => ( isset( $field['order'] ) ) ? "{$field['order']}" : '',
						'placeholder'     => ( isset( $field['placeholder'] ) ) ? "{$field['placeholder']}" : '',
						'callback_change' => 'change',
						'items'           => $prefix . ".{$field['attribute_name']}_items",
						'hide_empty'      => ( isset( $field['hide_empty'] ) ) ? "{$field['hide_empty']}" : '',
						'attribute_name'  => $field['attribute_name'],
					)
				) . '</div>';
			}

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_DATE and ! empty( $field['attribute_name'] ) ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ] = '';
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 '>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'callback_change' => 'change',
						'name'            => ( isset( $field['attribute_name'] ) ) ? "{$field['attribute_name']}" : '',
						'date_type'       => ( isset( $field['date_type'] ) ) ? "{$field['date_type']}" : '',
						'placeholder'     => ( isset( $field['placeholder'] ) ) ? "{$field['placeholder']}" : '',
					)
				) . '</div>';
			}

			if ( $field['field_type'] == StmListingType::SEARCH_FORM_TYPE_CHECKBOX and ! empty( $field['attribute_name'] ) ) {
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] ]            = array();
				$listing_type_data[ 'listing_type_' . $listingsType->ID ][ $field['attribute_name'] . '_items' ] = isset( $field['items'] ) ? $field['items'] : array();
				$listing_type_form[ $listingsType->ID ] .= "<div data-v-if='category_selected.type_selected == " . $listingsType->ID . "' class='col-12 col-md-6 col-lg-3 " . $field['attribute_name'] . '-checkbox-wrap fullwidth' . "'>" . StmListingTemplate::load_template(
					'components/fields/' . $field['type'],
					array(
						'model'           => $prefix . ".{$field['attribute_name']}",
						'order_by'        => ( isset( $field['order_by'] ) ) ? "{$field['order_by']}" : '',
						'order'           => ( isset( $field['order'] ) ) ? "{$field['order']}" : '',
						'callback_change' => 'change',
						'items'           => $prefix . ".{$field['attribute_name']}_items",
						'hide_empty'      => ( isset( $field['hide_empty'] ) ) ? "{$field['hide_empty']}" : '',
					)
				) . '</div>';
			}
		}
	}
	?>
<?php endforeach; ?>
<div id="stm_search_form_category_<?php echo esc_attr( $id ); ?>" class="stm-c-f-search-form-wrap">
	<stm-search-form-category key="<?php echo esc_attr( $id ); ?>"
							  :stm_search_form_category_data="stm_search_form_category_data"
							  :stm_search_form_category_texts="stm_search_form_category_text" inline-template>
		<div>
			<ul class="nav nav-tabs">
				<?php
				$i = 0;
				foreach ( $categories as $category ) :
					if ( $i == 0 ) {
										$data['active_tab'] = $category->term_id;
					}
					?>
					<li class="nav-item">
						<?php $icon = ( apply_filters( 'stm_is_listing_five', false ) ) ? stm_c_f_get_category_icon( $category->term_id ) : stm_c_six_get_category_icon( $category->term_id ); ?>
						<a class="nav-link stm-cursor-pointer heading-font"
						   data-v-bind_class="{ active: active_tab == <?php echo esc_attr( $category->term_id ); ?>}"
						   data-v-on_click="set_active_tab(<?php echo esc_attr( $category->term_id ); ?>)"><i
									class="<?php echo esc_attr( $icon ); ?>"></i> <?php echo esc_html( $category->name ); ?>
						</a>
					</li>
					<?php
					$i ++;
endforeach;
				?>
			</ul>
			<div class="tab-content">
				<?php foreach ( $categories as $category ) : ?>
					<div data-v-if="active_tab == <?php echo esc_attr( $category->term_id ); ?>"
						 class="tab-pane fade show active">
						<div class="row">
							<?php foreach ( $listing_type_form as $key => $form ) : ?>
								<?php echo html_entity_decode( $form ); ?>
							<?php endforeach; ?>
						</div>
						<div class="btn-search">
							<a data-v-bind_href="category_selected.url"
							   class="btn btn-primary w-full"><?php _e( 'Search', 'motors' ); ?></a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</stm-search-form-category>
</div>

<?php
$data['data'] = $listing_type_data;

wp_add_inline_script( 'stm-search-form-category', " new VueW3CValid({ el: '#stm_search_form_category_" . $id . "' }); new Vue({el:'#stm_search_form_category_" . $id . "',data:{stm_search_form_category_data:json_parse('" . ulisting_convert_content( json_encode( $data ) ) . "'), stm_search_form_category_text: " . json_encode( apply_filters( 'ulisting_search_form_category_text', array() ) ) . '}}) ' );
//phpcs:enable