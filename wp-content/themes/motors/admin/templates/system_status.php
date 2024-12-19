<?php

// Do not allow directly accessing this file.
if ( !defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>

<div class="wrap stm-admin-wrap stm-system-status stm-admin-status-screen">
    <?php STM_Theme_Admin_Pages::get_admin_tabs( 'system-status' ); ?>
    <div class="stm-admin-system-status-wrap">
        <div class="stm-admin-ss-header">
            <h3>System Requirements</h3>
            <div class="desc">
                <i class="stmadmin-icon-info"></i>
                <span>It is recommended to meet the system requirements for the best experience with the theme. Regularly check for system updates and update it till the requirement configurations. The red warning sign in the Your System column means that the system does not meet the requirements of the theme.</span>
            </div>
        </div>
        <div class="stm-admin-ss-row">
            <div class="stm-admin-ss-col-left">
                <h3 class="screen-reader-text">WordPress Environment</h3>
                <table class="widefat" cellspacing="0">
                    <thead>
                    <tr>
                        <th>WordPress Environment</th>
                        <th>Your System</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( STM_Theme_System_Status::get_wp_env() as $item ) : ?>
                        <tr>
                            <td data-export-label="<?php echo esc_attr( $item['title'] ); ?>"><?php echo esc_html( $item['title'] ); ?>
                                :
                            </td>
                            <td><?php echo wp_kses_post( $item['system'] ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h3 class="screen-reader-text">Server Environment</h3>
                <table class="widefat" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Server Environment</th>
                        <th>Requirement</th>
                        <th>Your System</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ( STM_Theme_System_Status::get_server_env() as $item ) : ?>
                        <tr>
                            <td data-export-label="<?php echo esc_attr( $item['title'] ); ?>"><?php echo esc_html( $item['title'] ); ?>
                                :
                            </td>
                            <td class="help"><?php echo wp_kses_post( $item['recommend'] ); ?></td>
                            <td><?php echo wp_kses_post( $item['system'] ); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <h3 class="screen-reader-text">Active Plugins</h3>
                <table class="widefat" cellspacing="0" id="status">
                    <thead>
                    <tr>
                        <th>Active Plugins (<?php echo count( (array)get_option( 'active_plugins' ) ); ?>)</th>
                        <th>Version</th>
                        <th>Plugin Author</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $active_plugins = (array)get_option( 'active_plugins', [] );
                    $allPlugins = get_site_transient( 'update_plugins' );
                    
                    if ( is_multisite() ) {
                        $active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
                    }
                    
                    foreach ( $active_plugins as $plugin ) {
                        
                        $plugin_data = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
                        $dirname = dirname( $plugin );
                        $badge = '<i class="stmadmin-icon-imported-check"></i>';
                        $version_number = $plugin_data['Version'];
                        $version_upd_number = $plugin_data['Version'];
                        
                        if ( isset( $allPlugins->response[$plugin] ) ) {
                            STM_Theme_System_Status::$notification = true;
                            $badge = '<i class="stmadmin-icon-round-error"></i>';
                            $version_number = '<span class="plug_bold">' . $plugin_data['Version'] . '</span>';
                            $version_upd_number = $allPlugins->response[$plugin]->new_version;
                        }
                        
                        if ( !empty( $plugin_data['Name'] ) ) {
                            
                            // Link the plugin name to the plugin url if available.
                            $plugin_name = esc_html( $plugin_data['Name'] );
                            
                            if ( !empty( $plugin_data['PluginURI'] ) ) {
                                $plugin_name = '<a target="_blank" href="' . esc_url( $plugin_data['PluginURI'] ) . '" title="Visit plugin homepage">' . $plugin_name . '</a>';
                            }
                            ?>
                            <tr>
                                <td><b><?php echo sanitize_text_field( $plugin_name ); ?></b></td>
                                <td><?php echo sprintf( '%s %s', $badge, $version_number ); ?></td>
                                <td><?php echo apply_filters( 'stm_theme_esc_variable', str_replace( '">', '" target="_blank">', $plugin_data['Author'] ) ); ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="stm-admin-ss-col-right">
                <a href="<?php echo esc_url( STM_PREMIUM_THEMES ); ?>" target="_blank">
                    <img src="<?php echo STM_Theme_Info::get_image_url( 'banner_system_status.png' ); ?>"/>
                </a>
            </div>
        </div>
    </div>
</div>
<?php STM_Theme_System_Status::set_notification_transient(); ?>
