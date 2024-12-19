<?php
$themeName = STM_THEME_NAME;
?>
<div class="stm-admin-changelog">
	<div class="__top">
		<h3><i class="stmadmin-icon-clock_arrow"></i>Changelog</h3>
		<div class="update-status <?php echo ( false ) ? 'updated' : 'need_update' ?>" style="display: none;">
			<?php if ( false ) : ?>
				<i class="stmadmin-icon-imported-check"></i>
				<?php echo esc_html( $themeName ); ?><br/>is up to date
			<?php else: ?>
				<a href="">
					<i class="stmadmin-icon-update"></i>
					Update your<br/><?php echo esc_html( $themeName ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="chlg-row">
		<?php
		$chlg = STM_Theme_Changelog::get_theme_changelog();

		if ( ! empty( $chlg ) ) {
			for ( $i = 0; $i <= 2; $i ++ ) :
				$chlg_item = $chlg[ $i ];
				?>
				<div class="chlg-head">
					<h4>Version: <?php echo esc_html( $chlg_item['heading'] ) ?></h4>
					<span class="chlg-date"><?php echo esc_html( $chlg_item['date'] ); ?></span>
				</div>
				<div class="chlg-list">
					<ul>
						<?php foreach ( $chlg_item['list'] as $item ): ?>
							<li><?php echo wp_kses_post( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php
			endfor;
		}
		?>
	</div>
	<div class="chlg-footer-bg">
		<div class="chlg-footer">
			<a href="<?php echo esc_url( STM_CHANGELOG_URL ); ?>" target="_blank">See More</a>
			<div class="vote">
				<?php echo sprintf( 'Create a %s roadmap with us:', $themeName ); ?>
				<a href="<?php echo esc_url( STM_VOTE_URL ); ?>" target="_blank">Vote for next feature<i
						class="stmadmin-icon-link"></i></a>
			</div>
		</div>
	</div>
</div>