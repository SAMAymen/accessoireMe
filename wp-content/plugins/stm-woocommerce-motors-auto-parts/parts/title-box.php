<?php
$current_id          = get_the_ID();
$show_title_box      = 'hide';
$title_style         = '';
$is_shop             = false;
$is_account          = false;
$is_product          = false;
$is_product_category = false;

if ( function_exists( 'is_shop' ) && is_shop() ) {
	$is_shop = true;
}

if ( function_exists( 'is_product_category' ) && is_product_category() ) {
	$is_product_category = true;
}

if ( function_exists( 'is_product' ) && is_product() ) {
	$is_product = true;
}

$my_account_page_id = get_option( 'woocommerce_myaccount_page_id' );
if ( get_the_ID() === $my_account_page_id ) {
	$is_account = true;
}

if ( is_home() || is_category() || is_search() ) {
	$current_id = get_option( 'page_for_posts' );
}

if ( $is_shop ) {
	$current_id = get_option( 'woocommerce_shop_page_id' );
}

if ( is_checkout() ) {
	$is_shop = true;
}

$stm_title = '';

if ( is_home() ) {
	if ( ! get_option( 'page_for_posts' ) ) {
		$stm_title = __( 'News', 'stm-woocommerce-motors-auto-parts' );
	} else {
		$stm_title = get_the_title( $current_id );
	}
} elseif ( $is_product ) {
	$stm_title = esc_html__( 'Shop', 'stm-woocommerce-motors-auto-parts' );
} elseif ( $is_product_category ) {
	$stm_title  = single_cat_title( '', false );
	$current_id = get_option( 'woocommerce_shop_page_id' );
} elseif ( is_post_type_archive( apply_filters( 'stm_listings_post_type', 'listings' ) ) ) {
	$stm_title = apply_filters( 'stm_me_get_nuxy_mod', esc_html__( 'Inventory', 'stm-woocommerce-motors-auto-parts' ), 'classic_listing_title' );
} elseif ( is_category() ) {
	$stm_title = single_cat_title( '', false );
} elseif ( is_tag() ) {
	$stm_title = single_tag_title( '', false );
} elseif ( is_search() ) {
	$stm_title = __( 'Search', 'stm-woocommerce-motors-auto-parts' );
} elseif ( is_day() ) {
	$stm_title = get_the_time( 'd' );
} elseif ( is_month() ) {
	$stm_title = get_the_time( 'F' );
} elseif ( is_year() ) {
	$stm_title = get_the_time( 'Y' );
} else {
	$stm_title = get_the_title( $current_id );
}

$alignment                     = get_post_meta( $current_id, 'alignment', true );
$title_style_h1                = array();
$title_style_subtitle          = array();
$title_box_bg_color            = get_post_meta( $current_id, 'title_box_bg_color', true );
$title_box_font_color          = get_post_meta( $current_id, 'title_box_font_color', true );
$title_box_line_color          = get_post_meta( $current_id, 'title_box_line_color', true );
$title_box_custom_bg_image     = get_post_meta( $current_id, 'title_box_custom_bg_image', true );
$title_tag                     = ( empty( get_post_meta( $current_id, 'stm_title_tag', true ) ) ) ? 'h2' : get_post_meta( $current_id, 'stm_title_tag', true );
$sub_title                     = get_post_meta( $current_id, 'sub_title', true );
$breadcrumbs                   = get_post_meta( $current_id, 'breadcrumbs', true );
$breadcrumbs_font_color        = get_post_meta( $current_id, 'breadcrumbs_font_color', true );
$title_box_subtitle_font_color = get_post_meta( $current_id, 'title_box_subtitle_font_color', true );
$sub_title_instead             = get_post_meta( $current_id, 'sub_title_instead', true );


if ( empty( $alignment ) || is_post_type_archive( apply_filters( 'stm_listings_post_type', 'listings' ) ) ) {
	$alignment = 'left';
}


if ( $title_box_bg_color ) {
	$title_style .= 'background-color: ' . $title_box_bg_color . ';';
}

if ( $title_box_font_color ) {
	$title_style_h1['font_color'] = 'color: ' . $title_box_font_color . ';';
}

