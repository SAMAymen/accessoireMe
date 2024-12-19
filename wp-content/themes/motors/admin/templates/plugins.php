<?php
// Do not allow directly accessing this file.
if ( !defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

$current_demo = apply_filters( 'stm_theme_demo_layout', '' );
?>
<div class="wrap stm-admin-wrap stm-admin-plugins-screen">
	<?php STM_Theme_Admin_Pages::get_admin_tabs( 'plugins' ); ?>
    <div class="stm-admin-plugins-tab-content-wrap">
        <div class="header-plugins">
            <div class="hp-left">
                <h3>Plugins</h3>
                <div class="plug-navigate">
                    <ul>
                        <?php
                        foreach ( STM_TGM_Plugins::get_plugins_navigate_view($current_demo) as $k => $val ) {
                            echo sprintf( "<li><a href='#%s' class='plugins_isotope_nav ' data-filter='%s'>%s (%d)</a></li>", $k, $k, $val['label'], $val['count'] );
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="hp-right">
                <h4>Search Plugin</h4>
                <div class="plugin-search-wrap">
                    <input type="text" name="search_plugin" id="search_plugin" placeholder="Enter plugin name..."/>
                </div>
            </div>
        </div>
        <div class="plug-bg">
            <div class="plug-wrap">
                <div class="plugins-grid">
                    <?php
                    $plugins =  STM_TGM_Plugins::get_plugins_data($current_demo);

                    foreach ( $plugins['all'] as $plugin ) {
                        get_template_part( 'admin/templates/parts/plugin_loop', '', $plugin );
                    }
                    ?>
                </div>
            </div>
        </div>
		<?php if ( !$current_demo ) get_template_part( 'admin/templates/parts/plugins_install_denied_popup' ); ?>
    </div>
</div>