<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
?>

<div class="stm_review_excerption">
	<?php echo wpb_js_remove_wpautop( $content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
