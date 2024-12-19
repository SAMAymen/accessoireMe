<?php
add_action( 'admin_notices', 'stm_motors_notice_wpb_intall_plugin' );
function stm_motors_notice_wpb_intall_plugin() {
	if ( defined( 'WPB_VC_VERSION' ) && ! defined( 'STM_MWW_PATH' ) ) {

		if ( defined( 'STM_ADMIN_NOTICES_VERSION' ) && function_exists( 'stm_admin_notices_init' ) ) {

			$init_data = array(
				'notice_type'          => 'animate-circle-notice only-title has-btn',
				'notice_logo'          => 'wpb.svg',
				'notice_title'         => 'Please install Motors WPBakery Widgets in Motors > Plugins',
				'notice_btn_one'       => admin_url( 'plugins.php' ),
				'notice_btn_one_title' => 'Install Plugin',
			);

			stm_admin_notices_init( $init_data );

		}
	}
}

add_filter( 'upload_mimes', 'stm_svg_mime', 100 );
function stm_svg_mime( $mimes ) {
	$mimes['ico'] = 'image/icon';
	$mimes['svg'] = 'image/svg+xml';
	$mimes['xml'] = 'application/xml';

	return $mimes;
}

if ( apply_filters( 'stm_is_auto_parts', false ) ) {
	add_filter( 'stm_hb_icons_set', 'stm_motors_icons_set', 100, 1 );
	add_filter( 'stm_hb_elements', 'stm_motors_hb_elements', 100 );
}

add_action(
	'admin_init',
	function () {
		delete_transient( 'elementor_activation_redirect' );
	}
);

add_filter( 'stm_me_wpcfto_sidebars_list', 'stm_motors_add_sidebars' );
function stm_motors_add_sidebars( $sb ) {
	global $wp_registered_sidebars;

	foreach ( $wp_registered_sidebars as $sidebar ) {
		if ( in_array( $sidebar['id'], array( 'default', 'footer' ), true ) ) {
			continue;
		}

		$sb[ $sidebar['id'] ] = $sidebar['name'];
	}

	return $sb;
}

function stm_motors_hb_elements( $elements ) {
	$elements[] = array(
		'label'             => esc_html__( 'Icon Box 2', 'motors' ),
		'type'              => 'text',
		'icon'              => 'icon-magnifier',
		'view_template'     => 'icon-box-2',
		'settings_template' => 'hb_templates/modals/icon-box-2',
	);

	$elements[] = array(
		'label'             => esc_html__( 'Cart 2', 'motors' ),
		'type'              => 'text',
		'icon'              => 'icon-cart',
		'view_template'     => 'cart-2',
		'settings_template' => 'hb_templates/modals/cart-2',
	);

	$elements[] = array(
		'label'             => esc_html__( 'Text Link', 'motors' ),
		'type'              => 'text',
		'icon'              => 'icon-cart',
		'view_template'     => 'text-link',
		'settings_template' => 'hb_templates/modals/text-link',
	);

	$elements[] = array(
		'label'             => esc_html__( 'Multi Currency Select', 'motors' ),
		'type'              => 'text',
		'icon'              => 'icon-cart',
		'view_template'     => 'multi-currency',
		'settings_template' => 'hb_templates/modals/multi-currency',
	);

	return $elements;
}

function stm_motors_icons_set( $icons ) {
	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$json_file         = get_theme_file_path() . '/assets/fonts/auto-parts-font/selection.json';
	$custom_icons_json = json_decode( $wp_filesystem->get_contents( $json_file ), true );
	$custom_icons      = array();

	if ( ! empty( $custom_icons_json ) ) {
		$set_name   = $custom_icons_json['metadata']['name'];
		$set_prefix = $custom_icons_json['preferences']['fontPref']['prefix'];
		foreach ( $custom_icons_json['icons'] as $icon ) {
			$custom_icons[] = $set_prefix . $icon['properties']['name'];
		}

		if ( ! empty( $custom_icons ) ) {
			$icons['stm-icon'] = $custom_icons;
		}
	}

	return $icons;
}

function stm_motors_check_recaptcha( $secret, $token ) {
	$url_google_api = 'https://www.google.com/recaptcha/api/siteverify';

	$remote_address = '';

	if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$remote_address = sanitize_text_field( $_SERVER['REMOTE_ADDR'] );
	}

	$query = $url_google_api . '?secret=' . $secret . '&response=' . $token . '&remoteip=' . $remote_address;

	$http = wp_remote_get( $query );

	$body = wp_remote_retrieve_body( $http );

	$data = json_decode( $body, true );

	$score = ( ! empty( $data['score'] ) ) ? $data['score'] : null;

	if ( $score < 0.4 ) {
		return false;
	}

	return true;
}

// Comments.
if ( ! function_exists( 'stm_comment' ) ) {
	function stm_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		extract( $args, EXTR_SKIP );

		if ( 'div' === $args['style'] ) {
			$tag       = 'div ';
			$add_below = 'comment';
		} else {
			$tag       = 'li ';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( 'div' !== $args['style'] ) { ?>
			<div id="div-comment-<?php comment_ID(); ?>" class="comment-body clearfix">
		<?php } ?>
		<?php if ( 0 !== $args['avatar_size'] ) { ?>
			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 80 ); ?>
			</div>
		<?php } ?>
		<div class="comment-info-wrapper">
			<div class="comment-info">
				<div class="clearfix">
					<div class="comment-author pull-left"><span
								class="h5"><?php echo get_comment_author_link(); ?></span></div>
					<div class="comment-meta commentmetadata pull-right">
						<a class="comment-date" href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>">
							<?php printf( '%1$s', esc_html( get_comment_date() ) ); ?>
						</a>
						<span class="comment-meta-data-unit">
							<?php
							comment_reply_link(
								array_merge(
									$args,
									array(
										'reply_text' => __( '<span class="comment-divider">/</span><i class="fas fa-reply"></i> Reply', 'motors' ),
										'add_below'  => $add_below,
										'depth'      => $depth,
										'max_depth'  => $args['max_depth'],
									)
								)
							);
							?>
						</span>
						<span class="comment-meta-data-unit">
							<?php edit_comment_link( __( '<span class="comment-divider">/</span><i class="fas fa-pen-square"></i> Edit', 'motors' ), '  ', '' ); ?>
						</span>
					</div>
				</div>
				<?php if ( '0' === $comment->comment_approved ) { ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'motors' ); ?></em>
				<?php } ?>
			</div>
			<div class="comment-text">
				<?php comment_text(); ?>
			</div>
		</div>

		<?php if ( 'div' !== $args['style'] ) { ?>
			</div>
		<?php } ?>
		<?php
	}
}


add_filter( 'comment_form_default_fields', 'stm_bootstrap3_comment_form_fields' );

if ( ! function_exists( 'stm_bootstrap3_comment_form_fields' ) ) {
	function stm_bootstrap3_comment_form_fields( $fields ) {
		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
		$html5     = current_theme_supports( 'html5', 'comment-form' ) ? 1 : 0;
		$fields    = array(
			'author' => '<div class="row stm-row-comments">
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group comment-form-author">
			           			<input placeholder="' . sprintf( esc_attr__( 'Name %s', 'motors' ), ( $req ? '*' : '' ) ) . '" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />
		                       </div>
		                   </div>',
			'email'  => '<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="form-group comment-form-email">
								<input placeholder="' . sprintf( esc_attr__( 'E-mail %s', 'motors' ), ( $req ? '*' : '' ) ) . '" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />
							</div>
						</div>',
			'url'    => '<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group comment-form-url">
							<input placeholder="' . esc_attr__( 'Website', 'motors' ) . '" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
						</div>
					</div></div>',
		);

		return $fields;
	}
}

add_filter( 'comment_form_defaults', 'stm_bootstrap3_comment_form' );

if ( ! function_exists( 'stm_bootstrap3_comment_form' ) ) {
	function stm_bootstrap3_comment_form( $args ) {
		$args['comment_field'] = '<div class="form-group comment-form-comment">
			<textarea placeholder="' . esc_attr__( 'Message *', 'motors' ) . '" name="comment" rows="9" aria-required="true"></textarea>
	   </div>';

		return $args;
	}
}

if ( ! function_exists( 'stm_is_woocommerce_activated' ) ) {
	function stm_is_woocommerce_activated() {
		return class_exists( 'Woocommerce' );
	}
}

if ( ! function_exists( 'stm_body_class' ) ) {
	function stm_body_class( $classes ) {

		$user_agent = apply_filters( 'stm_get_global_server_val', 'HTTP_USER_AGENT' );

		$macintosh = ( ! empty( $user_agent ) ) ? strpos( $user_agent, 'Macintosh' ) ? true : false : false;
		global $wp_customize;

		if ( $macintosh ) {
			$classes[] = 'stm-macintosh';
		}

		if ( strstr( $user_agent, ' AppleWebKit/' ) && strstr( $user_agent, ' Mobile/' ) ) {
			$classes[] = 'stm-ios_safari';
		}

		$boxed    = apply_filters( 'stm_me_get_nuxy_mod', false, 'site_boxed' );
		$bg_image = apply_filters( 'stm_me_get_nuxy_mod', false, 'bg_image' );

		if ( $boxed ) {
			$classes[] = 'stm-boxed';
			if ( $bg_image ) {
				$classes[] = $bg_image;
			}
		}

		// Layout class.
		$layout             = stm_get_current_layout();
		$listings_post_type = apply_filters( 'stm_listings_post_type', 'listings' );

		if ( empty( $layout ) ) {
			$class = '';
			switch ( get_current_blog_id() ) {
				case 1:
					$class = 'car_dealer';
					break;
				case 2:
					$class = 'listing';
					break;
				case 4:
					$class = 'boats';
					break;
				case 5:
					$class = 'motorcycle';
					break;
				case 7:
					$class = 'car_rental';
					break;
				case 8:
					$class = 'car_magazine';
					break;
				case 9:
					$class = 'car_dealer_two';
					break;
				case 10:
					$class = 'listing_two';
					break;
				case 11:
					$class = 'listing_three';
					break;
			}
			$layout = $class;
		}

		if ( ( 'car_magazine' === $layout && is_singular( 'post' ) && ! is_page_template( 'single-interview.php' ) ) || ( 'car_magazine' === $layout && is_category() ) ) {
			$classes[] = 'no_margin';
		}

		if ( 'car_dealer_two' === $layout || 'car_dealer_two_elementor' === $layout ) {
			global $wp_query;

			$inventory_class = ( is_singular( array( $listings_post_type ) ) || 'listing_template' === get_post_type() || ( ! empty( $wp_query->post->post_content ) && preg_match( '/stm_sold_cars/', $wp_query->post->post_content ) ) || is_post_type_archive( $listings_post_type ) || ( ! empty( $wp_query->post->ID ) && intval( apply_filters( 'motors_vl_get_nuxy_mod', 0, 'listing_archive' ) ) === $wp_query->post->ID ) ) ? ' inventory-' . apply_filters( 'stm_me_get_nuxy_mod', 'dark', 'inventory_layout' ) : '';

			$classes[] = 'no_margin' . $inventory_class;

			$show_title_box   = get_post_meta( get_the_ID(), 'title', true );
			$show_breadcrumbs = get_post_meta( get_the_ID(), 'breadcrumbs', true );

			if ( get_the_ID() !== get_option( 'page_on_front' ) && get_the_ID() !== get_option( 'page_for_posts' ) && 'hide' === $show_title_box ) {
				$classes[] = 'title-box-hide';
			}

			if ( get_the_ID() !== get_option( 'page_on_front' ) && get_the_ID() !== get_option( 'page_for_posts' ) && 'hide' === $show_breadcrumbs ) {
				$classes[] = 'breadcrumbs-hide';
			}

			if ( is_singular( array( $listings_post_type ) ) && 'hide' !== $show_title_box ) {
				$classes[] = 'single-listing-title-box-show';
			}
		}

		$classes[] = 'stm-template-' . $layout;

		if ( is_singular( $listings_post_type ) ) {
			global $post;
			$has_id = get_post_meta( $post->ID, 'automanager_id', true );
			if ( ! empty( $has_id ) ) {
				$classes[] = 'automanager-listing-page';
			}
		}

		if ( ! is_user_logged_in() ) {
			$classes[] = 'stm-user-not-logged-in';
		}

		if ( ! empty( apply_filters( 'stm_get_global_server_val', 'HTTP_USER_AGENT' ) ) ) {
			$agent = apply_filters( 'stm_get_global_server_val', 'HTTP_USER_AGENT' );
			if ( strlen( strstr( $agent, 'Firefox' ) ) > 0 ) {
				$classes[] = 'stm-firefox';
			}
		}

		if ( isset( $wp_customize ) ) {
			$classes[] = 'stm-customize-page';
			$classes[] = 'stm-customize-layout-' . $layout;
		}

		if ( apply_filters( 'stm_is_boats', false ) ) {
			global $post;
			if ( ! empty( $post->ID ) ) {
				$transparent = get_post_meta( $post->ID, 'transparent_header', true );
				if ( ! empty( $transparent ) && 'on' === $transparent ) {
					$transparent = 'stm-boats-transparent';
				} else {
					$transparent = 'stm-boats-default';
				}
				$classes[] = $transparent;
			}
		}

		if ( apply_filters( 'stm_is_listing', false ) ) {
			$fixed_header = apply_filters( 'stm_me_get_nuxy_mod', false, 'header_sticky' );
			if ( ! $fixed_header ) {
				$classes[] = 'header-listing-mobile-unfixed';
			}
		}

		if ( ! apply_filters( 'stm_me_get_nuxy_mod', false, 'header_compare_show' ) ) {
			$classes[] = 'header_remove_compare';
		}

		if ( ! apply_filters( 'stm_me_get_nuxy_mod', false, 'header_cart_show' ) ) {
			$classes[] = 'header_remove_cart';
		}

		if ( apply_filters( 'stm_is_rental', false ) && stm_is_woocommerce_activated() && is_cart() ) {
			$classes[] = 'woocommerce';
		}

		if ( apply_filters( 'stm_is_rental', false ) && is_page( apply_filters( 'stm_me_get_nuxy_mod', false, 'rental_datepick' ) ) ) {
			$classes[] = 'stm-template-rental-daypicker-page';
		}

		if ( apply_filters( 'stm_is_auto_parts', false ) && apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_preloader' ) ) {

			$classes[] = 'stm-preloader';
		}

		if ( apply_filters( 'stm_is_aircrafts', false ) ) {
			global $wp_query;

			if ( ! empty( $wp_query->post ) && intval( apply_filters( 'motors_vl_get_nuxy_mod', 0, 'listing_archive' ) ) === $wp_query->post->ID ) {
				$classes[] = 'stm-inventory-page';
			}
		}

		$header_style = stm_get_header_layout();

		if ( apply_filters( 'stm_is_magazine', false ) || 'car_dealer_two' === $header_style ) {
			$classes[] = 'no_margin';
		}

		if ( apply_filters( 'stm_is_service', false ) ) {
			$header_style = 'service';
		}

		$classes[] = 'stm-layout-header-' . $header_style;

		$transparent_header = get_post_meta( get_the_id(), 'transparent_header', true );

		if ( ! empty( $transparent_header ) ) {
			$classes[] = 'transparent-header';
		}

		if ( class_exists( 'breadcrumb_navxt' ) ) {
			$classes[] = 'has-breadcrumb_navxt';
		}

		// interactive hoverable featured images.
		$galleries_hoverable = apply_filters( 'motors_vl_get_nuxy_mod', false, 'gallery_hover_interaction' );
		if ( true === $galleries_hoverable ) {
			$classes[] = 'stm-hoverable-interactive-galleries';
		}

		// single listing template type.
		if ( is_singular( apply_filters( 'stm_listings_multi_type', array( 'listings' ) ) ) ) {
			$vc_status = get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );
			if ( 'false' !== $vc_status && true === $vc_status ) {
				// will check for elementor in future.
				$classes[] = 'single_builder_wpb';
			} else {
				$classes[] = 'single_builder_none';
			}
		}

		if ( ! in_array( 'theme-motors', $classes, true ) ) {
			$classes[] = 'theme-motors';
		}

		return $classes;
	}
}

add_filter( 'body_class', 'stm_body_class' );

add_filter( 'language_attributes', 'stm_preloader_html_class' );

function stm_preloader_html_class( $output ) {
	$enable_preloader = apply_filters( 'stm_me_get_nuxy_mod', false, 'enable_preloader' );

	$preloader_class = '';
	if ( ! function_exists( 'preloader_timer_var' ) ) {
		function preloader_timer_var() {
			$preloader_timer = apply_filters( 'stm_me_get_nuxy_mod', false, 'preloader_timer' );
			if ( empty( $preloader_timer ) ) {
				$preloader_timer = '';
			}
			?>
			<script type="text/javascript">
				var preloader_timer = <?php echo wp_json_encode( $preloader_timer ); ?>;
			</script>
			<?php
		}
	}

	add_action( 'wp_footer', 'preloader_timer_var' );

	if ( $enable_preloader ) {
		$preloader_class = ' class="stm-site-preloader"';
		if ( apply_filters( 'stm_is_rental', false ) ) {
			$preloader_class = ' class="stm-site-preloader stm-site-preloader-anim"';

			if ( get_option( 'woocommerce_myaccount_page_id' ) === get_the_ID() && is_user_logged_in() ) {
				$preloader_class = '';
			}
		}
	}

	return $output . $preloader_class;
}

