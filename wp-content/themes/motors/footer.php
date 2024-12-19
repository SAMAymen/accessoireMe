</div> <!--main-->
</div> <!--wrapper-->
<?php

do_action( 'stm_pre_footer' );

if ( ! is_404() && ! is_page_template( 'coming-soon.php' ) ) { ?>
	<footer id="footer">
		<?php
		// need to make this multilisting ready.
		if ( ! is_plugin_active( 'elementor/elementor.php' ) && is_singular( apply_filters( 'stm_get_multilisting_types', apply_filters( 'stm_listings_post_type', 'listings' ) ) ) ) {
			do_action( 'stm_listings_load_template', 'single-car/search-results-carousel' );
		}

		// widget areas.
		get_template_part( 'partials/footer/footer' );

		// copyright text & social icons.
		get_template_part( 'partials/footer/copyright' );

		get_template_part( 'partials/global-alerts' );

		?>
	</footer>
	<?php
} elseif ( is_page_template( 'coming-soon.php' ) ) {
	get_template_part( 'partials/footer/footer-coming-soon' );
}

wp_footer();

?>
<div id="stm-overlay"></div>
</body>
</html>
