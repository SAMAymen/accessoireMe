<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

if ( empty( $image_size ) ) {
	$image_size = '213x142';
}

$thumbnail = '';

if ( ! empty( $image ) ) {
	$image = explode( ',', $image );
	if ( ! empty( $image[0] ) ) {
		$image          = $image[0];
		$post_thumbnail = wpb_getImageBySize(
			array(
				'attach_id'  => $image,
				'thumb_size' => $image_size,
			)
		);

		$thumbnail = $post_thumbnail['thumbnail'];
	}
}

$stm_icon_class = 'stm_icon_class_' . wp_rand( 0, 99999 );

?>
<div class="testimonial-unit <?php echo esc_attr( $style_view ); ?>">
	<?php if ( 'style_1' === $style_view ) { ?>
	<div class="clearfix">
		<?php if ( ! empty( $thumbnail ) ) : ?>
			<div class="image">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</div>
		<?php endif; ?>
		<?php if ( 'car_rental' === stm_get_current_layout() ) : ?>
			<div class="testimonial-info">
				<?php if ( ! empty( $author ) ) : ?>
					<div class="author heading-font">
						<?php echo esc_attr( $author ); ?>
					</div>


					<?php if ( ! empty( $author_car ) ) : ?>
					<div class="author-car">
						<i class="stm-icon-car"></i>
						<?php echo esc_attr( $author_car ); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
		<?php endif; ?>

		<div class="content">
			<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
		</div>

	</div>
		<?php if ( 'car_rental' !== stm_get_current_layout() ) : ?>
		<div class="testimonial-meta">
			<?php if ( ! empty( $author ) ) : ?>
				<div class="author heading-font">
					<?php echo esc_attr( $author ); ?>
				</div>


				<?php if ( ! empty( $author_car ) ) : ?>
				<div class="author-car">
					<i class="stm-icon-car"></i>
					<?php echo esc_attr( $author_car ); ?>
				</div>
			<?php endif; ?>
		</div>
			<?php endif; ?>
	<?php endif; ?>
		<?php
	} else {
		?>
	<div class="clearfix">
		<?php if ( ! empty( $thumbnail ) ) : ?>
			<div class="image">
				<?php echo wp_kses_post( $thumbnail ); ?>
			</div>
		<?php endif; ?>
		<div class="author_info">
			<div class="author_name heading-font"><?php echo esc_html( $author ); ?></div>
			<div class="author_position heading-font"><?php echo ( ! empty( $author_position ) ) ? esc_html( $author_position ) : ''; ?></div>
		</div>
		<div class="content normal_font">
			<?php echo wp_kses_post( wpb_js_remove_wpautop( $content ) ); ?>
		</div>
		<?php if ( ! empty( $icon ) ) : ?>
			<?php if ( ! empty( $icon_color ) ) : ?>
				<?php
				wp_enqueue_style( 'icon-inline-styles', get_stylesheet_uri(), array(), WPB_VC_VERSION );
				$custom_css = '.' . esc_attr( $stm_icon_class ) . '::before { color: ' . esc_attr( $icon_color ) . '; }';
				wp_add_inline_style( 'icon-inline-styles', $custom_css );
				?>
			<?php endif; ?>
			<div class="icon">
				<i class="<?php echo esc_attr( $icon ); ?> <?php echo esc_attr( $stm_icon_class ); ?>"></i>
			</div>
		<?php endif; ?>
	</div>
		<?php
	}
	?>
</div>
