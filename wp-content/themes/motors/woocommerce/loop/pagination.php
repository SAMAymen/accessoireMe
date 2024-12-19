<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_query;

if ( $wp_query->max_num_pages <= 1 ) {
	return;
}
?>
<div class="row">
	<div class="col-md-12">
		<div class="stm-blog-pagination">
			<?php if ( get_previous_posts_link() ) {
				echo '<div class="stm-prev-next stm-prev-btn">';
				previous_posts_link( '<i class="fas fa-angle-left"></i>' );
				echo '</div>';
			} else {
				echo '<div class="stm-prev-next stm-prev-btn disabled"><i class="fas fa-angle-left"></i></div>';
			}

			echo paginate_links( array(
				'type'      => 'list',
				'prev_next' => false
			) );

			if ( get_next_posts_link() ) {
				echo '<div class="stm-prev-next stm-next-btn">';
				next_posts_link( '<i class="fas fa-angle-right"></i>' );
				echo '</div>';
			} else {
				echo '<div class="stm-prev-next stm-next-btn disabled"><i class="fas fa-angle-right"></i></div>';
			} ?>
		</div>
	</div>
</div>
