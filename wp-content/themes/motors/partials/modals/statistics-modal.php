<div class="modal" id="statistics-modal" tabindex="-1" role="dialog" aria-labelledby="stmStatisticsModal">
	<div id="statistics-modal-wrap">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<div class="close-statistics-modal" data-dismiss="modal" aria-label="Close">
						<i class="fas fa-times" aria-hidden="true"></i>
					</div>
					<p class="statistics-modal-title">
						<span class="bold">
							<?php echo esc_html__( 'Listing statistics for:', 'motors' ); ?>
						</span>
						<span id="statistics-listing-title"></span>
					</p>
					<hr>
					<div class="navigator">
						<div class="row">
							<div class="col-md-4">
								<select name="statistics-period" id="statistics-period" class="form-control">
									<option value="week"><?php echo esc_html__( 'Last week', 'motors' ); ?></option>
									<option value="month"><?php echo esc_html__( 'Last 30 days', 'motors' ); ?></option>
								</select>
							</div>
							<div class="col-md-8">
								<ul class="statistics-type">
									<li class="listing_views" id="view-toggler">
										<i class="far fa-circle view-circle"></i>
										<?php echo esc_html__( 'Listing views', 'motors' ); ?>
									</li>
									<li class="phone_reveals" id="phone-toggler">
										<i class="far fa-circle phone-circle"></i>
										<?php echo esc_html__( 'Phone number views', 'motors' ); ?>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="chart">
						<canvas id="listingsChart"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$query = ( function_exists( 'stm_user_listings_query' ) ) ? stm_user_listings_query( get_current_user_id(), 'any' ) : null;

$start_date = strtotime( gmdate( 'Y-m-d', strtotime( '-30 days', time() ) ) );
$end_date   = strtotime( gmdate( 'Y-m-d' ) );

$thirty_dates = array();
for ( $i = $start_date; $i <= $end_date; $i = $i + 86400 ) {
	$date = gmdate( 'Y-m-d', $i );
	array_push( $thirty_dates, $date );
}

$listing_stats = array();

if ( ! empty( $query ) && $query->have_posts() ) {
	$listings = $query->get_posts();

	foreach ( $listings as $listing ) {
		foreach ( $thirty_dates as $date ) {
			$listing_stats[ $listing->ID ][ $date ] = array(
				'phone'        => ( get_post_meta( $listing->ID, 'phone_reveals_stat_' . $date, true ) ) ? get_post_meta( $listing->ID, 'phone_reveals_stat_' . $date, true ) : 0,
				'view'         => ( get_post_meta( $listing->ID, 'car_views_stat_' . $date, true ) ) ? get_post_meta( $listing->ID, 'car_views_stat_' . $date, true ) : 0,
				'day_name'     => gmdate( 'l', strtotime( $date ) ),
				'day_number'   => gmdate( 'd', strtotime( $date ) ),
				'tooltip_date' => gmdate( 'F j, Y', strtotime( $date ) ),
			);
		}
	}
}

?>

<style>
	.select2-container.statistics-period .select2-search.select2-search--dropdown.select2-search--hide {
		display: none !important;
	}

	.select2-container.statistics-period .select2-dropdown {
		margin-top: 30px !important;
	}

	.select2-container.statistics-period {
		z-index: 2000;
	}
</style>