if ( ! function_exists( 'stm_print_styles' ) ) {
	function stm_print_styles() {
		$site_css = apply_filters( 'stm_me_get_nuxy_mod', '', 'custom_css' );
		if ( ! empty( $site_css ) && is_string( $site_css ) ) {
			$site_css = preg_replace( '/\s+/', ' ', $site_css );
			wp_add_inline_style( 'stm-theme-style', $site_css );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'stm_print_styles' );

// Hex to rgba.
if ( ! function_exists( 'stm_hex2rgb' ) ) {
	add_filter( 'stm_hex2rgb', 'stm_hex2rgb' );
	function stm_hex2rgb( $colour ) {
		if ( ! empty( $colour[0] ) && '#' === $colour[0] ) {
			$colour = substr( $colour, 1 );
		}
		if ( 6 === strlen( $colour ) ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
		} elseif ( 3 === strlen( $colour ) ) {
			list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
		} else {
			return false;
		}
		$r = hexdec( $r );
		$g = hexdec( $g );
		$b = hexdec( $b );

		return $r . ',' . $g . ',' . $b;
	}
}


// Limit content by chars.
if ( ! function_exists( 'stm_limit_content' ) ) {
	function stm_limit_content( $limit ) {
		$content = explode( ' ', get_the_content(), $limit );
		if ( count( $content ) >= $limit ) {
			array_pop( $content );
			$content = implode( ' ', $content ) . '...';
		} else {
			$content = implode( ' ', $content );
		}
		$content = preg_replace( '/\[.+\]/', '', $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}
}

// Get socials.
if ( ! function_exists( 'stm_get_header_socials' ) ) {
	function stm_get_header_socials( $socials_pos = 'header_socials_enable' ) {
		$socials_array = array();

		$header_socials_enable = apply_filters( 'stm_me_get_nuxy_mod', false, $socials_pos );

		$socials = apply_filters( 'stm_me_get_nuxy_mod', '', 'socials_link' );

		$socials_values = array();
		if ( ! empty( $socials ) && is_array( $socials ) ) {
			foreach ( $socials as $k => $soc ) {
				if ( ! empty( $soc['value'] ) ) {
					$socials_values[ $soc['key'] ] = $soc['value'];
				}
			}
		}

		if ( $header_socials_enable && is_array( $header_socials_enable ) ) {
			foreach ( $header_socials_enable as $social ) {
				if ( ! empty( $socials_values[ $social ] ) ) {
					$socials_array[ $social ] = $socials_values[ $social ];
				}
			}
		}

		return $socials_array;
	}
}

// Sidebar layout.
if ( ! function_exists( 'stm_sidebar_layout_mode' ) ) {
	function stm_sidebar_layout_mode( $position = 'left', $sidebar_id = false ) {
		$content_before = '';
		$content_after  = '';
		$sidebar_before = '';
		$sidebar_after  = '';
		$show_title     = '';
		$default_row    = '';
		$default_col    = '';

		if ( 'post' === get_post_type() ) {
			if ( ! empty( $_GET['show-title-box'] ) && 'hide' === $_GET['show-title-box'] ) {
				$blog_archive_id = get_option( 'page_for_posts' );
				if ( ! empty( $blog_archive_id ) ) {

					$get_the_title = get_the_title( $blog_archive_id );

					if ( ! empty( $get_the_title ) ) {
						$show_title = '<h2 class="stm-blog-main-title">' . $get_the_title . '</h2>';
					}
				}
			}
		}

		if ( ! $sidebar_id ) {
			$content_before .= '<div class="col-md-12">';

			$content_after .= '</div>';

			$default_row = 3;
			$default_col = 'col-md-4 col-sm-4 col-xs-12';
		} else {
			$hide_classes = 'hidden-sm hidden-xs';
			if ( stm_is_dealer_two() || stm_is_car_dealer() ) {
				$hide_classes = 'col-sm-12 col-xs-12';
			}
			if ( 'right' === $position ) {
				$content_before .= '<div class="col-md-9 col-sm-12 col-xs-12"><div class="sidebar-margin-top clearfix"></div>';
				$sidebar_before .= '<div class="col-md-3 ' . $hide_classes . '">';

				$sidebar_after .= '</div>';
				$content_after .= '</div>';
			} elseif ( 'left' === $position ) {
				$content_before .= '<div class="col-md-9 col-md-push-3 col-sm-12 col-xs-12"><div class="sidebar-margin-top clearfix"></div>';
				$sidebar_before .= '<div class="col-md-3 col-md-pull-9 ' . $hide_classes . '">';

				$sidebar_after .= '</div>';
				$content_after .= '</div>';
			}
			$default_row = 2;
			$default_col = ( apply_filters( 'stm_is_listing_five', false ) || apply_filters( 'stm_is_listing_six', false ) ) ? 'col-md-4 col-sm-4 col-xs-12' : 'col-md-6 col-sm-6 col-xs-12';
		}

		$return                   = array();
		$return['content_before'] = $content_before;
		$return['content_after']  = $content_after;
		$return['sidebar_before'] = $sidebar_before;
		$return['sidebar_after']  = $sidebar_after;
		$return['show_title']     = $show_title;
		$return['default_row']    = $default_row;
		$return['default_col']    = $default_col;

		return $return;
	}
}

/**
 * Add empty gravatar
 */
function stm_default_avatar( $avatar_defaults ) {
	$stm_avatar                     = get_stylesheet_directory_uri() . '/assets/images/gravataricon.png';
	$avatar_defaults[ $stm_avatar ] = esc_html__( 'Motors Theme Default', 'motors' );

	return $avatar_defaults;
}

add_filter( 'avatar_defaults', 'stm_default_avatar' );

// Crop title.
if ( ! function_exists( 'stm_trim_title' ) ) {
	function stm_trim_title( $title = '', $number = 35, $after = '...' ) {
		if ( ! intval( $number ) ) {
			return $title;
		}

		$title = '';
		$title = esc_attr( trim( preg_replace( '/\s+/', ' ', mb_substr( get_the_title(), 0, $number ) ) ) );
		if ( mb_strlen( get_the_title() ) > $number ) {
			$title .= esc_attr( $after );
		}

		return $title;
	}

	add_filter( 'stm_trim_title', 'stm_trim_title', 10, 3 );
}

// After crop chars.
if ( ! function_exists( 'stm_excerpt_more_new' ) ) {
	function stm_excerpt_more_new( $more ) {
		return '...';
	}

	add_filter( 'excerpt_more', 'stm_excerpt_more_new' );
}

if ( ! function_exists( 'stm_custom_pagination' ) ) {
	function stm_custom_pagination() {
		global $wp_query;
		$show_pagination = true;
		if ( ! empty( $wp_query->found_posts ) && ! empty( $wp_query->query_vars['posts_per_page'] ) ) {
			if ( $wp_query->found_posts <= $wp_query->query_vars['posts_per_page'] ) {
				$show_pagination = false;
			}
		}
		if ( $show_pagination ) :
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="stm-blog-pagination">
						<?php if ( get_previous_posts_link() ) { ?>
							<div class="stm-prev-next stm-prev-btn">
								<?php previous_posts_link( '<i class="fas fa-angle-left"></i>' ); ?>
							</div>
						<?php } else { ?>
							<div class="stm-prev-next stm-prev-btn disabled"><i class="fas fa-angle-left"></i></div>
							<?php
						}

						echo wp_kses_post(
							paginate_links(
								array(
									'type'      => 'list',
									'prev_next' => false,
								)
							)
						);

			if ( get_next_posts_link() ) {
				?>
							<div class="stm-prev-next stm-next-btn">
								<?php next_posts_link( '<i class="fas fa-angle-right"></i>' ); ?>
							</div>
						<?php } else { ?>
							<div class="stm-prev-next stm-next-btn disabled"><i class="fas fa-angle-right"></i></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'stm_custom_prev_next' ) ) {
	function stm_custom_prev_next( $post_id ) {
		global $post;

		$old_global     = $post;
		$post           = get_post( $post_id );
		$next_post      = get_next_post();
		$prev_post      = get_previous_post();
		$post           = $old_global;
		$prev_next_post = array();

		if ( ! empty( $prev_post ) ) {
			$prev_next_post['prev'] = $prev_post;
		}
		if ( ! empty( $next_post ) ) {
			$prev_next_post['next'] = $next_post;
		}

		return $prev_next_post;
	}
}

function stm_setup_listing_options() {
	$stm_listings = array(
		1  => array(
			'single_name'                          => 'Condition',
			'plural_name'                          => 'Conditions',
			'slug'                                 => 'condition',
			'font'                                 => '',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => false,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => true,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		2  => array(
			'single_name'                          => 'Body',
			'plural_name'                          => 'Bodies',
			'slug'                                 => 'body',
			'font'                                 => '',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		3  => array(
			'single_name'                          => 'Make',
			'plural_name'                          => 'Makes',
			'slug'                                 => 'make',
			'font'                                 => '',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => false,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => true,
			'use_on_car_modern_filter_view_images' => true,
			'use_on_car_filter_links'              => false,
		),
		5  => array(
			'single_name'                          => 'Model',
			'plural_name'                          => 'Models',
			'slug'                                 => 'serie',
			'font'                                 => '',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => false,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		6  => array(
			'single_name'                          => 'Mileage',
			'plural_name'                          => 'Mileages',
			'slug'                                 => 'mileage',
			'font'                                 => 'stm-icon-road',
			'numeric'                              => true,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => true,
			'use_on_car_archive_listing_page'      => true,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => false,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		7  => array(
			'single_name'                          => 'Fuel type',
			'plural_name'                          => 'Fuel types',
			'slug'                                 => 'fuel',
			'font'                                 => 'stm-icon-fuel',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => true,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => false,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		8  => array(
			'single_name'                          => 'Engine',
			'plural_name'                          => 'Engines',
			'slug'                                 => 'engine',
			'font'                                 => 'stm-icon-engine_fill',
			'numeric'                              => true,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => true,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => false,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		9  => array(
			'single_name'                          => 'Year',
			'plural_name'                          => 'Years',
			'slug'                                 => 'ca-year',
			'font'                                 => 'stm-icon-road',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		10 => array(
			'single_name'                          => 'Price',
			'plural_name'                          => 'Prices',
			'slug'                                 => 'price',
			'font'                                 => '',
			'numeric'                              => true,
			'listing_price_field'                  => true,
			'use_on_single_listing_page'           => true,
			'use_on_car_listing_page'              => true,
			'use_on_car_archive_listing_page'      => true,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => true,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		11 => array(
			'single_name'                          => 'Fuel consumption',
			'plural_name'                          => 'Fuel consumptions',
			'slug'                                 => 'fuel-consumption',
			'font'                                 => 'stm-icon-fuel',
			'numeric'                              => true,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => true,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => false,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => false,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		12 => array(
			'single_name'                          => 'Transmission',
			'plural_name'                          => 'Transmission',
			'slug'                                 => 'transmission',
			'font'                                 => 'stm-icon-transmission_fill',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => true,
			'use_on_car_archive_listing_page'      => true,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => true,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => true,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		13 => array(
			'single_name'                          => 'Drive',
			'plural_name'                          => 'Drives',
			'slug'                                 => 'drive',
			'font'                                 => 'stm-icon-drive_2',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => true,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => false,
			'use_on_car_modern_filter'             => true,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		14 => array(
			'single_name'                          => 'Fuel economy',
			'plural_name'                          => 'Fuel economy',
			'slug'                                 => 'fuel-economy',
			'font'                                 => '',
			'numeric'                              => true,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => false,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		15 => array(
			'single_name'                          => 'Exterior Color',
			'plural_name'                          => 'Exterior Colors',
			'slug'                                 => 'exterior-color',
			'font'                                 => '',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
		16 => array(
			'single_name'                          => 'Interior Color',
			'plural_name'                          => 'Interior Colors',
			'slug'                                 => 'interior-color',
			'font'                                 => '',
			'numeric'                              => false,
			'use_on_single_listing_page'           => false,
			'use_on_car_listing_page'              => false,
			'use_on_car_archive_listing_page'      => false,
			'use_on_single_car_page'               => true,
			'use_on_map_page'                      => false,
			'use_on_car_filter'                    => true,
			'use_on_car_modern_filter'             => false,
			'use_on_car_modern_filter_view_images' => false,
			'use_on_car_filter_links'              => false,
		),
	);
	if ( ! get_option( 'stm_vehicle_listing_options' ) ) {
		update_option( 'stm_vehicle_listing_options', $stm_listings );
	}
}

add_action( 'after_switch_theme', 'stm_setup_listing_options' );
add_action( 'load-themes.php', 'stm_setup_listing_options' );

// After import hook and add menu, home page, slider, blog page.
if ( ! function_exists( 'stm_importer_done_function' ) ) {
	add_action( 'stm_importer_done', 'stm_importer_done_function', 10, 1 );
	function stm_importer_done_function( $layout ) {
		global $wp_filesystem;

		if ( apply_filters( 'stm_is_auto_parts', false ) ) {
			if ( class_exists( 'ClassWCMAPSearchFilter' ) ) {
				$query = new WP_Query(
					array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
					)
				);

				foreach ( $query->posts as $prod ) {
					do_action( 'stm_after_import_woocommerce_update_product', $prod->ID );
				}

				wp_reset_postdata();
			}

			if ( class_exists( 'YITH_WCWL_Install' ) ) {
				$whishlist = YITH_WCWL_Install::get_instance();
				$whishlist->init();
			}
		}

		if ( apply_filters( 'stm_is_rental_two', false ) ) {
			global $wp_rewrite;
			$wp_rewrite->set_permalink_structure( '/%postname%/' );
			$wp_rewrite->flush_rules();
		}

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$fxml = get_temp_dir() . $layout . '.xml';
		$fzip = get_temp_dir() . $layout . '.zip';

		if ( file_exists( $fxml ) ) {
			unlink( $fxml );
		}

		if ( file_exists( $fzip ) ) {
			unlink( $fzip );
		}
	}
}

if ( ! function_exists( 'stm_upload_user_file' ) ) {
	function stm_upload_user_file( $file = array() ) {
		require_once ABSPATH . 'wp-admin/includes/admin.php';

		if ( ! function_exists( 'wp_handle_upload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$file_return = wp_handle_upload( $file, array( 'test_form' => false ) );

		if ( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
			return false;
		} else {
			$filename   = $file_return['file'];
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $file_return['url'],
			);

			$attachment_id = wp_insert_attachment( $attachment, $file_return['file'] );
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if ( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'stm_get_price_currency' ) ) {
	/**
	 * Get price currency
	 */
	function stm_get_price_currency() {
		$currency = apply_filters( 'motors_vl_get_nuxy_mod', '$', 'price_currency' );
		if ( isset( $_COOKIE['stm_current_currency'] ) ) {
			$cookie   = explode( '-', sanitize_text_field( $_COOKIE['stm_current_currency'] ) );
			$currency = $cookie[0];
		}

		return $currency;
	}
}

if ( ! function_exists( 'stm_generate_title_from_slugs' ) ) {
	function stm_generate_title_from_slugs( $title, $post_id, $show_labels = false ) {
		// turns this off if type is multilisting && custom settings disabled.
		if ( stm_is_multilisting() && $show_labels && get_post_type( $post_id ) !== apply_filters( 'stm_listings_post_type', 'listings' ) ) {
			$multilisting              = new STMMultiListing();
			$custom_inventory_settings = $multilisting->stm_get_listing_type_settings( 'inventory_custom_settings', get_post_type( $post_id ) );
			$custom_label_show         = $multilisting->stm_get_listing_type_settings( 'show_generated_title_as_label', get_post_type( $post_id ) );
			if ( false === $custom_inventory_settings || false === $custom_label_show ) {
				$show_labels = false;
			}
		}

		$title_from = apply_filters( 'motors_vl_get_nuxy_mod', '', 'listing_directory_title_frontend' );

		$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

		$title_return = '';

		if ( in_array( get_post_type( $post_id ), $post_types, true ) ) {

			if ( ! empty( $title_from ) && boolval( apply_filters( 'is_listing', array() ) ) || apply_filters( 'stm_is_car_dealer', false ) || apply_filters( 'stm_is_dealer_two', false ) || apply_filters( 'stm_is_aircrafts', false ) || apply_filters( 'stm_is_ev_dealer', false ) ) {
				$title         = apply_filters( 'stm_replace_curly_brackets', $title_from );
				$title_counter = 0;

				if ( ! empty( $title ) ) {
					foreach ( $title as $title_part ) {
						$title_counter ++;
						if ( 1 === $title_counter ) {
							if ( $show_labels ) {
								$title_return .= '<div class="labels">';
							}
						}

						$term = wp_get_post_terms( $post_id, strtolower( $title_part ), array( 'orderby' => 'none' ) );
						if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
							if ( ! empty( $term[0] ) ) {
								if ( ! empty( $term[0]->name ) ) {
									if ( 1 === $title_counter ) {
										$title_return .= $term[0]->name;
									} else {
										$title_return .= ' ' . $term[0]->name;
									}
								} else {
									$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
									if ( ! empty( $number_affix ) ) {
										$title_return .= ' ' . $number_affix . ' ';
									}
								}
							}
						} else {
							$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
							if ( ! empty( $number_affix ) ) {
								$title_return .= ' ' . $number_affix . ' ';
							}
						}
						if ( $show_labels && 2 === $title_counter ) {
							$title_return .= '</div>';
						}
					}
				}
			} elseif ( ! empty( $title_from ) && apply_filters( 'stm_is_boats', false ) ) {
				$title = apply_filters( 'stm_replace_curly_brackets', $title_from );

				if ( ! empty( $title ) ) {
					foreach ( $title as $title_part ) {
						$value = get_post_meta( $post_id, $title_part, true );
						if ( ! empty( $value ) ) {
							$cat = get_term_by( 'slug', $value, $title_part );
							if ( ! is_wp_error( $cat ) && ! empty( $cat->name ) ) {
								$title_return .= $cat->name . ' ';
							} else {
								$title_return .= $value . ' ';
							}
						}
					}
				}
			} elseif ( ! empty( $title_from ) && apply_filters( 'stm_is_motorcycle', false ) ) {
				$title = apply_filters( 'stm_replace_curly_brackets', $title_from );

				$title_counter = 0;

				if ( ! empty( $title ) ) {
					foreach ( $title as $title_part ) {
						$value = get_post_meta( $post_id, $title_part, true );
						$title_counter ++;

						if ( ! empty( $value ) ) {
							$cat = get_term_by( 'slug', $value, $title_part );
							if ( ! is_wp_error( $cat ) && ! empty( $cat->name ) ) {
								if ( 1 === $title_counter && $show_labels ) {
									$title_return .= '<span class="stm-label-title">';
								}
								$title_return .= $cat->name . ' ';
								if ( 1 === $title_counter && $show_labels ) {
									$title_return .= '</span>';
								}
							} else {
								if ( 1 === $title_counter && $show_labels ) {
									$title_return .= '<span class="stm-label-title">';
								}
								$title_return .= $value . ' ';
								if ( 1 === $title_counter && $show_labels ) {
									$title_return .= '</span>';
								}
							}
						}
					}
				}
			} elseif ( ! empty( $title_from ) && apply_filters( 'stm_is_listing_three', false ) ) {
				$title         = apply_filters( 'stm_replace_curly_brackets', $title_from );
				$title_counter = 0;

				if ( ! empty( $title ) ) {
					foreach ( $title as $title_part ) {
						$title_counter ++;
						if ( 1 === $title_counter ) {
							if ( $show_labels ) {
								$title_return .= '<div class="labels">';
							}
						}

						$term = wp_get_post_terms( $post_id, strtolower( $title_part ), array( 'orderby' => 'none' ) );
						if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
							if ( ! empty( $term[0] ) ) {
								if ( ! empty( $term[0]->name ) ) {
									if ( 1 === $title_counter ) {
										$title_return .= $term[0]->name;
									} else {
										$title_return .= ' ' . $term[0]->name;
									}
								} else {
									$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
									if ( ! empty( $number_affix ) ) {
										$title_return .= ' ' . $number_affix . ' ';
									}
								}
							}
						} else {
							$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
							if ( ! empty( $number_affix ) ) {
								$title_return .= ' ' . $number_affix . ' ';
							}
						}
						if ( $show_labels && 2 === $title_counter ) {
							$title_return .= '</div>';
						}
					}
				}
			} elseif ( ! empty( $title_from ) && apply_filters( 'stm_is_equipment', false ) ) {
				$title         = apply_filters( 'stm_replace_curly_brackets', $title_from );
				$title_counter = 0;

				if ( ! empty( $title ) ) {
					foreach ( $title as $title_part ) {
						$title_counter ++;
						if ( 1 === $title_counter ) {
							if ( $show_labels ) {
								$title_return .= '<div class="labels">';
							}
						}

						$term = wp_get_post_terms( $post_id, strtolower( $title_part ), array( 'orderby' => 'none' ) );

						if ( ! empty( $term ) && ! is_wp_error( $term ) ) {

							if ( ! empty( $term[0] ) ) {

								if ( ! empty( $term[0]->name ) ) {

									if ( 1 === $title_counter ) {
										$title_return .= $term[0]->name;
									} else {
										$title_return .= ' ' . $term[0]->name;
									}
								} else {
									$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
									if ( ! empty( $number_affix ) ) {
										$title_return .= ' ' . $number_affix . ' ';
									}
								}
							}
						} else {
							$number_affix = get_post_meta( $post_id, strtolower( $title_part ), true );
							if ( ! empty( $number_affix ) ) {
								$title_return .= ' ' . $number_affix . ' ';
							}
						}

						if ( $show_labels && 2 === $title_counter ) {
							$title_return .= '</div>';
						}
					}
				}
			}
		}

		if ( empty( $title_return ) ) {
			$title_return = get_the_title( $post_id );
		}

		return $title_return;
	}

	add_filter( 'generate_title_from_slugs', 'motors_generate_title_from_slugs', 10, 3 );
}

if ( ! function_exists( 'stm_check_if_car_imported' ) ) {
	function stm_check_if_car_imported( $id ) {
		$return = false;
		if ( ! empty( $id ) ) {
			$has_id = get_post_meta( $id, 'automanager_id', true );
			if ( ! empty( $has_id ) ) {
				$return = true;
			} else {
				$return = false;
			}
		}

		return $return;
	}
}

if ( ! function_exists( 'stm_get_car_medias' ) ) {
	function stm_get_car_medias( $default, $post_id = '' ) {
		if ( ! empty( $post_id ) ) {

			$image_limit = '';

			if ( apply_filters( 'stm_pricing_enabled', false ) ) {
				$user_added = get_post_meta( $post_id, 'stm_car_user', true );
				if ( ! empty( $user_added ) ) {
					$limits      = apply_filters(
						'stm_get_post_limits',
						array(
							'premoderation' => true,
							'posts_allowed' => 0,
							'posts'         => 0,
							'images'        => 0,
							'role'          => 'user',
						),
						$user_added
					);
					$image_limit = $limits['images'];
				}
			}
			$car_media = array();

			// Photo.
			$car_photos         = array();
			$car_gallery        = get_post_meta( $post_id, 'gallery', true );
			$car_videos_posters = get_post_meta( $post_id, 'gallery_videos_posters', true );

			if ( has_post_thumbnail( $post_id ) ) {
				$car_photos[] = wp_get_attachment_url( get_post_thumbnail_id( $post_id ) );
			}

			if ( ! empty( $car_gallery ) ) {
				$i = 0;
				foreach ( $car_gallery as $car_gallery_image ) {
					if ( empty( $image_limit ) ) {
						if ( wp_get_attachment_url( $car_gallery_image ) ) {
							$car_photos[] = wp_get_attachment_url( $car_gallery_image );
						}
					} else {
						$i ++;
						if ( $i < $image_limit ) {
							if ( wp_get_attachment_url( $car_gallery_image ) ) {
								$car_photos[] = wp_get_attachment_url( $car_gallery_image );
							}
						}
					}
				}
			}

			$car_photos = array_unique( $car_photos );

			$car_media['car_photos']       = $car_photos;
			$car_media['car_photos_count'] = count( $car_photos );

			// Video.
			$car_video      = array();
			$car_video_main = get_post_meta( $post_id, 'gallery_video', true );
			$car_videos     = get_post_meta( $post_id, 'gallery_videos', true );

			if ( ! empty( $car_video_main ) ) {
				$car_video[] = $car_video_main;
			}

			if ( ! empty( $car_videos ) ) {
				foreach ( $car_videos as $car_video_single ) {
					if ( ! empty( $car_video_single ) ) {
						$car_video[] = $car_video_single;
					}
				}
			}

			$car_media['car_videos']         = $car_video;
			$car_media['car_videos_posters'] = $car_videos_posters;
			$car_media['car_videos_count']   = count( $car_video );

			return $car_media;
		}
	}

	add_filter( 'stm_get_car_medias', 'stm_get_car_medias', 10, 2 );
}

if ( ! function_exists( 'stm_similar_cars' ) ) {
	function stm_similar_cars( $similar_taxonomies = array() ) {
		$tax_query = array();
		$taxes     = ( count( $similar_taxonomies ) === 0 ) ? apply_filters( 'motors_vl_get_nuxy_mod', '', 'stm_similar_query' ) : $similar_taxonomies;
		$query     = array(
			'post_type'      => apply_filters( 'stm_listings_post_type', 'listings' ),
			'post_status'    => 'publish',
			'posts_per_page' => '3',
			'post__not_in'   => array( get_the_ID() ),
		);

		if ( ! empty( $taxes ) ) {
			if ( count( $similar_taxonomies ) === 0 ) {
				$taxes = array_filter( array_map( 'trim', explode( ',', $taxes ) ) );
			}

			$attributes = stm_listings_attributes( array( 'key_by' => 'slug' ) );

			foreach ( $taxes as $tax ) {
				if ( ! isset( $attributes[ $tax ] ) || ! empty( $attributes[ $tax ]['numeric'] ) ) {
					continue;
				}

				$terms = get_the_terms( get_the_ID(), $tax );
				if ( ! is_array( $terms ) ) {
					continue;
				}

				$tax_query[] = array(
					'taxonomy' => esc_attr( $tax ),
					'field'    => 'slug',
					'terms'    => wp_list_pluck( $terms, 'slug' ),
				);
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query['tax_query'] = array( 'relation' => 'OR' ) + $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		return new WP_Query( apply_filters( 'stm_similar_cars_query', $query ) );
	}
}

if ( ! function_exists( 'stm_get_footer_terms' ) ) {
	function stm_get_footer_terms() {
		$taxonomies  = apply_filters( 'stm_get_footer_taxonomies', array() );
		$terms       = array();
		$terms_slugs = array();
		$tax_slug    = array();
		$tax_names   = array();

		if ( ! is_wp_error( $taxonomies ) ) {
			$defaults = array(
				'update_term_meta_cache' => false,
				'hide_empty'             => false,
			);

			foreach ( $taxonomies as $tax_key => $taxonomy ) {
				$_slug = $taxonomy['slug'];

				if ( ! empty( $_slug ) ) {
					if ( $tax_key < 2 ) {
						$tax_names[] = esc_html( $taxonomy['single_name'] );
					}

					$tmp_terms = get_terms(
						wp_parse_args(
							array( 'taxonomy' => $_slug ),
							$defaults
						)
					);

					if ( ! is_wp_error( $tmp_terms ) ) {
						foreach ( $tmp_terms as $tmp_term ) {
							if ( ! empty( $tmp_term->name ) ) {
								$terms[]       = $tmp_term->name;
								$terms_slugs[] = $tmp_term->slug;
								$tax_slug[]    = $_slug;
							}
						}
					}
				}
			}
		}

		$input_placeholder = sprintf(
			/* translators: %s name options */
			esc_html__( 'Enter %s', 'motors' ),
			implode( ' ' . esc_html__( 'or', 'motors' ) . ' ', $tax_names )
		);

		return apply_filters(
			'stm_get_footer_terms_response',
			array(
				'names'       => $terms,
				'slugs'       => $terms_slugs,
				'tax'         => $tax_slug,
				'placeholder' => $input_placeholder,
			)
		);
	}
}

if ( ! function_exists( 'stm_get_user_role' ) ) {
	function stm_get_user_role( $default, $user_id = null ) {
		$user_data = get_userdata( $user_id ? $user_id : get_current_user_id() );

		return ! empty( $user_data ) && in_array( 'stm_dealer', $user_data->roles, true );
	}

	add_filter( 'stm_get_user_role', 'stm_get_user_role', 10, 2 );
}

if ( ! function_exists( 'stm_account_navigation' ) ) {
	function stm_account_navigation() {
		$nav = array(
			'inventory' => array(
				'label' => esc_html__( 'My Inventory', 'motors' ),
				'url'   => apply_filters( 'stm_get_author_link', '' ),
				'icon'  => 'stm-service-icon-inventory',
			),
			'favourite' => array(
				'label' => esc_html__( 'My Favorites', 'motors' ),
				'url'   => add_query_arg( array( 'page' => 'favourite' ), apply_filters( 'stm_get_author_link', '' ) ),
				'icon'  => 'stm-service-icon-star-o',
			),
			'plans'     => array(
				'label' => esc_html__( 'My Plans', 'motors' ),
				'url'   => add_query_arg( array( 'page' => 'my-plans' ), apply_filters( 'stm_get_author_link', '' ) ),
				'icon'  => 'stm-service-icon-inventory',
			),
			'settings'  => array(
				'label' => esc_html__( 'Profile Settings', 'motors' ),
				'url'   => add_query_arg( array( 'page' => 'settings' ), apply_filters( 'stm_get_author_link', '' ) ),
				'icon'  => 'fa fa-cog',
			),
		);

		if ( ! stm_show_my_plans() ) {
			unset( $nav['plans'] );
		}

		return apply_filters( 'stm_account_navigation', $nav );
	}
}

function stm_send_cf7_message_to_user( $wpcf ) {
	if ( ! empty( $_POST['stm_changed_recepient'] ) ) {

		$mail = $wpcf->prop( 'mail' );

		$mail_to = get_the_author_meta( 'email', filter_var( $_POST['stm_changed_recepient'], FILTER_SANITIZE_NUMBER_INT ) );

		if ( ! empty( $mail_to ) ) {
			$mail['recipient'] = sanitize_email( $mail_to );
			$wpcf->set_properties( array( 'mail' => $mail ) );
		}
	}

	return $wpcf;
}

add_action( 'wpcf7_before_send_mail', 'stm_send_cf7_message_to_user', 8, 1 );

if ( ! function_exists( 'stm_force_favourites' ) ) {
	function stm_force_favourites( $user_id ) {
		$user_exist_fav = get_the_author_meta( 'stm_user_favourites', $user_id );
		if ( ! empty( $user_exist_fav ) ) {
			$user_exist_fav = explode( ',', $user_exist_fav );
		} else {
			$user_exist_fav = array();
		}

		if ( ! empty( $_COOKIE['stm_car_favourites'] ) ) {
			$cookie_fav = explode( ',', sanitize_text_field( $_COOKIE['stm_car_favourites'] ) );
			setcookie( 'stm_car_favourites', '', time() - 3600, '/' );
		} else {
			$cookie_fav = array();
		}

		if ( ! empty( $user_exist_fav ) || ! empty( $cookie_fav ) ) {
			$new_fav = implode( ',', array_unique( array_merge( $user_exist_fav, $cookie_fav ) ) );
			if ( ! empty( $new_fav ) ) {
				update_user_meta( $user_id, 'stm_user_favourites', $new_fav );
			}
		}
	}
}

if ( ! function_exists( 'stm_edit_delete_user_car' ) ) {
	function stm_edit_delete_user_car() {
		$demo = stm_is_site_demo_mode();
		if ( ! $demo ) {

			if ( isset( $_GET['stm_unmark_as_sold_car'] ) ) {
				update_post_meta( sanitize_text_field( $_GET['stm_unmark_as_sold_car'] ), 'car_mark_as_sold', '' );
			} elseif ( isset( $_GET['stm_mark_as_sold_car'] ) ) {
				update_post_meta( sanitize_text_field( $_GET['stm_mark_as_sold_car'] ), 'car_mark_as_sold', 'on' );
			}

			$featured_payment_enabled = apply_filters( 'motors_vl_get_nuxy_mod', false, 'dealer_payments_for_featured_listing' );

			$featured_listing_price = apply_filters( 'motors_vl_get_nuxy_mod', 0, 'featured_listing_price' );

			// multilisting compatibility.
			if ( stm_is_multilisting() ) {

				$post_type = ( isset( $_GET['stm_make_featured'] ) ) ? get_post_type( sanitize_text_field( $_GET['stm_make_featured'] ) ) : '';

				if ( apply_filters( 'stm_listings_post_type', 'listings' ) !== $post_type ) {

					$ml = new STMMultiListing();

					if ( $ml->stm_get_listing_type_settings( 'inventory_custom_settings', $post_type ) === true ) {

						$custom_dealer_ppl = $ml->stm_get_listing_type_settings( 'dealer_payments_for_featured_listing', $post_type );
						if ( ! empty( $custom_dealer_ppl ) ) {
							$featured_payment_enabled = $custom_dealer_ppl;
						}

						$custom_price = $ml->stm_get_listing_type_settings( 'featured_listing_price', $post_type );
						if ( ! empty( $custom_price ) ) {
							$featured_listing_price = $custom_price;
						}
					}
				}
			}

			if ( isset( $_GET['stm_make_featured'] ) && ! empty( $_GET['stm_make_featured'] ) && is_numeric( $_GET['stm_make_featured'] ) ) {
				if ( stm_is_woocommerce_activated() && $featured_payment_enabled ) {

					update_post_meta( sanitize_text_field( $_GET['stm_make_featured'] ), '_price', $featured_listing_price );
					update_post_meta( sanitize_text_field( $_GET['stm_make_featured'] ), 'car_make_featured', 'on' );

					$checkoutUrl = wc_get_checkout_url() . '?add-to-cart=' . sanitize_text_field( $_GET['stm_make_featured'] ) . '&make_featured=yes';

					wp_safe_redirect( $checkoutUrl );
					die();

				}
			}

			if ( ! empty( $_GET['stm_disable_user_car'] ) ) {
				$car = intval( $_GET['stm_disable_user_car'] );

				$author = get_post_meta( $car, 'stm_car_user', true );
				$user   = wp_get_current_user();

				if ( intval( $author ) === intval( $user->ID ) ) {
					$status = get_post_status( $car );
					if ( 'publish' === $status ) {
						$disabled_car = array(
							'ID'          => $car,
							'post_status' => 'draft',
						);

						if ( class_exists( '\MotorsVehiclesListing\Features\MultiplePlan' ) ) {
							\MotorsVehiclesListing\Features\MultiplePlan::updateListingStatus( $car, 'draft' );
						}

						wp_update_post( $disabled_car );
					}
				}
			}

			if ( ! empty( $_GET['stm_enable_user_car'] ) ) {
				$car = intval( $_GET['stm_enable_user_car'] );

				$author = get_post_meta( $car, 'stm_car_user', true );
				$user   = wp_get_current_user();

				if ( intval( $author ) === intval( $user->ID ) ) {
					$status = get_post_status( $car );
					if ( 'draft' === $status ) {
						$disabled_car = array(
							'ID'          => $car,
							'post_status' => 'publish',
						);

						$can_update = true;

						if ( apply_filters( 'stm_pricing_enabled', false ) ) {
							$user_limits = apply_filters(
								'stm_get_post_limits',
								array(
									'premoderation' => true,
									'posts_allowed' => 0,
									'posts'         => 0,
									'images'        => 0,
									'role'          => 'user',
								),
								$user->ID,
								'edit_delete'
							);

							if ( ! $user_limits['posts'] ) {
								$can_update = false;
							}
						}

						if ( $can_update ) {
							if ( class_exists( '\MotorsVehiclesListing\Features\MultiplePlan' ) ) {
								\MotorsVehiclesListing\Features\MultiplePlan::updateListingStatus( $car, 'active' );
							}
							wp_update_post( $disabled_car );
						} else {
							add_action( 'wp_enqueue_scripts', 'stm_user_out_of_limit' );
							function stm_user_out_of_limit() {
								$field_limit  = 'jQuery(document).ready(function(){';
								$field_limit .= 'jQuery(".stm-no-available-adds-overlay, .stm-no-available-adds").removeClass("hidden");';
								$field_limit .= 'jQuery(".stm-no-available-adds-overlay").on("click", function(){';
								$field_limit .= 'jQuery(".stm-no-available-adds-overlay, .stm-no-available-adds").addClass("hidden")';
								$field_limit .= '});';
								$field_limit .= '});';
								wp_add_inline_script( 'stm-theme-scripts', $field_limit );
							}
						}
					}
				}
			}

			if ( ! empty( $_GET['stm_move_trash_car'] ) ) {
				$car    = intval( $_GET['stm_move_trash_car'] );
				$author = get_post_meta( $car, 'stm_car_user', true );
				$user   = wp_get_current_user();

				if ( intval( $author ) === intval( $user->ID ) ) {
					if ( 'draft' === get_post_status( $car ) || 'pending' === get_post_status( $car ) ) {
						if ( class_exists( '\MotorsVehiclesListing\Features\MultiplePlan' ) ) {
							\MotorsVehiclesListing\Features\MultiplePlan::updateListingStatus( $car, 'trash' );
						}

						wp_trash_post( $car, false );

					}
				}
			}
		}
	}
}

add_action( 'wp', 'stm_edit_delete_user_car' );

if ( ! function_exists( 'stm_filter_display_name' ) ) {
	function stm_filter_display_name( $display_name, $user_id, $user_login = '', $f_name = '', $l_name = '' ) {
		$user = get_userdata( $user_id );

		if ( empty( $user_login ) ) {
			$login = ( ! empty( $user ) ) ? $user->get( 'user_login' ) : '';
		} else {
			$login = $user_login;
		}
		if ( empty( $f_name ) ) {
			$first_name = get_the_author_meta( 'first_name', $user_id );
		} else {
			$first_name = $f_name;
		}

		if ( empty( $l_name ) ) {
			$last_name = get_the_author_meta( 'last_name', $user_id );
		} else {
			$last_name = $l_name;
		}

		$display_name = $login;

		if ( ! empty( $first_name ) ) {
			$display_name = $first_name;
		}

		if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
			$display_name .= ' ' . $last_name;
		}

		if ( empty( $first_name ) && ! empty( $last_name ) ) {
			$display_name = $last_name;
		}

		if ( ! empty( $user ) && in_array( 'stm_dealer', $user->roles, true ) ) {
			$company_name = get_the_author_meta( 'stm_company_name', $user_id );
			if ( ! empty( $company_name ) ) {
				return ( $company_name );
			} else {
				return ( $display_name );
			}
		} else {
			return ( $display_name );
		}
	}

	add_filter( 'stm_filter_display_user_name', 'stm_filter_display_name', 20, 5 );
}

// Add car helpers.
/**
* TO DO - will be remove after active free plugin
**/
if ( ! function_exists( 'stm_get_dealer_marks' ) ) {
	function stm_get_dealer_marks( $dealer_id = '' ) {
		if ( ! empty( $dealer_id ) ) {
			$args = array(
				'post_type'      => 'dealer_review',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => 'stm_review_added_on',
						'value'   => intval( $dealer_id ),
						'compare' => '=',
					),
				),
			);

			$query = new WP_Query( $args );

			$ratings = array(
				'average'     => 0,
				'rate1'       => 0,
				'rate1_label' => apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Customer Service', 'motors' ), 'dealer_rate_1' ),
				'rate2'       => 0,
				'rate2_label' => apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Buying Process', 'motors' ), 'dealer_rate_2' ),
				'rate3'       => 0,
				'rate3_label' => apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Overall Experience', 'motors' ), 'dealer_rate_3' ),
				'likes'       => 0,
				'dislikes'    => 0,
				'count'       => 0,
			);

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$rate1           = get_post_meta( get_the_id(), 'stm_rate_1', true );
					$rate2           = get_post_meta( get_the_id(), 'stm_rate_2', true );
					$rate3           = get_post_meta( get_the_id(), 'stm_rate_3', true );
					$stm_recommended = get_post_meta( get_the_id(), 'stm_recommended', true );

					if ( ! empty( $rate1 ) ) {
						$ratings['rate1'] = intval( $ratings['rate1'] ) + intval( $rate1 );
					}
					if ( ! empty( $rate2 ) ) {
						$ratings['rate2'] = intval( $ratings['rate2'] ) + intval( $rate2 );
					}
					if ( ! empty( $rate1 ) ) {
						$ratings['rate3'] = intval( $ratings['rate3'] ) + intval( $rate3 );
					}

					if ( 'yes' === $stm_recommended ) {
						$ratings['likes'] ++;
					}

					if ( 'no' === $stm_recommended ) {
						$ratings['dislikes'] ++;
					}
				}
				$total            = $query->found_posts;
				$ratings['count'] = $total;

				$average_num = 0;

				if ( empty( $ratings['rate1_label'] ) ) {
					$ratings['rate1'] = 0;
				} else {
					$ratings['rate1'] = round( $ratings['rate1'] / $ratings['count'], 1 );

					$ratings['rate1_width'] = ( ( $ratings['rate1'] * 100 ) / 5 ) . '%';

					$ratings['average'] = $ratings['average'] + $ratings['rate1'];

					$average_num ++;
				}

				if ( empty( $ratings['rate2_label'] ) ) {
					$ratings['rate2'] = 0;
				} else {
					$ratings['rate2'] = round( $ratings['rate2'] / $ratings['count'], 1 );

					$ratings['rate2_width'] = ( ( $ratings['rate2'] * 100 ) / 5 ) . '%';

					$ratings['average'] = $ratings['average'] + $ratings['rate2'];

					$average_num ++;
				}

				if ( empty( $ratings['rate3_label'] ) ) {
					$ratings['rate3'] = 0;
				} else {
					$ratings['rate3'] = round( $ratings['rate3'] / $ratings['count'], 1 );

					$ratings['rate3_width'] = ( ( $ratings['rate3'] * 100 ) / 5 ) . '%';

					$ratings['average'] = $ratings['average'] + $ratings['rate3'];

					$average_num ++;
				}

				$ratings['average']       = number_format( round( $ratings['average'] / $average_num, 1 ), '1', '.', '' );
				$ratings['average_width'] = ( ( $ratings['average'] * 100 ) / 5 ) . '%';

				if ( empty( $ratings['rate1_label'] ) && empty( $ratings['rate2_label'] ) && empty( $ratings['rate3_label'] ) ) {
					$ratings['average'] = 0;
				}

				wp_reset_postdata();
			}

			return $ratings;
		}
	}
}

if ( ! function_exists( 'stm_dealer_gmap' ) ) {
	function stm_dealer_gmap( $lat, $lng ) {
		do_action( 'stm_google_places_script', 'enqueue', true );
		?>

		<div id="stm-dealer-gmap"></div>
		<?php //phpcs:disable ?>
		<script>
            jQuery(document).ready(function ($) {
                var center, map;

                function init() {
                    center = new google.maps.LatLng(<?php echo esc_js( $lat ); ?>, <?php echo esc_js( $lng ); ?>);
                    var mapOptions = {
                        zoom: 15,
                        center: center,
                        fullscreenControl: true,
                        scrollwheel: false
                    };
                    var mapElement = document.getElementById('stm-dealer-gmap');
                    map = new google.maps.Map(mapElement, mapOptions);
                    var marker = new google.maps.Marker({
                        position: center,
                        icon: '<?php echo wp_kses_post( get_stylesheet_directory_uri() ); ?>/assets/images/stm-map-marker-green.png',
                        map: map
                    });
                }

                $(window).on('resize', function () {
                    if (typeof map != 'undefined' && typeof center != 'undefined') {
                        setTimeout(function () {
                            map.setCenter(center);
                        }, 1000);
                    }
                });

                // initialize map
                document.body.addEventListener('stm_gmap_api_loaded', init, false);
            });
		</script>
		<?php //phpcs:enable ?>
		<?php
	}
}

if ( ! function_exists( 'stm_get_dealer_reviews' ) ) {
	function stm_get_dealer_reviews( $dealer_id = '', $per_page = 6, $offset = 0 ) {
		if ( ! empty( $dealer_id ) ) {
			$args = array(
				'post_type'      => 'dealer_review',
				'posts_per_page' => intval( $per_page ),
				'offset'         => intval( $offset ),
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'     => 'stm_review_added_on',
						'value'   => intval( $dealer_id ),
						'compare' => '=',
					),
				),
			);

			$query = new WP_Query( $args );

			return $query;
		}
	}
}

if ( ! function_exists( 'stm_get_user_reviews' ) ) {
	function stm_get_user_reviews( $dealer_id = '', $dealer_id_from = '' ) {
		if ( ! empty( $dealer_id ) && ! empty( $dealer_id_from ) ) {
			$args = array(
				'post_type'   => 'dealer_review',
				'post_status' => 'publish',
				'meta_query'  => array(
					array(
						'key'     => 'stm_review_added_by',
						'value'   => intval( $dealer_id ),
						'compare' => '=',
					),
					array(
						'key'     => 'stm_review_added_on',
						'value'   => intval( $dealer_id_from ),
						'compare' => '=',
					),
				),
			);

			$query = new WP_Query( $args );

			return $query;
		}
	}
}

if ( ! function_exists( 'stm_get_dealer_logo_placeholder' ) ) {
	function stm_get_dealer_logo_placeholder() {
		echo esc_url( get_stylesheet_directory_uri() . '/assets/images/empty_dealer_logo.png' );
	}
}

if ( ! function_exists( 'stm_delete_media' ) ) {
	function stm_delete_media( $media_id ) {
		$current_user = wp_get_current_user();
		$media_id     = intval( $media_id );
		if ( ! empty( $current_user->ID ) ) {
			$current_user_id = $current_user->ID;

			$args = array(
				'author'      => intval( $current_user_id ),
				'post_status' => 'any',
				'post__in'    => array( $media_id ),
				'post_type'   => 'attachment',
			);

			$query = new WP_Query( $args );

			if ( 1 === $query->found_posts ) {
				wp_delete_attachment( $media_id, true );
			}
		}
	}
}

if ( ! function_exists( 'stm_data_binding' ) ) {
	function stm_data_binding( $data = array(), $allowAll = false, $is_add_car = false ) {
		$attributes = apply_filters( 'stm_get_car_parent_exist', array() );

		$bind_tax = array();
		$depends  = array();
		foreach ( $attributes as $attr ) {

			$parent = $attr['listing_taxonomy_parent'];
			$slug   = $attr['slug'];

			$depends[] = array(
				'parent' => $parent,
				'dep'    => $slug,
			);

			if ( ! isset( $bind_tax[ $parent ] ) ) {
				$bind_tax[ $parent ] = array();
			}

			$bind_tax[ $slug ] = array(
				'dependency' => $parent,
				'allowAll'   => $allowAll,
				'options'    => array(),
			);

			/** @var WP_Term $term */

			foreach ( apply_filters( 'stm_get_category_by_slug_all', array(), $slug, $is_add_car, false ) as $term ) {
				$deps = array_values( array_filter( (array) get_term_meta( $term->term_id, 'stm_parent' ) ) );

				$bind_tax[ $slug ]['options'][] = array(
					'value' => $term->slug,
					'label' => $term->name,
					'count' => $term->count,
					'deps'  => $deps,
				);
			}
		}

		$sort_dependencies = array();
		$dependency_count  = count( $depends );
		for ( $q = 0; $q < $dependency_count; $q ++ ) {
			if ( 0 === $q ) {
				$sort_dependencies[] = $depends[ $q ]['parent'];
				$sort_dependencies[] = $depends[ $q ]['dep'];
			} else {
				if ( in_array( $depends[ $q ]['dep'], $sort_dependencies, true ) ) {
					array_splice( $sort_dependencies, array_search( $depends[ $q ]['dep'], $sort_dependencies, true ), 0, $depends[ $q ]['parent'] );
				} elseif ( in_array( $depends[ $q ]['parent'], $sort_dependencies, true ) ) {
					array_splice( $sort_dependencies, array_search( $depends[ $q ]['parent'], $sort_dependencies, true ) + 1, 0, $depends[ $q ]['dep'] );
				} elseif ( ! in_array( $depends[ $q ]['parent'], $sort_dependencies, true ) ) {
					array_splice( $sort_dependencies, 0, 0, $depends[ $q ]['parent'] );
					array_splice( $sort_dependencies, count( $sort_dependencies ), 0, $depends[ $q ]['dep'] );
				}
			}
		}

		$new_tax_bind = array();

		foreach ( $sort_dependencies as $val ) {
			$new_tax_bind[ $val ] = $bind_tax[ $val ];
		}

		return apply_filters( 'stm_data_binding', $new_tax_bind );
	}

	add_filter( 'stm_data_binding_func', 'stm_data_binding', 11, 3 );
}

if ( ! function_exists( 'stm_is_site_demo_mode' ) ) {
	function stm_is_site_demo_mode() {
		$site_demo_mode = apply_filters( 'stm_me_get_nuxy_mod', false, 'site_demo_mode' );

		return apply_filters( 'stm_site_demo_mode', $site_demo_mode );
	}
}


if ( ! function_exists( 'stm_payment_enabled' ) ) {
	function stm_payment_enabled() {
		$paypal_options = array(
			'enabled' => false,
		);

		$paypal_email    = apply_filters( 'motors_vl_get_nuxy_mod', '', 'paypal_email' );
		$paypal_currency = apply_filters( 'motors_vl_get_nuxy_mod', 'USD', 'paypal_currency' );
		$paypal_mode     = apply_filters( 'motors_vl_get_nuxy_mod', 'sandbox', 'paypal_mode' );
		$membership_cost = apply_filters( 'motors_vl_get_nuxy_mod', '', 'membership_cost' );

		if ( ! empty( $paypal_email ) && ! empty( $paypal_currency ) && ! empty( $paypal_mode ) && ! empty( $membership_cost ) ) {
			$paypal_options['enabled'] = true;
		}

		$paypal_options['email']    = $paypal_email;
		$paypal_options['currency'] = $paypal_currency;
		$paypal_options['mode']     = $paypal_mode;
		$paypal_options['price']    = $membership_cost;

		return $paypal_options;
	}
}

if ( ! function_exists( 'stm_generatePayment' ) ) {

	function stm_generatePayment() {
		$user = wp_get_current_user();

		if ( ! empty( $user->ID ) ) {

			$user_id = $user->ID;

			$return['result'] = true;

			$base = 'https://' . stm_paypal_url() . '/cgi-bin/webscr';

			$return_url = add_query_arg( array( 'become_dealer' => 1 ), apply_filters( 'stm_get_author_link', $user_id ) );

			$url_args = array(
				'cmd'           => '_xclick',
				'business'      => apply_filters( 'motors_vl_get_nuxy_mod', '', 'paypal_email' ),
				'item_name'     => $user->data->user_login,
				'item_number'   => $user_id,
				'amount'        => apply_filters( 'motors_vl_get_nuxy_mod', '', 'membership_cost' ),
				'no_shipping'   => '1',
				'no_note'       => '1',
				'currency_code' => apply_filters( 'motors_vl_get_nuxy_mod', 'USD', 'paypal_currency' ),
				'bn'            => 'PP%2dBuyNowBF',
				'charset'       => 'UTF%2d8',
				'invoice'       => $user_id,
				'return'        => $return_url,
				'rm'            => '2',
				'notify_url'    => home_url(),
			);

			$return = add_query_arg( $url_args, $base );
		}

		return $return;

	}
}
/**
 * TO DO will be removed
**/
if ( ! function_exists( 'stm_get_dealer_list_page' ) ) {
	function stm_get_dealer_list_page() {
		$dealer_list_page = apply_filters( 'motors_vl_get_nuxy_mod', 2173, 'dealer_list_page' );

		$dealer_list_page = apply_filters( 'stm_motors_wpml_is_page', $dealer_list_page );

		$link = get_permalink( $dealer_list_page );

		return $link;
	}

	add_filter( 'stm_get_dealer_list_page', 'stm_get_dealer_list_page' );
}

if ( ! function_exists( 'hma_default_layout_colors' ) ) {
	function hma_default_layout_colors( $template, $color ) {
		$colors = array(
			'car_dealer'               => array(
				'hma_background_color' => '#eaedf0',
				'hma_hover_bg_color'   => '#cc6119',
				'hma_hover_text_color' => '#ffffff',
			),
			'car_dealer_elementor'     => array(
				'hma_background_color' => '#eaedf0',
				'hma_hover_bg_color'   => '#cc6119',
				'hma_hover_text_color' => '#ffffff',
			),
			'car_dealer_elementor_rtl' => array(
				'hma_background_color' => '#eaedf0',
				'hma_hover_bg_color'   => '#cc6119',
				'hma_hover_text_color' => '#ffffff',
			),
			'listing'                  => array(
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_one_elementor'    => array(
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_two'              => array(
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_two_elementor'    => array(
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_three'            => array(
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_three_elementor' => array(
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_four'             => array(
				'hma_background_color' => '#eaedf0',
				'hma_hover_bg_color'   => '#cc6119',
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_four_elementor'   => array(
				'hma_background_color' => '#eaedf0',
				'hma_hover_bg_color'   => '#cc6119',
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_five'             => array(
				'hma_hover_bg_color'   => '#eb2321',
				'hma_hover_text_color' => '#ffffff',
			),
			'listing_five_elementor'   => array(
				'hma_hover_bg_color'   => '#eb2321',
				'hma_hover_text_color' => '#ffffff',
			),
			'motorcycle'               => array(
				'hma_background_color' => '#df1d1d',
				'hma_item_bg_color'    => '#df1d1d',
				'hma_hover_bg_color'   => '#df1d1d',
			),
		);
		return ! empty( $colors[ $template ][ $color ] ) ? $colors[ $template ][ $color ] : '';
	}
}


if ( ! function_exists( 'init_theme_motors_root_colors' ) ) {
	function init_theme_motors_root_colors() {
		$colors_css = '';

		$template                = get_option( 'stm_motors_chosen_template', 'dealer' );
		$default_base_color      = stm_get_default_color( $template, 'site_style_base_color' );
		$default_secondary_color = stm_get_default_color( $template, 'site_style_secondary_color' );
		$base_color              = stm_get_theme_color( 'site_style_base_color' );
		$secondary_color         = stm_get_theme_color( 'site_style_secondary_color' );

		$hma_background_color      = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_background_color' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_background_color' ) : hma_default_layout_colors( $template, 'hma_background_color' );
		$hma_item_bg_color         = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_item_bg_color' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_item_bg_color' ) : hma_default_layout_colors( $template, 'hma_item_bg_color' );
		$hma_hover_bg_color        = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_bg_color' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_bg_color' ) : hma_default_layout_colors( $template, 'hma_hover_bg_color' );
		$hma_hover_active_bg_color = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_active_bg_color' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_active_bg_color' ) : hma_default_layout_colors( $template, 'hma_hover_active_bg_color' );
		$hma_hover_text_color      = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_text_color' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_text_color' ) : hma_default_layout_colors( $template, 'hma_hover_text_color' );
		$hma_underline             = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_underline' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_underline' ) : hma_default_layout_colors( $template, 'hma_underline' );
		$hma_underline_2           = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_underline_2' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_underline_2' ) : hma_default_layout_colors( $template, 'hma_underline_2' );
		$hma_hover_underline       = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_underline' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_underline' ) : hma_default_layout_colors( $template, 'hma_hover_underline' );
		$hma_hover_underline_2     = ( ! empty( apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_underline_2' ) ) ) ? apply_filters( 'stm_me_get_nuxy_mod', '', 'hma_hover_underline_2' ) : hma_default_layout_colors( $template, 'hma_hover_underline_2' );

			$colors_css .= '
			:root{
				--motors-default-base-color: ' . $default_base_color . ';
				--motors-default-secondary-color: ' . $default_secondary_color . ';
				--motors-base-color: ' . $base_color . ';
				--motors-secondary-color: ' . $secondary_color . ';
				--hma_background_color: ' . $hma_background_color . ';
				--hma_item_bg_color: ' . $hma_item_bg_color . ';
				--hma_hover_bg_color:' . $hma_hover_bg_color . ';
				--hma_hover_active_bg_color: ' . $hma_hover_active_bg_color . ';
				--hma_hover_text_color: ' . $hma_hover_text_color . ';
				--hma_underline: ' . $hma_underline . ';
				--hma_underline_2: ' . $hma_underline_2 . ';
				--hma_hover_underline: ' . $hma_hover_underline . ';
				--hma_hover_underline_2: ' . $hma_hover_underline_2 . ';
			}
		';

		wp_add_inline_style( 'stm-theme-style', $colors_css );
	}
}

add_action( 'wp_enqueue_scripts', 'init_theme_motors_root_colors' );

// Add user custom color styles.
if ( ! function_exists( 'stm_print_styles_color' ) ) {
	function stm_print_styles_color() {
		$css          = '';
		$css_listing  = '';
		$css_magazine = '';

		$layout               = stm_get_current_layout();
		$site_color_style     = apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' );
		$custom_colors_helper = new STM_Custom_Colors_Helper();

		$predefined_colors = array(
			'ev_dealer'                => array(
				'site_style_blue' => array(
					'primary'   => '#0d46ff',
					'secondary' => '#102127',
				),
			),
			'dealer'                   => array(
				'site_style_blue'       => array(
					'primary'   => '#7c9fda',
					'secondary' => '#dd8411',
				),
				'site_style_light_blue' => array(
					'primary'   => '#2ea6b8',
					'secondary' => '#2ea6b8',
				),
				'site_style_orange'     => array(
					'primary'   => '#58ba3a',
					'secondary' => '#58ba3a',
				),
				'site_style_red'        => array(
					'primary'   => '#e41515',
					'secondary' => '#e41515',
				),
				'site_style_yellow'     => array(
					'primary'   => '#ecbf24',
					'secondary' => '#22b7d2',
				),
			),
			'listing_four'             => array(
				'site_style_blue'       => array(
					'primary'           => '#7c9fda',
					'secondary'         => '#dd8411',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_light_blue' => array(
					'primary'           => '#2ea6b8',
					'secondary'         => '#2ea6b8',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_orange'     => array(
					'primary'           => '#58ba3a',
					'secondary'         => '#58ba3a',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_red'        => array(
					'primary'           => '#e41515',
					'secondary'         => '#e41515',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_yellow'     => array(
					'primary'           => '#ecbf24',
					'secondary'         => '#22b7d2',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
			),
			'listing_four_elementor'   => array(
				'site_style_blue'       => array(
					'primary'           => '#7c9fda',
					'secondary'         => '#dd8411',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_light_blue' => array(
					'primary'           => '#2ea6b8',
					'secondary'         => '#2ea6b8',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_orange'     => array(
					'primary'           => '#58ba3a',
					'secondary'         => '#58ba3a',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_red'        => array(
					'primary'           => '#e41515',
					'secondary'         => '#e41515',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
				'site_style_yellow'     => array(
					'primary'           => '#ecbf24',
					'secondary'         => '#22b7d2',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24',
				),
			),
			'classified'               => array(
				'site_style_blue'       => array(
					'primary'           => '#7c9fda', /*light blue*/
					'secondary'         => '#7c9fda',
					'primary_listing'   => '#7c9fda',
					'secondary_listing' => '#121e24', /*Dark one*/
				),
				'site_style_light_blue' => array(
					'primary'           => '#2ea6b8',
					'secondary'         => '#2ea6b8',
					'primary_listing'   => '#2ea6b8',
					'secondary_listing' => '#1d2428',
				),
				'site_style_orange'     => array(
					'primary'           => '#2d8611',
					'secondary'         => '#2d8611',
					'primary_listing'   => '#2d8611',
					'secondary_listing' => '#202a30',
				),
				'site_style_red'        => array(
					'primary'           => '#e41515',
					'secondary'         => '#e41515',
					'primary_listing'   => '#e41515',
					'secondary_listing' => '#333',
				),
				'site_style_yellow'     => array(
					'primary'           => '#ecbf24',
					'secondary'         => '#22b7d2',
					'primary_listing'   => '#ecbf24',
					'secondary_listing' => '#333',
				),
			),
			'boats'                    => array(
				'site_style_blue'       => array(
					'primary'   => '#31a3c6',
					'secondary' => '#ffa07a',
					'third'     => '#211133',
				),
				'site_style_light_blue' => array(
					'primary'   => '#31a3c6',
					'secondary' => '#21d99b',
					'third'     => '#004015',
				),
				'site_style_orange'     => array(
					'primary'   => '#31a3c6',
					'secondary' => '#58ba3a',
					'third'     => '#102d40',
				),
				'site_style_red'        => array(
					'primary'   => '#31a3c6',
					'secondary' => '#e41515',
					'third'     => '#232628',
				),
			),
			'magazine'                 => array(
				'site_style_blue' => array(
					'primary'   => '#18ca3e',
					'secondary' => '#3c98ff',
					'third'     => '#ff1b1b',
				),
			),
			'dealer_two'               => array(
				'site_style_blue' => array(
					'primary'   => '#4971ff',
					'secondary' => '#ffb129',
					'third'     => '#ffb100',
					'four'      => '#3350b8',
				),
			),
			'car_dealer_two_elementor' => array(
				'site_style_blue' => array(
					'primary'   => '#4971ff',
					'secondary' => '#ffb129',
					'third'     => '#ffb100',
					'four'      => '#3350b8',
				),
			),
			'listing_two'              => array(
				'site_style_blue' => array(
					'primary'   => '#269aff',
					'secondary' => '#1e7bcc',
					'third'     => '#2289e2',
					'four'      => '#2289e2',
				),
			),
			'listing_two_elementor'    => array(
				'site_style_blue' => array(
					'primary'   => '#269aff',
					'secondary' => '#1e7bcc',
					'third'     => '#2289e2',
					'four'      => '#2289e2',
				),
			),
			'listing_three'            => array(
				'site_style_blue' => array(
					'primary'   => '#ff9500',
					'secondary' => '#cc7700',
					'third'     => '#2289e2',
					'four'      => '#2289e2',
				),
			),
			'listing_three_elementor'  => array(
				'site_style_blue' => array(
					'primary'   => '#ff9500',
					'secondary' => '#cc7700',
					'third'     => '#2289e2',
					'four'      => '#2289e2',
				),
			),
			'auto_parts'               => array(
				'site_style_blue' => array(
					'primary'   => '#cc6119',
					'secondary' => '#6c98e1',
					'third'     => '#cc6119',
					'four'      => '#cc6119',
				),
			),
			'aircrafts'                => array(
				'site_style_blue' => array(
					'primary'   => '#6c98e1',
					'secondary' => '#cc6119',
					'third'     => '#4c94fa',
					'four'      => '#ff9420',
				),
			),
			'rental_two'               => array(
				'site_style_blue' => array(
					'primary'   => '#6c98e1',
					'secondary' => '#cc6119',
					'third'     => '#1e81f6',
					'four'      => '#0e56ab',
				),
			),
			'equipment'                => array(
				'site_style_blue' => array(
					'primary'   => '#6c98e1',
					'secondary' => '#cc6119',
					'third'     => '#1e81f6',
					'four'      => '#0e56ab',
				),
			),

		);

		if ( 'site_style_default' !== $site_color_style ) {

			$colors_differences = false;
			$colors_arr         = array();

			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$theme_path    = get_template_directory_uri() . '/assets/';
			$css_directory = get_template_directory() . '/assets/css/dist/';

			/*Motorcycle*/
			if ( 'motorcycle' === $layout ) {
				$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'motorcycle/app.css' );

				$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

				$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#df1d1d', 'site_style_base_color' );
				$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#2f3c40', 'site_style_secondary_color' );

				$colors_arr[] = $base_color;
				$colors_arr[] = $secondary_color;

				$custom_style_css = str_replace(
					array(
						'#df1d1d', // 1
						'#2f3c40', // 2
						'#243136', // 3
						'#1d282c', // 4
						'#272e36', // 5
						'#27829e',
						'#1b92a8',
						'36,49,54',
						'36, 49, 54',
						'../../../',
						'../../',
						'#b11313',
						'#d11717',
						'#b01b1c',
						'#1bc744',
					),
					array(
						$base_color, // 1
						$secondary_color, // 2
						$secondary_color, // 3
						'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)', // 4
						$secondary_color, // 5
						'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
						'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
						stm_hex2rgb( $secondary_color ),
						stm_hex2rgb( $secondary_color ),
						$theme_path,
						$theme_path,
						'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
						'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
						$base_color, // 1
						$base_color, // 1
					),
					$custom_style_css
				);

				$css .= $custom_style_css;
			} else {

				if ( 'boats' !== $layout ) {

					/*Rental*/
					$custom_style_css = '';
					if ( apply_filters( 'stm_is_rental', false ) ) {
						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'rental/app.css' );
					}
					$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#f0c540', 'site_style_base_color' );
					$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#2a4045', 'site_style_secondary_color' );
					if ( apply_filters( 'stm_is_rental_one_elementor', false ) ) {
						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'app-car_rental_elementor.css' );
					}

					$colors_arr[] = $base_color;
					$colors_arr[] = $secondary_color;

					$custom_style_css = str_replace(
						array(
							'#f0c540',
							'#2a4045',
							'../../../',
							'../../',
						),
						array(
							$base_color,
							$secondary_color,
							$theme_path,
							$theme_path,
						),
						$custom_style_css
					);

					$css .= $custom_style_css;

					/*Dealer*/
					$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'app.css' );

					$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

					if ( 'site_style_custom' === $site_color_style ) {
						$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#183650', 'site_style_base_color' );
						$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#34ccff', 'site_style_secondary_color' );
					} else {
						$base_color      = $predefined_colors['dealer'][ $site_color_style ]['primary'];
						$secondary_color = $predefined_colors['dealer'][ $site_color_style ]['secondary'];
					}

					$colors_arr[] = $base_color;
					$colors_arr[] = $secondary_color;

					$custom_style_css = str_replace(
						array(
							'#cc6119',
							'#6c98e1',
							'#567ab4',
							'#6c98e1',
							'#1b92a8',
							'204, 97, 25',
							'#ecbf24',
							'#22b7d2',
							'../../../',
							'../../',
							'#1bc744',
						),
						array(
							$base_color,
							$secondary_color,
							'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
							'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
							'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
							stm_hex2rgb( $base_color ),
							$base_color,
							$secondary_color,
							$theme_path,
							$theme_path,
							$base_color,
						),
						$custom_style_css
					);

					$css .= $custom_style_css;

					/*Listing Four*/
					if ( apply_filters( 'stm_is_listing_four', false ) ) {
						$custom_style_css  = $wp_filesystem->get_contents( $css_directory . 'app.css' );
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();
						if ( apply_filters( 'stm_is_listing_four_elementor', false ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_four_elementor.css' );
						} else {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'listing_four/app.css' );
						}

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#183650', 'site_style_base_color' );
							$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#34ccff', 'site_style_secondary_color' );
							$third_color     = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors['listing_four']['site_style_blue']['primary_listing'], 'site_style_base_color_listing' );
							$four_color      = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors['listing_four']['site_style_blue']['secondary_listing'], 'site_style_secondary_color_listing' );
						} else {
							$base_color      = $predefined_colors['dealer'][ $site_color_style ]['primary'];
							$secondary_color = $predefined_colors['dealer'][ $site_color_style ]['secondary'];
							$third_color     = $predefined_colors['listing_four'][ $site_color_style ]['primary_listing'];
							$four_color      = $predefined_colors['listing_four'][ $site_color_style ]['secondary_listing'];
						}

						$colors_arr[] = $base_color;
						$colors_arr[] = $secondary_color;

						$custom_style_css = str_replace(
							array(
								'#cc6119',
								'#6c98e1',
								'#567ab4',
								'#6c98e1',
								'#1b92a8',
								'204, 97, 25',
								'#ecbf24',
								'#22b7d2',
								'../../../',
								'../../',
								'#1bc744',
								'#6c98e2',
								'#4e90ce',
							),
							array(
								$base_color,
								$secondary_color,
								'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
								'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.75)',
								'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
								stm_hex2rgb( $base_color ),
								$base_color,
								$secondary_color,
								$theme_path,
								$theme_path,
								$base_color,
								$third_color,
								$four_color,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					/*Listing Five*/
					if ( apply_filters( 'stm_is_listing_five', false ) ) {
						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'app.css' );

						if ( apply_filters( 'stm_is_listing_five_elementor', false ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_five_elementor.css' );
						} else {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_five.css' );
						}

						if ( defined( 'ULISTING_VERSION' ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_five_ulisting.css' );
						}

						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#183650', 'site_style_base_color' );
							$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#34ccff', 'site_style_secondary_color' );
						} else {
							$base_color      = $predefined_colors['dealer'][ $site_color_style ]['primary'];
							$secondary_color = $predefined_colors['dealer'][ $site_color_style ]['secondary'];
						}

						$colors_arr[] = $base_color;
						$colors_arr[] = $secondary_color;

						$custom_style_css = str_replace(
							array(
								'#eb232c',
								'#ff2325',
								'#cc6119',
								'#6c98e1',
								'#153e4d',
								'#1bc744',
								'204, 97, 25',
								'../../../',
								'../../',
							),
							array(
								$base_color,
								$base_color,
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								stm_hex2rgb( $base_color ),
								$theme_path,
								$theme_path,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					/*Listing Six*/
					if ( apply_filters( 'stm_is_listing_six', false ) ) {
						$custom_style_css  = $wp_filesystem->get_contents( $css_directory . 'app.css' );
						$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_six.css' );
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#2c6dff', 'site_style_base_color' );
							$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#2c6dff', 'site_style_secondary_color' );
						} else {
							$base_color      = $predefined_colors['dealer'][ $site_color_style ]['primary'];
							$secondary_color = $predefined_colors['dealer'][ $site_color_style ]['secondary'];
						}

						$colors_arr[] = $base_color;
						$colors_arr[] = $secondary_color;

						$custom_style_css = str_replace(
							array(
								'#eb232c',
								'#2c6dff',
								'#ff2325',
								'#cc6119',
								'#6c98e1',
								'#153e4d',
								'#1bc744',
								'204, 97, 25',
								'../../../',
								'../../',
							),
							array(
								$base_color,
								$base_color,
								$base_color,
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								stm_hex2rgb( $base_color ),
								$theme_path,
								$theme_path,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					/* Electric Vehicle Dealership */
					if ( apply_filters( 'stm_is_ev_dealer', false ) ) {
						$custom_style_css  = $wp_filesystem->get_contents( $css_directory . 'app.css' );
						$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-ev_dealer.css' );
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#0d46ff', 'site_style_base_color' );
							$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#102127', 'site_style_secondary_color' );
						} else {
							$base_color      = $predefined_colors['ev_dealer']['site_style_blue']['primary'];
							$secondary_color = $predefined_colors['ev_dealer']['site_style_blue']['secondary'];
						}

						$colors_arr[] = $base_color;
						$colors_arr[] = $secondary_color;

						$custom_style_css = str_replace(
							array(
								'#0d46ff',
								'#2c6dff',
								'#ff2325',
								'#cc6119',
								'#102127',
								'#153e4d',
								'#1bc744',
								'204, 97, 25',
								'../../../',
								'../../',
								'#6c98e1',
							),
							array(
								$base_color,
								$base_color,
								$base_color,
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								stm_hex2rgb( $base_color ),
								$theme_path,
								$theme_path,
								$base_color,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					/*Listing*/
					if ( apply_filters( 'stm_is_listing', false ) ) {
						$custom_style_css  = ( apply_filters( 'stm_is_elementor_demo', false ) ) ? $wp_filesystem->get_contents( $css_directory . 'app-listing_one_elementor.css' ) : $wp_filesystem->get_contents( $css_directory . 'listing/app.css' );
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color              = apply_filters( 'stm_me_get_nuxy_mod', '#1bc744', 'site_style_base_color' );
							$secondary_color         = apply_filters( 'stm_me_get_nuxy_mod', '#153e4d', 'site_style_secondary_color' );
							$base_color_listing      = apply_filters( 'stm_me_get_nuxy_mod', '#1bc744', 'site_style_base_color_listing' );
							$secondary_color_listing = apply_filters( 'stm_me_get_nuxy_mod', '#153e4d', 'site_style_secondary_color_listing' );
						} else {
							$base_color              = $predefined_colors['classified'][ $site_color_style ]['primary'];
							$secondary_color         = $predefined_colors['classified'][ $site_color_style ]['secondary'];
							$base_color_listing      = $predefined_colors['classified'][ $site_color_style ]['primary_listing'];
							$secondary_color_listing = $predefined_colors['classified'][ $site_color_style ]['secondary_listing'];
						}

						$colors_arr[] = $base_color_listing;
						$colors_arr[] = $secondary_color_listing;

						$custom_style_css = str_replace(
							array(
								'#1bc744',
								'#11323e',
								'#153e4d',
								'#cc6119',
								'#169f36',
								'#4e90cc',
								'51,51,51,0.9',
								'../../../',
								'../../',
								'#32cd57',
								'#19b33e',
								'#609bd1',
								'#4782b8',
								'27, 199, 68',
								'#133340',
								'#6c98e2',
								'#6c98e1',
								'#4e90ce',
							),
							array(
								$base_color_listing,
								'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 0.9)',
								$secondary_color_listing,
								$base_color_listing,
								'rgba(' . stm_hex2rgb( $base_color_listing ) . ', 0.75)',
								$base_color,
								stm_hex2rgb( $secondary_color_listing ) . ',0.8',
								$theme_path,
								$theme_path,
								$base_color_listing,
								$base_color_listing,
								'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 1)',
								'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 0.8)',
								stm_hex2rgb( $base_color_listing ),
								'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 1)',
								$secondary_color_listing,
								$secondary_color_listing,
								'rgba(' . stm_hex2rgb( $secondary_color_listing ) . ', 0.8)',
							),
							$custom_style_css
						);

						$css_listing .= $custom_style_css;
						$css         .= $css_listing;
					}

					/*Magazine*/
					if ( apply_filters( 'stm_is_magazine', false ) ) {
						$l = 'magazine';

						$custom_style_css  = $wp_filesystem->get_contents( $css_directory . 'app.css' );
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();
						$custom_style_css .= $wp_filesystem->get_contents( $css_directory . $l . '/app.css' );

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color      = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['primary'], 'site_style_base_color_listing' );
							$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['secondary'], 'site_style_secondary_color_listing' );
						} else {
							$base_color      = $predefined_colors[ $l ]['site_style_blue']['primary'];
							$secondary_color = $predefined_colors[ $l ]['site_style_blue']['secondary'];
						}

						$colors_arr[] = $base_color;
						$colors_arr[] = $secondary_color;

						$custom_style_css = str_replace(
							array(
								'#cc6119',
								'#6c98e1',
								$predefined_colors[ $l ]['site_style_blue']['primary'],
								$predefined_colors[ $l ]['site_style_blue']['secondary'],
								'../../../',
								'../../',
								'#1bc744',
								'rgba(60, 152, 255, 0.7)',
							),
							array(
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								$theme_path,
								$theme_path,
								$base_color,
								str_replace( '1)', '0.7)', $secondary_color ),
							),
							$custom_style_css
						);

						$css_magazine .= $custom_style_css;
						$css           = $css_magazine;
					}

					/*Dealler Two*/
					if ( apply_filters( 'stm_is_dealer_two', false ) || boolval( apply_filters( 'is_listing', array( 'listing_two', 'listing_two_elementor', 'listing_three', 'listing_three_elementor' ) ) ) ) {
						$l = ( 'car_dealer_two' === get_option( 'stm_motors_chosen_template' ) ) ? 'dealer_two' : get_option( 'stm_motors_chosen_template' );

						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'app.css' );
						if ( boolval( apply_filters( 'is_listing', array( 'listing_two', 'listing_three' ) ) ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'listing/app.css' );
						}
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						if ( boolval( apply_filters( 'is_listing', array( 'listing_two_elementor' ) ) ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_two_elementor.css' );
						} elseif ( boolval( apply_filters( 'is_listing', array( 'listing_three_elementor' ) ) ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-listing_three_elementor.css' );
						} elseif ( apply_filters( 'stm_is_dealer_two', false ) && apply_filters( 'stm_is_elementor_demo', false ) ) {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-car_dealer_two_elementor.css' );
						} else {
							$custom_style_css .= $wp_filesystem->get_contents( $css_directory . $l . '/app.css' );
						}

						if ( 'site_style_custom' === $site_color_style ) {
							$base_color      = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['primary'], 'site_style_base_color' );
							$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['secondary'], 'site_style_secondary_color' );
							$third_color     = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['third'], 'site_style_base_color_listing' );
							$four_color      = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['four'], 'site_style_secondary_color_listing' );
						} else {
							$base_color      = $predefined_colors[ $l ]['site_style_blue']['primary'];
							$secondary_color = $predefined_colors[ $l ]['site_style_blue']['secondary'];
							$third_color     = $predefined_colors[ $l ]['site_style_blue']['third'];
							$four_color      = $predefined_colors[ $l ]['site_style_blue']['four'];
						}

						$custom_style_css = str_replace(
							array(
								'#6c98e1',
								'#cc6119',
								'#1bc744',
								'#169f36',
								'#567ab4',
								'#3c98ff',
								'#18ca3e',
								'#6c98e2',
								'#4e90ce',
								'27, 199, 68, 0.85',
								$predefined_colors[ $l ]['site_style_blue']['primary'],
								$predefined_colors[ $l ]['site_style_blue']['secondary'],
								$predefined_colors[ $l ]['site_style_blue']['third'],
								$predefined_colors[ $l ]['site_style_blue']['four'],
								'../../../',
								'../../',
							),
							array(
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								$secondary_color,
								$base_color,
								$third_color,
								$third_color,
								$four_color,
								stm_hex2rgb( $secondary_color ) . ',0.8',
								$base_color,
								$secondary_color,
								$third_color,
								$four_color,
								$theme_path,
								$theme_path,
							),
							$custom_style_css
						);

						$css_magazine = $custom_style_css;
						$css          = $css_magazine;
					}

					/*Auto Parts*/
					if ( apply_filters( 'stm_is_auto_parts', false ) ) {
						$l = 'auto_parts';

						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'auto-parts/app.css' );

						$base_color      = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['primary'], 'site_style_base_color' ) : $predefined_colors[ $l ]['site_style_blue']['primary'];
						$secondary_color = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['secondary'], 'site_style_secondary_color' ) : $predefined_colors[ $l ]['site_style_blue']['secondary'];
						$third_color     = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['third'], 'site_style_base_color_listing' ) : $predefined_colors[ $l ]['site_style_blue']['third'];
						$four_color      = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['four'], 'site_style_secondary_color_listing' ) : $predefined_colors[ $l ]['site_style_blue']['four'];

						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						$custom_style_css = str_replace(
							array(
								'#cc6119',
								'#6c98e1',
								'#1bc744',
								'#169f36',
								'#567ab4',
								'#3c98ff',
								'#18ca3e',
								'27, 199, 68, 0.85',
								$predefined_colors[ $l ]['site_style_blue']['primary'],
								$predefined_colors[ $l ]['site_style_blue']['secondary'],
								$predefined_colors[ $l ]['site_style_blue']['third'],
								$predefined_colors[ $l ]['site_style_blue']['four'],
								'../../../',
								'../../',
							),
							array(
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								stm_hex2rgb( $secondary_color ) . ',0.8',
								$base_color,
								$secondary_color,
								$third_color,
								$four_color,
								$theme_path,
								$theme_path,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					/*Aircrafts*/
					if ( apply_filters( 'stm_is_aircrafts', false ) ) {
						$l = 'aircrafts';

						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'app.css' );

						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-aircrafts.css' );

						$base_color      = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['primary'], 'site_style_base_color' ) : $predefined_colors[ $l ]['site_style_blue']['primary'];
						$secondary_color = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['secondary'], 'site_style_secondary_color' ) : $predefined_colors[ $l ]['site_style_blue']['secondary'];
						$third_color     = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['third'], 'site_style_base_color_listing' ) : $predefined_colors[ $l ]['site_style_blue']['third'];
						$four_color      = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['four'], 'site_style_secondary_color_listing' ) : $predefined_colors[ $l ]['site_style_blue']['four'];

						$custom_style_css = str_replace(
							array(
								'#6c98e1',
								'#cc6119',
								'#1bc744',
								'#169f36',
								'#567ab4',
								'#3c98ff',
								'#18ca3e',
								'27, 199, 68, 0.85',
								$predefined_colors[ $l ]['site_style_blue']['primary'],
								$predefined_colors[ $l ]['site_style_blue']['secondary'],
								$predefined_colors[ $l ]['site_style_blue']['third'],
								$predefined_colors[ $l ]['site_style_blue']['four'],
								'../../../',
								'../../',
								'#cc6119',
							),
							array(
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								$third_color,
								$base_color,
								$secondary_color,
								stm_hex2rgb( $secondary_color ) . ',0.8',
								$base_color,
								$secondary_color,
								$third_color,
								$four_color,
								$theme_path,
								$theme_path,
								$secondary_color,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					if ( apply_filters( 'stm_is_rental_two', false ) ) {
						$l = 'rental_two';

						$custom_style_css  = $wp_filesystem->get_contents( $css_directory . 'app.css' );
						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();
						$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-rental_two.css' );

						$base_color      = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['primary'], 'site_style_base_color' ) : $predefined_colors[ $l ]['site_style_blue']['primary'];
						$secondary_color = ( 'site_style_custom' === $site_color_style ) ? apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['secondary'], 'site_style_secondary_color' ) : $predefined_colors[ $l ]['site_style_blue']['secondary'];

						$custom_style_css = str_replace(
							array(
								'#6c98e1',
								'#cc6119',
								'#1e81f6',
								'#0e56ab',
								'27, 199, 68, 0.85',
								'../../../',
								'../../',
							),
							array(
								$base_color,
								$secondary_color,
								$base_color,
								$secondary_color,
								stm_hex2rgb( $secondary_color ) . ',0.8',
								$theme_path,
								$theme_path,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}

					if ( apply_filters( 'stm_is_equipment', false ) && defined( 'STM_MOTORS_EQUIPMENT_PATH' ) ) {
						$l = 'equipment';

						$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'app.css' );

						$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

						$custom_style_css .= $wp_filesystem->get_contents( STM_MOTORS_EQUIPMENT_PATH . '/assets/css/vc_ss/stm_equip_category_grid_filter.css' );
						$custom_style_css .= $wp_filesystem->get_contents( STM_MOTORS_EQUIPMENT_PATH . '/assets/css/vc_ss/stm_equip_contact_info.css' );
						$custom_style_css .= $wp_filesystem->get_contents( STM_MOTORS_EQUIPMENT_PATH . '/assets/css/vc_ss/stm_equip_featured.css' );
						$custom_style_css .= $wp_filesystem->get_contents( STM_MOTORS_EQUIPMENT_PATH . '/assets/css/vc_ss/stm_equip_inventory.css' );
						$custom_style_css .= $wp_filesystem->get_contents( STM_MOTORS_EQUIPMENT_PATH . '/assets/css/vc_ss/stm_equip_search.css' );

						$custom_style_css .= $wp_filesystem->get_contents( $css_directory . 'app-equipment.css' );

						$base_color      = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['primary'], 'site_style_base_color' );
						$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', $predefined_colors[ $l ]['site_style_blue']['secondary'], 'site_style_secondary_color' );

						$custom_style_css = str_replace(
							array(
								'#6c98e1',
								'#cc6119',
								'#1bc744',
								'27, 199, 68, 0.85',
								'../../../',
								'../../',
							),
							array(
								$base_color,
								$secondary_color,
								$secondary_color,
								stm_hex2rgb( $secondary_color ) . ',0.8',
								$theme_path,
								$theme_path,
							),
							$custom_style_css
						);

						$css = $custom_style_css;
					}
				} else {
					/*Boats*/
					$custom_style_css = $wp_filesystem->get_contents( $css_directory . 'boats/app.css' );

					$custom_style_css .= $custom_colors_helper->stm_cch_get_css_modules();

					if ( 'site_style_custom' === $site_color_style ) {
						$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#31a3c6', 'site_style_base_color' );
						$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#ceac61', 'site_style_secondary_color' );
						$third_color     = apply_filters( 'stm_me_get_nuxy_mod', '#002568', 'site_style_base_color_listing' );
					} else {
						$base_color      = $predefined_colors['boats'][ $site_color_style ]['primary'];
						$secondary_color = $predefined_colors['boats'][ $site_color_style ]['secondary'];
						$third_color     = $predefined_colors['boats'][ $site_color_style ]['third'];
					}

					$custom_style_css = str_replace(
						array(
							'#31a3c6',
							'#ceac61',
							'#002568',
							'#27829e',
							'#1b92a8',
							'204, 97, 25',
							'../../../',
							'../../',
							'#1bc744',
							'#cc6119',
						),
						array(
							$base_color,
							$secondary_color,
							$third_color,
							'rgba(' . stm_hex2rgb( $base_color ) . ', 0.75)',
							'rgba(' . stm_hex2rgb( $secondary_color ) . ', 0.8)',
							stm_hex2rgb( $base_color ),
							$theme_path,
							$theme_path,
							$base_color,
							$secondary_color,
						),
						$custom_style_css
					);

					$css .= $custom_style_css;
				}
			}

			$header_style = stm_get_header_layout();

			if ( apply_filters( 'stm_is_listing_six', false ) ) {
				$header_style = 'listing_five';
			}

			if ( ! empty( $header_style ) ) {

				$header_style_css = $wp_filesystem->get_contents( $css_directory . 'headers/header-' . $header_style . '.css' );

				// fallback styles for old users with uListing.
				if ( 'listing_five' === $header_style && defined( 'ULISTING_VERSION' ) ) {
					$header_style_css = $wp_filesystem->get_contents( $css_directory . 'headers/header-listing_five_ulisting.css' );
				}

				$base_color      = apply_filters( 'stm_me_get_nuxy_mod', '#31a3c6', 'site_style_base_color' );
				$secondary_color = apply_filters( 'stm_me_get_nuxy_mod', '#ceac61', 'site_style_secondary_color' );

				$header_style_css = str_replace(
					array(
						'#cc6119',
						'#1bc744',
						'rgba(27, 199, 68, 0.85)',
						'#ffb129',
						'#df1d1d',
						'#ceac61',
						'#f0c540',
						'#6c98e1',
						'#3c98ff',
						'#18ca3e',
						'#31a3c6',
						'#4c94fa',
						'#ff2325',
						'#002568',
						'#021f53',
						'#0d46ff', // ev_dealer primary.
						'#102127', // ev_dealer secondary.
					),
					array(
						$base_color,
						$base_color,
						'rgba(' . stm_hex2rgb( $base_color ) . ', 0.85)',
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$base_color,
						$secondary_color,
						$base_color, // ev_dealer primary.
						$secondary_color, // ev_dealer secondary.
					),
					$header_style_css
				);

				$css .= $header_style_css;
			}

			$upload_dir = wp_upload_dir();

			if ( ! $wp_filesystem->is_dir( $upload_dir['basedir'] . '/stm_uploads' ) ) {
				do_action( 'stm_create_dir' );
			}

			if ( $custom_style_css ) {
				$css_to_filter = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
				$css_to_filter = str_replace(
					array(
						"\r\n",
						"\r",
						"\n",
						"\t",
						'  ',
						'    ',
						'    ',
					),
					'',
					$css_to_filter
				);

				$custom_style_file = $upload_dir['basedir'] . '/stm_uploads/skin-custom.css';

				$wp_filesystem->put_contents( $custom_style_file, $css_to_filter, FS_CHMOD_FILE );

				$current_style = get_option( 'stm_custom_style', '4' );
				update_option( 'stm_custom_style', $current_style + 1 );
			}
		}
	}
}
add_action( 'stm_print_styles_color', 'stm_print_styles_color' );
add_action( 'wpcfto_after_settings_saved', 'stm_print_styles_color', 10, 2 );

