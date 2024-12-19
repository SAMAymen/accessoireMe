<?php
$socials = stm_get_header_socials('top_bar_socials_enable');

if( !empty($socials) ): ?>
    <div class="header-top-bar-socs">
        <ul class="clearfix">
            <?php foreach ( $socials as $key => $val ): ?>
                <li>
                    <a href="<?php echo esc_url($val) ?>" target="_blank">
                        <i class="fab fa-<?php echo esc_attr($key); ?>"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>