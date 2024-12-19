<?php
/**
 * Shortcode attributes
 * @var $atts
 * @var $css
 * */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) );
$pin_url   = get_stylesheet_directory_uri() . '/assets/images/map-marker.png';

if ( ! empty( $pin ) ) {
	$pin = explode( ',', $pin );
	if ( ! empty( $pin[0] ) ) {
		$pin     = $pin[0];
		$pin     = wp_get_attachment_image_src( $pin, 'full' );
		$pin_url = $pin[0];
	}
}

if ( empty( $map_height ) ) {
	$map_height = '545';
}

$pin_url_2 = $pin_url;

if ( ! empty( $pin_2 ) ) {
	$pin_2 = explode( ',', $pin_2 );
	if ( ! empty( $pin_2[0] ) ) {
		$pin_2     = $pin_2[0];
		$pin_2     = wp_get_attachment_image_src( $pin_2, 'full' );
		$pin_url_2 = $pin_2[0];
	}
}

if ( empty( $map_zoom ) ) {
	$map_zoom = 13;
}

do_action( 'stm_google_places_script', 'enqueue', true );

wp_enqueue_script( 'info-box' );

$locations = apply_filters( 'stm_rental_locations', array() );

if ( ! empty( $locations ) ) : ?>
	<script>
		jQuery(document).on('ready', function ($) {

			var icon1 = '<?php echo esc_js( $pin_url ); ?>';
			var icon2 = '<?php echo esc_js( $pin_url_2 ); ?>';

			function initialize() {
				var map;
				var mapStyles = [
					{
						"featureType": "administrative",
						"elementType": "labels.text.fill",
						"stylers": [
							{
								"color": "#444444"
							}
						]
					},
					{
						"featureType": "landscape",
						"elementType": "all",
						"stylers": [
							{
								"color": "#f2f2f2"
							}
						]
					},
					{
						"featureType": "poi",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "road",
						"elementType": "all",
						"stylers": [
							{
								"saturation": -100
							},
							{
								"lightness": 45
							}
						]
					},
					{
						"featureType": "road.highway",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "simplified"
							}
						]
					},
					{
						"featureType": "road.arterial",
						"elementType": "labels.icon",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "transit",
						"elementType": "all",
						"stylers": [
							{
								"visibility": "off"
							}
						]
					},
					{
						"featureType": "water",
						"elementType": "all",
						"stylers": [
							{
								"color": "#6c98e1"
							},
							{
								"visibility": "on"
							}
						]
					}
				];
				var bounds = new google.maps.LatLngBounds();
				var mapOptions = {
					mapTypeId: 'roadmap',
					scrollwheel: false,
					styles: mapStyles
				};

				// Display a map on the page
				map = new google.maps.Map(document.getElementById("stm_map_offices"), mapOptions);
				map.setTilt(45);

				var markers = <?php echo wp_json_encode( $locations ); ?>;

				// Display multiple markers on a map
				var infoWindow = new google.maps.InfoWindow({
					// pixelOffset: new google.maps.Size(-140, -215)
				}), marker, i;

				// Loop through our array of markers & place each one on the map
				for( i = 0; i < markers.length; i++ ) {
					var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
					bounds.extend(position);
					marker = new google.maps.Marker({
						position: position,
						map: map,
						title: markers[i][4],
						icon: icon1
					});

					google.maps.event.addListener(marker, 'mouseover', function() {
						this.setIcon(icon2);
					});
					google.maps.event.addListener(marker, 'mouseout', function() {
						this.setIcon(icon1);
					});

					// Allow each marker to have an info window
					google.maps.event.addListener(marker, 'click', (function(marker, i) {
						return function() {
							infoWindow.setContent(markers[i][0]);
							infoWindow.open(map, marker);
						}
					})(marker, i));

					// Automatically center the map fitting all markers on the screen
					map.fitBounds(bounds);
				}

				var timeOut;
				// Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
				var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
					window.clearTimeout(timeOut);
					timeOut = window.setTimeout(function () {
						map.setZoom(<?php echo esc_js( $map_zoom ); ?>);
					}, 400);

					google.maps.event.removeListener(boundsListener);
				});

				google.maps.event.addListener(infoWindow, 'domready', function() {

					var iwOuter = $('.gm-style-iw');
					var iwBackground = iwOuter.prev();
					iwBackground.addClass('stm-iw-wrapper');
					iwBackground.children(':nth-child(1)').addClass('stm-iw-first');
					iwBackground.children(':nth-child(2)').addClass('stm-iw-second');
					iwBackground.children(':nth-child(3)').addClass('stm-iw-third');
					iwBackground.children(':nth-child(4)').addClass('stm-iw-fourth');

				});
			}

			// initialize map
			document.body.addEventListener('stm_gmap_api_loaded', initialize, false);
		});
	</script>

	<div id="stm_map_offices" style="height:<?php echo esc_attr( $map_height ); ?>px;" class="<?php echo esc_attr( $css_class ); ?>"></div>
	<?php
endif;
