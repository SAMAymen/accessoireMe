<?php
/**
 * Shortcode attributes
 * @var $atts
 * */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

wp_reset_postdata();

$cars = new WP_Query(
	apply_filters(
		'_stm_listings_build_query_args',
		array(
			'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
			'order'          => 'DESC',
			'orderby'        => 'date',
			'sold_car'       => 'off',
			'nopaging'       => true,
			'posts_per_page' => - 1,
		)
	)
);

$placeholder_path = 'plchldr255.png';
if ( apply_filters( 'stm_is_boats', false ) ) {
	$placeholder_path = 'boats-placeholders/Boat-small.jpg';
} elseif ( apply_filters( 'stm_is_aircrafts', false ) ) {
	$placeholder_path = 'Plane-small.jpg';
} elseif ( apply_filters( 'stm_is_motorcycle', false ) ) {
	$placeholder_path = 'Motor-small.jpg';
}

$map_location_car = array();
$cars_data        = array();
$markers          = array();
$cars_info        = array();

foreach ( apply_filters( 'stm_get_map_listings', array() ) as $k => $val ) {
	if ( isset( $val['use_on_map_page'] ) && true === boolval( $val['use_on_map_page'] ) ) {
		$cars_info[ count( $cars_info ) ] = array(
			'key'  => $val['slug'],
			'icon' => $val['font'],
		);
	}
}