<script>
	(function ($) {
		$(document).ready(function () {

			// currently selected
			let stat_listing_id = '';

			// hide/show modal
			$('.listing_stats_wrap > div').on('click touchend', function () {

				var type = $(this).data('type');

				// disable both toggles
				$('ul.statistics-type li').addClass('off');

				if (type == 'phone') {
					$('ul.statistics-type li.phone_reveals').removeClass('off');
				} else {
					$('ul.statistics-type li.listing_views').removeClass('off');
				}

				$('#statistics-modal').modal('show');

				if (typeof $(this).data('id') !== 'undefined') {
					stat_listing_id = $(this).data('id');
					build_stat_chart(stat_listing_id);
				}

				$('#statistics-listing-title').html($(this).data('title'));
			});

			// toggle view and phone stat types
			$('ul.statistics-type li').on('click', function () {
				if ($(this).hasClass('off')) {
					$(this).removeClass('off');
				} else {
					$(this).addClass('off');
				}

				if (stat_listing_id > 0) build_stat_chart(stat_listing_id);
			});

			// toggle between statistics periods - week vs month
			$('#statistics-period').on('change', function () {
				if (stat_listing_id > 0) build_stat_chart(stat_listing_id);
			});

			// build initial empty chart
			var config = {
				type: 'line',
				data: {
					labels: [],
					tooltipDates: [],
					datasets: [
						{
							label: '<?php echo esc_html__( 'Listing views', 'motors' ); ?>',
							backgroundColor: 'rgb(65, 123, 223, 0.4)',
							borderColor: 'rgb(65, 123, 223)',
							data: [],
							pointBackgroundColor: 'rgb(65, 123, 223)'
						},
						{
							label: '<?php echo esc_html__( 'Phone views', 'motors' ); ?>',
							backgroundColor: 'rgb(22, 203, 100, 0.4)',
							borderColor: 'rgb(22, 203, 100)',
							data: [],
							pointBackgroundColor: 'rgb(22, 203, 100)'
						}
					]
				},
				options: {
					'plugins': {
						'legend': {
							'display': false,
						},
						'tooltip': {
							titleAlign: 'center',
							yAlign: 'bottom',
							titleFont: {
								weight: 'normal'
							},
							callbacks: {
								title: function (tooltipItem, data) {
									return tooltipItem[0].chart.config.data.tooltipDates[tooltipItem[0].dataIndex]
								}
							},
							titleColor: '#fff',
							usePointStyle: true,
							displayColors: true,
							boxWidth: 8,
							boxHeight: 8,
						}
					},
					'elements': {
						'line': {
							'fill': true,
							'borderWidth': 1.5,
						},
						'point': {
							'borderWidth': 4,
						}
					},
					scale: {
						ticks: {
							precision: 0
						}
					},
					scales: {
						y: {
							min: 0
						}
					},
				}
			};

			listingsChart = new Chart(
				document.getElementById('listingsChart'),
				config
			);

			// get stat data for all owner listings
			var stat_data = <?php echo wp_json_encode( $listing_stats ); ?>;

			// build stat data according to the current selection
			function build_stat_chart(id) {
				if (typeof stat_data === 'undefined' || stat_data.length == 0) return;

				// remove old data
				listingsChart.data.labels = [];
				listingsChart.data.tooltipDates = [];
				listingsChart.data.datasets[0].data = [];
				listingsChart.data.datasets[1].data = [];

				// build data
				if ($('#statistics-period').val() == 'week') {
					// get last week data
					var seven_days = Object.keys(stat_data[id]).slice(-7).map(key => ({[key]: stat_data[id][key]}));
					for (var key in seven_days) {
						for (var date in seven_days[key]) {
							listingsChart.data.labels.push(seven_days[key][date].day_name);
							listingsChart.data.tooltipDates.push(seven_days[key][date].tooltip_date);

							if (!$('#view-toggler').hasClass('off')) {
								listingsChart.data.datasets[0].data.push(seven_days[key][date].view);
							}

							if (!$('#phone-toggler').hasClass('off')) {
								listingsChart.data.datasets[1].data.push(seven_days[key][date].phone);
							}
						}
					}
				} else {
					// get last month labels
					Object.values(stat_data[id]).forEach(val => {
						listingsChart.data.labels.push(val.day_number);
						listingsChart.data.tooltipDates.push(val.tooltip_date);

						if (!$('#view-toggler').hasClass('off')) {
							listingsChart.data.datasets[0].data.push(val.view);
						}

						if (!$('#phone-toggler').hasClass('off')) {
							listingsChart.data.datasets[1].data.push(val.phone);
						}
					});
				}

				// show new result
				listingsChart.update();

			}

		});

	})(jQuery);
</script>