if ( ! function_exists( 'stm_boats_styles' ) ) {
	function stm_boats_styles() {

		$front_css = '';

		if ( apply_filters( 'stm_is_motorcycle', false ) || apply_filters( 'stm_is_rental', false ) ) {
			$defined_color = ( apply_filters( 'stm_is_rental', false ) ) ? '#eeeeee' : '#0e1315';

			$site_bg = apply_filters( 'stm_me_get_nuxy_mod', $defined_color, 'site_bg_color' );

			$front_css .= '
				#wrapper {
					background-color: ' . $site_bg . ' !important;
				}
				.stm-single-car-page:before,
				.stm-simple-parallax .stm-simple-parallax-gradient:before {
					background: -moz-linear-gradient(left, rgba(' . $site_bg . ') 0%, rgba(' . $site_bg . ') 100%);
					background: -webkit-linear-gradient(left, rgba(' . $site_bg . ',1) 0%,rgba(' . $site_bg . ') 100%);
					background: linear-gradient(to right, rgba(' . $site_bg . ',1) 0%,rgba(' . $site_bg . ') 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#0e1315\', endColorstr=\'#000e1315\',GradientType=1 ); /* IE6-9 */
				}
				.stm-single-car-page:after,
				.stm-simple-parallax .stm-simple-parallax-gradient:after {
					background: -moz-linear-gradient(left, rgba(' . $site_bg . ') 0%, rgba(' . $site_bg . ') 99%, rgba(' . $site_bg . ') 100%);
					background: -webkit-linear-gradient(left, rgba(' . $site_bg . ') 0%,rgba(' . $site_bg . ') 99%,rgba(' . $site_bg . ') 100%);
					background: linear-gradient(to right, rgba(' . $site_bg . ') 0%,rgba(' . $site_bg . ') 99%,rgba(' . $site_bg . ') 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#000e1315\', endColorstr=\'#0e1315\',GradientType=1 );
				}
			';

			$stm_single_car_page = apply_filters( 'mvl_get_nuxy_img_src', '', 'stm_single_car_page' );

			if ( ! empty( $stm_single_car_page ) ) {
				$front_css .= '
				.stm-single-car-page {
					background-image: url(" ' . $stm_single_car_page . ' ");
				}
			';
			}
			wp_add_inline_style( 'stm-theme-style', $front_css );
		}

		if ( apply_filters( 'stm_is_equipment', false ) ) {
			$defined_color = ( apply_filters( 'stm_is_rental', false ) ) ? '#eeeeee' : '#0e1315';
			$site_bg       = apply_filters( 'stm_me_get_nuxy_mod', $defined_color, 'site_bg_color' );

			$front_css .= '
				body {
					background-color: ' . $site_bg . ' !important;
				}
			';

			$stm_single_car_page = apply_filters( 'mvl_get_nuxy_img_src', '', 'stm_single_car_page' );

			if ( ! empty( $stm_single_car_page ) ) {
				$front_css .= '
				.single-listings:before {
				   content: "";
				   display: block;
				   height: 100vh;
				   position: fixed;
				   top: 0;
				   bottom: 0;
				   left: 0;
				   right: 0;
					background-image: url(" ' . $stm_single_car_page . ' ");
					background-size: cover;
					background-position: 0;
					background-repeat: no-repeat;
					z-index: 0;
				}
			';
			}
			wp_add_inline_style( 'stm-theme-style', $front_css );
		}

		if ( apply_filters( 'stm_is_dealer_two', false ) ) {
			$stm_single_car_page = apply_filters( 'mvl_get_nuxy_img_src', '', 'stm_single_car_page' );

			if ( ! empty( $stm_single_car_page ) && ( is_singular( array( apply_filters( 'stm_listings_post_type', 'listings' ) ) ) ) ) {
				$front_css = '
				#main {
					background-image: url(" ' . $stm_single_car_page . ' ");
                    background-repeat: no-repeat;					
				}
			';
				wp_add_inline_style( 'stm-theme-style', $front_css );
			}
		}

		if ( 'site_style_default' === apply_filters( 'stm_me_get_nuxy_mod', 'site_style_default', 'site_style' ) ) {
			wp_add_inline_style( 'stm-theme-style', $front_css );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'stm_boats_styles' );

if ( ! function_exists( 'stm_get_boats_image_hover' ) ) {
	function stm_get_boats_image_hover( $id ) {
		$car_media = apply_filters( 'stm_get_car_medias', array(), $id );

		$dynamic_photo_class = 'stm-car-photos-' . $id . '-' . wp_rand( 1, 99999 );
		$dynamic_video_class = 'stm-car-videos-' . $id . '-' . wp_rand( 1, 99999 );

		echo '<div class="boats-image-unit">';
		if ( ! empty( $car_media['car_photos_count'] ) ) :
			?>
			<div class="stm-listing-photos-unit stm-car-photos-<?php echo esc_attr( get_the_ID() ); ?> <?php echo esc_attr( $dynamic_photo_class ); ?>">
				<i class="stm-boats-icon-camera"></i>
				<span><?php echo esc_html( $car_media['car_photos_count'] ); ?></span>
			</div>

			<?php //phpcs:disable ?>
			<script>
                jQuery(document).ready(function () {

                    jQuery(".<?php echo esc_attr( $dynamic_photo_class ); ?>").on('click', function (e) {
                        e.preventDefault();
                        jQuery(this).lightGallery({
                            dynamic: true,
                            dynamicEl: [
								<?php foreach ( $car_media['car_photos'] as $car_photo ) : ?>
                                {
                                    src: "<?php echo esc_url( $car_photo ); ?>",
                                    thumb: "<?php echo esc_url( $car_photo ); ?>"
                                },
								<?php endforeach; ?>
                            ],
                            download: false,
                            mode: 'lg-fade',
                        })

                    });
                });

			</script>
			<?php //phpcs:enable ?>
		<?php endif; ?>
		<?php if ( ! empty( $car_media['car_videos_count'] ) ) : ?>
			<div class="stm-listing-videos-unit stm-car-videos-<?php echo esc_attr( get_the_ID() ); ?> <?php echo esc_attr( $dynamic_video_class ); ?>">
				<i class="stm-boats-icon-movie"></i>
				<span><?php echo esc_html( $car_media['car_videos_count'] ); ?></span>
			</div>
			<?php //phpcs:disable ?>
			<script>
                jQuery(document).ready(function () {

                    jQuery(".<?php echo esc_attr( $dynamic_video_class ); ?>").on('click', function (e) {
                        e.preventDefault();
                        jQuery(this).lightGallery({
                            dynamic: true,
                            iframe: true,
                            dynamicEl: [
								<?php foreach ( $car_media['car_videos'] as $car_video ) : ?>
                                {
                                    src: "<?php echo esc_url( $car_video ); ?>"
                                },
								<?php endforeach; ?>
                            ],
                            download: false,
                            mode: 'lg-fade',
                        })

                    }); //click
                }); //ready

			</script>
			<?php //phpcs:enable ?>
			<?php
		endif;
		echo '</div>';
	}
}

if ( ! function_exists( 'stm_get_boats_comapre' ) ) {
	function stm_get_boats_compare( $id ) {
		if ( ! empty( $show_compare ) && $show_compare ) :
			?>
			<div
					class="stm-listing-compare stm-compare-directory-new"
					data-post-type="<?php echo esc_attr( get_post_type( get_the_ID() ) ); ?>"
					data-id="<?php echo esc_attr( get_the_id() ); ?>"
					data-title="<?php echo wp_kses_post( apply_filters( 'stm_generate_title_from_slugs', get_the_title( get_the_ID() ), get_the_ID(), false ) ); ?>"
					data-toggle="tooltip" data-placement="left"
					title="<?php echo esc_attr__( 'Add to compare', 'motors' ); ?>"
			>
				<i class="stm-service-icon-compare-new"></i>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'stm_display_script_sort' ) ) {
	function stm_display_script_sort( $tax_info ) {
		?>
		case '<?php echo esc_attr( $tax_info['slug'] . '_low' ); ?>':
		<?php
		$slug      = sanitize_title( str_replace( '-', '_', $tax_info['slug'] ) );
		$sort_asc  = 'true';
		$sort_desc = 'false';
		if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) {
			$sort_asc  = 'false';
			$sort_desc = 'true';
		}
		?>
		$container.isotope({
		getSortData: {
		<?php echo esc_attr( $slug ); ?>: function( itemElem ) {
		<?php if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = '0';
			}
			return parseFloat(<?php echo esc_attr( $slug ); ?>);
		<?php else : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = 'n/a';
			}
			return <?php echo esc_attr( $slug ); ?>;
		<?php endif; ?>

		}
		},
		sortBy: '<?php echo esc_attr( $slug ); ?>',
		sortAscending: <?php echo esc_attr( $sort_asc ); ?>
		});
		break
		case '<?php echo esc_attr( $tax_info['slug'] . '_high' ); ?>':
		$container.isotope({
		getSortData: {
		<?php echo esc_attr( $slug ); ?>: function( itemElem ) {
		<?php if ( ! empty( $tax_info['numeric'] ) && $tax_info['numeric'] ) : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = '0';
			}
			return parseFloat(<?php echo esc_attr( $slug ); ?>);
		<?php else : ?>
			var <?php echo esc_attr( $slug ); ?> = $(itemElem).data('<?php echo esc_attr( $tax_info['slug'] ); ?>');
			if(typeof(<?php echo esc_attr( $slug ); ?>) == 'undefined') {
			<?php echo esc_attr( $slug ); ?> = 'n/a';
			}
			return <?php echo esc_attr( $slug ); ?>;
		<?php endif; ?>

		}
		},
		sortBy: '<?php echo esc_attr( $tax_info['slug'] ); ?>',
		sortAscending: <?php echo esc_attr( $sort_desc ); ?>
		});
		break
		<?php
	}
}

