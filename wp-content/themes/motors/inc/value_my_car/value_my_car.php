<?php

define( 'STM_VALUE_MY_CAR', 'stm_value_my_car' );

function adminEnqueueScriptsStyles() {
	wp_enqueue_style( 'stm-admin-vmc-style', get_template_directory_uri() . '/inc/value_my_car/assets/css/admin-vmc-style.css', null, STM_THEME_VERSION, 'all' );
	wp_enqueue_script( 'stm-admin-vmc-script', get_template_directory_uri() . '/inc/value_my_car/assets/js/admin-vmc.js', 'jquery', STM_THEME_VERSION, true );
}

add_action( 'admin_enqueue_scripts', 'adminEnqueueScriptsStyles' );

function enqueueScriptStyles() {
	$directoryStylesheet = get_template_directory_uri();

	wp_enqueue_style( 'stm-vmc', $directoryStylesheet . '/inc/value_my_car/assets/css/vmc-style.css', null, STM_THEME_VERSION, 'all' );
	wp_enqueue_script( 'stm-vmc-script', $directoryStylesheet . '/inc/value_my_car/assets/js/vmc.js', 'jquery', STM_THEME_VERSION, true );
}

if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'enqueueScriptStyles' );
}

function addVMCMenu() {
	$title = esc_html__( 'Value My Car', 'motors' );

	add_menu_page( $title, $title, 'administrator', 'value-my-car', 'vmcTemplateView', '', 50 );
}

add_action( 'admin_menu', 'addVMCMenu' );

function vmcTemplateView() {
	get_template_part( 'inc/value_my_car/admin-page' );
}

function stm_ajax_value_my_car() {

	check_ajax_referer( 'stm_security_nonce', 'security' );

	$responce = array();

	//email, phone,make, model, year, mileage, vin, photos
	if ( ( isset( $_POST['make'] ) && empty( $_POST['make'] ) ) || ( isset( $_POST['model'] ) && empty( $_POST['model'] ) ) || ( isset( $_POST['email'] ) && empty( $_POST['email'] ) ) || ( isset( $_POST['phone'] ) && empty( $_POST['phone'] ) ) ) {
		$responce['status'] = 'error';
		$responce['msg']    = esc_html__( 'Please enter required fields', 'motors' );
	} else {
		$opt         = stm_get_value_my_car_options();
		$postTitle   = '';
		$postContent = '<table>';

		foreach ( $_POST as $k => $val ) {
			if ( ! empty( $val ) && 'action' !== $k ) {
				if ( 'make' === $k ) {
					$postTitle .= $val;
				} elseif ( 'model' === $k ) {
					$postTitle .= ' ' . $val;
				} elseif ( 'security' !== $k ) {
					$postContent .= '<tr><td><b>' . array_search( $k, $opt, true ) . '</b> </td><td> - ' . $val . '</td></tr>';
				}
			}
		}

		$postContent .= '</table>';

		$args = array(
			'post_author'  => 1,
			'post_title'   => $postTitle,
			'post_content' => $postContent,
			'post_status'  => 'pending',
			'post_type'    => 'car_value',

		);

		$postId = wp_insert_post( $args );

		if ( ! empty( $_POST['email'] ) ) {
			update_post_meta( $postId, 'vmc_email', sanitize_text_field( $_POST['email'] ) );
		}
		if ( ! empty( $_POST['phone'] ) ) {
			update_post_meta( $postId, 'vmc_phone', sanitize_text_field( $_POST['phone'] ) );
		}

		if ( count( $_FILES ) > 0 && ! is_wp_error( $postId ) && 0 !== $postId ) {
			uploadVMCPhotos( $_FILES, $postId );
		}

		if ( ! is_wp_error( $postId ) && 0 !== $postId ) {
			$responce['status'] = 'success';
			$responce['msg']    = esc_html__( 'Thanks for your request, we will contact you as soon as we review you car.', 'motors' );
		} else {
			$responce['status'] = 'error';
			$responce['msg']    = esc_html__( 'Error', 'motors' );
		}
	}

	wp_send_json( $responce );
	exit;
}

