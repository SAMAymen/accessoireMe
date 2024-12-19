<?php
$user    = get_queried_object();
$user_id = $user->ID;

if ( is_user_logged_in() ) :

	$user_current    = wp_get_current_user();
	$user_current_id = $user_current->ID;
	$user_reviews    = stm_get_user_reviews( $user_current_id, $user_id );

	if ( intval( $user_reviews->found_posts ) > 0 ) : ?>

		<div class="stm_user_added_review heading-font"><?php esc_html_e( 'You have already added review. If you leave another one review, previous will be deleted.', 'motors' ); ?></div>

	<?php endif; ?>

	<form action="" method="post" id="stm_submit_review">
		<input type="hidden" name="stm_user_on" value="<?php echo intval( $user_id ); ?>"/>
		<div class="stm-write-dealer-review clearfix">
			<div class="left">
				<div class="form-group">
					<h4><?php esc_html_e( 'Title', 'motors' ); ?></h4>
					<input type="text" name="stm_title"
						placeholder="<?php esc_attr_e( 'Example: Great Service, clean facilities, etc', 'motors' ); ?>"
						required/>
				</div>
				<div class="form-group">
					<h4><?php esc_html_e( 'Your Review', 'motors' ); ?></h4>
					<textarea name="stm_content" placeholder="<?php esc_attr_e( 'Enter Your Review', 'motors' ); ?>"></textarea>
				</div>
				<div class="stm-checker-required">
					<label>
						<input type="checkbox" name="stm_required"/>
						<span class="stm-label"><?php esc_html_e( 'I am not a dealer, and I am not employed by a dealership.', 'motors' ); ?></span>
					</label>
				</div>
				<a href="#" class="dp-in button disabled hidden-xs"><?php esc_html_e( 'Submit review', 'motors' ); ?></a>
				<i class="stm-icon-load1 hidden-xs"></i>
			</div>
			<div class="right">
				<?php
				$rate_1_label = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Customer Service', 'motors' ), 'dealer_rate_1' );
				$rate_2_label = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Buying Process', 'motors' ), 'dealer_rate_2' );
				$rate_3_label = apply_filters( 'motors_vl_get_nuxy_mod', esc_html__( 'Overall Experience', 'motors' ), 'dealer_rate_3' );
				?>

				<?php
				for ( $i = 1; $i < 4; $i++ ) :
					$label = ${'rate_' . $i . '_label'};
					if ( ! empty( $label ) ) :
						?>
						<h4><?php echo esc_html( $label ); ?></h4>
						<div class="stm-star-rating" data-width="0">
							<div class="inner">
								<div class="stm-star-rating-upper"></div>
								<div class="stm-star-rating-lower"></div>
							</div>
							<span><strong>0</strong> <?php esc_html_e( 'out of', 'motors' ); ?> 5</span>
							<input type="hidden" name="stm_rate_<?php echo esc_attr( $i ); ?>"/>
						</div>
					<?php endif; ?>
				<?php endfor; ?>

				<div class="stm-write-recommend">
					<h4><?php esc_html_e( 'Would you recommend this dealer?', 'motors' ); ?></h4>
					<label>
						<input type="radio" name="recommend" value="yes" checked/>
						<span class="stm-label"><?php esc_html_e( 'Yes', 'motors' ); ?></span>
					</label>
					<label>
						<input type="radio" name="recommend" value="no"/>
						<span class="stm-label"><?php esc_html_e( 'No', 'motors' ); ?></span>
					</label>
				</div>

			</div>

			<div class="clearfix"></div>
			<a href="#" class="dp-in button disabled visible-xs"><?php esc_html_e( 'Submit review', 'motors' ); ?></a>
			<div id="write-review-message"></div>

		</div>
	</form>

	<script>
		jQuery(document).ready(function () {
			var $ = jQuery;
			$('.stm-write-dealer-review .stm-star-rating .inner').on('mousemove', function (event) {
				var parentOffset = $(this).offset().left;
				var parentWidth = $(this).width();
				var currentMousePos = parseInt(event.pageX);
				currentMousePos = parentWidth - ((parentOffset + parentWidth) - currentMousePos);
				var stmCurrent = (currentMousePos * 100) / parentWidth;

				var ratingChange = $(this).closest('.stm-star-rating').find('span strong');

				if (stmCurrent <= 20) {
					$(this).find('.stm-star-rating-upper').css('width', '20%');
					ratingChange.text('1');
				} else if (stmCurrent > 20 && stmCurrent <= 40) {
					$(this).find('.stm-star-rating-upper').css('width', '40%');
					ratingChange.text('2');
				} else if (stmCurrent > 40 && stmCurrent <= 60) {
					$(this).find('.stm-star-rating-upper').css('width', '60%');
					ratingChange.text('3');
				} else if (stmCurrent > 60 && stmCurrent <= 80) {
					$(this).find('.stm-star-rating-upper').css('width', '80%');
					ratingChange.text('4');
				} else if (stmCurrent > 80) {
					$(this).find('.stm-star-rating-upper').css('width', '100%');
					ratingChange.text('5');
				} else {
					$(this).find('.stm-star-rating-upper').css('width', '0%');
				}
			});

			$('.stm-write-dealer-review .stm-star-rating .inner').on('click', function (event) {
				var parentOffset = $(this).offset().left;
				var parentWidth = $(this).width();
				var currentMousePos = parseInt(event.pageX);
				currentMousePos = parentWidth - ((parentOffset + parentWidth) - currentMousePos);
				var stmCurrent = (currentMousePos * 100) / parentWidth;

				if (stmCurrent <= 20) {
					$(this).find('.stm-star-rating-upper').css('width', '20%');
					$(this).closest('.stm-star-rating').attr('data-width', '1');
					$(this).closest('.stm-star-rating').find('input').val(1);
				} else if (stmCurrent > 20 && stmCurrent <= 40) {
					$(this).find('.stm-star-rating-upper').css('width', '40%');
					$(this).closest('.stm-star-rating').attr('data-width', '2');
					$(this).closest('.stm-star-rating').find('input').val(2);
				} else if (stmCurrent > 40 && stmCurrent <= 60) {
					$(this).find('.stm-star-rating-upper').css('width', '60%');
					$(this).closest('.stm-star-rating').attr('data-width', '3');
					$(this).closest('.stm-star-rating').find('input').val(3);
				} else if (stmCurrent > 60 && stmCurrent <= 80) {
					$(this).find('.stm-star-rating-upper').css('width', '80%');
					$(this).closest('.stm-star-rating').attr('data-width', '4');
					$(this).closest('.stm-star-rating').find('input').val(4);
				} else if (stmCurrent > 80) {
					$(this).find('.stm-star-rating-upper').css('width', '100%');
					$(this).closest('.stm-star-rating').attr('data-width', '5');
					$(this).closest('.stm-star-rating').find('input').val(5);
				} else {
					$(this).find('.stm-star-rating-upper').css('width', '0%');
					$(this).closest('.stm-star-rating').attr('data-width', '0');
					$(this).closest('.stm-star-rating').find('input').val(0);
				}
			});

			$('.stm-write-dealer-review .stm-star-rating .inner').on('mouseleave', function (event) {
				var stmChangeWidth = $(this).closest('.stm-star-rating').attr('data-width');
				var ratingChange = $(this).closest('.stm-star-rating').find('span strong');
				ratingChange.text(stmChangeWidth);
				stmChangeWidth = (stmChangeWidth * 100) / 5;
				$(this).find('.stm-star-rating-upper').css('width', stmChangeWidth + '%');
			});

			$('#stm_submit_review input[type="checkbox"]').on('click', function () {
				var checked = $(this).prop('checked');
				if (checked) {
					$('#stm_submit_review .button').removeClass('disabled');
				} else {
					$('#stm_submit_review .button').addClass('disabled');
				}
			})

		});
	</script>

<?php else : ?>

	<h4 class="stm-login-review-leave">
		<a href="<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>">
			<?php esc_html_e( 'Login', 'motors' ); ?>
		</a> <?php esc_html_e( 'to leave a review', 'motors' ); ?>
	</h4>

<?php endif; ?>