$i = 0;
foreach ( $cars->get_posts() as $k => $val ) {

	if ( ! empty( get_post_meta( $val->ID, 'stm_lat_car_admin', true ) ) && 'publish' === $val->post_status || 'private' === $val->post_status ) {

		$car_meta = get_post_meta( $val->ID, '' );

		$img = "<img src='" . get_stylesheet_directory_uri() . '/assets/images/' . $placeholder_path . "'/>";
		if ( has_post_thumbnail( $val->ID ) ) {
			$img = get_the_post_thumbnail( $val->ID, 'full' );
		}

		$price = ( isset( $car_meta['price'] ) ) ? apply_filters( 'stm_filter_price_view', '', $car_meta['price'][0] ) : 0 . apply_filters( 'stm_get_price_currency', apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' ) );
		if ( isset( $car_meta['sale_price'] ) && ! empty( $car_meta['sale_price'][0] ) ) {
			$price = apply_filters( 'stm_filter_price_view', '', $car_meta['sale_price'][0] );
		}

		$car_price_form       = get_post_meta( $val->ID, 'car_price_form', true );
		$car_price_form_label = get_post_meta( $val->ID, 'car_price_form_label', true );
		if ( ! empty( $car_price_form_label ) ) {
			$price = $car_price_form_label;
		}

		$mileage      = '';
		$engine       = '';
		$transmission = '';

		if ( ! empty( $cars_info[0]['key'] ) ) {
			$term_one = wp_get_post_terms( $val->ID, $cars_info[0]['key'] );
		}

		if ( ! empty( $cars_info[1]['key'] ) ) {
			$term_two = wp_get_post_terms( $val->ID, $cars_info[1]['key'] );
		}

		if ( ! empty( $cars_info[2]['key'] ) ) {
			$term_three = wp_get_post_terms( $val->ID, $cars_info[2]['key'] );
		}

		if ( ! empty( $term_one[0] ) && ! is_wp_error( $term_one ) ) {
			$mileage = $term_one[0]->name;
		} elseif ( ! empty( $cars_info[0] ) && ! empty( $cars_info[0]['key'] ) && ! empty( $car_meta[ $cars_info[0]['key'] ][0] ) ) {
			$mileage = $car_meta[ $cars_info[0]['key'] ][0];
		}

		if ( ! empty( $term_two[0] ) && ! is_wp_error( $term_two ) ) {
			$engine = $term_two[0]->name;
		} elseif ( ! empty( $cars_info[1] ) && ! empty( $cars_info[1]['key'] ) && ! empty( $car_meta[ $cars_info[1]['key'] ][0] ) ) {
			$engine = $car_meta[ $cars_info[1]['key'] ][0];
		}

		if ( ! empty( $term_three[0] ) && ! is_wp_error( $term_three ) ) {
			$transmission = $term_three[0]->name;
		} elseif ( ! empty( $cars_info[2] ) && ! empty( $cars_info[2]['key'] ) && ! empty( $car_meta[ $cars_info[2]['key'] ][0] ) ) {
			$transmission = $car_meta[ $cars_info[2]['key'] ][0];
		}

		$cars_data[ $i ]['id']                = $val->ID;
		$cars_data[ $i ]['link']              = get_the_permalink( $val->ID );
		$cars_data[ $i ]['title']             = $val->post_title;
		$cars_data[ $i ]['image']             = $img;
		$cars_data[ $i ]['price']             = $price;
		$cars_data[ $i ]['year']              = ( isset( $car_meta['ca-year'] ) ) ? $car_meta['ca-year'][0] : '';
		$cars_data[ $i ]['condition']         = ( isset( $car_meta['condition'] ) ) ? mb_strtoupper( str_replace( '-cars', '', $car_meta['condition'][0] ) ) : '';
		$cars_data[ $i ]['mileage']           = $mileage;
		$cars_data[ $i ]['engine']            = $engine;
		$cars_data[ $i ]['transmission']      = $transmission;
		$cars_data[ $i ]['mileage_font']      = ( isset( $cars_info[0] ) && isset( $cars_info[0]['icon'] ) ) ? $cars_info[0]['icon'] : '';
		$cars_data[ $i ]['engine_font']       = ( isset( $cars_info[1] ) && isset( $cars_info[1]['icon'] ) ) ? $cars_info[1]['icon'] : '';
		$cars_data[ $i ]['transmission_font'] = ( isset( $cars_info[2] ) && isset( $cars_info[2]['icon'] ) ) ? $cars_info[2]['icon'] : '';

		if ( ! empty( $car_meta['stm_lat_car_admin'][0] ) && is_numeric( $car_meta['stm_lat_car_admin'][0] ) ) {
			$markers[ $i ]['lat'] = (float) round( $car_meta['stm_lat_car_admin'][0], 7 );
		}

		if ( ! empty( $car_meta['stm_lng_car_admin'][0] ) && is_numeric( $car_meta['stm_lng_car_admin'][0] ) ) {
			$markers[ $i ]['lng'] = (float) round( $car_meta['stm_lng_car_admin'][0], 7 );
		}

		$markers[ $i ]['location'] = ( ! empty( $car_meta['stm_car_location'] ) ) ? $car_meta['stm_car_location'][0] : 'no location';

		if ( ! empty( $markers[ $i ]['lat'] ) && is_numeric( $markers[ $i ]['lat'] ) ) {
			$map_location_car[ (string) round( $markers[ $i ]['lat'], 7 ) ][] = $i;
		}

		$i++;
	}
}


wp_reset_postdata();

$random_id = wp_rand( 1, 99999 );

if ( empty( $lat ) ) {
	$lat = 36.169941;
}
if ( empty( $lng ) ) {
	$lng = -115.139830;
}

$map_style           = array();
$map_style['width']  = ' width: 100vw;';
$map_style['height'] = ' height: 100%;';
$disable_mouse_whell = 'true';
$pin_url             = get_stylesheet_directory_uri() . '/assets/images/classified_inventory_pin.png';
$cluster_url_path    = get_template_directory_uri() . '/assets/images/';

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image   = $image[0];
		$image   = wp_get_attachment_image_src( $image, 'full' );
		$pin_url = $image[0];
	}
}

