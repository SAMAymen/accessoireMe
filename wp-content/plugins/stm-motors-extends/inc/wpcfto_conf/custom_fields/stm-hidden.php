<?php
/**
 * @var $field
 * @var $field_id
 * @var $field_value
 * @var $field_label
 * @var $field_name
 * @var $section_name
 */

wp_enqueue_script( 'stm-hidden-component', STM_MOTORS_EXTENDS_URL . '/inc/wpcfto_conf/custom_fields/js_components/stm_hidden.js', null, get_bloginfo( 'version' ), true );
wp_enqueue_style( 'stm-hidden-css', STM_MOTORS_EXTENDS_URL . '/inc/wpcfto_conf/custom_fields/css/stm_hidden.css', null, get_bloginfo( 'version' ), 'all' );

?>
<wpcfto_stm_hidden :fields="<?php echo esc_attr( $field ); ?>"
	:field_label="<?php echo esc_attr( $field_label ); ?>"
	:field_name="'<?php echo esc_attr( $field_name ); ?>'"
	:field_id="'<?php echo esc_attr( $field_id ); ?>'"
	:field_value="<?php echo esc_attr( $field_value ); ?>"
	@wpcfto-get-value="<?php echo esc_attr( $field_value ); ?> = $event">
</wpcfto_stm_hidden>