if ( ! function_exists( 'stm_theme_add_body_class' ) ) {
	function stm_theme_add_body_class( $classes ) {
		return "$classes stm-template-" . stm_get_current_layout();
	}
}

add_filter( 'stm_listings_admin_body_class', 'stm_theme_add_body_class' );

if ( ! function_exists( 'stm_display_wpml_switcher' ) ) {
	function stm_display_wpml_switcher( $langs = array() ) {
		if ( ! empty( apply_filters( 'stm_get_global_server_val', 'HTTP_HOST' ) ) ) {
			$server_uri = apply_filters( 'stm_get_global_server_val', 'HTTP_HOST' );
			if ( 'motors.stm' === $server_uri || 'motors.stylemixthemes.com' === $server_uri ) {
				$langs = array(
					'en' => array(
						'active'      => 1,
						'url'         => '#',
						'native_name' => esc_html__( 'English', 'motors' ),
					),
					'fr' => array(
						'active'      => 0,
						'url'         => '#',
						'native_name' => esc_html__( 'Français', 'motors' ),
					),
				);

				$lang_name = esc_html__( 'English', 'motors' );
			}
		}

		if ( ! empty( $langs ) ) :
			?>
			<!--LANGS-->
			<?php
			if ( count( $langs ) > 1 ) {
				$langs_exist = 'dropdown_toggle';
			} else {
				$langs_exist = 'no_other_langs';
			}
			if ( defined( 'ICL_LANGUAGE_NAME' ) ) {
				$lang_name = ICL_LANGUAGE_NAME;
			}
			?>
			<div class="pull-left language-switcher-unit">
				<div
						class="stm_current_language <?php echo esc_attr( $langs_exist ); ?>"
					<?php
					if ( count( $langs ) > 1 ) {
						?>
						id="lang_dropdown" data-toggle="dropdown" <?php } ?>><?php echo esc_attr( $lang_name ); ?><?php if ( count( $langs ) > 1 ) { ?>
						<i class="fas fa-angle-down"></i><?php } ?></div>
				<?php if ( count( $langs ) > 1 ) : ?>
					<ul class="dropdown-menu lang_dropdown_menu" role="menu" aria-labelledby="lang_dropdown">
						<?php foreach ( $langs as $lang ) : ?>
							<?php if ( ! $lang['active'] ) : ?>
								<li role="presentation">
									<a role="menuitem" tabindex="-1" href="<?php echo esc_url( $lang['url'] ); ?>">
										<?php echo esc_attr( $lang['native_name'] ); ?>
									</a>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}
}

