<?php
if ( ! class_exists( 'PearlAnnouncements' ) ) {
	class PearlAnnouncements {

		public $apiurl       = 'https://stylemixthemes.scdn2.secure.raxcdn.com/api/announcement.json';
		public $announcement = array();

		public function __construct() {
			add_action( 'wp_dashboard_setup', array( $this, 'dashboard_changelog' ) );
			add_action( 'wp_dashboard_setup', array( $this, 'dashboard_news' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
		}

		public function dashboard_changelog() {
			add_meta_box( 'pearl_dashboard_announcement', 'Announcement by StylemixThemes', array( $this, 'announcement_screen' ), 'dashboard', 'side', 'high' );
		}

		public function dashboard_news() {
			add_meta_box( 'pearl_dashboard_news', 'News by StylemixThemes', array( $this, 'news_screen' ), 'dashboard', 'side', 'high' );
		}

		public function announcement_screen() {         ?>
			<div id="pearl-announcement">
				<div v-for="announcement in announcements">
					<div v-html="announcement.content"></div>
				</div>
			</div>
			<?php
		}

		public function news_screen() {
			?>
			<div id="pearl-changelog">
				<ul>
					<li v-for="item in news">
						<a v-bind:href="item.link" v-html="item.title.rendered" target="_blank"></a>
					</li>
				</ul>
			</div>
			<?php
		}

		public function scripts( $hook ) {
			if ( 'index.php' === $hook ) {
				$theme_info = time();
				$assets     = STM_MOTORS_EXTENDS_URL . '/inc/announcement/assets/';
				wp_enqueue_style( 'milligram', $assets . 'custom.css', null, $theme_info, 'all' );

				wp_enqueue_script( 'vue.js', $assets . 'vue.min.js', null, $theme_info, true );
				wp_enqueue_script( 'vue-resource.js', $assets . 'vue-resource.js', array( 'vue.js' ), $theme_info, true );
				wp_enqueue_script( 'pearl-vue.js', $assets . 'vue.js', array( 'vue.js' ), $theme_info, true );
			}
		}
	}

	new PearlAnnouncements();
}
