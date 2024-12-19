<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

$css_class          = ( ! empty( $css ) ) ? apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) ) : '';
$css_class_icon     = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css_icon, ' ' ) );
$stm_link           = vc_build_link( $link );
$class_icon_box     = 'icon_box_' . wp_rand( 1, 99999 );
$class_icon_element = 'icon_element_' . wp_rand( 1, 99999 );

if ( ! empty( $box_bg_color ) ) {
	$rgba = apply_filters( 'stm_hex2rgb', $box_bg_color );
}

if ( empty( $box_text_color ) ) {
	$box_text_color = '#232628';
}

if ( empty( $icon_size ) ) {
	$icon_size = '54';
	if ( apply_filters( 'stm_is_boats', false ) ) {
		$icon_size = '48';
	}
}

if ( empty( $style_layout ) ) {
	$style_layout = 'car_dealer';
}

if ( empty( $content_font_size ) ) {
	$content_font_size = 14;
}
?>


<?php if ( ! empty( $stm_link['url'] ) && empty( $btn_text ) ) : ?>
<a
		class="icon-box-link"
		href="<?php echo esc_url( $stm_link['url'] ); ?>"
		title="
		<?php
		if ( ! empty( $stm_link['title'] ) ) {
			echo esc_attr( $stm_link['title'] );
		}
		?>
		"
	<?php if ( ! empty( $stm_link['target'] ) ) : ?>
		target="_blank"
	<?php endif; ?>>
	<?php endif; ?>

	<div class="icon-box <?php echo esc_attr( $css_class . ' ' . $class_icon_box ); ?> stm-layout-box-<?php echo esc_attr( $style_layout ); ?> <?php
	if ( ! empty( $btn_text ) ) {
		echo 'with_btn';
	}

	if ( empty( $icon ) ) {
		echo 'no-icon';
	}
	?>
" style="color:<?php echo esc_attr( $box_text_color ); ?>">
		<div class="boat-line"></div>
		<?php if ( ! empty( $icon ) ) : ?>
			<div
				class="icon boat-third-color <?php echo esc_attr( $class_icon_element . ' ' . $css_class_icon ); ?>"
				style="font-size:<?php echo esc_attr( floatval( $icon_size ) ); ?>px;
				<?php
				if ( ! empty( $icon_color ) ) {
					echo esc_attr( 'color:' . $icon_color . '; ' );
				}
				if ( ! empty( $icon_width ) ) {
					echo esc_attr( $icon_width );
				}
				if ( ! empty( $icon_bg_color ) ) {
					echo esc_attr( 'background-color:' . $icon_bg_color );
				}
				?>
				">
				<i class="<?php echo esc_attr( $icon ); ?>"></i>
			</div>
		<?php endif; ?>
		<div class="icon-text">
			<?php if ( ! empty( $title ) ) : ?>
			<<?php echo esc_attr( $title_holder ); ?> class="title heading-font"
			style="color:<?php echo esc_attr( $box_text_color ); ?>">
				<?php echo esc_attr( $title ); ?>
		</<?php echo esc_attr( $title_holder ); ?>>
	<?php endif; ?>
		<?php if ( ! empty( $content ) ) : ?>
			<div class="content heading-font" 
			style="
				<?php
				if ( ! empty( $line_height ) ) {
					echo esc_attr( 'line-height:' . floatval( $line_height ) . 'px;' );
				}
				if ( ! empty( $content_font_size ) ) {
					echo esc_attr( 'font-size:' . floatval( $content_font_size ) . 'px;' );
				}
				if ( ! empty( $content_font_weight ) ) {
					echo esc_attr( 'font-weight:' . floatval( $content_font_weight ) . ';' );
				}
				?>
			">
				<?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $stm_link['url'] ) && ! empty( $btn_text ) ) : ?>
		<a class="icon-box-link-btn button" href="<?php echo esc_url( $stm_link['url'] ); ?>"
			title="
			<?php
			if ( ! empty( $stm_link['title'] ) ) {
				echo esc_attr( $stm_link['title'] );
			}
			?>
			" 
			<?php
			if ( ! empty( $stm_link['target'] ) ) :
				?>
				target="_blank" <?php endif; ?>>
			<?php endif; ?>
			<?php echo esc_html( apply_filters( 'stm_dynamic_string_translation', $btn_text, 'Button text (Stm Icon Box)' ) ); ?>
			<?php if ( ! empty( $stm_link['url'] ) && ! empty( $btn_text ) ) : ?>
		</a>
	<?php endif; ?>
	</div>
	<?php if ( ! empty( $bottom_triangle ) && $bottom_triangle ) : ?>
		<div class="icon-box-bottom-triangle">

		</div>
	<?php endif; ?>
	<div class="clearfix"></div>
	</div>

	<?php if ( ! empty( $stm_link['url'] ) && empty( $btn_text ) ) : ?>
</a>
<?php endif; ?>


<style>
	<?php if ( ! empty( $icon_color ) ) : ?>
	.<?php echo esc_attr( $class_icon_element ); ?> i::before {
		color: <?php echo esc_attr( $icon_color ); ?>!important;
	}
	<?php endif; ?>

	<?php if ( ! empty( $box_bg_color ) ) : ?>
		.<?php echo esc_attr( $class_icon_box ); ?> {
			background-color: <?php echo esc_attr( $box_bg_color ); ?>;
		}

		}
		.<?php echo esc_attr( $class_icon_box ); ?> .icon-box-bottom-triangle {
			border-right-color: rgba(<?php echo esc_attr( $rgba ); ?>, 0.9);
		}

		.<?php echo esc_attr( $class_icon_box ); ?>:hover .icon-box-bottom-triangle {
			border-right-color: rgba(<?php echo esc_attr( $rgba ); ?>, 1);
		}
	<?php endif; ?>

	<?php if ( ! empty( $box_text_color ) ) : ?>
	.icon-box .icon-text .content a {
		color: <?php echo esc_attr( $box_text_color ); ?>;
	}
	<?php endif; ?>

	<?php if ( ! empty( $box_text_color_hover ) ) : ?>
	.<?php echo esc_attr( $class_icon_box ); ?>:hover .icon-text .content span,
	.<?php echo esc_attr( $class_icon_box ); ?>:hover .icon-text .content p {
		color: <?php echo esc_attr( $box_text_color_hover ); ?> !important;
	}
	<?php endif; ?>

	<?php if ( ! empty( $btn_color ) ) : ?>
	.<?php echo esc_attr( $class_icon_box ); ?> .icon-text .icon-box-link-btn.button {
		background-color: <?php echo esc_attr( $btn_color ); ?> !important;
	}
	<?php endif; ?>

	<?php if ( ! empty( $btn_hover_color ) ) : ?>
	.<?php echo esc_attr( $class_icon_box ); ?> .icon-text .icon-box-link-btn.button:hover:before {
		background-color: <?php echo esc_attr( $btn_hover_color ); ?> !important;
	}

		<?php if ( '#ffffff' === $btn_hover_color ) : ?>
	.<?php echo esc_attr( $class_icon_box ); ?> .icon-text .icon-box-link-btn:hover {
		color: #333333 !important;
	}
	<?php endif; ?>

	<?php endif; ?>
</style>