if ( ! function_exists( 'stm_listing_filter_get_selects' ) && defined( 'STM_LISTINGS' ) ) {
	function stm_listing_filter_get_selects( $select_strings, $tab_name = '', $words = array(), $show_amount = 'yes', $is_slide = false, $show_sold = false ) {
		if ( ! empty( $select_strings ) ) {
			$select_strings = explode( ',', $select_strings );

			if ( ! empty( $select_strings ) ) {
				$i       = 0;
				$output  = '';
				$output .= '<div class="row">';
				foreach ( $select_strings as $select_string ) {

					if ( empty( $select_string ) ) {
						continue;
					}

					$select_string = trim( $select_string );

					$taxonomy_info = stm_get_taxonomies_with_type( $select_string );

					if ( $is_slide && 4 === $i && count( $select_strings ) > 4 ) {
						$output .= '<div class="stm-slide-content">';
					}

					// col-md dynamic.
					$col_md = ( count( $select_strings ) < 3 ) ? 'col-md-6' : 'col-md-3';

					$output .= '<div class="' . $col_md . ' col-sm-6 col-xs-12 stm-select-col">';
					// numeric slider.
					if ( ! empty( $taxonomy_info['slider_in_tabs'] ) && $taxonomy_info['slider_in_tabs'] ) {
						$args = array(
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => false,
							'fields'     => 'all',
						);

						$for_range = array();

						$terms = get_terms( $select_string, $args );

						if ( ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								$for_range[] = intval( $term->name );
							}

							sort( $for_range );
						}

						ob_start();
						do_action(
							'stm_listings_load_template',
							'filter/types/vc_price',
							array(
								'taxonomy'    => $select_string,
								'options'     => $for_range,
								'tab_name'    => $tab_name,
								'label'       => $taxonomy_info['single_name'],
								'slider_step' => ( ! empty( $taxonomy_info['slider_step'] ) ) ? $taxonomy_info['slider_step'] : 10,
							)
						);

						$output .= ob_get_clean();

						// price.
					} elseif ( apply_filters( 'stm_is_listing_price_field', false, $select_string ) ) {
						$args = array(
							'orderby'    => 'name',
							'order'      => 'ASC',
							'hide_empty' => false,
							'fields'     => 'all',
						);

						$prices = array();

						$terms = get_terms( $select_string, $args );

						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$prices[] = intval( $term->name );
							}

							sort( $prices );
						}

						$number_string = '';

						if ( ! empty( $words['number_prefix'] ) ) {
							$number_string .= $words['number_prefix'] . ' ';
						} else {
							$number_string = esc_html__( 'Max', 'motors' ) . ' ';
						}

						$number_string .= apply_filters( 'stm_dynamic_string_translation', stm_get_name_by_slug( $select_string, $tab_name ), 'Select Text' );

						if ( ! empty( $words['number_affix'] ) ) {
							$number_string .= ' ' . $words['number_affix'];
						}

						$output .= '<select class="stm-filter-ajax-disabled-field" name="max_' . $select_string . '" data-class="stm_select_overflowed">';
						$output .= '<option value="">' . $number_string . '</option>';
						if ( ! empty( $terms ) ) {
							foreach ( $prices as $price ) {
								$selected = '';
								if ( apply_filters( 'stm_is_equipment', false ) ) {
									$selected = ( isset( $_GET[ $select_string ] ) && $_GET[ $select_string ] === $price ) ? 'selected' : '';
								}

								$output .= '<option value="' . esc_attr( $price ) . '" ' . $selected . '>' . apply_filters( 'stm_filter_price_view', '', $price ) . '</option>';
							}
						}
						$output .= '</select>';
					} else {
						// If numeric.
						if ( ! empty( $taxonomy_info['numeric'] ) && $taxonomy_info['numeric'] ) {
							$args    = array(
								'orderby'    => 'name',
								'order'      => 'ASC',
								'hide_empty' => false,
								'fields'     => 'all',
							);
							$numbers = array();

							$terms = get_terms( $select_string, $args );

							$select_main = '';
							if ( ! empty( $words['number_prefix'] ) ) {
								$select_main .= $words['number_prefix'] . ' ';
							} else {
								$select_main .= esc_html__( 'Choose', 'motors' ) . ' ';
							}

							$select_main .= apply_filters( 'stm_dynamic_string_translation', stm_get_name_by_slug( $select_string, $tab_name ), 'Option text' );

							if ( ! empty( $words['number_affix'] ) ) {
								$select_main .= ' ' . $words['number_affix'];
							}

							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {
									$numbers[] = intval( $term->name );
								}
							}
							sort( $numbers );

							if ( ! empty( $numbers ) ) {
								$output .= '<select name="' . $select_string . '" data-class="stm_select_overflowed" data-sel-type="' . esc_attr( $select_string ) . '">';
								$output .= '<option value="">' . $select_main . '</option>';
								foreach ( $numbers as $number_key => $number_value ) {

									$selected = '';

									if ( 0 === $number_key ) {
										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && sprintf( '< %s', esc_attr( $number_value ) ) === $_GET[ $select_string ] ) ? 'selected' : '';
										}

										$output .= '<option value="' . sprintf( '< %s', esc_attr( $number_value ) ) . '" ' . $selected . '>< ' . $number_value . '</option>';
									} elseif ( count( $numbers ) - 1 === $number_key ) {
										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && sprintf( '> %s', esc_attr( $number_value ) ) === $_GET[ $select_string ] ) ? 'selected' : '';
										}

										$output .= '<option value="' . sprintf( '> %s', esc_attr( $number_value ) ) . '" ' . $selected . '>> ' . $number_value . '</option>';
									} else {
										$option_value = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;
										$option_name  = $numbers[ ( $number_key - 1 ) ] . '-' . $number_value;

										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && $_GET[ $select_string ] === $option_value ) ? 'selected' : '';
										}

										$output .= '<option value="' . esc_attr( $option_value ) . '" ' . $selected . '> ' . $option_name . '</option>';
									}
								}
								$output .= '<input type="hidden" name="min_' . $select_string . '"/>';
								$output .= '<input type="hidden" name="max_' . $select_string . '"/>';
								$output .= '</select>';
							}
							// other default values.
						} else {
							if ( 'location' === $select_string ) {
								wp_enqueue_script( 'stm-google-places' );

								$output .= '<div class="stm-location-search-unit">';
								$output .= '<input type="text" placeholder="' . esc_attr__( 'Enter a location', 'motors' ) . '" class="stm_listing_filter_text stm_listing_search_location" id="stm-car-location-' . $tab_name . '" name="ca_location" />';
								$output .= '<input type="hidden" name="stm_lat"/>';
								$output .= '<input type="hidden" name="stm_lng"/>';
								if ( apply_filters( 'motors_vl_get_nuxy_mod', true, 'enable_distance_search' ) ) {
									$radius  = apply_filters( 'stm_distance_search_value', 100 );
									$radius  = ( ! empty( $radius ) ) ? $radius : 100;
									$output .= '<input type="hidden" name="max_search_radius" value="' . $radius . '"/>';
								}
								$output .= '</div>';
							} else {
								if ( ! empty( $taxonomy_info['listing_taxonomy_parent'] ) ) {
									$terms = array();
								} else {
									$terms = apply_filters( 'stm_get_category_by_slug_all', array(), $select_string, false, ( 'yes' === $show_amount ) );
								}

								$select_main = '';
								if ( ! empty( $words['select_prefix'] ) ) {
									$select_main .= $words['select_prefix'] . ' ';
								} else {
									$select_main .= esc_html__( 'Choose', 'motors' ) . ' ';
								}

								$select_main .= apply_filters( 'stm_dynamic_string_translation', stm_get_name_by_slug( $select_string, $tab_name ), 'Option select text' );

								if ( ! empty( $words['select_affix'] ) ) {
									$select_main .= ' ' . $words['select_affix'];
								}

								$output .= '<div class="stm-ajax-reloadable">';
								$output .= '<select name="' . esc_attr( $select_string ) . '" data-class="stm_select_overflowed">';
								$output .= '<option value="">' . $select_main . '</option>';
								if ( ! empty( $terms ) ) {
									foreach ( $terms as $term ) {

										if ( ! $term || is_array( $term ) && ! empty( $term['invalid_taxonomy'] ) ) {
											continue;
										}

										$selected = '';
										if ( apply_filters( 'stm_is_equipment', false ) ) {
											$selected = ( isset( $_GET[ $select_string ] ) && $_GET[ $select_string ] === $term->slug ) ? 'selected' : '';
										}

										if ( 'yes' === $show_amount ) {
											$output .= '<option value="' . esc_attr( $term->slug ) . '" ' . $selected . '>' . $term->name . ' (' . $term->count . ') </option>';
										} else {
											$output .= '<option value="' . esc_attr( $term->slug ) . '" ' . $selected . '>' . $term->name . ' </option>';
										}
									}
								}
								$output .= '</select>';
								$output .= '</div>';
							}
						}
					}
					$output .= '</div>';
					if ( $is_slide && count( $select_strings ) - 1 === $i && count( $select_strings ) > 4 ) {
						$output .= '</div>';
					}
					$i ++;
				}

				$active_get = ( isset( $_GET['listing_status'] ) && 'active' === $_GET['listing_status'] ) ? 'selected' : '';
				$sold_get   = ( isset( $_GET['listing_status'] ) && 'sold' === $_GET['listing_status'] ) ? 'selected' : '';

				if ( $show_sold ) :
					$output .= '<div class="' . $col_md . ' col-sm-6 col-xs-12 stm-select-col">';
					$output .= '<select name="listing_status" class="stm-filter-ajax-disabled-field" data-class="stm_select_overflowed">';
					$output .= '<option value="">';
					$output .= __( 'Listing status', 'motors' );
					$output .= '</option>';
					$output .= '<option value="active" ' . $active_get . '>';
					$output .= __( 'Active', 'motors' );
					$output .= '</option>';
					$output .= '<option value="sold" ' . $sold_get . '>';
					$output .= __( 'Sold', 'motors' );
					$output .= '</option>';
					$output .= '</select>';
					$output .= '</div>';
				endif;

				$output .= '</div>'; // row.

				if ( ! empty( $output ) ) {
					echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
		}
	}

	add_filter( 'stm_listing_filter_get_selects', 'stm_listing_filter_get_selects', 10, 6 );
}