function uploadVMCPhotos( $files, $parentId ) {
	$files_approved = array();

	$_FILES = $files;

	foreach ( $_FILES['files']['name'] as $f => $name ) {
		$tmp_name             = $_FILES['files']['tmp_name'][ $f ];
		$error                = $_FILES['files']['error'][ $f ];
		$type                 = $_FILES['files']['type'][ $f ];
		$files_approved[ $f ] = compact( 'name', 'tmp_name', 'type', 'error' );
	}

	require_once ABSPATH . 'wp-admin/includes/image.php';

	$attachments_ids = array();

	foreach ( $files_approved as $f => $file ) {
		$uploaded = wp_handle_upload(
			$file,
			array(
				'test_form' => false,
				'action'    => 'stm_ajax_add_a_car_media',
			)
		);

		if ( $uploaded['error'] ) {
			$response['errors'][ $file['name'] ] = $uploaded;
			continue;
		}

		$filetype = wp_check_filetype( basename( $uploaded['file'] ), null );

		// Insert attachment to the database
		$attach_id = wp_insert_attachment(
			array(
				'guid'           => $uploaded['url'],
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $uploaded['file'] ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			),
			$uploaded['file'],
			$parentId
		);

		if ( 0 === $f ) {
			set_post_thumbnail( $parentId, $attach_id );
		}

		$attachments_ids[ $f ] = $attach_id;
	}

	update_post_meta( $parentId, 'vmc_gallery', $attachments_ids );

	do_action( 'stm_vmc_gallery_saved', $parentId, $attachments_ids );

	return esc_html__( 'Thanks for your request, we will contact you as soon as we review you car.', 'motors' );
}

add_action( 'wp_ajax_stm_ajax_value_my_car', 'stm_ajax_value_my_car' );
add_action( 'wp_ajax_nopriv_stm_ajax_value_my_car', 'stm_ajax_value_my_car' );

function stm_ajax_get_file_size() {

	check_ajax_referer( 'stm_security_nonce', 'security' );

	echo esc_html( stm_get_filesize( $_FILES['photo']['tmp_name'] ) );
	exit;
}

add_action( 'wp_ajax_stm_ajax_get_file_size', 'stm_ajax_get_file_size' );
add_action( 'wp_ajax_nopriv_stm_ajax_get_file_size', 'stm_ajax_get_file_size' );

function stm_get_filesize( $file ) {
	$bytes = filesize( $file );

	return $bytes;
}

function vmc_send_mess( $postId, $status ) {
	$userEmail = get_post_meta( $postId, '' );
}

function stm_ajax_set_vmc_status() {

	check_ajax_referer( 'stm_ajax_set_vmc_status', 'security' );

	if ( 'declined' === $_POST['status'] ) {
		wp_trash_post( filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT ) );
	}

	$blogname  = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	$wp_email  = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( apply_filters( 'stm_get_global_server_val', 'SERVER_NAME' ) ) );
	$headers[] = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";

	$args = array(
		'car'   => $_POST['vmc-car'],
		'email' => filter_var( $_POST['vmc-email'], FILTER_SANITIZE_EMAIL ),
	);

	$subject = apply_filters( 'get_generate_subject_view', '', 'value_my_car_reject', $args );
	$body    = apply_filters( 'get_generate_template_view', '', 'value_my_car_reject', $args );

	do_action( 'stm_wp_mail', filter_var( $_POST['vmc-email'], FILTER_SANITIZE_EMAIL ), $subject, nl2br( $body ), $headers );

	update_post_meta( filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT ), 'vmc_status', sanitize_text_field( $_POST['status'] ) );
	exit;
}

add_action( 'wp_ajax_stm_ajax_set_vmc_status', 'stm_ajax_set_vmc_status' );
add_action( 'wp_ajax_nopriv_stm_ajax_set_vmc_status', 'stm_ajax_set_vmc_status' );

function stm_ajax_send_vmc_reply() {

	check_ajax_referer( 'stm_ajax_send_vmc_reply', 'security' );
	$blogname  = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
	$wp_email  = 'wordpress@' . preg_replace( '#^www\.#', '', strtolower( apply_filters( 'stm_get_global_server_val', 'SERVER_NAME' ) ) );
	$headers[] = 'From: ' . $blogname . ' <' . $wp_email . '>' . "\r\n";

	$args = array(
		'car'   => sanitize_text_field( $_POST['vmc-car'] ),
		'email' => filter_var( $_POST['vmc-email'], FILTER_SANITIZE_EMAIL ),
		'price' => sanitize_text_field( $_POST['vmc-price'] ),
	);

	$subject = apply_filters( 'get_generate_subject_view', '', 'value_my_car', $args );
	$body    = apply_filters( 'get_generate_template_view', '', 'value_my_car', $args );

	do_action( 'stm_wp_mail', filter_var( $_POST['vmc-email'], FILTER_SANITIZE_EMAIL ), $subject, nl2br( $body ), $headers );

	$response['message'] = esc_html__( 'Reply was sent', 'motors' );
	wp_send_json( $response );
}

add_action( 'wp_ajax_stm_ajax_send_vmc_reply', 'stm_ajax_send_vmc_reply' );
add_action( 'wp_ajax_nopriv_stm_ajax_send_vmc_reply', 'stm_ajax_send_vmc_reply' );
