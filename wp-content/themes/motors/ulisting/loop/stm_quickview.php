<?php
/**
 * Loop grid
 *
 * Template can be modified by copying it to yourtheme/ulisting/loop/stm_quickview.php.
 *
 * @see     #
 * @package uListing/Templates
 * @version 2.0.0
 */
?>
<script>
var ajaxurl = '<?php echo esc_url(get_site_url()); ?>/wp-admin/admin-ajax.php';
</script>
<?php
     wp_enqueue_script('ulisting-quickview', get_template_directory_uri() . '/assets/js/ulisting/frontend/ulisting-quickview.js', array('jquery'), null, true);
     wp_enqueue_script('owl.carousel', get_template_directory_uri() . '/js/owl.carousel.js', array(), false, true);
     wp_enqueue_style('owl.carousel', get_template_directory_uri() . '/assets/css/owl.carousel.css');
?>
<div class="modal fade" id="centralModalSm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="stm-quick-view" id="stm-quick-view">
                <div id="stm-quickview-contain">
                    <div class="stm-quickview-contain">
                        <a href="#" class="quickview-close" data-dismiss="modal">X</a>
                        <div class="quickview-content ulisting_gallery_style_1">
                            <div class="row">
                                <div class="col-lg-6 col-12 quickview-left">
                                    <div class="big-carousel-wrap">
                                        <div class="big-carousel-wrap">
                                            <div class="big-wrap" id="quick-view-gallery"></div>
                                        </div>
                                        <div class="thumbs-wrap" id="quick-view-thumb"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12 quickview-right">
                                    <div class="content-info">
                                        <div class="stm-listing-info">
                                            <span class="listing-cat"></span>
                                        </div>
                                        <h2 class="stm-quickview-title"></h2>
                                    </div>
                                    <div class="stm-listing-desc"></div>
                                    <div class="listing-atribute">
                                        <div class="content-atribute">
                                        </div>
                                    </div>
                                    <div style="clear:both"></div>
                                    <div class="stm-listing-price">
                                        <span class="listing_price"></span>
                                    </div>
                                    <div class="stm-listing-view-info">
                                        <div class="view-button">
                                            <a class="listing-btn-view elementor-button elementor-size-sm" href="">
                                                <?php echo esc_html__( 'View Details', 'motors' ); ?>
                                            </a>
                                        </div>
                                        <div class="stm-wishlist">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