function stm_pricing_enabled() {
	$enabled = apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_plans' );

	/*
	* TO DO
	* 'Subscriptio' will be removed
	*/

	if ( $enabled && class_exists( 'WooCommerce' ) && ( class_exists( 'Subscriptio' ) || class_exists( 'RP_SUB' ) ) ) {
		$enabled = true;
	} else {
		$enabled = false;
	}

	return ( $enabled );
}

add_filter( 'stm_pricing_enabled', 'stm_pricing_enabled' );

function stm_pricing_link() {
	$pricing_link = apply_filters( 'motors_vl_get_nuxy_mod', '', 'pricing_link' );
	if ( ! empty( $pricing_link ) ) {
		$pricing_link = apply_filters( 'stm_motors_wpml_is_page', $pricing_link );
	}

	return get_permalink( $pricing_link );
}

// Filters from new plugin.
if ( ! function_exists( 'stm_filter_add_links' ) ) {
	function stm_filter_add_links( $taxes ) {
		/*Filter links*/
		$filter_links = stm_get_car_filter_links();
		if ( ! empty( $filter_links ) && ! empty( $taxes ) ) {
			foreach ( $filter_links as $key => $tax ) {
				if ( ! array_key_exists( $key, $taxes ) ) {
					$taxes[] = $key;
				}
			}
		}

		/*Filter checkboxes*/
		$filter_checkboxes = stm_get_car_filter_checkboxes();
		if ( ! empty( $filter_checkboxes ) && ! empty( $taxes ) ) {
			foreach ( $filter_checkboxes as $key => $tax ) {
				if ( ! array_key_exists( $key, $taxes ) ) {
					$taxes[] = $key;
				}
			}
		}

		return $taxes;
	}
}

if ( ! function_exists( 'stm_listings_filter_classified_title' ) ) {
	function stm_listings_filter_classified_title( $params ) {
		$title_default           = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Cars for sale', 'motors' ), 'listing_directory_title_default' );
		$title_generated_postfix = esc_html__( ' for sale', 'motors' );

		$title_response = '';

		$titles_args = stm_get_filter_title();

		if ( stm_is_multilisting() ) {
			$current_type = STMMultiListing::stm_get_current_listing_slug();
			if ( $current_type ) {
				$titles_args = apply_filters( 'stm_get_listings_filter', array(), $current_type, array( 'where' => array( 'use_on_directory_filter_title' => true ) ), false );
			}
		}

		$title_generated_counter = 0;
		foreach ( $titles_args as $title_arg ) {
			if ( ! empty( $_GET[ $title_arg['slug'] ] ) ) {
				$title_generated_counter ++;
				if ( ! is_array( $_GET[ $title_arg['slug'] ] ) ) {
					$category = get_term_by( 'slug', sanitize_text_field( $_GET[ $title_arg['slug'] ] ), $title_arg['slug'] );
					if ( ! empty( $category ) && ! is_wp_error( $category ) ) {
						$title_response .= ' ' . $category->name;
					}
				}
			}
		}

		if ( empty( $title_response ) ) {
			$title_response = $title_default;
		} else {
			if ( 1 === $title_generated_counter ) {
				$title_response .= ' ' . strtolower( $title_default );
			} else {
				$title_response .= $title_generated_postfix;
			}
		}

		$params['listing_title'] = $title_response;

		return $params;
	}

	if ( apply_filters( 'stm_is_listing', false ) || apply_filters( 'stm_is_motorcycle', false ) || apply_filters( 'stm_is_dealer_two', false ) || apply_filters( 'stm_is_listing_two', false ) || apply_filters( 'stm_is_listing_five', false ) ) {
		add_filter( 'stm_listings_filter', 'stm_listings_filter_classified_title' );
	}
}

function stm_listing_pre_get_vehicles( $query_vars ) {
	if ( ! isset( $query_vars['meta_query'] ) ) {
		$query_vars['meta_query'] = array();
	}

	if ( ! empty( $_GET['featured_top'] ) ) {
		if ( apply_filters( 'stm_is_listing', false ) ) {
			$query_vars['meta_query'] = array(
				array(
					'key'     => 'special_car',
					'value'   => 'on',
					'compare' => '=',
				),
				$query_vars['meta_query'],
			);
		} else {
			$query_vars['meta_query'][] = array(
				'key'     => 'special_car',
				'value'   => 'on',
				'compare' => '=',
			);
		}
	}

	if ( ! empty( $_GET['sale_cars'] ) ) {
		if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
			$query_vars['meta_query'] = array(
				array(
					'key'     => 'sale_price',
					'value'   => '',
					'compare' => '!=',
				),
				array(
					'key'     => 'car_price_form',
					'value'   => '',
					'compare' => '==',
				),
				$query_vars['meta_query'],
			);
		} else {
			$query_vars['meta_query'] = array(
				array(
					'key'     => 'sale_price',
					'value'   => '',
					'compare' => '!=',
				),
				array(
					'key'     => 'car_price_form',
					'value'   => '',
					'compare' => '==',
				),
			);
		}
	}

	if ( ! is_admin() ) {
		$posts_per_page = intval( apply_filters( 'stm_listings_input', null, 'posts_per_page' ) );

		if ( empty( $posts_per_page ) ) {
			$view_type      = sanitize_file_name( apply_filters( 'stm_listings_input', apply_filters( 'motors_vl_get_nuxy_mod', 'list', 'listing_view_type' ), 'view_type' ) );
			$posts_per_page = ( ! empty( get_post_meta( stm_get_listing_archive_page_id(), ( 'grid' === $view_type ) ? 'ppp_on_grid' : 'ppp_on_list', true ) ) ) ? get_post_meta( stm_get_listing_archive_page_id(), ( 'grid' === $view_type ) ? 'ppp_on_grid' : 'ppp_on_list', true ) : get_option( 'posts_per_page' );
		}

		$query_vars['posts_per_page'] = intval( $posts_per_page );

		if ( ! empty( $_GET['stm-footer-search-name'] ) ) {
			$query_vars['s'] = sanitize_text_field( $_GET['stm-footer-search-name'] );
		} elseif ( ! empty( $_GET['s'] ) ) {
			$query_vars['s'] = sanitize_text_field( $_GET['s'] );
		}

		$features = apply_filters( 'stm_listings_input', null, 'stm_features' );
		if ( ! empty( $features ) && is_array( $features ) ) {
			$query_vars['tax_query'][] = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'stm_additional_features',
					'field'    => 'slug',
					'terms'    => array_map( 'sanitize_title', $features ),
				),
			);
		}
	}

	return $query_vars;
}

add_filter( 'stm_listings_build_query_args', 'stm_listing_pre_get_vehicles', 20 );

if ( ! function_exists( 'stm_get_sidebar_position' ) ) {
	function stm_get_sidebar_position() {
		$listing_filter_position = apply_filters( 'motors_vl_get_nuxy_mod', 'left', 'listing_filter_position' );
		if ( ! empty( $_GET['filter_position'] ) && 'right' === $_GET['filter_position'] ) {
			$listing_filter_position = 'right';
		}

		$sidebar_pos_classes = '';
		$content_pos_classes = '';

		if ( 'right' === $listing_filter_position ) {
			$sidebar_pos_classes = 'col-md-push-9 col-sm-push-0';
			$content_pos_classes = 'col-md-pull-3 col-sm-pull-0';
		}

		$position = array(
			'sidebar' => $sidebar_pos_classes,
			'content' => $content_pos_classes,
		);

		return $position;
	}

	add_filter( 'stm_get_sidebar_position', 'stm_get_sidebar_position' );
}

// Media upload limit.
if ( ! function_exists( 'stm_filter_media_upload_size' ) ) {
	function stm_filter_media_upload_size( $size ) {
		$size = apply_filters( 'stm_me_get_nuxy_mod', '4000', 'user_image_size_limit' ) * 1024;

		return $size;
	}

	add_filter( 'stm_listing_media_upload_size', 'stm_filter_media_upload_size' );
}

if ( ! function_exists( 'stm_display_user_name' ) ) {
	/**
	 * User display name
	 *
	 * @param $user_id
	 * @param string $user_login
	 * @param string $f_name
	 * @param string $l_name
	 */
	function stm_display_user_name( $user_id, $user_login = '', $f_name = '', $l_name = '' ) {
		$user = get_userdata( $user_id );

		if ( empty( $user_login ) ) {
			$login = $user->data->user_login;
		} else {
			$login = $user_login;
		}
		if ( empty( $f_name ) ) {
			$first_name = get_the_author_meta( 'first_name', $user_id );
		} else {
			$first_name = $f_name;
		}

		if ( empty( $l_name ) ) {
			$last_name = get_the_author_meta( 'last_name', $user_id );
		} else {
			$last_name = $l_name;
		}

		$display_name = $login;

		if ( ! empty( $first_name ) ) {
			$display_name = $first_name;
		}

		if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
			$display_name .= ' ' . $last_name;
		}

		if ( empty( $first_name ) && ! empty( $last_name ) ) {
			$display_name = $last_name;
		}

		echo wp_kses_post( apply_filters( 'stm_filter_display_user_name', $display_name, $user_id, $user_login, $f_name, $l_name ) );

	}
}

function stm_theme_image_sizes_js( $response, $attachment, $meta ) {
	$size_array = array( 'stm-img-796-466', 'stm-img-350' );

	foreach ( $size_array as $size ) :

		if ( isset( $meta['sizes'][ $size ] ) ) {
			$attachment_url = wp_get_attachment_url( $attachment->ID );
			$base_url       = str_replace( wp_basename( $attachment_url ), '', $attachment_url );
			$size_meta      = $meta['sizes'][ $size ];

			$response['sizes'][ $size ] = array(
				'height'      => $size_meta['height'],
				'width'       => $size_meta['width'],
				'url'         => $base_url . $size_meta['file'],
				'orientation' => $size_meta['height'] > $size_meta['width'] ? 'portrait' : 'landscape',
			);
		}

	endforeach;

	return $response;
}

add_filter( 'wp_prepare_attachment_for_js', 'stm_theme_image_sizes_js', 10, 3 );

if ( ! function_exists( 'stm_listings_archive_inventory_page_id' ) ) {
	function stm_listings_archive_inventory_page_id( $id ) {
		if ( $id ) {
			/*Polylang*/
			if ( function_exists( 'pll_current_language' ) ) {
				$id = pll_current_language();
			}

			if ( class_exists( 'SitePress' ) ) {
				$id = stm_motors_wpml_binding( $id, 'page' );
			}
		}

		return $id;
	}

	add_filter( 'stm_listings_inventory_page_id', 'stm_listings_archive_inventory_page_id' );
}

function stm_motors_wpml_binding( $id, $type ) {
	return apply_filters( 'wpml_object_id', $id, $type );
}

function stm_motors_wpml_is_page( $page_id ) {
	if ( class_exists( 'SitePress' ) ) {
		$id = stm_motors_wpml_binding( $page_id, 'page' );
		if ( is_page( $id ) ) {
			return $id;
		}
	}

	return $page_id;
}

add_filter( 'stm_motors_wpml_is_page', 'stm_motors_wpml_is_page' );


function stm_verify_motors_theme( $v ) {
	return true;
}

add_filter( 'stm_listing_is_motors_theme', 'stm_verify_motors_theme', 100 );

function stm_woo_shop_page_id() {
	return apply_filters( 'stm_woo_shop_page_id', get_option( 'woocommerce_shop_page_id' ) );
}

function stm_woo_shop_page_url() {
	return apply_filters( 'stm_woo_shop_page_url', get_permalink( stm_woo_shop_page_id() ) );
}

function stm_woo_shop_checkout_id() {
	return apply_filters( 'woocommerce_checkout_page_id', get_option( 'woocommerce_checkout_page_id' ) );
}


function stm_woo_shop_checkout_url() {
	return apply_filters( 'stm_woo_shop_page_url', get_permalink( stm_woo_shop_checkout_id() ) );
}

add_action( 'wp_loaded', 'stm_pmxi_disable_rich_editor' );
function stm_pmxi_disable_rich_editor() {
	if ( is_admin() ) {
		if ( ! empty( $_GET['page'] ) ) {
			if ( 'pmxi-admin-manage' === $_GET['page'] || 'pmxi-admin-import' === $_GET['page'] ) {
				add_filter( 'user_can_richedit', '__return_false', 50 );
			}
		}
	}
}

/*WPML duplicate*/
add_action( 'icl_make_duplicate', 'stm_duplicate_wpml_post', 1, 4 );
add_action( 'icl_make_duplicate', 'stm_duplicate_wpml_post_update_additional_features', 1, 4 );
add_action( 'edit_term', 'stm_save_additional_features', 1, 2 );

function stm_duplicate_wpml_post( $master_post_id, $lang, $post_array, $id ) {
	$post_id    = $master_post_id;
	$taxonomies = array();

	$filter_options = get_option( 'stm_vehicle_listing_options' );

	foreach ( $filter_options as $filter_option ) {
		if ( $filter_option['numeric'] ) {
			continue;
		}

		$slug = $filter_option['slug'];

		$terms = wp_get_post_terms( $post_id, $slug );

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( empty( $taxonomies[ $slug ] ) ) {
					$taxonomies[ $slug ] = array();
				}

				$term_id     = $term->term_id;
				$binded_id   = stm_motors_wpml_binding( $term_id, $slug );
				$binded_term = get_term( $binded_id, $slug );

				if ( ! empty( $binded_term ) && ! is_wp_error( $binded_term ) ) {
					$taxonomies[ $slug ][] = $binded_term->slug;
				}
			}
		}
	}

	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $meta_key => $meta_value ) {
			update_post_meta( $id, $meta_key, implode( ',', $meta_value ) );
		}
	}
}

function stm_duplicate_wpml_post_update_additional_features( $master_post_id, $lang, $post_array, $id ) {
	$post_id    = $master_post_id;
	$taxonomies = array();

	$slug = 'stm_additional_features';

	$terms = wp_get_post_terms( $post_id, $slug );

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			if ( empty( $taxonomies[ $slug ] ) ) {
				$taxonomies[ $slug ] = array();
			}

			$term_id     = $term->term_id;
			$binded_id   = stm_motors_wpml_binding( $term_id, $slug );
			$binded_term = get_term( $binded_id, $slug );

			if ( ! empty( $binded_term ) && ! is_wp_error( $binded_term ) ) {
				$taxonomies[ $slug ][] = $binded_term->name;
			}
		}
	}

	if ( ! empty( $taxonomies ) ) {
		delete_post_meta( $id, 'additional_features', '' );
		foreach ( $taxonomies as $meta_key => $meta_value ) {
			update_post_meta( $id, 'additional_features', implode( ',', $meta_value ) );
		}
	}
}

function stm_save_additional_features( $args, $args2 ) {
	$term_object = get_term_by( 'term_taxonomy_id', $args );

	if ( false !== $term_object && 'stm_additional_features' === $term_object->taxonomy ) {

		$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

		$args = array(
			'post_type'   => $post_types,
			'post_status' => 'publish',
			'tax_query'   => array(
				array(
					'taxonomy' => $term_object->taxonomy,
					'field'    => 'slug',
					'terms'    => $term_object->slug,
				),
			),
		);

		$posts_list = get_posts( $args );

		if ( ! empty( $posts_list ) ) {
			foreach ( $posts_list as $k => $post ) {
				$post_id = $post->ID;

				$taxonomies = array();

				$slug = 'stm_additional_features';

				$terms = wp_get_post_terms( $post_id, $slug );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $key => $term ) {
						if ( empty( $taxonomies[ $slug ] ) ) {
							$taxonomies[ $slug ] = array();
						}

						$taxonomies[ $slug ][] = $term->name;
					}
				}

				if ( ! empty( $taxonomies ) ) {
					delete_post_meta( $post_id, 'additional_features', '' );
					update_post_meta( $post_id, 'additional_features', implode( ',', $taxonomies[ $slug ] ) );
				}
			}
		}
	}
}

add_filter( 'stm_listings_default_search_inventory', 'stm_enable_listing_search_name' );
function stm_enable_listing_search_name() {
	return ( apply_filters( 'motors_vl_get_nuxy_mod', false, 'enable_search' ) );
}

function stm_disableDisplayAddToAny() {
	$new_options['display_in_posts_on_front_page']    = '-1';
	$new_options['display_in_posts_on_archive_pages'] = '-1';
	$new_options['display_in_excerpts']               = '-1';
	$new_options['display_in_posts']                  = '-1';
	$new_options['display_in_pages']                  = '-1';
	$new_options['display_in_attachments']            = '-1';
	$new_options['display_in_feed']                   = '-1';

	$custom_post_types = array_values(
		get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			),
			'objects'
		)
	);
	foreach ( $custom_post_types as $custom_post_type_obj ) {
		$placement_name                                     = $custom_post_type_obj->name;
		$new_options[ 'display_in_cpt_' . $placement_name ] = '-1';
	}

	$existing_options = get_option( 'addtoany_options' );

	// Merge $new_options into $existing_options to retain AddToAny options from all other screens/tabs.
	if ( $existing_options ) {
		$new_options = array_merge( $existing_options, $new_options );
	}

	update_option( 'addtoany_options', $new_options );
}

if ( function_exists( 'A2A_SHARE_SAVE_options_page' ) ) {
	add_action( 'stm_importer_done', 'stm_disableDisplayAddToAny', 100 );
}

