<?php
use uListing\Classes\StmListingTemplate;

?>
<div class="stm-cf-compare-page">
	<?php echo StmListingTemplate::load_template( 'listing-list/breadcrumbs'); ?>
<?php
if($listing_types):
?>
    <h2><?php echo esc_html__('COMPARE ITEMS', 'motors'); ?></h2>
	<ul class="nav nav-pills">
		<?php $i = 0; foreach ($listing_types as $listing_type):?>
			<?php
			$active = "";
			if( ($i == 0 AND !$listing_type_id) OR ($listing_type_id == $listing_type->ID))
				$active = "active";
			?>
			<li class="nav-item">
				<a class="nav-link heading-font <?php echo esc_html($active)?>" href="<?php echo esc_url($page_url."?listing_type_id=".$listing_type->ID)?>">
					<?php echo esc_html($listing_type->post_title)?>
					<span  class="badge badge-dark "><?php echo esc_attr($listing_type->lisitng_total_count) ?></span>
				</a>
			</li>
			<?php $i++; endforeach;?>
	</ul>
	<?php if(!empty($listing_type_attributes)): ?>
    <div class="stm-compare-table-wrap">
		<table class="table">
			<thead>
			<tr>
				<th scope="col"></th>
				<?php foreach ($listings as $listing):?>
					<th scope="col">
                        <div class="preview-item">
                            <div class="thumb-wrap">
                                <?php echo get_the_post_thumbnail($listing->ID, 'stm-img-255-135-x-2')?>
                                <a href="#" onclick="remove_listing_compare(<?php echo esc_attr($listing->ID)?>)"><i class="stm-all-icon-remove"></i><?php _e("Remove From List", 'motors')?></a>
                            </div>
                            <div class="item-info">
							    <?php $priceObj = $listing->getAttributeValue('price'); ?>
                                <div class="listing-title heading-font">
                                    <?php echo esc_html($listing->post_title); ?>
                                </div>
                                <div class="price-wrap heading-font">
                                    <?php echo ulisting_currency_format($priceObj['price']);?>
                                </div>
                            </div>
                        </div>
					</th>
				<?php endforeach;?>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($listing_type_attributes as $listing_type_attribute):?>
				<tr>
					<th scope="row normal_font"><?php echo esc_html($listing_type_attribute->title)?></th>
					<?php foreach ($listings as $listing):?>
						<td class="heading-font">
							<?php echo \uListing\ListingCompare\Classes\UlistingListingCompare::render_attribute_value($listing, $listing_type_attribute);?>
						</td>
					<?php endforeach;?>
				</tr>
			<?php endforeach;?>
			</tbody>
		</table>
    </div>
	<?php endif; ?>
<?php else:?>
	<h2 class="text-center margin-t-50"><?php _e("You have not added cars to compare!", 'motors')?></h2>
<?php endif;?>
</div>



