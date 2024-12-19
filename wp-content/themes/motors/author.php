<?php
$user = wp_get_current_user();

$vars = get_queried_object();
?>

<?php get_header(); ?>
<?php
	$user         = get_queried_object();
	$current_user = wp_get_current_user();

	$display_footer = '';
if ( $user->ID === $current_user->ID && empty( $_GET['view-myself'] ) ) {
	?>
		<style type="text/css">
			footer#footer {
				display: none;
			}
		</style>

		<script>
			jQuery(document).ready(function(){
				stm_private_user_height();

				<?php if ( ! empty( $_GET['stm_unmark_as_sold_car'] ) ) : ?>
					window.history.pushState('','','<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>');
				<?php endif; ?>

				<?php if ( ! empty( $_GET['stm_mark_as_sold_car'] ) ) : ?>
					window.history.pushState('','','<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>');
				<?php endif; ?>

				<?php if ( ! empty( $_GET['stm_disable_user_car'] ) ) : ?>
					window.history.pushState('','','<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>');
				<?php endif; ?>

				<?php if ( ! empty( $_GET['stm_enable_user_car'] ) ) : ?>
					window.history.pushState('','','<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>');
				<?php endif; ?>

				<?php if ( ! empty( $_GET['stm_move_trash_car'] ) ) : ?>
					window.history.pushState('','','<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>');
				<?php endif; ?>

				<?php if ( ! empty( $_GET['listing_type'] ) ) : ?>
					window.history.pushState('','','<?php echo esc_url( apply_filters( 'stm_get_author_link', '' ) ); ?>');
				<?php endif; ?>
			});

			jQuery(window).on('load', function(){
				stm_private_user_height();
			});

			jQuery(window).on('resize', function(){
				stm_private_user_height();
			});

			function stm_private_user_height() {
				var $ = jQuery;
				var windowH = $(window).outerHeight();
				var topBarH = $('#top-bar').outerHeight();
				var headerH = $('#header').outerHeight();

				var topH = 0;

				if(topBarH != null) {
					topH = topBarH;
				}

				if(headerH != null) {
					topH += headerH;
				}

				var minH = windowH - topH;

				$('.stm-user-private-sidebar').css({
					'min-height' : minH + 'px'
				})
			}
		</script>
	<?php
}

if ( isset( $_GET['view-myself'] ) && ! empty( $_GET['view-myself'] ) ) {
	get_template_part( 'partials/user/user-public-profile', 'route' );
} else {
	if ( is_user_logged_in() ) {
		get_template_part( 'partials/user/user-private-profile', 'route' );
	} else {
		get_template_part( 'partials/user/user-public-profile', 'route' );
	}
}
?>

<?php get_footer(); ?>