add_filter( 'get_avatar', 'stm_cyb_get_avatar', 10, 5 );
function stm_cyb_get_avatar( $avatar = '', $id_or_email = '', $size = 96, $default = '', $alt = '' ) {
	if ( is_object( $id_or_email ) && isset( $id_or_email->user_id ) ) {
		$user = apply_filters( 'stm_get_user_custom_fields', $id_or_email->user_id );
		// Replace $avatar with your own image element, for example.
		if ( ! empty( $user['image'] ) ) {
			$avatar = "<img alt='{$alt}' src='{$user['image']}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}
	}

	return $avatar;
}


if ( ! function_exists( 'stm_get_filter_badges' ) ) {
	function stm_get_filter_badges() {
		$attributes = stm_listings_filter_terms();

		$filter_badges = array();
		foreach ( $attributes as $attribute => $terms ) {
			/*Text field*/
			$options = apply_filters( 'stm_vl_get_all_by_slug', array(), $attribute );

			/*Field affix like mi, km or another defined by user*/
			$affix = '';
			if ( ! empty( $options['number_field_affix'] ) ) {
				$affix = apply_filters( 'stm_dynamic_string_translation', $options['number_field_affix'], 'Affix text' );
			}

			/*Slider badge*/
			if ( ! empty( $options['slider'] ) && $options['slider'] ) {
				if ( isset( $_GET[ 'max_' . $attribute ] ) && ! empty( $_GET[ 'max_' . $attribute ] ) && ( isset( $_GET[ 'min_' . $attribute ] ) && ! empty( $_GET[ 'min_' . $attribute ] ) ) ) {
					reset( $terms );
					$start_value = key( $terms );
					end( $terms );
					$end_value = key( $terms );

					if ( 'price' === $attribute ) {
						$value = apply_filters( 'stm_filter_price_view', '', apply_filters( 'stm_listings_input', $start_value, 'min_' . $attribute ) ) . ' - ' . apply_filters( 'stm_filter_price_view', '', apply_filters( 'stm_listings_input', $end_value, 'max_' . $attribute ) );
					} else {
						$value = apply_filters( 'stm_listings_input', $start_value, 'min_' . $attribute ) . ' - ' . apply_filters( 'stm_listings_input', $end_value, 'max_' . $attribute ) . ' ' . $affix;
					}

					$filter_badges[ $attribute ] = array(
						'slug'   => $attribute,
						'name'   => stm_get_name_by_slug( $attribute ),
						'type'   => 'slider',
						'value'  => $value,
						'origin' => array( 'min_' . $attribute, 'max_' . $attribute ),
					);

					$filter_badges[ $attribute ]['url'] = stm_get_filter_badge_url( $filter_badges[ $attribute ] );
				}
				/*Badge of number field*/
			} elseif ( ! empty( $options['numeric'] ) && $options['numeric'] ) {
				if ( ! empty( $_GET[ $attribute ] ) ) {
					$filter_badges[ $attribute ] = array(
						'slug'   => $attribute,
						'name'   => stm_get_name_by_slug( $attribute ),
						'value'  => sanitize_text_field( $_GET[ $attribute ] ) . ' ' . $affix,
						'type'   => 'number',
						'origin' => array( $attribute ),
					);

					$filter_badges[ $attribute ]['url'] = stm_get_filter_badge_url( $filter_badges[ $attribute ] );
				}
				/*Badge of text field*/
			} else {
				if ( ! empty( $_GET[ $attribute ] ) || ( class_exists( \MotorsVehiclesListing\Features\FriendlyUrl::class ) && ! empty( \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter[ $attribute ] ) ) ) {

					$selected = ( ! empty( $_GET[ $attribute ] ) ) ? $_GET[ $attribute ] : '';

					if ( class_exists( \MotorsVehiclesListing\Features\FriendlyUrl::class ) && ! empty( \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter[ $attribute ] ) ) {
						$selected = \MotorsVehiclesListing\Features\FriendlyUrl::$for_filter[ $attribute ];
					}

					$txt = '';
					if ( is_array( $selected ) ) {
						foreach ( $selected as $k => $val ) {
							if ( ! isset( $terms[ $val ] ) ) {
								continue;
							}

							$txt .= $terms[ $val ]->name;
							$txt .= ( count( $selected ) - 1 !== $k ) ? ', ' : '';
						}
					} else {
						$txt = $terms[ $selected ]->name;
					}

					$filter_badges[ $attribute ] = array(
						'slug'     => $attribute,
						'name'     => stm_get_name_by_slug( $attribute ),
						'value'    => $txt,
						'origin'   => array( $attribute ),
						'type'     => 'select',
						'multiple' => array_key_exists( 'is_multiple_select', $options ) ? $options['is_multiple_select'] : 0,
					);

					$filter_badges[ $attribute ]['url'] = stm_get_filter_badge_url( $filter_badges[ $attribute ] );
				}
			}
		}

		return apply_filters( 'stm_get_filter_badges', $filter_badges );
	}
}


if ( ! function_exists( 'stm_get_filter_badge_url' ) ) {
	function stm_get_filter_badge_url( $badge_info ) {
		$remove_args   = $badge_info['origin'];
		$remove_args[] = 'ajax_action';

		return apply_filters( 'stm_get_filter_badge_url', remove_query_arg( $remove_args ), $badge_info, $remove_args );
	}
}

function stm_wsl_new_register_redirect_url( $user_id, $provider, $hybridauth_user_profile, $redirect_to ) {
	if ( ! empty( $user_id ) ) {
		do_action( 'wsl_clear_user_php_session' );
		if ( apply_filters( 'stm_is_rental_two', false ) ) {
			$get_page = get_page_by_path( 'My Account' );
			wp_safe_redirect( get_the_permalink( $get_page->ID ) );
		} else {
			wp_safe_redirect( get_author_posts_url( $user_id ) );
		}
		die();
	}
}

add_action( 'wsl_hook_process_login_before_wp_safe_redirect', 'stm_wsl_new_register_redirect_url', 100, 4 );

function stm_get_formated_date( $date, $format ) {
	$datetime1 = new DateTime( $date );

	return date( $format, strtotime( 'now', $datetime1->getTimestamp() ) );
}

function stm_get_locale_formated_date( $date, $format ) {
	$datetime1 = new DateTime( $date );
	setlocale( LC_ALL, get_locale() . '.UTF-8' );

	return strftime( $format, $datetime1->getTimestamp() );
}

function stm_motors_get_formatted_date( $unix, $custom_format = '' ) {
	$format = ( ! empty( $custom_format ) ) ? $custom_format : get_option( 'date_format' );

	return ( date_i18n( $format, $unix ) );
}

add_filter( 'stm_motors_get_formatted_date', 'stm_motors_get_formatted_date', 10, 2 );

function stm_motors_get_terms_array( $id, $taxonomy, $filter, $link = false, $args = '' ) {
	$terms = wp_get_post_terms( $id, $taxonomy );
	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		if ( $link ) {
			$links = array();
			if ( ! empty( $args ) ) {
				$args = stm_motors_array_as_string( $args );
			}
			foreach ( $terms as $term ) {
				$url     = get_term_link( $term );
				$links[] = "<a {$args} href='{$url}' title='{$term->name}'>{$term->name}</a>";
			}
			$terms = $links;
		} else {
			$terms = wp_list_pluck( $terms, $filter );
		}
	} else {
		$terms = array();
	}

	return apply_filters( 'stm_motors_get_terms_array', $terms );
}

function stm_motors_array_as_string( $arr ) {
	$r = implode( ' ', array_map( 'stm_motors_array_map', $arr, array_keys( $arr ) ) );

	return $r;
}

function stm_motors_array_map( $v, $k ) {
	return $k . '="' . $v . '"';
}

/*TO DO DELETE DEPRECATED*/
if ( ! function_exists( 'motors_get_formatted_date' ) ) {
	function motors_get_formatted_date( $unix, $custom_format = '' ) {
		$format = ( ! empty( $custom_format ) ) ? $custom_format : get_option( 'date_format' );

		return ( date_i18n( $format, $unix ) );
	}
}

if ( ! function_exists( 'motors_get_terms_array' ) ) {
	function motors_get_terms_array( $id, $taxonomy, $filter, $link = false, $args = '' ) {
		$terms = wp_get_post_terms( $id, $taxonomy );
		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			if ( $link ) {
				$links = array();
				if ( ! empty( $args ) ) {
					$args = motors_array_as_string( $args );
				}
				foreach ( $terms as $term ) {
					$url     = get_term_link( $term );
					$links[] = "<a {$args} href='{$url}' title='{$term->name}'>{$term->name}</a>";
				}
				$terms = $links;
			} else {
				$terms = wp_list_pluck( $terms, $filter );
			}
		} else {
			$terms = array();
		}

		return apply_filters( 'motors_get_terms_array', $terms );
	}
}

if ( ! function_exists( 'motors_array_as_string' ) ) {
	function motors_array_as_string( $arr ) {
		$r = implode( ' ', array_map( 'motors_array_map', $arr, array_keys( $arr ) ) );

		return $r;
	}
}

if ( ! function_exists( 'motors_array_map' ) ) {
	function motors_array_map( $v, $k ) {
		return $k . '="' . $v . '"';
	}
}
/*TO DO DELETE DEPRECATED*/

add_action( 'admin_enqueue_scripts', 'stm_sticky_admin_enqueue_scripts' );
function stm_sticky_admin_enqueue_scripts() {
	$screen = get_current_screen();

	// Only continue if this is an edit screen for a custom post type.
	if ( ! in_array( $screen->base, array( 'post', 'edit' ), true ) || in_array( $screen->post_type, array( 'post', 'page' ), true ) ) {
		return;
	}

	// Editing an individual custom post.
	if ( 'post' === $screen->base ) {
		$is_sticky = is_sticky();
		$js_vars   = array(
			'screen'                 => 'post',
			'is_sticky'              => $is_sticky ? 1 : 0,
			'checked_attribute'      => checked( $is_sticky, true, false ),
			'label_text'             => __( 'Stick this post to the front page', 'motors' ),
			'sticky_visibility_text' => __( 'Public, Sticky', 'motors' ),
		);

		// Browsing custom posts.
	} else {
		global $wpdb;

		$sticky_posts = implode( ', ', array_map( 'absint', (array) get_option( 'sticky_posts' ) ) );
		$sticky_count = $sticky_posts
			? $wpdb->get_var( $wpdb->prepare( "SELECT COUNT( 1 ) FROM $wpdb->posts WHERE post_type = %s && post_status NOT IN ('trash', 'auto-draft') && ID IN (%s)", $screen->post_type, $sticky_posts ) )
			: 0;

		$js_vars = array(
			'screen'            => 'edit',
			'post_type'         => $screen->post_type,
			'status_label_text' => __( 'Status', 'motors' ),
			'label_text'        => __( 'Make this post sticky', 'motors' ),
			'sticky_text'       => __( 'Sticky', 'motors' ),
			'sticky_count'      => $sticky_count,
		);
	}

	wp_enqueue_script(
		'script-admin',
		get_template_directory_uri() . '/assets/admin/js/admin-sticky.min.js',
		array( 'jquery' ),
		1,
		true
	);

	wp_localize_script( 'script-admin', 'sscpt', $js_vars );
}

function stm_empty_cart( $passed, $product_id, $quantity, $variation_id = '', $variations = '' ) {
	$isSubscribtio = get_post_meta( $product_id, '_subscriptio', true );

	if ( ! empty( $isSubscribtio ) && 'yes' === $isSubscribtio ) {
		WC()->cart->empty_cart();
	}

	return $passed;
}

if ( boolval( apply_filters( 'is_listing', array() ) ) ) {
	add_filter( 'woocommerce_add_to_cart_validation', 'stm_empty_cart', 10, 5 );
}

if ( ! function_exists( 'stm_addition_fields' ) ) {
	function stm_add_a_car_addition_fields( $get_params = false, $histories = '', $post_id = '' ) {
		do_action(
			'stm_listings_load_template',
			'add_car/step_1_additional_fields',
			array(
				'histories' => $histories,
				'post_id'   => $post_id,
			)
		);
	}
}

if ( ! function_exists( 'stm_add_a_car_features' ) ) {
	function stm_add_a_car_features( $user_features, $get_params = false, $post_id = '' ) {
		do_action(
			'stm_listings_load_template',
			'add_car/step_2_items',
			array(
				'items' => $user_features,
				'id'    => $post_id,
			)
		);
	}
}

if ( ! function_exists( 'stm_add_a_car_registration' ) ) {
	function stm_add_a_car_registration( $user_title = '', $user_text = '', $link = array() ) {
		do_action(
			'stm_listings_load_template',
			'add_car/registration.php',
			array(
				'stm_title_user' => $user_title,
				'stm_text_user'  => $user_text,
				'link'           => $link,
			)
		);
	}
}

if ( ! function_exists( 'stm_add_a_car_user_info_theme' ) ) {
	function stm_add_a_car_user_info_theme( $user_login = '', $f_name = '', $l_name = '', $user_id = '' ) {
		do_action(
			'stm_listings_load_template',
			'add_car/user_info.php',
			array(
				'user_login' => '',
				'f_name'     => '',
				'l_name'     => '',
				'user_id'    => $user_id,
			)
		);
	}
}

function stm_get_value_my_car_options() {
	$stm_value_my_car_options = array(
		esc_html__( 'Email', 'motors' )   => 'email',
		esc_html__( 'Phone', 'motors' )   => 'phone',
		esc_html__( 'Make', 'motors' )    => 'make',
		esc_html__( 'Model', 'motors' )   => 'model',
		esc_html__( 'Year', 'motors' )    => 'year',
		esc_html__( 'Mileage', 'motors' ) => 'mileage',
		esc_html__( 'VIN', 'motors' )     => 'vin',
		esc_html__( 'Photo', 'motors' )   => 'photo',
	);

	return $stm_value_my_car_options;
}

if ( has_filter( 'wp_get_attachment_image_src', 'stm_get_thumbnail_filter' ) === false && function_exists( 'stm_get_thumbnail' ) && ! function_exists( 'wp_get_attachment_image_src' ) ) {
	add_filter( 'wp_get_attachment_image_src', 'stm_get_thumbnail_filter', 100, 4 );
	function stm_get_thumbnail_filter( $image, $attachment_id, $size = 'thumbnail', $icon = false ) {
		$file       = wp_check_filetype( get_attached_file( $attachment_id ) );
		$image_exts = array( 'jpg', 'jpeg', 'jpe', 'png', 'webp' );

		if ( ! in_array( $file['ext'], $image_exts, true ) ) {
			return $image;
		}

		return stm_get_thumbnail( $attachment_id, $size, $icon = false );
	}
}

// get sort options array.
if ( ! function_exists( 'stm_get_sort_options_array' ) ) {
	function stm_get_sort_options_array() {

		$display_multilisting_sorts = false;

		if ( stm_is_multilisting() ) {
			$current_slug = STMMultiListing::stm_get_current_listing_slug();
			if ( ! empty( $current_slug ) ) {
				$display_multilisting_sorts = true;
			}
		}

		if ( $display_multilisting_sorts ) {
			$ml        = new STMMultiListing();
			$sort_args = multilisting_default_sortby( $current_slug );

			$custom_inventory = $ml->stm_get_listing_type_settings( 'inventory_custom_settings', $current_slug );

			if ( false === $custom_inventory ) {
				$enabled_options = array( 'date_high', 'date_low' );
			} else {
				$enabled_options = apply_filters( 'stm_prefix_given_sort_options', $ml->stm_get_listing_type_settings( 'multilisting_sort_options', $current_slug ) );
			}
		} else {
			$sort_args       = apply_filters( 'stm_me_wpcfto_sortby', array() );
			$sort_options    = apply_filters( 'stm_me_get_nuxy_mod', array(), 'sort_options' );
			$enabled_options = apply_filters( 'stm_prefix_given_sort_options', $sort_options );
		}

		if ( ! empty( $enabled_options ) ) {
			foreach ( $sort_args as $slug => $label ) {
				if ( ! in_array( $slug, $enabled_options, true ) ) {
					unset( $sort_args[ $slug ] );
				}
			}
		}

		return $sort_args ?? array();
	}
}

if ( ! function_exists( 'stm_get_sort_options_html' ) ) {
	function stm_get_sort_options_html() {

		$html = '';

		$default_sort       = apply_filters( 'stm_get_default_sort_option', 'date_high' );
		$currently_selected = apply_filters( 'stm_listings_input', $default_sort, 'sort_order' );
		$sort_args          = stm_get_sort_options_array();

		if ( sort_distance_nearby() ) {
			$sort_args['distance_nearby'] = esc_html__( 'Distance : nearby', 'motors' );
			$currently_selected           = 'distance_nearby';
		}

		foreach ( $sort_args as $slug => $label ) {
			$selected = ( $slug === $currently_selected ) ? ' selected' : '';
			$html    .= '<option value="' . $slug . '" ' . $selected . '>' . $label . '</option>';
		}

		return $html;
	}

	add_filter( 'stm_get_sort_options_html', 'stm_get_sort_options_html' );
}

function stm_motors_sanitize_text_field( $text ) {
	return apply_filters( 'stm_motors_sanitize_text_field', $text );
}

function stm_motors_is_unit_test_mod() {
	return ( ! stm_is_use_plugin( 'stm-motors-extends/stm-motors-extends.php' ) ) ? true : false;
}

if ( ! stm_motors_is_unit_test_mod() ) {
	add_filter(
		'use_block_editor_for_post',
		function ( $enable, $post_object ) {
			if ( 'post' !== $post_object->post_type && 'page' !== $post_object->post_type ) {
				return false;
			}

			return $enable;
		},
		20,
		2
	);

}

if ( ! function_exists( 'stm_motors_getGlobalWPDB' ) ) {
	function stm_motors_getGlobalWPDB() {
		global $wpdb;

		return $wpdb;
	}
}

if ( ! function_exists( 'stm_motors_getGlobalPageNow' ) ) {
	function stm_motors_getGlobalPageNow() {
		global $pagenow;

		return $pagenow;
	}
}

function stm_dynamic_string_translation( $string, $desc ) {
	do_action( 'wpml_register_single_string', 'motors', $desc, $string );

	return apply_filters( 'wpml_translate_single_string', $string, 'motors', $desc );
}

add_filter( 'stm_dynamic_string_translation', 'stm_dynamic_string_translation', 10, 2 );

function stm_get_plchdr( $layout ) {
	if ( file_exists( get_theme_file_path( '/assets/images/' . $layout . '_plchldr.png' ) ) ) {
		return get_stylesheet_directory_uri() . '/assets/images/' . $layout . '_plchldr.png';
	}

	return '';
}

function stm_get_wpml_query_default_attr() {
	if ( class_exists( 'SitePress' ) ) {
		return array( 'suppress_filters' => true );
	}

	return array();
}

if ( ! function_exists( 'stm_get_wpml_product_parent_id' ) ) {
	add_filter( 'stm_get_wpml_product_parent_id', 'stm_get_wpml_product_parent_id' );
	function stm_get_wpml_product_parent_id( $id ) {

		if ( class_exists( 'SitePress' ) ) {
			global $sitepress;
			$parent_id = apply_filters( 'wpml_object_id', $id, 'product', false, $sitepress->get_default_language() );

			return $parent_id;
		}

		return $id;
	}
}

if ( ! function_exists( 'stm_get_wpml_product_child_id' ) ) {
	add_filter( 'stm_get_wpml_product_child_id', 'stm_get_wpml_product_child_id' );
	function stm_get_wpml_product_child_id( $id ) {

		if ( class_exists( 'SitePress' ) ) {
			$parent_id = apply_filters( 'wpml_object_id', $id, 'product', true );

			return $parent_id;
		}

		return $id;
	}
}

function stm_get_wpml_office_parent_id( $id ) {
	add_filter( 'stm_get_wpml_office_parent_id', 'stm_get_wpml_office_parent_id' );

	if ( class_exists( 'SitePress' ) ) {
		global $sitepress;
		$parent_id = apply_filters( 'wpml_object_id', $id, 'stm_office', false, $sitepress->get_default_language() );

		return $parent_id;
	}

	return $id;
}

function stm_get_img_alt( $image_alt, $image_id = null ) {
	$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$image_alt = ( ! empty( $image_alt ) ) ? $image_alt : get_the_title( $image_id );

	return esc_attr( $image_alt );
}

add_filter( 'stm_get_img_alt', 'stm_get_img_alt', 10, 2 );

function stm_wc_terms_and_conditions_page_content() {
	if ( ! class_exists( 'WPBMap' ) ) {
		return;
	}

	WPBMap::addAllMappedShortcodes();
	$terms_page_id = wc_terms_and_conditions_page_id();

	if ( ! $terms_page_id ) {
		return;
	}

	$page = get_post( $terms_page_id );
	setup_postdata( $page );

	if ( $page && 'publish' === $page->post_status && $page->post_content && ! has_shortcode( $page->post_content, 'woocommerce_checkout' ) ) {
		echo '<div class="woocommerce-terms-and-conditions" style="display: none; max-height: 200px; overflow: auto;">' . wp_kses_post( apply_filters( 'the_content', $page->post_content ) ) . '</div>';
	}
}

function stm_wc_checkout_privacy_policy_text() {
	if ( ! class_exists( 'WPBMap' ) ) {
		return;
	}

	WPBMap::addAllMappedShortcodes();

	echo '<div class="woocommerce-privacy-policy-text">';
	apply_filters( 'the_content', wc_get_privacy_policy_text( 'checkout' ) );
	echo '</div>';
}

remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
add_action( 'woocommerce_checkout_terms_and_conditions', 'stm_wc_terms_and_conditions_page_content', 50 );

if ( ! function_exists( 'stm_get_listing_seller_note' ) ) {
	function stm_get_listing_seller_note( $listing_id ) {
		return get_post_meta( $listing_id, 'listing_seller_note', true ) ?? '';
	}
}

function stm_show_my_plans() {

	if ( false === apply_filters( 'stm_pricing_enabled', false ) ) {
		return false;
	}

	if ( class_exists( 'Subscriptio' ) ) {
		$options = get_option( 'subscriptio_options' );

		if ( ! empty( $options['subscriptio_multiproduct_subscription'] ) && $options['subscriptio_multiproduct_subscription'] ) {
			return true;
		}
	}

	$subscription_option = ( class_exists( 'RP_SUB' ) ) ? get_option( 'rp_sub_settings', '' ) : '';

	$show_my_plans = false;
	if ( $subscription_option && 'multiple_subscriptions' === $subscription_option[1]['multiple_product_checkout'] ) {
		$show_my_plans = true;
	}

	return $show_my_plans;
}

if ( ! function_exists( 'stm_get_listing_archive_page_id' ) ) {
	add_filter( 'stm_get_listing_archive_page_id', 'stm_get_listing_archive_page_id' );
	function stm_get_listing_archive_page_id() {

		// return the page ID of Sold Cars Inventory IF it's the actual Sold Cars Inventory page.
		if ( ! empty( url_to_postid( wp_get_referer() ) ) && ! empty( get_post_meta( url_to_postid( wp_get_referer() ), 'is_sold_cars_inventory', true ) ) ) {
			return url_to_postid( wp_get_referer() );
		}

		// multilisting.
		if ( stm_is_multilisting() ) {
			$current_type = STMMultiListing::stm_get_current_listing_slug();
			if ( $current_type ) {
				$options = get_option( 'stm_motors_listing_types', array() );
				if ( isset( $options['multilisting_repeater'] ) && ! empty( $options['multilisting_repeater'] ) ) {
					foreach ( $options['multilisting_repeater'] as $key => $listing ) {
						if ( $listing['slug'] === $current_type ) {
							if ( ! empty( $listing['inventory_page'] ) ) {
								return intval( $listing['inventory_page'] );
							}
						}
					}
				}
			}
		}

		return apply_filters( 'motors_vl_get_nuxy_mod', null, 'listing_archive' );
	}
}

if ( ! function_exists( 'stm_get_inventory_page_url' ) ) {
	function stm_get_inventory_page_url( $default = '', $listing_type = 'listings' ) {

		if ( 'listings' === $listing_type ) {
			$archive_listings_page = apply_filters( 'motors_vl_get_nuxy_mod', false, 'listing_archive' );

			if ( $archive_listings_page ) {
				return get_permalink( $archive_listings_page );
			}

			return get_post_type_archive_link( 'listings' );

		} elseif ( stm_is_multilisting() ) {
			$options = get_option( 'stm_motors_listing_types', array() );

			if ( isset( $options['multilisting_repeater'] ) && ! empty( $options['multilisting_repeater'] ) ) {
				foreach ( $options['multilisting_repeater'] as $key => $listing ) {
					if ( $listing['slug'] === $listing_type ) {
						if ( ! empty( $listing['inventory_page'] ) ) {
							$page = get_post( $listing['inventory_page'] );
							if ( ! is_null( $page ) ) {
								return get_permalink( $page );
							}
						}

						return get_post_type_archive_link( $listing_type );
					}
				}
			}
		}

		return $default;
	}

	add_filter( 'stm_inventory_page_url', 'stm_get_inventory_page_url', 10, 2 );
}

if ( ! function_exists( 'stm_vc_stm_classic_filter_save_options' ) ) {

	function stm_vc_stm_classic_filter_save_options( $id, $post ) {
		if ( preg_match( '/stm_classic_filter/', $post->post_content, $match ) ) {
			preg_match_all( '/quant_listing_on_grid="(.*?)"/', $post->post_content, $quant_grid_items );
			preg_match_all( '/ppp_on_list="(.*?)"/', $post->post_content, $ppp_on_list );
			preg_match_all( '/ppp_on_grid="(.*?)"/', $post->post_content, $ppp_on_grid );

			update_post_meta( $id, 'quant_grid_items', ( ! empty( $quant_grid_items[1][0] ) ) ? $quant_grid_items[1][0] : 3 );
			update_post_meta( $id, 'ppp_on_list', ( ! empty( $ppp_on_list[1][0] ) ) ? $ppp_on_list[1][0] : 10 );
			update_post_meta( $id, 'ppp_on_grid', ( ! empty( $ppp_on_grid[1][0] ) ) ? $ppp_on_grid[1][0] : 9 );
		}

	}

	add_action( 'save_post', 'stm_vc_stm_classic_filter_save_options', 10, 2 );
}

/**
 * Contact form 7 custom recipient
 */