$filter = apply_filters( 'stm_listings_filter_func', null );
?>
<div class="stm-inventory-map-main-wrap">
	<div class="stm-inventory-map-wrap">
		<div <?php echo ( $map_style ) ? 'style="' . esc_attr( implode( ' ', $map_style ) ) . ' margin: 0 auto; "' : ''; ?>
			id="stm_map-<?php echo esc_attr( $random_id ); ?>"
			class="stm_gmap">
		</div>
		<div class="stm-inventory-map-filter-arrow-wrap">
			<div class="stm-filter-arrow stm-map-filter-open"></div>
			<div class="stm-inventory-map-filter-wrap">
				<div class="stm-filter-scrollbar" style="overflow-y: scroll;">
					<form action="<?php echo esc_url( apply_filters( 'stm_listings_current_url', '' ) ); ?>" method="get" data-trigger="filter-map" data-action="listings-result">
						<div class="filter filter-sidebar ajax-filter">

							<?php
							/**
							 * Hook: stm_listings_filter_before.
							 *
							 * @hooked stm_listings_parent_list_response - 10
							 * @hooked stm_listings_filter_nonce_response - 15
							 */
							do_action( 'stm_listings_filter_before' );
							?>

							<div class="sidebar-entry-header">
								<i class="stm-icon-car_search"></i>
								<span class="h4"><?php esc_html_e( 'Search Options', 'motors-wpbakery-widgets' ); ?></span>
							</div>

							<div class="row row-pad-top-24">

								<?php
								if ( empty( $filter['filters'] ) ) :
									$post_type_name = esc_html__( 'Listings', 'motors-wpbakery-widgets' );
									if ( stm_is_multilisting() ) {
										$ml = new STMMultiListing();
										if ( ! empty( $ml->stm_get_current_listing() ) ) {
											$multitype      = $ml->stm_get_current_listing();
											$post_type_name = $multitype['label'];
										}
									}
									?>
									<div class="col-md-12 col-sm-12">
										<p class="text-muted text-center">
											<?php echo sprintf( esc_html__( 'No categories created for %s', 'motors-wpbakery-widgets' ), $post_type_name ); //phpcs:ignore ?>
										</p>
									</div>
									<?php
								else :
									do_action(
										'stm_listings_load_template',
										'filter/types/keywords',
										array(
											'position' => 'top',
										)
									);

									$parent_list = apply_filters( 'stm_listings_parent_list', false );
									if ( ! is_array( $parent_list ) ) {
										$parent_list = array();
									}
									foreach ( $filter['filters'] as $attribute => $config ) :
										if ( true === apply_filters( 'stm_is_listing_price_field', $attribute ) ) {
											continue;
										}

										if ( ! empty( $filter['options'][ $attribute ] ) ) :
											if ( ! empty( $config['slider'] ) && ! empty( $config['numeric'] ) ) :
												do_action(
													'stm_listings_load_template',
													'filter/types/slider',
													array(
														'taxonomy' => $config,
														'options'  => $filter['options'][ $attribute ],
													)
												);
											else :
												?>
											<div class="col-md-12 col-sm-6 stm-filter_<?php echo esc_attr( $attribute ); ?>">
												<div class="form-group">
													<?php
													do_action(
														'stm_listings_load_template',
														'filter/types/select',
														array(
															'options' => $filter['options'][ $attribute ],
															'name' => $attribute,
															'is_parent' => in_array( $attribute, $parent_list, true ),
															'multiple' => array_key_exists( 'is_multiple_select', $config ) ? $config['is_multiple_select'] : false,
														)
													);
													?>
												</div>
											</div>
												<?php
											endif;
										endif;
									endforeach;
									?>

									<?php
									do_action( 'stm_listings_load_template', 'filter/types/location' );

									do_action(
										'stm_listings_load_template',
										'filter/types/features',
										array(
											'taxonomy' => 'stm_additional_features',
										)
									);

									do_action(
										'stm_listings_load_template',
										'filter/types/keywords',
										array(
											'position' => 'bottom',
										)
									);
								endif;
								?>

							</div>

							<!--View type-->
							<input type="hidden" id="stm_view_type" name="view_type" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'view_type' ) ); ?>"/>
							<!--Filter links-->
							<input type="hidden" id="stm-filter-links-input" name="stm_filter_link" value=""/>
							<!--Popular-->
							<input type="hidden" name="popular" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'popular' ) ); ?>"/>

							<input type="hidden" name="s" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 's' ) ); ?>"/>
							<input type="hidden" name="sort_order" value="<?php echo esc_attr( apply_filters( 'stm_listings_input', null, 'sort_order' ) ); ?>"/>

							<div class="sidebar-action-units">
								<input id="stm-classic-filter-submit" class="hidden" type="submit" value="<?php esc_attr_e( 'Show cars', 'motors-wpbakery-widgets' ); ?>"/>

								<a href="<?php echo esc_url( get_permalink() ); ?>" class="button">
									<span>
										<?php esc_html_e( 'Reset all', 'motors-wpbakery-widgets' ); ?>
									</span>
								</a>
							</div>

							<?php do_action( 'stm_listings_filter_after' ); ?>
						</div>

						<!--Classified price-->
						<?php
						if ( ! empty( $filter['options'] ) && ! empty( $filter['options']['price'] ) && ! empty( $filter['filters']['price']['slider'] ) && ! empty( $filter['filters']['price']['numeric'] ) ) {
							do_action(
								'stm_listings_load_template',
								'filter/types/price',
								array(
									'taxonomy' => 'price',
									'options'  => $filter['options']['price'],
								)
							);
						}

						do_action( 'stm_listings_load_template', 'filter/types/checkboxes', array( 'filter' => $filter ) );

						?>
					</form>
				</div>
				<div class="stm-inventory-map-btn" style="background-color: #fff;">
					<div class="stm-inventory-map-cars-count" data-sprint="
						<?php
						/* translators: number of matched listings */
						echo esc_attr__( '%s matches', 'motors-wpbakery-widgets' );
						?>
						">
						<?php
						echo sprintf(
							/* translators: number of car data */
							esc_html__( '%s matches', 'motors-wpbakery-widgets' ),
							count( $cars_data )
						);
						?>
					</div>
					<input class="button" type="submit" value="<?php esc_html_e( 'Apply', 'motors-wpbakery-widgets' ); ?>" />
				</div>
			</div>
		</div>
	</div>
