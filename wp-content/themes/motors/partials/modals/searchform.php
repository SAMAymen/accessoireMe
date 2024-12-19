<div class="modal" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-body heading_font">
				<div class="search-title">
					<?php esc_html_e( 'Search', 'motors' ); ?>
				</div>
				<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="search-wrapper">
						<input placeholder="<?php esc_attr_e( 'Start typing here...', 'motors' ); ?>" type="text" class="form-control search-input" value="<?php echo get_search_query(); ?>" name="s" id="s" />
						<button type="submit" class="search-submit" ><i class="fas fa-search"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
