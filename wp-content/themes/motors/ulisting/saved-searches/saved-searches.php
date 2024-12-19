<?php
/**
 * Aaved searches
 *
 * Template can be modified by copying it to yourtheme/ulisting/saved-searches/saved-searches.php.
 **
 * @see     #
 * @package uListing/Templates
 * @version 1.0
 */

$searches = \uListing\Classes\UlistingSearch::get_user_searches(get_current_user_id());
?>

<?php if(!empty($searches)) : ?>
<table class="table ulisting-table saved-search-table">
	<thead>
	<tr>
		<th class="heading-font"><?php esc_html_e("Listing type", 'motors')?></th>
		<th class="heading-font"><?php esc_html_e("Params", 'motors')?></th>
		<th style="width: 160px;"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ( $searches as $search ):?>
		<tr class="ulisting-search-item-<?php echo esc_attr($search->id)?>">
			<td class="heading-font"><?php echo esc_attr($search->get_listing_type()->post_title)?></td>
			<td class="heading-font">
				<?php  foreach ($search->get_params() as $attribute):?>
					<?php echo esc_attr($attribute['title'])?> : <?php echo (is_array($attribute['value'])) ? implode(', ', $attribute['value']) : $attribute['value']?> ; &nbsp;
				<?php  endforeach;?>
			</td>
			<td style="text-align: right;">
				<a target="_blank" class="btn btn-info heading-font" href="<?php echo esc_url($search->get_url()) ?>"><i class="fas fa-eye" aria-hidden="true"></i> <?php echo esc_html__('View', 'motors');?></a>
				<button onclick="delete_search(this, <?php echo esc_attr($search->id)?>)" class="btn btn-danger"><i class="stm-all-icon-remove1"></i></button>
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>


<?php else : ?>
	<h3 class="text-center p-t-30"><?php _e("You don't have a saved search yet!", 'motors')?></h3>
<?php endif; ?>