</div>
	

	<style>
		/* width */
		.stm-filter-scrollbar::-webkit-scrollbar {
			width: 5px;
		}

		/* Track */
		.stm-filter-scrollbar::-webkit-scrollbar-track {
			background: #fff;
		}

		/* Handle */
		.stm-filter-scrollbar::-webkit-scrollbar-thumb {
			background: #707070;
		}

		/* Handle on hover */
		.stm-filter-scrollbar::-webkit-scrollbar-thumb:hover {
			background: #707070;
		}

		input.select2-search__field.focus-visible {
			background-color: transparent!important;
			color: #000!important;
		}
	</style>


	<script>
		jQuery("body").addClass("stm-inventory-map-body");
		jQuery(window).on('load',function () {
			var $ 			= jQuery;
			var windowWidth = jQuery(window).width();
			var height_window = parseInt($(window).height());

			if($("#top-bar").height() != undefined) {
			height_window = height_window - parseInt($("#top-bar").height());
			}

			var mapHeight  = (height_window - parseInt($("#header").height())) - parseInt($("#footer").height());


			if (mapHeight > 440) {
				$(".stm-inventory-map-wrap").height(mapHeight);
				$(".stm-filter-scrollbar").height(mapHeight - $(".stm-inventory-map-btn").outerHeight() + 2);
			}

			google.maps.Map.prototype.panToWithOffset = function (latlng, offsetX, offsetY) {
				var map = this;
				var ov = new google.maps.OverlayView();
				ov.onAdd = function () {
					var proj = this.getProjection();
					var aPoint = proj.fromLatLngToContainerPixel(latlng);
					aPoint.x = aPoint.x + offsetX;
					aPoint.y = aPoint.y + offsetY;
					map.panTo(proj.fromContainerPixelToLatLng(aPoint));
				};
				ov.draw = function () {
				};
				ov.setMap(this);
			};

			var center, map;
			var markers = [];
			var markerCluster;

			function init() {
				var locations = <?php echo wp_json_encode( $markers ); ?>;
				var carData = <?php echo wp_json_encode( $cars_data ); ?>;
				var mapLocationCar = <?php echo wp_json_encode( $map_location_car ); ?>;

				var ordX = -24;
				var ordY = -18;
				var ordY1 = -355;
				var ordY2 = -515;

				if (windowWidth < 1100) {
					ordY1 = -315;
					ordY2 = -475;
				}

				if (windowWidth < 737) {
					ordY = 0;
				}

				if (locations.length > 0) center = new google.maps.LatLng(locations[0]["lat"], locations[0]["lng"]);
				else center = new google.maps.LatLng(<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>);
				var mapOptions = {
					zoom: 3,
					center: center,
					scrollwheel: <?php echo esc_js( $disable_mouse_whell ); ?>,
					mapTypeId: 'roadmap',
					minZoom: 2,
					maxZoom: 20,
				};

				var mapElement = document.getElementById('stm_map-<?php echo esc_js( $random_id ); ?>');
				map = new google.maps.Map(mapElement, mapOptions);

				for (var i = 0; i < locations.length; i++) {

					var latLng = new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]);
					var marker = new google.maps.Marker({
						position: latLng,
						icon: '<?php echo esc_url( $pin_url ); ?>',
						map: map
					});

					var infowindow = new google.maps.InfoWindow({
						pixelOffset: new google.maps.Size(ordX, ordY),
						disableAutoPan: true
					});

					markers.push(marker);

					google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
						return function () {

							if (typeof mapLocationCar[locations[i]["lat"]] != "undefined") {

								var groupClass = (mapLocationCar[locations[i]["lat"]].length <= 1) ? "stm_if_group_" + mapLocationCar[locations[i]["lat"]].length + " stm_if_group_no_scroll" : "stm_if_group_scroll"

								<?php if ( wp_is_mobile() ) : ?>
								groupClass += " stm_is_mobile"
								<?php endif; ?>

								var infoWindowHtml = '<div class="stm_map_info_window_group_wrap ' + groupClass + '">';
								if (mapLocationCar[locations[i]["lat"]].length === 1) {

									infowindow.setOptions({pixelOffset: new google.maps.Size(ordX, ordY)});

									infoWindowHtml += '<a class="stm_iw_link" href="' + carData[i]["link"] + '"> <div class="stm_map_info_window_wrap">' +
										'<div class="stm_iw_condition">' + carData[i]["condition"] + ' ' + carData[i]["year"] + '</div>' +
										'<div class="stm_iw_title">' + carData[i]["title"] + '</div>' +
										'<div class="stm_iw_car_data_wrap">' +
										'<div class="stm_iw_img_wrap">' +
										carData[i]["image"] +
										'</div>' +
										'<div class="stm_iw_car_info">' +
										'<span class="stm_iw_car_opt stm_car_mlg"><i class="' + carData[i]["mileage_font"] + '"></i>' + carData[i]["mileage"] + '</span>' +
										'<span class="stm_iw_car_opt stm_car_engn"><i class="' + carData[i]["engine_font"] + '"></i>' + carData[i]["engine"] + '</span>' +
										'<span class="stm_iw_car_opt stm_car_trnsmsn"><i class="' + carData[i]["transmission_font"] + '"></i>' + carData[i]["transmission"] + '</span>' +
										'</div>' +
										'</div>' +
										'<div class="stm_iw_car_price"><span class="stm_iw_price_trap"></span>' + carData[i]["price"] + '</div>' +
										'</div></a>';
								} else {
									infoWindowHtml += '<div class="stm_if_scroll">'

									if (mapLocationCar[locations[i]["lat"]].length == 2) {
										infowindow.setOptions({pixelOffset: new google.maps.Size(-152, ordY1)})
									} else {
										infowindow.setOptions({pixelOffset: new google.maps.Size(-152, ordY2)})
									}

									for (var w = 0; w < mapLocationCar[locations[i]["lat"]].length; w++) {
										var carPos = mapLocationCar[locations[i]["lat"]][w];
										infoWindowHtml += '<a class="stm_iw_link" href="' + carData[carPos]["link"] + '"> <div class="stm_map_info_window_wrap">' +
											'<div class="stm_iw_condition">' + carData[carPos]["condition"] + ' ' + carData[carPos]["year"] + '</div>' +
											'<div class="stm_iw_title">' + carData[carPos]["title"] + '</div>' +
											'<div class="stm_iw_car_data_wrap">' +
											'<div class="stm_iw_img_wrap">' +
											carData[carPos]["image"] +
											'</div>' +
											'<div class="stm_iw_car_info">' +
											'<span class="stm_iw_car_opt stm_car_mlg"><i class="' + carData[carPos]["mileage_font"] + '"></i>' + carData[carPos]["mileage"] + '</span>' +
											'<span class="stm_iw_car_opt stm_car_engn"><i class="' + carData[carPos]["engine_font"] + '"></i>' + carData[carPos]["engine"] + '</span>' +
											'<span class="stm_iw_car_opt stm_car_trnsmsn"><i class="' + carData[carPos]["transmission_font"] + '"></i>' + carData[carPos]["transmission"] + '</span>' +
											'</div>' +
											'</div>' +
											'<div class="stm_iw_car_price"><span class="stm_iw_price_trap"></span>' + carData[carPos]["price"] + '</div>' +
											'</div></a>';
									}
									infoWindowHtml += '</div>'
								}
								infoWindowHtml += '</div>';

								infowindow.setContent(infoWindowHtml);


								infowindow.open(map, marker);


								if (windowWidth < 737) {
									setTimeout(function () {
										var lat = marker.getPosition().lat();
										var lng = marker.getPosition().lng();
										var centerMarker = new google.maps.LatLng(lat, lng);

										map.panToWithOffset(centerMarker, 0, -150);
									}, 200);
								}
							}
						}

					})(marker, i));

					google.maps.event.addListener(marker, 'click', function (marker, i) {
						google.maps.event.trigger(this, 'mouseover');
					});
				}

				let algorithm = new markerClusterer.GridAlgorithm({
					maxZoom: 9,
					averageCenter: true,
				});

				let renderer = {
					render({ count, position }) {
						return new google.maps.Marker({
							label: {
								text: String(count),
								color: 'white',
								fontSize: '20px',
								fontWeight: 'bold',
							},
							position,
							icon: {
								url: '<?php echo esc_url( $cluster_url_path ); ?>1.png',
								scaledSize: new google.maps.Size(60, 60),
							},
							zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,
						});
					}
				}

				markerCluster = new markerClusterer.MarkerClusterer({
					map: map,
					markers: markers,
					algorithm: algorithm,
					renderer: renderer,
				});

				google.maps.event.addListener(map, 'click', function () {
					if (infowindow) {
						infowindow.close();
					}
				});
			}

			jQuery(window).on('resize', function () {
				if (typeof map != 'undefined' && typeof center != 'undefined') {
					setTimeout(function () {
						map.setCenter(center);
					}, 1000);
				}
			});

			jQuery('#ca_location_listing_filter').on('keydown', function () {
				jQuery("form[data-trigger=filter-map]").on('submit', function (e) {
					e.preventDefault();
				});
				buildUrl();
			});

			var ordX = -24;
			var ordY = -18;
			var ordY1 = -355;
			var ordY2 = -515;

			if (windowWidth < 1100) {
				ordY1 = -315;
				ordY2 = -475;
			}

			if (windowWidth < 737) {
				ordY = 0;
			}

			jQuery(".stm-inventory-map-btn input[type='submit']").on("click", function () {
				$(".stm_gmap").addClass("stm-loading");

				$("form[data-trigger=filter-map]").on('submit', function (e) {
					e.preventDefault();
				});
				var data = [];

				$.each($("form[data-trigger=filter-map]").serializeArray(), function (i, field) {
					if (field.value != '') {
						data.push(field.name + '=' + field.value)
					}
				});

				for (var i = 0; i < markers.length; i++) {
					markers[i].setMap(null);
					markerCluster.removeMarkers(markers);
				}

				markers.length = 0;

				jQuery.ajax({
					url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
					type: "GET",
					data: 'action=stm_ajax_get_cars_for_inventory_map&security=' + stm_security_nonce + '&' + data.join('&'),
					dataType: "json",
					success: function (msg) {
						jQuery(".stm_gmap").removeClass("stm-loading");
						locations = msg['markers'];
						carData = msg['carsData'];
						mapLocationCar = msg['mapLocationCar'];

						var strForReplace = jQuery(".stm-inventory-map-cars-count").attr("data-sprint");
						jQuery(".stm-inventory-map-cars-count").text(strForReplace.replace("%s", carData.length));

						for (var i = 0; i < locations.length; i++) {
							var latLng = new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]);
							var marker = new google.maps.Marker({
								position: latLng,
								icon: '<?php echo esc_url( $pin_url ); ?>',
								map: map
							});

							var infowindow = new google.maps.InfoWindow({
								pixelOffset: new google.maps.Size(ordX, ordY)
							});

							markers.push(marker);

							google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
								return function () {
									if (typeof mapLocationCar[locations[i]["lat"]] != "undefined") {
										var groupClass = (mapLocationCar[locations[i]["lat"]].length <= 1) ? "stm_if_group_" + mapLocationCar[locations[i]["lat"]].length + " stm_if_group_no_scroll" : "stm_if_group_scroll"

										<?php if ( wp_is_mobile() ) : ?>
										groupClass += " stm_is_mobile"
										<?php endif; ?>

										// var infoWindowHtml = '<div class="stm_map_info_window_group_wrap ' + groupClass + '"><div class="stm_if_scroll">';
										var infoWindowHtml = '<div class="stm_map_info_window_group_wrap ' + groupClass + '">';

										if (mapLocationCar[locations[i]["lat"]].length === 1) {

											infowindow.setOptions({pixelOffset: new google.maps.Size(ordX, ordY)})

											infoWindowHtml += '<a class="stm_iw_link" href="' + carData[i]["link"] + '"> <div class="stm_map_info_window_wrap">' +
												'<div class="stm_iw_condition">' + carData[i]["condition"] + ' ' + carData[i]["year"] + '</div>' +
												'<div class="stm_iw_title">' + carData[i]["title"] + '</div>' +
												'<div class="stm_iw_car_data_wrap">' +
												'<div class="stm_iw_img_wrap">' +
												carData[i]["image"] +
												'</div>' +
												'<div class="stm_iw_car_info">' +
												'<span class="stm_iw_car_opt stm_car_mlg"><i class="' + carData[i]["mileage_font"] + '"></i>' + carData[i]["mileage"] + '</span>' +
												'<span class="stm_iw_car_opt stm_car_engn"><i class="' + carData[i]["engine_font"] + '"></i>' + carData[i]["engine"] + '</span>' +
												'<span class="stm_iw_car_opt stm_car_trnsmsn"><i class="' + carData[i]["transmission_font"] + '"></i>' + carData[i]["transmission"] + '</span>' +
												'</div>' +
												'</div>' +
												'<div class="stm_iw_car_price"><span class="stm_iw_price_trap"></span>' + carData[i]["price"] + '</div>' +
												'</div></a>';
										} else {
											infoWindowHtml += '<div class="stm_if_scroll">';

											if (mapLocationCar[locations[i]["lat"]].length == 2) {
												infowindow.setOptions({pixelOffset: new google.maps.Size(-152, ordY1)})
											} else {
												infowindow.setOptions({pixelOffset: new google.maps.Size(-152, ordY2)})
											}

											for (var w = 0; w < mapLocationCar[locations[i]["lat"]].length; w++) {
												var carPos = mapLocationCar[locations[i]["lat"]][w];
												infoWindowHtml += '<a class="stm_iw_link" href="' + carData[carPos]["link"] + '"> <div class="stm_map_info_window_wrap">' +
													'<div class="stm_iw_condition">' + carData[carPos]["condition"] + ' ' + carData[carPos]["year"] + '</div>' +
													'<div class="stm_iw_title">' + carData[carPos]["title"] + '</div>' +
													'<div class="stm_iw_car_data_wrap">' +
													'<div class="stm_iw_img_wrap">' +
													carData[carPos]["image"] +
													'</div>' +
													'<div class="stm_iw_car_info">' +
													'<span class="stm_iw_car_opt stm_car_mlg"><i class="' + carData[carPos]["mileage_font"] + '"></i>' + carData[carPos]["mileage"] + '</span>' +
													'<span class="stm_iw_car_opt stm_car_engn"><i class="' + carData[carPos]["engine_font"] + '"></i>' + carData[carPos]["engine"] + '</span>' +
													'<span class="stm_iw_car_opt stm_car_trnsmsn"><i class="' + carData[carPos]["transmission_font"] + '"></i>' + carData[carPos]["transmission"] + '</span>' +
													'</div>' +
													'</div>' +
													'<div class="stm_iw_car_price"><span class="stm_iw_price_trap"></span>' + carData[carPos]["price"] + '</div>' +
													'</div></a>';
											}
											infoWindowHtml += '</div>';
										}
										infoWindowHtml += '</div>';

										infowindow.setContent(infoWindowHtml);

										infowindow.open(map, marker);
									}
								}

							})(marker, i));
						}

						let algorithm = new markerClusterer.GridAlgorithm({
							maxZoom: 9,
							averageCenter: true,
						});

						let renderer = {
							render({ count, position }) {
								return new google.maps.Marker({
									label: {
										text: String(count),
										color: 'white',
										fontSize: '20px',
										fontWeight: 'bold',
									},
									position,
									icon: {
										url: '<?php echo esc_url( $cluster_url_path ); ?>1.png',
										scaledSize: new google.maps.Size(60, 60),
									},
									zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,
								});
							}
						}

						markerCluster = new markerClusterer.MarkerClusterer({
							map: map,
							markers: markers,
							algorithm: algorithm,
							renderer: renderer,
						});

						google.maps.event.addListener(map, 'click', function () {
							if (infowindow) {
								infowindow.close();
							}
						});
					}
				});
			});

			jQuery(".stm-filter-arrow").on("click", function () {
				setTimeout(function () {
					google.maps.event.trigger(map, "resize");
				}, 400);
				if (jQuery(this).hasClass("stm-map-filter-open")) {
					jQuery(this).removeClass("stm-map-filter-open").addClass("stm-map-filter-close");
				} else {
					jQuery(this).removeClass("stm-map-filter-close").addClass("stm-map-filter-open");
				}
			});

			// initialize map
			init();
		});


		function buildUrl() {
			var data = [],
				url = jQuery("form[data-trigger=filter-map]").attr('action'),
				sign = url.indexOf('?') < 0 ? '?' : '&';

			jQuery.each(jQuery("form[data-trigger=filter-map]").serializeArray(), function (i, field) {
				if (field.value != '') {
					data.push(field.name + '=' + field.value)
				}
			});

			url = url + sign + data.join('&');
			window.history.pushState('', '', decodeURI(url));
		}
	</script>
