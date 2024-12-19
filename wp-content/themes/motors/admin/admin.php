<?php
define( 'STM_ADMIN_VERSION', '2.1.2' );
defined( 'STM_ACTIVATION_URL' ) || define( 'STM_ACTIVATION_URL', 'https://support.stylemixthemes.com/theme-activation?envatoId=%s&site=%s' );
defined( 'STM_LICENSE_API_URL' ) || define( 'STM_LICENSE_API_URL', 'https://license.stylemixthemes.com' );

define( 'STM_SERVICE_URL', 'https://microservices.stylemixthemes.com/changelog/' );
define( 'STM_GITBOOK_ACCESSES_TRANSIENT', 'gitbook_accesses_transient' );

define( 'STM_PREMIUM_PLUGINS', 'https://stylemixthemes.com/plugins/' );
define( 'STM_PREMIUM_THEMES', 'https://stylemixthemes.com/themes/' );
define( 'STM_CUSTOMIZATION', 'https://stylemix.net/?utm_source=themes&utm_medium=08_07_2022&utm_campaign=hire_us' );

require_once __DIR__ . '/classes/stm-theme-support.php';

STM_Theme_Support::init();

if ( is_admin() ) {
	require_once __DIR__ . '/classes/stm-tgm-plugins.php';
	require_once __DIR__ . '/classes/stm-review-notice.php';
	require_once __DIR__ . '/classes/stm-theme-info.php';
	require_once __DIR__ . '/classes/stm-theme-admin-templates.php';
	require_once __DIR__ . '/classes/stm-theme-admin-pages.php';
	require_once __DIR__ . '/classes/stm-theme-downloads.php';
	require_once __DIR__ . '/classes/stm-theme-plugins.php';
	require_once __DIR__ . '/classes/stm-theme-activation.php';
	require_once __DIR__ . '/classes/stm-reset-demo.php';
	require_once __DIR__ . '/classes/stm-theme-system-status.php';
	require_once __DIR__ . '/classes/stm-theme-changelog.php';
	require_once __DIR__ . '/admin-notices/admin-notices.php';
	require_once __DIR__ . '/admin-notifications-popup/admin-notification-popup.php';

	STM_Review_Notice::init();
	STM_Theme_Admin_Pages::init();
	STM_Theme_Downloads::init();
	STM_Theme_Plugins::init();
	STM_TGM_Plugins::init();
	STM_Theme_Activation::init();
	STM_Reset_Demo::init();
	STM_Theme_System_Status::init();
	STM_Theme_Changelog::init();
}

// Load WP CLI Custom Commands
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once __DIR__ . '/wp-cli/stm-import-cli.php';
}