add_action( 'wpcf7_before_send_mail', 'stm_motors_send_cf7_message_to_user', 8, 1 );
function stm_motors_send_cf7_message_to_user( $wpcf ) {
	if ( ! empty( $_POST['motors_changed_recipient'] ) ) {
		$mail = $wpcf->prop( 'mail' );

		$mail_to = get_the_author_meta( 'email', filter_var( $_POST['motors_changed_recipient'], FILTER_SANITIZE_NUMBER_INT ) );

		if ( ! empty( $mail_to ) ) {
			$mail['recipient'] = sanitize_email( $mail_to );
			$wpcf->set_properties( array( 'mail' => $mail ) );
		}
	}

	return $wpcf;
}

// add listings type admin column.
if ( apply_filters( 'stm_sold_status_enabled', false ) ) {
	$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

	foreach ( $post_types as $post_type ) {
		add_filter( 'manage_' . $post_type . '_posts_columns', 'stm_set_listings_sold_column' );
	}
}
function stm_set_listings_sold_column( $columns ) {
	if ( ! empty( $columns ) ) {
		foreach ( $columns as $key => $value ) {
			if ( 'date' === $key ) {  // when we find the date column.
				$new['mark_as_sold'] = __( 'Mark as sold', 'motors' );  // put the mark column before it.
			}
			$new[ $key ] = $value;
		}

		return $new;
	}

	return $columns;
}

// add stock number column to listings.
$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

foreach ( $post_types as $post_type ) {
	add_filter( 'manage_' . $post_type . '_posts_columns', 'stm_set_listing_stock_number_column' );
	add_action( 'manage_' . $post_type . '_posts_custom_column', 'stm_custom_stock_number_column', 10, 2 );
}

function stm_set_listing_stock_number_column( $columns ) {
	$columns = array_slice( $columns, 0, count( $columns ) - 1, true ) + array( 'stock_number' => __( 'Stock Number', 'motors' ) ) + array_slice( $columns, count( $columns ) - 1, 1, true );

	return $columns;
}

function stm_custom_stock_number_column( $column, $post_id ) {
	switch ( $column ) {

		case 'stock_number':
			$stock_number = get_post_meta( $post_id, 'stock_number', true );
			echo wp_kses_post( $stock_number );
			break;

	}
}

// add mark car as sold toggler to the column.
if ( apply_filters( 'stm_sold_status_enabled', false ) ) {
	$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

	foreach ( $post_types as $post_type ) {
		add_action( 'manage_' . $post_type . '_posts_custom_column', 'stm_listings_sold_custom_column_data', 10, 2 );
	}
}

function stm_listings_sold_custom_column_data( $column, $post_id ) {
	switch ( $column ) {
		case 'mark_as_sold':
			$sold = get_post_meta( $post_id, 'car_mark_as_sold', true );
			?>
			<input class="car_mark_as_sold" type="checkbox"
				name="<?php echo esc_attr( $post_id ); ?>" <?php echo ( ! empty( $sold ) ) ? 'checked' : ''; ?>>
			<?php
			break;
	}
}

// include mark as sold ajax script in admin footer.
if ( ! function_exists( 'stm_listings_mark_sold_ajax' ) ) {

	if ( apply_filters( 'stm_sold_status_enabled', false ) ) {
		add_action( 'load-edit.php', 'stm_listings_mark_sold_ajax' );
	}

	function stm_listings_mark_sold_ajax() {
		$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );

		if ( in_array( get_current_screen()->post_type, $post_types, true ) ) {
			add_action( 'admin_footer', 'stm_listings_mark_sold_ajax_js' );
		}
	}
}

// ajax script to be included in admin footer.
if ( ! function_exists( 'stm_listings_mark_sold_ajax_js' ) ) {

	function stm_listings_mark_sold_ajax_js() {
		?>
		<?php //phpcs:disable ?>
		<script>
            (function ($) {
                $(document).ready(function () {
                    $(".car_mark_as_sold").change(function () {
                        $.ajax({
                            data: {
                                action: "stm_motors_mark_as_sold_action",
                                post_id: $(this).attr('name'),
                            },
                            type: "POST",
                            url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
                            success: function (data) {
                                // done!
                            }
                        });
                    });
                });
            })(jQuery);
		</script>
		<?php //phpcs:enable ?>
		<?php
	}
}


// toggle sold status from admin panel, listings table column checkbox.
if ( ! function_exists( 'stm_motors_mark_as_sold_action' ) ) {

	if ( apply_filters( 'stm_sold_status_enabled', false ) ) {
		add_action( 'wp_ajax_stm_motors_mark_as_sold_action', 'stm_motors_mark_as_sold_action' );
	}

	function stm_motors_mark_as_sold_action() {
		$post_id = intval( filter_var( wp_unslash( $_POST['post_id'] ), FILTER_SANITIZE_NUMBER_INT ) );

		if ( ! empty( $post_id ) ) {
			$sold = get_post_meta( $post_id, 'car_mark_as_sold', true );

			if ( empty( $sold ) ) {
				update_post_meta( $post_id, 'car_mark_as_sold', 'on' );
			} else {
				update_post_meta( $post_id, 'car_mark_as_sold', '' );
			}
		}

		wp_send_json( array( 'status' => 'done' ), 200 );
	}
}


// get sold/active listings count for displaying on aircraft inventory filter.
if ( ! function_exists( 'stm_get_listings_count_by_status' ) ) {

	function stm_get_listings_count_by_status( $status = 'active' ) {
		if ( ! in_array( $status, array( 'active', 'sold' ), true ) ) {
			return 0;
		}

		// need to make this multilisting ready.
		$args = array(
			'post_type'      => 'listings',
			'posts_per_page' => - 1,
			'fields'         => 'ids',
		);

		$args['meta_query'][] = array(
			'key'     => 'car_mark_as_sold',
			'value'   => ( 'active' === $status ) ? '' : 'on',
			'compare' => '=',
		);

		$posts = new WP_Query( $args );

		return $posts->found_posts;
	}
}


// meta for sold cars inventory page.
if ( ! function_exists( 'stm_detect_sold_cars_inventory_page' ) ) {

	add_action( 'save_post_page', 'stm_detect_sold_cars_inventory_page', 10, 3 );

	function stm_detect_sold_cars_inventory_page( $listing_id, $post, $update ) {
		if ( $post && preg_match( '/stm_sold_cars/', $post->post_content ) ) {
			// mark this page as Sold Cars Inventory.
			update_post_meta( $listing_id, 'is_sold_cars_inventory', true );

			preg_match_all( '/quant_listing_on_grid="(.*?)"/', $post->post_content, $quant_grid_items );
			preg_match_all( '/ppp_on_list="(.*?)"/', $post->post_content, $ppp_on_list );
			preg_match_all( '/ppp_on_grid="(.*?)"/', $post->post_content, $ppp_on_grid );

			// record vc shorcode attributes.
			update_post_meta( $listing_id, 'quant_grid_items', ( ! empty( $quant_grid_items[1][0] ) ) ? $quant_grid_items[1][0] : 3 );
			update_post_meta( $listing_id, 'ppp_on_list', ( ! empty( $ppp_on_list[1][0] ) ) ? $ppp_on_list[1][0] : 10 );
			update_post_meta( $listing_id, 'ppp_on_grid', ( ! empty( $ppp_on_grid[1][0] ) ) ? $ppp_on_grid[1][0] : 9 );
		} else {
			// it's not Sold Cars Inventory any longer.
			delete_post_meta( $listing_id, 'is_sold_cars_inventory' );

			// clean up vc shortcode attributes.
			delete_post_meta( $listing_id, 'ppp_on_list' );
			delete_post_meta( $listing_id, 'ppp_on_grid' );
			delete_post_meta( $listing_id, 'quant_grid_items' );
		}
	}
}


// is Inventory Search Results enabled?
if ( ! function_exists( 'stm_search_results_enabled' ) ) {

	function stm_search_results_enabled() {
		if ( apply_filters( 'stm_is_listing_five', false ) || apply_filters( 'stm_is_listing_six', false ) || apply_filters( 'stm_is_service', false ) || apply_filters( 'stm_is_rental', false ) || apply_filters( 'stm_is_rental_one_elementor', false ) || apply_filters( 'stm_is_rental_two', false ) || apply_filters( 'stm_is_magazine', false ) || apply_filters( 'stm_is_auto_parts', false ) ) {
			return false;
		} else {
			return true;
		}
	}
}


// Check image exists.
function stm_img_exists_by_url( $url ) {
	if ( empty( $url ) ) {
		return false;
	}

	$response = wp_remote_head( $url );

	if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
		return true;
	}

	return false;
}


// redirect to user profile page if logged in user tries to view a page with "stm_login_register" VC module.
if ( ! function_exists( 'stm_redirect_login_register_to_profile' ) ) {

	add_action( 'template_redirect', 'stm_redirect_login_register_to_profile' );

	function stm_redirect_login_register_to_profile() {
		$current_page = get_post( get_the_ID() );
		if ( ! empty( $current_page ) && preg_match( '/stm_login_register/', $current_page->post_content, $match ) && is_user_logged_in() ) {
			wp_safe_redirect( get_author_posts_url( get_current_user_id() ) );
		}
	}
}


// hide classified settings for old uListing users on Classified Five & Six.
add_action( 'admin_footer', 'stm_hide_classified_settings_for_ulisting' );
function stm_hide_classified_settings_for_ulisting() {
	$layouts = array( 'wpcfto_motors_listing_five_settings', 'wpcfto_motors_listing_six_settings' );

	if ( defined( 'ULISTING_VERSION' ) && isset( $_GET['page'] ) && in_array( $_GET['page'], $layouts, true ) ) {
		?>
		<?php //phpcs:disable ?>
		<style>
            .wpcfto-tab-nav .wpcfto-nav div[data-section="inventory_settings"],
            .wpcfto-tab-nav .wpcfto-nav div[data-section="single_listing"],
            .wpcfto-tab-nav .wpcfto-nav div[data-section="user_dealer"] {
                display: none !important;
            }
		</style>
		<?php //phpcs:enable ?>
		<?php
	}
}


// manage search results transients for modern inventory.
add_action( 'wp_ajax_stm_set_query_transients', 'stm_set_query_transients' );
add_action( 'wp_ajax_nopriv_stm_set_query_transients', 'stm_set_query_transients' );
function stm_set_query_transients() {
	$blog_id = get_current_blog_id();
	if ( isset( $_COOKIE[ 'stm_visitor_' . $blog_id ] ) ) {
		$fake_id = sanitize_text_field( $_COOKIE[ 'stm_visitor_' . $blog_id ] );

		$_inventory_query = apply_filters( 'stm_listings_input', null, 'inventory_query' );
		$_inventory_link  = apply_filters( 'stm_listings_input', null, 'inventory_link' );

		set_transient( 'stm_search_results_query_' . $fake_id, sanitize_text_field( $_inventory_query ), HOUR_IN_SECONDS );
		set_transient( 'stm_modern_inventory_link_' . $fake_id, filter_var( $_inventory_link, FILTER_SANITIZE_URL ), HOUR_IN_SECONDS );
	}
}


// delete query transients everwhere except listing single page.
add_action( 'template_redirect', 'stm_delete_transients_everywhere' );
function stm_delete_transients_everywhere() {
	if ( ! is_singular( apply_filters( 'stm_listings_post_type', 'listings' ) ) ) {
		$blog_id = get_current_blog_id();
		if ( isset( $_COOKIE[ 'stm_visitor_' . $blog_id ] ) ) {
			$fake_id = sanitize_text_field( $_COOKIE[ 'stm_visitor_' . $blog_id ] );
			delete_transient( 'stm_search_results_query_' . $fake_id );
			delete_transient( 'stm_modern_inventory_link_' . $fake_id );
		}
	}
}


// do stuff after (ONLY theme or plugin) update.
add_action( 'upgrader_process_complete', 'stm_run_after_theme_plugin_update', 10, 2 );
function stm_run_after_theme_plugin_update( $upgrader_object, $options ) {
	if ( ! empty( $options['action'] ) && 'update' === $options['action'] && in_array( $options['type'], array( 'theme', 'plugin' ), true ) ) {
		// refresh motors icon set.
		stm_refresh_motors_iconset();
	}
}


// refresh default motors icon set.
if ( ! function_exists( 'stm_refresh_motors_iconset' ) ) {
	function stm_refresh_motors_iconset() {
		$existing_packs = get_option( 'stm_fonts' );
		if ( false !== $existing_packs && ! empty( $existing_packs['stm-icon'] ) ) {
			unset( $existing_packs['stm-icon'] );
			update_option( 'stm_fonts', $existing_packs );
		}
	}
}

// get compare listings.
if ( ! function_exists( 'stm_get_compared_items' ) ) {
	function stm_get_compared_items( $listing_type = null ) {
		$post_types     = stm_listings_multi_type( true );
		$compared_items = array();
		$prefix         = stm_compare_cookie_name_prefix();

		if ( empty( $listing_type ) ) {
			foreach ( $post_types as $post_type ) {
				if ( ! empty( $_COOKIE[ $prefix . $post_type ] ) && is_array( $_COOKIE[ $prefix . $post_type ] ) ) {
					foreach ( $_COOKIE[ $prefix . $post_type ] as $key => $listing_id ) {
						if ( 'publish' !== get_post_status( $listing_id ) ) {
							stm_remove_compared_item( $listing_id );
						}
					}

					$compared_items = array_merge( $compared_items, $_COOKIE[ $prefix . $post_type ] );
				}
			}
		} elseif ( ! empty( $listing_type ) && in_array( $listing_type, $post_types, true ) ) {
			if ( ! empty( $_COOKIE[ $prefix . $listing_type ] ) && is_array( $_COOKIE[ $prefix . $listing_type ] ) ) {
				foreach ( $_COOKIE[ $prefix . $listing_type ] as $key => $listing_id ) {
					if ( 'publish' !== get_post_status( $listing_id ) ) {
						stm_remove_compared_item( $listing_id );
					}
				}

				$compared_items = $_COOKIE[ $prefix . $listing_type ];
			}
		}

		return array_values( $compared_items );
	}
}

if ( ! function_exists( 'stm_motors_get_compared_items' ) ) {
	function stm_motors_get_compared_items( $compared_items, $listing_type = null ) {
		return stm_get_compared_items( $listing_type );
	}

	add_filter( 'stm_get_compared_items', 'stm_motors_get_compared_items', 10, 2 );
}

// add listing to compare list.
if ( ! function_exists( 'stm_set_compared_item' ) ) {
	function stm_set_compared_item( $item_id = null ) {
		if ( ! empty( $item_id ) && is_numeric( $item_id ) ) {
			$post_types = apply_filters( 'stm_listings_multi_type', array( 'listings' ) );
			$post_type  = get_post_type( $item_id );

			if ( in_array( $post_type, $post_types, true ) ) {
				$prefix = apply_filters( 'stm_compare_cookie_name_prefix', '' );

				if ( empty( $_COOKIE[ $prefix . $post_type ] ) || ! is_array( $_COOKIE[ $prefix . $post_type ] ) ) {
					$_COOKIE[ $prefix . $post_type ] = array();
				}

				if ( 'publish' === get_post_status( $item_id ) ) {
					$status = setcookie( $prefix . $post_type . '[' . $item_id . ']', $item_id, time() + ( 86400 * 30 ), '/' );

					return $status;
				}
			}
		}

		return false;
	}
}

add_filter( 'wp_kses_allowed_html', 'stm_wp_kses_allowed_html' );
function stm_wp_kses_allowed_html( $allowed_html ) {
	$allowed_atts = array(
		'align'       => array(),
		'class'       => array(),
		'type'        => array(),
		'id'          => array(),
		'dir'         => array(),
		'lang'        => array(),
		'style'       => array(),
		'xml:lang'    => array(),
		'src'         => array(),
		'alt'         => array(),
		'href'        => array(),
		'rel'         => array(),
		'rev'         => array(),
		'target'      => array(),
		'novalidate'  => array(),
		'value'       => array(),
		'name'        => array(),
		'tabindex'    => array(),
		'action'      => array(),
		'method'      => array(),
		'for'         => array(),
		'width'       => array(),
		'height'      => array(),
		'data'        => array(),
		'title'       => array(),
		'placeholder' => array(),
		'selected'    => array(),
	);

	$allowed_html['select']             = $allowed_atts;
	$allowed_html['input']              = $allowed_atts;
	$allowed_html['option']             = $allowed_atts;
	$allowed_html['option']['selected'] = array();

	return $allowed_html;
}


add_filter( 'stm_motors_all_default_icons', 'stm_motors_get_all_default_icons' );
function stm_motors_get_all_default_icons( $icon_conf = array() ) {
	// generate charmap files if don't exist.
	$jsons = array(
		'theme_icons',
		'aircrafts_icons',
		'auto_parts_icons',
		'boat_icons',
		'listing_icons',
		'magazine_icons',
		'moto_icons',
		'rental_one_icons',
		'service_icons',
	);

	foreach ( $jsons as $filename ) {
		if ( ! file_exists( get_template_directory() . '/assets/icons_json/' . $filename . '_charmap.json' ) ) {
			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ABSPATH . '/wp-admin/includes/file.php';
				WP_Filesystem();
			}

			$json_content = json_decode( file_get_contents( get_template_directory_uri() . '/assets/icons_json/' . $filename . '.json' ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

			$icons_collect = array();

			foreach ( $json_content->icons as $k => $icon ) {
				$icons_collect['icons'][] = $icon->properties->name;
			}

			$json = wp_json_encode( $icons_collect );

			$wp_filesystem->put_contents( get_template_directory() . '/assets/icons_json/' . $filename . '_charmap.json', $json, FS_CHMOD_FILE );
		}
	}

	// get all available iconsets.
	$icon_conf = array(
		array(
			'name'       => esc_html__( 'Default icons', 'motors' ),
			'handle'     => 'theme_icons',
			'prefix'     => 'stm-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-default.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/theme_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-icon-car',
		),
		array(
			'name'       => esc_html__( 'Aircrafts icons', 'motors' ),
			'handle'     => 'aircrafts_icons',
			'prefix'     => 'ac-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-aircrafts.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/aircrafts_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-boats-icon-fan',
		),
		array(
			'name'       => esc_html__( 'Auto Parts icons', 'motors' ),
			'handle'     => 'auto_parts_icons',
			'prefix'     => 'icon-ap-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-auto-parts.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/auto_parts_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-icon-gear',
		),
		array(
			'name'       => esc_html__( 'Boats icons', 'motors' ),
			'handle'     => 'boat_icons',
			'prefix'     => 'stm-boats-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-boats.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/boat_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-boats-icon-s_ship',
		),
		array(
			'name'       => esc_html__( 'Listings icons', 'motors' ),
			'handle'     => 'listing_icons',
			'prefix'     => 'stm-lt-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-listing-two.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/listing_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-lt-icon-add_car',
		),
		array(
			'name'       => esc_html__( 'Magazine icons', 'motors' ),
			'handle'     => 'magazine_icons',
			'prefix'     => 'mg-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-magazine.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/magazine_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'mg-icon-standart',
		),
		array(
			'name'       => esc_html__( 'Motorcycles icons', 'motors' ),
			'handle'     => 'moto_icons',
			'prefix'     => 'stm-moto-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-motorcycles.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/moto_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-moto-icon-motorcycle',
		),
		array(
			'name'       => esc_html__( 'Rental icons', 'motors' ),
			'handle'     => 'rental_one_icons',
			'prefix'     => 'stm-rental-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-rental.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/rental_one_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-rental-gps_rent',
		),
		array(
			'name'       => esc_html__( 'Service icons', 'motors' ),
			'handle'     => 'service_icons',
			'prefix'     => 'stm-service-icon-',
			'style_url'  => get_template_directory_uri() . '/assets/css/iconset-service.css',
			'charmap'    => get_template_directory_uri() . '/assets/icons_json/service_icons_charmap.json',
			'v'          => STM_THEME_VERSION,
			'label_icon' => 'stm-service-icon-add_check',
		),
	);

	return $icon_conf;
}

add_action( 'get_footer', 'stm_single_listing_edit_panel' );
if ( ! function_exists( 'stm_single_listing_edit_panel' ) ) {
	function stm_single_listing_edit_panel() {
		global $current_user, $post;

		if ( ! $current_user->exists() ) {
			return;
		}

		$post_types = array( apply_filters( 'stm_listings_post_type', 'listings' ) );

		if ( stm_is_multilisting() ) {
			$listings = STMMultiListing::stm_get_listings();
			if ( ! empty( $listings ) ) {
				foreach ( $listings as $listing ) {
					$post_types[] = $listing['slug'];
				}
			}
		}

		if ( apply_filters( 'is_listing', array() ) && is_singular( $post_types ) ) {
			if ( empty( $post->post_author ) || absint( $post->post_author ) !== absint( $current_user->ID ) ) {
				return;
			}

			wp_enqueue_style( 'stm-listing-edit-panel' );

			set_query_var( 'is_single', true );

			get_template_part( 'partials/single-car-listing/car-edit', 'panel' );
		}
	}
}

if ( ! function_exists( 'stm_underconstruction_mode' ) ) {
	function stm_underconstruction_mode() {
		if ( is_excluded_request() ) {
			return;
		}
		$underconstruction_mode    = apply_filters( 'stm_me_get_nuxy_mod', '', 'coming_soon_page' );
		$underconstruction_page_id = apply_filters( 'stm_me_get_nuxy_mod', '', 'coming_soon_page_dropdown' );
		if ( $underconstruction_mode && $underconstruction_page_id ) {
			display_underconstruction_page( $underconstruction_page_id );
			wp_footer();
			die();
		}
	}
}
add_action( 'wp', 'stm_underconstruction_mode', 10 );

function is_excluded_request() {
	if ( defined( 'DOING_CRON' ) && DOING_CRON ) {
		return true;
	}
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		return true;
	}
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return true;
	}
	$request_uri   = trailingslashit( strtolower( wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ) );
	$excluded_uris = array(
		'/wp-admin/',
		'/feed/',
		'/feed/rss/',
		'/feed/rss2/',
		'/feed/rdf/',
		'/feed/atom/',
		'/admin/',
		'/wp-login.php',
	);
	if ( in_array( $request_uri, $excluded_uris, true ) || current_user_can( 'manage_options' ) ) {
		return true;
	}
	return false;
}

function display_underconstruction_page( $page_id ) {
	get_header();
	$page_bg = wp_get_attachment_image_src( get_post_thumbnail_id( $page_id ), 'full' );
	if ( ! empty( $page_bg[0] ) ) :
		?>
		<style>
			body {
				background-image: url("<?php echo esc_url( $page_bg[0] ); ?>");
				background-position: 50% 50%;
				background-size: cover;
				background-attachment: fixed;
			}
		</style>
		<?php
	endif;
	if ( defined( 'ELEMENTOR_VERSION' ) && is_elementor_page( $page_id ) ) {
		display_elementor_page( $page_id );
	} elseif ( defined( 'WPB_VC_VERSION' ) && is_visual_composer_page( $page_id ) ) {
		display_visual_composer_page( $page_id );
	}
}

function is_elementor_page( $page_id ) {
	return ! empty( get_post_meta( $page_id, '_elementor_edit_mode', true ) );
}

function is_visual_composer_page( $page_id ) {
	return 'true' === get_post_meta( $page_id, '_wpb_vc_js_status', true );
}

function display_elementor_page( $page_id ) {
	delete_post_meta( $page_id, '_wpb_vc_js_status' );
	$elementor = \Elementor\Plugin::instance();
	$elementor->frontend->init();
	$page_content = $elementor->frontend->get_builder_content( $page_id );
	echo do_shortcode( $page_content );
}

function display_visual_composer_page( $page_id ) {
	delete_post_meta( $page_id, '_elementor_edit_mode' );
	enqueue_visual_composer_assets();
	?>
	<div class="container">
		<?php
		$page_post    = get_post( $page_id );
		$page_content = $page_post->post_content;
		WPBMap::addAllMappedShortcodes();
		echo do_shortcode( apply_filters( 'the_content', $page_content ) );
		$shortcodes_custom_css = get_post_meta( $page_id, '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$shortcodes_custom_css = wp_strip_all_tags( $shortcodes_custom_css );
			echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			echo esc_html( $shortcodes_custom_css );
			echo '</style>';
		}
		?>
	</div>
	<?php
}

function enqueue_visual_composer_assets() {
	$front_css_file = vc_asset_url( 'css/js_composer.min.css' );
	wp_register_style( 'js_composer_front', $front_css_file, array(), WPB_VC_VERSION );
	wp_register_script( 'wpb_composer_front_js', vc_asset_url( 'js/dist/js_composer_front.min.js' ), array( 'jquery-core' ), WPB_VC_VERSION, true );
	wp_enqueue_script( 'wpb_composer_front_js' );
	wp_enqueue_style( 'js_composer_front' );
}
