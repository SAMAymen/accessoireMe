<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if (!empty($element['data'])): ?>
    <div class="stm-cart-2">
        <?php if (!empty($element['data']['title'])): ?>
            <div class="stm-text-link">
            <?php if (!empty($element['data']['title'])): ?>
                <a href="<?php echo wp_kses($element['data']['link'], array('br' => array())); ?>">
            <?php endif; ?>
                <?php echo wp_kses($element['data']['title'], array('br' => array())); ?>
            <?php if (!empty($element['data']['title'])): ?>
                </a>
            <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>