<?php
if ( ! function_exists( 'inventory_on_map_scripts' ) ) {
	function inventory_on_map_scripts() {
		?>
		<script>
			jQuery(document).ready(function ($) {
				$("form[data-trigger=filter-map]").on('submit', function (e) {
					e.preventDefault();
				});
				$(document).on('slidestop', '.stm-filter-listing-directory-price .stm-price-range', function (event, ui) {
					$("form[data-trigger=filter-map]").on('submit', function (e) {
						e.preventDefault();
					});
					buildUrl();
				});

				$(document).on('click', '.stm-ajax-checkbox-button .button, .stm-ajax-checkbox-instant .stm-option-label input, .stm-ajax-checkbox-button .stm-option-label input', function (e) {
					$("form[data-trigger=filter-map]").on('submit', function (e) {
						e.preventDefault();
					});
					buildUrl();
				});

				$(document).on('change', '.ajax-filter select, .stm-sort-by-options select, .stm-slider-filter-type-unit', function (event) {
					$("form[data-trigger=filter-map]").on('submit', function (e) {
						e.preventDefault();
					});
					buildUrl();
				});

				$(document).on('slidestop', '.ajax-filter .stm-filter-type-slider', function (event, ui) {
					$("form[data-trigger=filter-map]").on('submit', function (e) {
						e.preventDefault();
					});
					buildUrl();
				});
			});
		</script>
		<?php
	}

	add_action( 'wp_footer', 'inventory_on_map_scripts' );
}

?>
