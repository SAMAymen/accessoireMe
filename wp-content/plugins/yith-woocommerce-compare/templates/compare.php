<?php
/**
 * Compare table
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 * @var array $products An array of products to compare.
 */

defined( 'YITH_WOOCOMPARE' ) || exit; // Exit if accessed directly.

// Remove the style of WooCommerce.
if ( defined( 'WOOCOMMERCE_USE_CSS' ) && WOOCOMMERCE_USE_CSS ) {
	wp_dequeue_style( 'woocommerce_frontend_styles' );
}

$is_iframe = ! empty( $_REQUEST['iframe'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

wp_enqueue_script( 'jquery-imagesloaded', YITH_WOOCOMPARE_ASSETS_URL . '/js/imagesloaded.pkgd.min.js', array( 'jquery' ), '3.1.8', true );
wp_enqueue_script( 'jquery-fixedheadertable', YITH_WOOCOMPARE_ASSETS_URL . '/js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.19', true );
wp_enqueue_script( 'jquery-fixedcolumns', YITH_WOOCOMPARE_ASSETS_URL . '/js/FixedColumns.min.js', array( 'jquery', 'jquery-fixedheadertable' ), '3.2.6', true );

$widths = array();
foreach ( $products as $product ) {
	$widths[] = '{ "sWidth": "205px", resizeable:true }';
}

$table_text = get_option( 'yith_woocompare_table_text', __( 'Compare products', 'yith-woocommerce-compare' ) );
do_action( 'wpml_register_single_string', 'Plugins', 'plugin_yit_compare_table_text', $table_text );
$localized_table_text = apply_filters( 'wpml_translate_single_string', $table_text, 'Plugins', 'plugin_yit_compare_table_text' );

?><!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" class="ie"<?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" class="ie"<?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" class="ie"<?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 9]>
<html id="ie9" class="ie"<?php language_attributes(); ?>>
<![endif]-->
<!--[if gt IE 9]>
<html class="ie"<?php language_attributes(); ?>>
<![endif]-->
<!--[if !IE]>
<html <?php language_attributes(); ?>>
<![endif]-->

<!-- START HEAD -->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width"/>
	<title><?php esc_html_e( 'Product Comparison', 'yith-woocommerce-compare' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>

	<?php wp_head(); ?>

	<?php do_action( 'yith_woocompare_popup_head' ); ?>

	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800"/> <?php // phpcs:ignore ?>
	<link rel="stylesheet" href="<?php echo esc_url( YITH_WOOCOMPARE_URL ); ?>assets/css/colorbox.css"/> <?php // phpcs:ignore ?>
	<link rel="stylesheet" href="<?php echo esc_url( YITH_WOOCOMPARE_URL ); ?>assets/css/jquery.dataTables.css"/> <?php // phpcs:ignore ?>
	<link rel="stylesheet" href="<?php echo esc_url( $this->stylesheet_url() ); ?>" type="text/css"/> <?php // phpcs:ignore ?>

	<style type="text/css">
		body.loading {
			background: url("<?php echo esc_url( YITH_WOOCOMPARE_URL ); ?>assets/images/colorbox/loading.gif") no-repeat scroll center center transparent;
		}
	</style>
</head>
<!-- END HEAD -->

<?php global $product; ?>

<!-- START BODY -->
<body <?php body_class( 'woocommerce yith-woocompare-popup' ); ?>>

<h1>
	<?php echo wp_kses_post( $localized_table_text ); ?>
	<?php
	if ( ! $is_iframe ) :
		?>
		<a class="close" href="#"><?php esc_html_e( 'Close window [X]', 'yith-woocommerce-compare' ); ?></a><?php endif; ?>
</h1>

<div id="yith-woocompare" class="woocommerce">

	<?php do_action( 'yith_woocompare_before_main_table' ); ?>

	<table class="compare-list" cellpadding="0" cellspacing="0" 
	<?php
	if ( empty( $products ) ) {
		echo ' style="width:100%"';
	}
	?>
	>
		<thead>
		<tr>
			<th>&nbsp;</th>
			<?php foreach ( $products as $product_id => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
		</thead>
		<tfoot>
		<tr>
			<th>&nbsp;</th>
			<?php foreach ( $products as $product_id => $product ) : ?>
				<td></td>
			<?php endforeach; ?>
		</tr>
		</tfoot>
		<tbody>

		<?php if ( empty( $products ) ) : ?>

			<tr class="no-products">
				<td><?php esc_html_e( 'No products added in the compare table.', 'yith-woocommerce-compare' ); ?></td>
			</tr>

		<?php else : ?>
			<tr class="remove">
				<th>&nbsp;</th>
				<?php
				$index = 0;
				foreach ( $products as $product_id => $product ) :
					$product_class = ( ( 0 === ( $index % 2 ) ) ? 'odd' : 'even' ) . ' product_' . $product_id
					?>
					<td class="<?php echo esc_attr( $product_class ); ?>">
						<a href="
						<?php
						echo add_query_arg( 'redirect', 'view', $this->remove_product_url( $product_id ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
						"
								data-product_id="<?php echo esc_attr( $product_id ); ?>"><?php esc_html_e( 'Remove', 'yith-woocommerce-compare' ); ?>
							<span class="remove">x</span></a>
					</td>
					<?php
					++ $index;
				endforeach;
				?>
			</tr>

			<?php foreach ( $fields as $field => $name ) : ?>

				<tr class="<?php echo esc_attr( $field ); ?>">

					<th>
						<?php
						if ( 'image' !== $field ) {
							echo esc_html( $name );
						}
						?>
					</th>

					<?php
					$index = 0;
					foreach ( $products as $product_id => $product ) :
						$product_class = ( ( 0 === ( $index % 2 ) ) ? 'odd' : 'even' ) . ' product_' . $product_id;
						?>
						<td class="<?php echo esc_attr( $product_class ); ?>">
							<?php
							switch ( $field ) {

								case 'image':
									echo '<div class="image-wrap">' . $product->get_image( 'yith-woocompare-image' ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									break;

								case 'add-to-cart':
									woocommerce_template_loop_add_to_cart();
									break;

								default:
									echo empty( $product->fields[ $field ] ) ? '&nbsp;' : $product->fields[ $field ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									break;
							}
							?>
						</td>
						<?php
						++ $index;
					endforeach;
					?>

				</tr>

			<?php endforeach; ?>

			<?php if ( 'yes' === $repeat_price && isset( $fields['price'] ) ) : ?>
				<tr class="price repeated">
					<th><?php echo wp_kses_post( $fields['price'] ); ?></th>

					<?php
					$index = 0;
					foreach ( $products as $product_id => $product ) :
						$product_class = ( ( 0 === ( $index % 2 ) ) ? 'odd' : 'even' ) . ' product_' . $product_id
						?>
						<td class="<?php echo esc_attr( $product_class ); ?>"><?php echo wp_kses_post( $product->fields['price'] ); ?></td>
						<?php
						++ $index;
					endforeach;
					?>

				</tr>
			<?php endif; ?>

			<?php if ( 'yes' === $repeat_add_to_cart && isset( $fields['add-to-cart'] ) ) : ?>
				<tr class="add-to-cart repeated">
					<th><?php echo wp_kses_post( $fields['add-to-cart'] ); ?></th>

					<?php
					$index = 0;
					foreach ( $products as $product_id => $product ) :
						$product_class = ( ( 0 === ( $index % 2 ) ) ? 'odd' : 'even' ) . ' product_' . $product_id
						?>
						<td class="<?php echo esc_attr( $product_class ); ?>">
							<?php woocommerce_template_loop_add_to_cart(); ?>
						</td>
						<?php
						++ $index;
					endforeach;
					?>

				</tr>
			<?php endif; ?>

		<?php endif; ?>

		</tbody>
	</table>

	<?php do_action( 'yith_woocompare_after_main_table' ); ?>

</div>

<?php
if ( wp_script_is( 'responsive-theme', 'enqueued' ) ) {
	wp_dequeue_script( 'responsive-theme' );
}
?>

<?php
if ( wp_script_is( 'responsive-theme', 'enqueued' ) ) {
	wp_dequeue_script( 'responsive-theme' );
}
?>
<?php print_footer_scripts(); ?>

<script type="text/javascript">

	jQuery( document ).ready( function( $ ) {
		$( 'a' ).attr( 'target', '_parent' );

		// ########## DATA TABLES ############

		$.dataTableFunction = function() {

			var t = $( 'table.compare-list' ),
				dTable;

			if( t.length && ! t.find('.no-products').length && typeof $.fn.DataTable != 'undefined' && typeof $.fn.imagesLoaded != 'undefined' ) {
				t.imagesLoaded( function(){
					dTable = t.DataTable( {
						'info': false,
						'scrollX': true,
						'scrollCollapse': true,
						'paging': false,
						'ordering': false,
						'searching': false,
						'autoWidth': false,
						'destroy': true,
						'fixedColumns':   {
							leftColumns: 1
						}
					});
				});

				$(window)
					.off('resize')
					.off('orientationchange')
					.on('resize orientationchange', function(){
						if ( typeof dTable !== 'undefined' ) {
							dTable.destroy();
							$.dataTableFunction();
						}
					});
			}
		};

		$.dataTableFunction();

		$( document ).on( 'yith_woocompare_render_table yith_woocompare_product_removed', function() {
			$.dataTableFunction();
		} );

		// add to cart
		var redirect_to_cart = false,
			body = $( 'body' );

		// close colorbox if redirect to cart is active after add to cart
		body.on( 'adding_to_cart', function( $thisbutton, data ) {
			if ( wc_add_to_cart_params.cart_redirect_after_add == 'yes' ) {
				wc_add_to_cart_params.cart_redirect_after_add = 'no';
				redirect_to_cart = true;
			}
		} );

		body.on( 'wc_cart_button_updated', function( ev, button ) {
			$( 'a.added_to_cart' ).attr( 'target', '_parent' );
		} );

		// remove add to cart button after added
		body.on( 'added_to_cart', function( ev, fragments, cart_hash, button ) {

			$( 'a' ).attr( 'target', '_parent' );

			if ( redirect_to_cart == true ) {
				// redirect
				parent.window.location = wc_add_to_cart_params.cart_url;
				return;
			}

			// Replace fragments
			if ( fragments ) {
				$.each( fragments, function( key, value ) {
					$( key, window.parent.document ).replaceWith( value );
				} );
			}
		} );

		// close window
		$( document ).on( 'click', 'a.close', function( e ) {
			e.preventDefault();
			window.close();
		} );
	} );

</script>

</body>
</html>
