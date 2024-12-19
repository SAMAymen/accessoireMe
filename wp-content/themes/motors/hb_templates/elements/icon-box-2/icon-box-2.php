<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if (!empty($element['data'])):

	$line_2_before = '';
	$line_2 = esc_attr($element['data']['description']);
	$line_2_after = '';

	$line_2_as_link = !empty($element['data']['line2AsLink']) && stm_hb_check_string($element['data']['line2AsLink']);

	if ($line_2_as_link) {
		$target = 'iconBoxModal_' . $element['data']['line2PageId'];
		$line_2_before = '<a class="stm_hb_mtc" href="#" data-toggle="modal" data-target="#' . $target . '">';
		$line_2_after = '</a>';
	}

	$line_2 = $line_2_before . $line_2 . $line_2_after;

	$style = (!empty($element['data']['iconBoxColor'])) ? "style='color:{$element['data']['iconBoxColor']}';" : "";


	?>
	<div class="stm-iconbox">
		<?php if (!empty($element['data']['icon'])): ?>
			<i class="stm-iconbox__icon stm_hb_mtc stm-iconbox__icon_left icon_22px <?php echo esc_attr($element['data']['icon']); ?>"></i>
		<?php endif; ?>
		<div class="stm-iconbox__info">
			<?php if (!empty($element['data']['title'])): ?>
				<div class="stm-iconbox__text stm-iconbox__text_nomargin" <?php echo wp_kses_post($style); ?>>
					<?php echo wp_kses($element['data']['title'], array('br' => array())); ?>
				</div>
			<?php endif; ?>
            <?php if (!empty($element['data']['subtitle'])): ?>
				<div class="stm-iconbox__text stm-iconbox__text_nomargin" <?php echo wp_kses_post($style); ?>>
					<?php echo wp_kses($element['data']['subtitle'], array('br' => array())); ?>
				</div>
			<?php endif; ?>
			<?php if (!empty($element['data']['description'])): ?>

				<div class="stm-iconbox__description" <?php echo wp_kses_post($style); ?>>
					<?php
					$line_2 = html_entity_decode($line_2);
					echo wp_kses($line_2,
						array(
							'a' => array(
								'href'        => array(),
								'data-toggle' => array(),
								'data-target' => array(),
								'class'       => array()
							),
							'br' => array()
						)
					)

					?>
				</div>

			<?php endif; ?>
		</div>
	</div>


<?php
	if ($line_2_as_link) {

		$modal_options = array('page_id' => $element['data']['line2PageId']);

		if (!empty($element['data']['modal2Width'])) {
			$modal_options['modal_width'] = $element['data']['modal2Width'];
		}

		stm_hb_load_element('iconbox', $modal_options, 'modal');
	}
endif;