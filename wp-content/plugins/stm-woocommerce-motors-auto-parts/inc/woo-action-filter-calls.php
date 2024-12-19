<?php


function stm_woocommerce_form_start() {
	echo '<div class="stm-woo-form-wrap">';
}

function stm_woocommerce_form_end() {
	echo '</div>';
}

add_action( 'woocommerce_login_form_start', 'stm_woocommerce_form_start' );
add_action( 'woocommerce_login_form_end', 'stm_woocommerce_form_end' );
add_action( 'woocommerce_register_form_start', 'stm_woocommerce_form_start' );
add_action( 'woocommerce_register_form_end', 'stm_woocommerce_form_end' );
add_action( 'woocommerce_before_cart_table', 'stm_wcmap_add_cart_title_box' );
add_action( 'woocommerce_after_cart_item_name', 'stm_wcmap_woocommerce_after_cart_item_name', 100, 2 );

add_filter( 'woocommerce_layered_nav_term_html', 'stm_wcmap_woocommerce_layered_nav_term_html', 100, 4 );

function stm_wcmap_add_cart_title_box() {
	$cart_page_id = get_option( 'woocommerce_cart_page_id' );
	?>
	<div class="stm-cart-title-box">
		<?php woocommerce_breadcrumb( array( 'delimiter' => '<i class="fas fa-chevron-right"></i>' ) ); ?>
		<h2 class="heading-font"><?php echo esc_html( get_the_title( $cart_page_id ) ); ?></h2>
	</div>
	<?php
}


function stm_wcmap_woocommerce_after_cart_item_name( $cart_item, $cart_item_key ) {
	$category_list = '<ul>';

	$terms = get_the_terms( $cart_item['product_id'], 'product_cat' );
	foreach ( $terms as $term ) {
		$category_list .= '<li><a href="' . esc_url( get_category_link( $term->term_id ) ) . '" class="normal_font">' . $term->name . '</a></li>';
	}

	$category_list .= '</ul>';
	?>
	<div class="stm-categ-wrap">
		<?php echo wp_kses_post( apply_filters( 'stm_wcmap_cat_list_filter', $category_list ) ); ?>
	</div>
	<div class="rating-wrap">
		<?php
		$average_rating = $cart_item['data']->get_average_rating();
		$review_count   = $cart_item['data']->get_review_count();

		echo wp_kses_post( wc_get_rating_html( $average_rating, $review_count ) );
		?>
	</div>

	<?php
}

function stm_wcmap_woocommerce_layered_nav_term_html( $term_html, $term, $link, $count ) {
	$ad_image_id  = get_term_meta( $term->term_id, 'stm_attr_wcmap_image', true );
	$ad_image_url = wp_get_attachment_image_url( $ad_image_id, 'stm-wcmap-210-260' );
	$image_class  = ( $ad_image_url ) ? 'has-icon' : 'has-checkbox';
	$html         = '<a href="' . $link . '" class="term-link ' . $image_class . '">';

	if ( $ad_image_url ) {
		$html .= '<span class="term-img"><img src=" ' . $ad_image_url . ' " /></span>';
	}

	$html .= '<span class="term-name">';
	$html .= $term->name;

	if ( ! empty( $term->count ) ) {
		$html .= '<span class="term-count">(' . $term->count . ')</span>';
	}

	$html .= '</span>';
	$html .= '</a>';

	echo wp_kses_post( apply_filters( 'stm_wcmap_fc_html_filter', $html ) );
}
