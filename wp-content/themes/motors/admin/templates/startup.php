<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="wrap stm-admin-wrap stm-admin-start-screen">
	<?php
	STM_Theme_Admin_Pages::get_admin_tabs();
	require_once __DIR__ . '/theme_info.php';
	?>

	<div class="stm-admin-dashboard-bg">
		<div class="stm-admin-dashboard-wrap">
			<div class="stm-admin-left">
				<?php require_once __DIR__ . '/dashboard/changelog.php'; ?>
			</div>
			<div class="stm-admin-right">
			<?php
			if ( apply_filters( 'stm_show_theme_activation', true ) ) {
				require_once __DIR__ . '/dashboard/theme_activation.php';
			}
			require_once __DIR__ . '/dashboard/support_community.php';
			?>
			</div>
		</div>
		<div class="stm-admin-dashboard-banners-wrap">
			<div class="stm-admin-banner">
				<a href="<?php echo esc_url( STM_PREMIUM_PLUGINS ); ?>" target="_blank">
					<img src="<?php echo esc_url( STM_Theme_Info::get_image_url( 'banner_1.png' ) ); ?>" />
				</a>
			</div>
			<div class="stm-admin-banner">
				<a href="<?php echo esc_url( STM_CUSTOMIZATION ); ?>" target="_blank">
					<img src="<?php echo esc_url( STM_Theme_Info::get_image_url( 'banner_2.png' ) ); ?>" />
				</a>
			</div>
			<?php if ( defined( 'STM_BUY_ANOTHER_LICENSE' ) ) : ?>
			<div class="stm-admin-banner">
				<a href="<?php echo esc_url( STM_BUY_ANOTHER_LICENSE ); ?>" target="_blank">
					<img src="<?php echo esc_url( STM_Theme_Info::get_image_url( 'banner_3.png' ) ); ?>" />
				</a>
			</div>
			<?php endif; ?>
		</div>
		<?php require_once __DIR__ . '/parts/developer_access_popup.php'; ?>
	</div>
</div>
