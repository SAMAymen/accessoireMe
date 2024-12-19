<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$search_radius = ( ! empty( $search_radius ) ) ? $search_radius : 1000;
$unique_id     = wp_rand( 1, 99999 );
$staring_zoom  = ( ! empty( $map_zoom ) ) ? $map_zoom : 5;

if ( empty( $lat ) ) {
	$lat = 36.169941;
}

if ( empty( $lng ) ) {
	$lng = -115.139830;
}

$map_style           = array();
$map_style['width']  = ' width: 100vw;';
$map_style['height'] = ' height: 100%;';
$mouse_whell         = ( ! empty( $map_scrollwheel ) ) ? $map_scrollwheel : 'false';

$stm_map_height = ( ! empty( $map_height ) ) ? $map_height : 580;
if ( wp_is_mobile() ) {
	$stm_map_height = 500;
}

$pin_url          = ( ! empty( $marker ) ) ? wp_get_attachment_image_src( $marker, 'full' )[0] : get_stylesheet_directory_uri() . '/assets/images/marker-listing-two.png';
$cluster_url_path = ( ! empty( $cluster ) ) ? wp_get_attachment_image_src( $cluster, 'full' )[0] : get_stylesheet_directory_uri() . '/assets/images/cluster-listing-two.png';
do_action( 'stm_google_places_script', 'enqueue' );
?>

<div class="stm-inventory-map-wrap" style="height: <?php echo esc_attr( $stm_map_height ); ?>px;">
	<div<?php echo( ( $map_style ) ? ' style="' . esc_attr( implode( ' ', $map_style ) ) . ' margin: 0 auto; "' : '' ); ?>
			id="stm_map-<?php echo esc_attr( $unique_id ); ?>" class="stm_gmap"></div>
</div>


<script>
	jQuery("body").addClass("stm-inventory-map-body");
	jQuery(document).on('ready', function ($) {
		var center, map;
		var markers = [];
		var markerCluster;

		function init() {
			center = new google.maps.LatLng(<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>);

			var mapOptions = {
				zoom: <?php echo esc_js( $staring_zoom ); ?>,
				center: center,
				scrollwheel: <?php echo esc_js( $mouse_whell ); ?>,
				mapTypeId: 'roadmap',
				minZoom: 2,
				maxZoom: 20,
			};

			var mapElement = document.getElementById('stm_map-<?php echo esc_js( $unique_id ); ?>');
			map = new google.maps.Map(mapElement, mapOptions);

			jQuery(".stm_gmap").addClass("stm-loading");

			markers.length = 0;

			var data = 'ca_location=<?php echo esc_attr( $address ); ?>&stm_lat=<?php echo esc_url( $lat ); ?>&stm_lng=<?php echo esc_url( $lng ); ?>&max_search_radius=<?php echo esc_url( $search_radius ); ?>';

			jQuery.ajax({
				url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
				type: "GET",
				data: 'action=stm_ajax_get_cars_for_inventory_map&security=' + stm_security_nonce + '&' + data,
				dataType: "json",
				success: function (msg) {
					jQuery(".stm_gmap").removeClass("stm-loading");

					locations = msg['markers'];
					carData = msg['carsData'];
					mapLocationCar = msg['mapLocationCar'];

					var ordX = -24;
					var ordY = -30;
					var ordY1 = -355;
					var ordY2 = -515;

					for (var i = 0; i < locations.length; i++) {

						var latLng = new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]);

						if (i == 0) {
							center = new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]);
							map.setCenter(center);
						}

						var marker = new google.maps.Marker({
							position: latLng,
							icon: '<?php echo esc_url( $pin_url ); ?>',
							map: map
						});

						var infowindow = new google.maps.InfoWindow({
							maxWidth: 315,
							disableAutoPan: true
						});

						markers.push(marker);
						google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
							return function () {
								var iwlatLng = new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]);

								if (mapLocationCar[locations[i]["lat"]].length == 1) {

									infowindow.setContent('<div class="stm_map_info_window_group_wrap"><a class="stm_iw_link" href="' + carData[i]["link"] + '" width="300" height="147"> <div class="stm_map_info_window_wrap">' +
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
										'</div></a></div>');

									infowindow.setOptions({pixelOffset: new google.maps.Size(ordX, ordY)});
									infowindow.open(map, marker);

								} else {
									var iwlatLng = new google.maps.LatLng(locations[i]["lat"], locations[i]["lng"]);
									var groupClass = (mapLocationCar[locations[i]["lat"]].length <= 3) ? "stm_if_group_" + mapLocationCar[locations[i]["lat"]].length + " stm_if_group_no_scroll" : "stm_if_group_scroll"

									var infoWindowHtml = '<div class="stm_map_info_window_group_wrap ' + groupClass + '"><div class="stm_if_scroll">';

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

									infoWindowHtml += '</div></div>';

									infowindow.setContent(infoWindowHtml);

									if (mapLocationCar[locations[i]["lat"]].length == 2) {
										infowindow.setOptions({pixelOffset: new google.maps.Size(ordX, ordY1)})
									} else {
										infowindow.setOptions({pixelOffset: new google.maps.Size(ordX, ordY2)})
									}

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
									url: '<?php echo esc_url( $cluster_url_path ); ?>',
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
		}

		jQuery(window).on('resize', function () {
			if (typeof map != 'undefined' && typeof center != 'undefined') {
				setTimeout(function () {
					map.setCenter(center);
				}, 1000);
			}
		});

		// initialize map
		document.body.addEventListener('stm_gmap_api_loaded', init, false);
	});
</script>
