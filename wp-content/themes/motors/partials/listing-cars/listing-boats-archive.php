<div class="row">

	<div class="col-md-12 col-sm-12 classic-filter-row sidebar-sm-mg-bt">
		<?php
		$sidebar_template = ( wp_is_mobile() ) ? 'filter/sidebar-mobile' : 'filter/horizontal-filter/horizontal-filter';
		do_action( 'stm_listings_load_template', $sidebar_template );
		?>
	</div>

	<div class="col-md-12 col-sm-12">

		<div class="stm-ajax-row">
			<?php do_action( 'stm_listings_load_template', 'filter/horizontal-filter/horizontal-filter-actions' ); ?>

			<div id="listings-result">
				<?php do_action( 'stm_listings_load_results' ); ?>
			</div>
		</div>

	</div> <!--col-md-9-->
</div>

<?php do_action( 'stm_listings_load_template', 'filter/horizontal-filter/horizontal-binding' ); ?>
