<?php

class STM_Review_Notice {

	public static function init() {
		add_action( 'wp_ajax_stm_ajax_add_review', [ self::class, 'stm_ajax_add_review' ] );
		add_action( 'wp_ajax_nopriv_stm_ajax_add_review', [ self::class, 'stm_ajax_add_review' ] );
		add_action( 'anp_popup_items', [ self::class, 'stm_review_admin_notice' ] );
	}

	public static function stm_ajax_add_review () {
		check_ajax_referer('stm_ajax_add_review', 'security');

		update_option('add_review_status', sanitize_text_field($_GET['add_review_status']));
	}

	public static function stm_review_admin_notice() {
		if ( empty( get_option('add_review_status', '') ) ) {
			$rateItem = new \ANP\Popup\Theme\ItemRateTheme(
				get_template_directory_uri() . '/assets/admin/images/logo.png',
				'If you are happy with the ' . STM_THEME_NAME,
				'Please give it a review on ThemeForest.net :)',
				'https://themeforest.net/downloads',
				'theme_review'
			);

			\ANP\NotificationEnqueueControl::addSecondItem( 'theme_review', $rateItem->createHtml() );
		}
	}

}
