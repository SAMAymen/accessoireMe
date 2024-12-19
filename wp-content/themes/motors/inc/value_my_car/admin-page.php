<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

if ( isset( $_POST['delete-vmc'] ) ) {
	foreach ( explode( ',', sanitize_text_field( $_POST['delete-vmc'] ) ) as $val ) {
		wp_delete_post( $val );
	}
}

$post_status = ( isset( $_GET['post-status'] ) ) ? sanitize_text_field( $_GET['post-status'] ) : 'all';

$cars = new WP_Query(
	array(
		'post_type'   => 'car_value',
		'post_status' => $post_status,
	)
);

$post_ids = array();
if ( $cars->have_posts() && 'trash' === $post_status ) {
	while ( $cars->have_posts() ) {
		$cars->the_post();
		$post_ids[] = get_the_ID();
	}
}

$sendVMCReply = wp_create_nonce( 'stm_ajax_send_vmc_reply' );
$setVMCStatus = wp_create_nonce( 'stm_ajax_set_vmc_status' );
?>
<script>
	var sendVMCReply = '<?php echo esc_js( $sendVMCReply ); ?>';
	var setVMCStatus = '<?php echo esc_js( $setVMCStatus ); ?>';
</script>
<div class="vmc-main-wrap">
	<div class="vmc-top-bar">
		<h1><?php echo esc_html__( 'Value My Car', 'motors' ); ?></h1>
		<div class="vmc-action-wrap">
			<form action="<?php echo esc_url( apply_filters( 'stm_get_global_server_val', 'REQUEST_URI' ) ); ?>"
					method="get">
				<input type="hidden" name="page" value="<?php echo esc_attr( $_GET['page'] ); ?>"/>
				<select name="post-status">
					<option value="all"
						<?php
						if ( 'all' === $post_status ) {
							echo 'selected';
						}
						?>
					>
						<?php echo esc_html__( 'All', 'motors' ); ?>
					</option>
					<option value="pending"
						<?php
						if ( 'pending' === $post_status ) {
							echo 'selected';
						}
						?>
					>
						<?php echo esc_html__( 'Pending', 'motors' ); ?>
					</option>
					<option value="trash"
						<?php
						if ( 'trash' === $post_status ) {
							echo 'selected';
						}
						?>
					>
						<?php echo esc_html__( 'Trash', 'motors' ); ?>
					</option>
				</select>
				<input type="submit" class="button-primary" value="<?php echo esc_html__( 'Apply', 'motors' ); ?>">
			</form>

			<?php if ( count( $post_ids ) > 0 ) : ?>
				<form action="<?php echo esc_url( apply_filters( 'stm_get_global_server_val', 'REQUEST_URI' ) ); ?>"
						method="POST">
					<input type="hidden" name="delete-vmc"
							value="<?php echo esc_attr( implode( ',', $post_ids ) ); ?>"/>
					<input type="submit" class="button-secondary" value="Empty Trash"/>
				</form>
			<?php endif; ?>
		</div>
	</div>

	<table>
		<thead>
		<tr>
			<th><?php echo esc_html__( 'Car', 'motors' ); ?></th>
			<th><?php echo esc_html__( 'Details', 'motors' ); ?></th>
			<th><?php echo esc_html__( 'Images', 'motors' ); ?></th>
			<th><?php echo esc_html__( 'Actions', 'motors' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<?php
		if ( $cars->have_posts() ) {
			while ( $cars->have_posts() ) {
				$cars->the_post();
				$attachIds = get_post_meta( get_the_ID(), 'vmc_gallery', true );
				$status    = ( get_post_meta( get_the_ID(), 'vmc_status', true ) ) ? 'vmc-' . get_post_meta( get_the_ID(), 'vmc_status', true ) : '';

				$imgs  = '';
				$id    = get_the_ID();
				$popup = '<div id="myModal-' . $id . '" class="modal">
                                      <span class="close cursor">&times;</span>
                                      <div class="modal-content">';
				if ( ! empty( $attachIds ) ) {
					$imgs = '<ul>';
					foreach ( $attachIds as $k => $val ) {
						$imgSrc      = wp_get_attachment_image_src( $val, 'thumbnail' );
						$imgSrcSlide = wp_get_attachment_image_src( $val, 'full' );
						$imgs       .= '<li><img src="' . $imgSrc[0] . '" data-modal-id="myModal-' . esc_attr( $id ) . '" data-curr-slide="' . ( $k ) . '" class="vmc-lghtbox hover-shadow"></li>';
						$popup      .= '<div class="mySlides">
                                      <img src="' . $imgSrcSlide[0] . '" style="max-width:100%;margin:0 auto;display:block;">
                                    </div>';
					}
					$imgs .= '</ul>';
				}

				$popup .= '<a class="prev">&#10094;</a>
                                        <a class="next">&#10095;</a>
                                      </div>
                                    </div>';

				$html = '<tr class="' . $status . '">
                                    <td>' . get_the_title() . '</td>
                                    <td>' . get_the_content() . '</td>
                                    <td>' . $imgs . '</td>';

				$btns = '<button class="stm-vmc-reply-btn button-primary" data-id="' . get_the_ID() . '" data-title="' . get_the_title() . '" data-email="' . get_post_meta( get_the_ID(), 'vmc_email', true ) . '" data-status="accepted">' . esc_html__( 'Reply', 'motors' ) . '</button>';

				if ( 'trash' === $post_status ) {
					$btns = '';
				} else {
					$btns .= '<button class="stm-vmc-action-btn button-secondary" data-id="' . get_the_ID() . '" data-title="' . get_the_title() . '" data-email="' . get_post_meta( get_the_ID(), 'vmc_email', true ) . '" data-status="declined">' . esc_html__( 'Trash', 'motors' ) . '</button>';
				}

				$html .= ( empty( $status ) ) ? '<td>' . $btns . '</td>' : '<td></td>';

				$html .= '</tr>';
				$html .= $popup;
				echo wp_kses_post( $html );
			}
		}
		?>
		</tbody>
	</table>
	<div class="vmc-modal-wrap">
		<div class="vmc-modal-overlay"></div>
		<div class="vmc-modal">
			<form name="vmc-reply-form">
				<label>User email</label>
				<input type="text" name="vmc-email"/>
				<label>Car</label>
				<input type="text" name="vmc-car"/>
				<label>Price</label>
				<input type="text" name="vmc-price"/>
				<input type="hidden" name="vmc-postid"/>
				<input type="hidden" name="vmc-status"/>
				<input type="submit" class="vmc-send-btn button-secondary" value="SEND"/>
			</form>
		</div>
	</div>
</div>
