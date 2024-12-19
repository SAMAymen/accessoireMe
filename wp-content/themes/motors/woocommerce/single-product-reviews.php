<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.3.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}


?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<h2 class="woocommerce-Reviews-title">
		<?php
		$count = $product->get_review_count();
		if ( 'yes' === get_option( 'woocommerce_enable_review_rating' ) && ( $count ) ) {
			/* translators: 1: reviews count 2: product name */
			printf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'motors' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );//phpcs:ignore
		} else {
			esc_html_e( 'Reviews', 'motors' );
		}
		?>
		</h2>

		<?php if ( have_comments() ) : ?>

			<ol class="commentlist">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => '&larr;',
							'next_text' => '&rarr;',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'motors' ); ?></p>

		<?php endif; ?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
					$commenter = wp_get_current_commenter();

					$comment_form = array(
						'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'motors' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'motors' ), get_the_title() ),
						'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'motors' ),
						'title_reply_before'  => '<span id="reply-title" class="comment-reply-title heading-font">',
						'title_reply_after'   => '</span>',
						'comment_notes_after' => '',
						'fields'              => array(
							'author' => '<div class="row">' .
										'<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">' .
										'<p class="comment-form-author">' .
										'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" placeholder="' . esc_attr__( 'Name', 'motors' ) . ' *" /></p>' .
										'</div>',
							'email'  => '<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">' .
										'<p class="comment-form-email">' .
										'<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" aria-required="true" placeholder="' . esc_attr__( 'Email', 'motors' ) . ' *" /></p>' .
										'</div>' .
										'</div>',
						),
						'label_submit'        => __( 'Submit', 'motors' ),
						'logged_in_as'        => '',
						'comment_field'       => '',
					);

					$account_page_url = wc_get_page_permalink( 'myaccount' );

					if ( $account_page_url ) {
						$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'motors' ), esc_url( $account_page_url ) ) . '</p>';
					}

					if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {

						$label = ( apply_filters( 'stm_is_auto_parts', false ) ) ? '<label for="rating">' . esc_html__( 'Your rating:', 'motors' ) . '</label>' : '';

						$comment_form['comment_field'] = '<p class="comment-form-rating">' . $label . '<select name="rating" id="rating">
									<option value="">' . esc_html__( 'Rate&hellip;', 'motors' ) . '</option>
									<option value="5">' . esc_html__( 'Perfect', 'motors' ) . '</option>
									<option value="4">' . esc_html__( 'Good', 'motors' ) . '</option>
									<option value="3">' . esc_html__( 'Average', 'motors' ) . '</option>
									<option value="2">' . esc_html__( 'Not that bad', 'motors' ) . '</option>
									<option value="1">' . esc_html__( 'Very poor', 'motors' ) . '</option>
								</select></p>';
					}

					$comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Your review', 'motors' ) . '"></textarea></p>';


					comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
					?>
			</div>
		</div>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'motors' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
