<?php
$theme = STM_Theme_Info::get_theme_info();
?>
<div class="stm_theme_info_wrap clearfix">
    <div class="theme_info_bg">
        <div class="left">
            <div class="stm_theme_info">
                <div class="stm_theme_version"><?php echo substr( $theme['v'], 0, 3 ); ?></div>
            </div>
            <div class="__header"><b>Welcome to <?php echo STM_THEME_NAME; ?> -</b><br/><?php echo STM_THEME_CATEGORY; ?></div>
        </div>
        <div class="right">
            <div class="stm-about-text-wrap">
                <?php echo STM_THEME_NAME; ?> is now installed and ready to use! Get ready to build something beautiful.
                Please register your purchase to import <?php echo STM_THEME_NAME; ?> demos and install premium plugins.
                <br/><a href="<?php echo esc_attr( STM_INSTALL_VIDEO_URL ); ?>" target="_blank">Watch Our Quick Guided
                    Tour!</a>
            </div>
        </div>
    </div>
</div>