if ( $title_box_subtitle_font_color ) {
	$title_style_subtitle['font_color'] = 'color: ' . $title_box_subtitle_font_color . ';';
}

$title_box_custom_bg_image = wp_get_attachment_image_src( $title_box_custom_bg_image, 'full' );

if ( $title_box_custom_bg_image ) {
	$title_style .= "background-image: url('" . $title_box_custom_bg_image[0] . "');";
}

$show_title_box = get_post_meta( $current_id, 'title', true );
if ( 'hide' === $show_title_box ) {
	$show_title_box = false;
} else {
	$show_title_box = true;
}

if ( is_cart() || $is_shop || is_product_category() ) {
	$show_title_box = false;
	$breadcrumbs    = 'hide';
}

$additional_classes = '';

if ( empty( $sub_title ) && empty( $title_box_line_color ) ) {
	$additional_classes = ' small_title_box';
}
if ( ( $is_shop || $is_product || $is_product_category ) && 'show' === $breadcrumbs ) {
	$additional_classes .= ' no_woo_padding';
}

/*Only for blog*/
$blog_margin = '';
if ( 'post' === get_post_type() ) {
	if ( ! empty( $_GET['show-title-box'] ) && 'hide' === $_GET['show-title-box'] ) {
		$show_title_box = false;
	}
	if ( ! empty( $_GET['show-breadcrumbs'] ) && 'yes' === $_GET['show-breadcrumbs'] ) {
		$breadcrumbs = 'show';
		$blog_margin = 'stm-no-margin-bc';
	}
}

if ( 'hide' !== $breadcrumbs ) :

	if ( $is_shop || $is_product || $is_product_category || $is_account ) {
		woocommerce_breadcrumb( array( 'delimiter' => '<i class="fas fa-chevron-right"></i>' ) );
	} else {
		if ( function_exists( 'bcn_display' ) ) { ?>
			<div class="stm_breadcrumbs_unit heading-font <?php echo esc_attr( $blog_margin ); ?>">
				<div class="container">
					<div class="navxtBreads">
						<?php bcn_display(); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
endif;

if ( $show_title_box ) {
	$disable_overlay = '';
	if ( apply_filters( 'stm_is_motorcycle', false ) ) :
		$disable_overlay = get_post_meta( $current_id, 'disable_title_box_overlay', true );
		if ( ! empty( $disable_overlay ) && 'on' === $disable_overlay ) {
			$disable_overlay = ' disable_overlay';
		}
	endif;
	?>
	<div class="entry-header <?php echo esc_attr( $alignment . $additional_classes . $disable_overlay ); ?>"
		 style="<?php echo esc_attr( $title_style ); ?>">
		<div class="container">
			<div class="entry-title">
				<<?php echo esc_attr( $title_tag ); ?> class="h1"
				style="<?php echo esc_attr( implode( ' ', $title_style_h1 ) ); ?>">
				<?php
				if ( ! empty( $sub_title_instead ) && apply_filters( 'stm_is_motorcycle', false ) ) {
					echo wp_kses_post( balanceTags( $sub_title_instead, true ) );
				} else {
					echo wp_kses_post( balanceTags( $stm_title, true ) );
				}
				?>
			</<?php echo esc_attr( $title_tag ); ?>>
			<?php if ( $title_box_line_color ) : ?>
				<div class="colored-separator">
					<div class="first-long"
						<?php
						if ( ! empty( $title_box_line_color ) ) :
							?>
							style="background-color: <?php echo esc_attr( $title_box_line_color ); ?>" <?php endif; ?>></div>
					<div class="last-short"
						<?php
						if ( ! empty( $title_box_line_color ) ) :
							?>
							style="background-color: <?php echo esc_attr( $title_box_line_color ); ?>" <?php endif; ?>></div>
				</div>
			<?php endif; ?>
			<?php if ( $sub_title && ! is_search() ) { ?>
				<div class="sub-title h5" style="<?php echo esc_attr( implode( ' ', $title_style_subtitle ) ); ?>">
					<?php echo wp_kses_post( balanceTags( $sub_title, true ) ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
	</div>
	<?php
}
