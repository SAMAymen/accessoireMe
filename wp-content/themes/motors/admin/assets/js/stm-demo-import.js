
    var layout_plugins  = [];
    var plugin          = false;
    var installation    = false;
    var builder         = get_importing_builder( 'js_composer' );
    var layout          = get_importing_layout( default_layout );
    var layout_name     = get_importing_layout_name( default_layout_name );
    var import_data     = get_import_data();
    var install_plugins = get_install_plugins();

    jQuery(document).ready(function () {

        jQuery('.stm_install__demo_popup .inner .stm_install__demo_start').on('click', function (e) {
            e.preventDefault();

            if (jQuery(this).attr('target') === '_blank') {
                var win = window.open(jQuery(this).attr('href'), '_blank');
                win.focus();

                return;
            }

            if (!jQuery(this).hasClass('installing')) {
                next_installable();

                if (!plugin) {
                    /* Plugins installed, Install demo */
                    performAjax('import_demo');
                } else {
                    /* Install plugin */
                    performAjax(plugins[plugin]);
                }
            }
        });

        jQuery('.builder').on('click', function () {
            if (jQuery(this).find('input').is(':checked')) {
                jQuery(this).parents('.stm-admin-demos-screen').addClass('builder-active');
                jQuery(this).parents('.stm-admin-demos-screen').removeClass('builder-no-active');
            } else {
                jQuery(this).parents('.stm-admin-demos-screen').removeClass('builder-active');
                jQuery('.stm_install__demo_popup').removeClass('active');
            }
        });

        /* Privacy Policy */
        jQuery('.show_policy').on('click', function (e) {
            e.preventDefault();
            jQuery('.stm_install__demo_popup .demo_install').hide();
            jQuery('.stm_install__demo_popup .privacy_policy').show();
            jQuery('.stm_install__demo_popup').addClass('active');
        });
        jQuery('#send_api').on('change', function() {
            jQuery('.privacy-unchecked').toggle(!jQuery(this).is(':checked'));
            jQuery('.privacy-unchecked-overlay').toggle(!jQuery(this).is(':checked'));

            if(!jQuery('input[name="builder"]').is(':checked')) {
                if (jQuery('.stm-admin-demos-screen').hasClass('builder-no-active') && !jQuery(this).is(':checked')) {
                    jQuery('.stm-admin-demos-screen').removeClass('builder-no-active');
                } else if (!jQuery('.stm-admin-demos-screen').hasClass('builder-active') && jQuery(this).is(':checked')) {
                    jQuery('.stm-admin-demos-screen').addClass('builder-no-active');
                }
            }
        });

        /* Choose Builder */
        jQuery('input[name="builder"]').on('change', function(event) {
            event.preventDefault();
            jQuery('label.builder').removeClass('checked');
            jQuery(this).parent('label').addClass('checked');
            jQuery('.builder-only').attr('class', 'builder-only');

            var selectedBuilder = jQuery(this).val();

            jQuery('.builder-only').each( function() {
                if(jQuery(this).data('builder') == selectedBuilder) {
                    jQuery(this).addClass(selectedBuilder + '-layout-active');
                } else {
                    jQuery(this).addClass('layout-hide');
                }
            });

            jQuery('.no_demos').toggle(jQuery('.stm_demo_import_choices').children('label:visible').length === 0);
        });

        /* Reset Demo Checkbox */
        jQuery('#reset_policy').on('change', function() {
            jQuery('.demo_buttons .demo_button.danger').toggleClass('disabled', !jQuery(this).is(':checked'));
        });

        /* Reset Demo */
        jQuery(document).on('click', '#reset_demo.danger:not(.disabled)', function() {
            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                context: this,
                data: {
                    'action': 'stm_reset_demo',
                    'nonce': reset_nonce,
                },
                beforeSend: function () {
                    jQuery('.demo_buttons .demo_button.primary').hide();
                    jQuery(this).removeClass('danger').addClass('success').text('Resetting...');
                },
                complete: function (data) {
                    jQuery('.stm_plugin_info').removeClass('installed').addClass('not-installed').attr('data-active', 'not-installed');
                    jQuery('.stm_plugin_info[data-required=""]').find('input[name="install_plugins[]"]').attr('disabled', false);
                    plugin = false;
                    skip_reset();
                    setTimeout(function() {
                        jQuery('.stm_install__demo_reset_notice').text(data.responseJSON).show().delay(3000).fadeOut();
                    }, 1000);
                }
            });
        });

        /* Skip Reset */
        jQuery('#skip_reset').on('click', function() {
            skip_reset();
        });

        /* Installing Plugins Checkboxes */
        jQuery('input[name="install_plugins[]"]').on('change', function() {
            install_plugins = get_install_plugins();
        });

        /* Import Data Checkboxes */
        jQuery('input[name="import_data[]"]').on('change', function() {
            // Enable/Disable Media Checkbox
            if  ( jQuery(this).val() === 'content' ) {
                var content_enabled = jQuery(this).is(':checked');
                jQuery('input:checkbox[value="media"]')
                    .attr('disabled', ! content_enabled)
                    .prop('checked', content_enabled);
            }
            // Generate Import Data
            import_data = get_import_data();
        });

        /* Search Layouts */
        jQuery('#search_demo').on('input', function() {
            var searchTerm = jQuery.trim(this.value);
            jQuery('.stm_demo_import_choices > label').each(function() {
                if ( searchTerm.length > 0 ) {
                    jQuery(this).toggle(
                        jQuery(this).find('.install').filter('[data-name*="' + capitalize_string(searchTerm) + '"], [data-slug*="' + searchTerm + '"]').length > 0
                    );
                } else {
                    jQuery(this).show();
                }
            });

            if(jQuery('.stm_demo_import_choices').children('label:visible').length === 0) {
                jQuery('.tooltip-no-demos').addClass('show-tooltip');
            } else {
                jQuery('.tooltip-no-demos').removeClass('show-tooltip');
            }

            jQuery('.no_demos').toggle(jQuery('.stm_demo_import_choices').children('label:visible').length === 0);
        });

        /* Sticky Top Panel */
        adjust_scroll_class();
        jQuery(window).on( 'scroll', function() {
            adjust_scroll_class();
        });

    });

    function performAjax(plugin_slug) {
        var installing      = "installing...";
        var installed       = "installed & activated";
        var $current_plugin = jQuery('#stm_' + plugin_slug);

        installation    = true;
        layout          = get_importing_layout( layout );
        layout_name     = get_importing_layout_name( layout_name );

        setPopupCloseBtnState('disabled');

        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            context: this,
            data: {
                'layout': layout,
                'plugin': plugin_slug,
                'action': 'stm_install_plugin',
                'security': stm_ajax_dashboard.stm_install_plugin,
                'builder': builder
            },
            beforeSend: function () {
                $current_plugin
                    .addClass('installing')
                    .find('.status').text(installing);
                jQuery('.stm_install__demo_popup .inner .stm_install__demo_start').addClass('installing');
                jQuery('.demo_progress_bar .progress_bar').addClass('active');

                calculate_progress();
            },
            complete: function (data) {
                $current_plugin.removeClass('installing').find('.status').html(installed);

                var dt = data.responseJSON;

                if (typeof dt.activated !== 'undefined' && dt.activated) {
                    plugin = dt.plugin_slug;
                    $current_plugin.removeClass('.not-installed').addClass('installed').attr('data-active', 'installed').attr('disabled', true);
                    $current_plugin.find('input[name="install_plugins[]"]').attr('disabled', true);
                }

                if (typeof dt.next !== 'undefined') {
                    plugin = false;
                    if ( jQuery('input:checkbox[value="' + dt.next + '"]').is(':checked') && jQuery('#stm_' + dt.next).attr('data-active') === 'not-installed' ) {
                        performAjax(dt.next);
                    } else {
                        next_installable();

                        if ( ! plugin ) {
                            /* Plugins installed, Install demo */
                            install_demo();
                        } else {
                            /* Install plugin */
                            performAjax(plugins[plugin]);
                        }
                    }
                }

                if (typeof dt.import_demo !== 'undefined' && dt.import_demo) {
                    install_demo()
                }
            },
            error: function () {
                window.location.href += '&layout_importing=' + layout + '&builder=' + builder + '&importing_data=' + import_data + '&installing_plugins=' + install_plugins;
            }

        });
    }

    function install_demo() {
        installation = true;
        var importing_demo_text = "importing...";
        var imported_demo_text  = "imported";
        var skip_continue_text  = "Skip & Continue";
        var nonce               = import_nonce;
        var current_import_data = '';
        var continue_loop       = true;

        setPopupCloseBtnState('disabled');

        import_data.forEach(function (data) {
            if ( jQuery('#import_data_' + data + '[data-status="not-imported"]').length > 0 && continue_loop) {
                current_import_data = data;
                continue_loop = false;
            }
        });

        var $current_import = jQuery('#import_data_' + current_import_data);
        var $demo_start     = jQuery('.stm_install__demo_popup .inner .stm_install__demo_start');

        layout      = get_importing_layout( layout );
        layout_name = get_importing_layout_name( layout_name );

        jQuery.ajax({
            url: ajaxurl,
            dataType: 'json',
            context: this,
            data: {
                'action': 'stm_demo_import_content',
                'nonce': nonce,
                'demo_template': layout,
                'builder': builder,
                'import_data': current_import_data,
                'import_media': import_data.includes('media')
            },
            beforeSend: function () {
                jQuery('input[name="import_data[]"]').attr('disabled', true);
                jQuery('.demo_error').hide();
                jQuery('.demo_status').html('Import Progress');

                $current_import.addClass('installing').removeClass('import_error');
                $current_import.find('.status').text(importing_demo_text);

                calculate_progress();
            },
            complete: function (data) {
                $current_import.removeClass('installing').addClass('installed');
                $current_import.find('.status').text(imported_demo_text);

                var dt = data.responseJSON;
                if ( typeof dt !== 'undefined' && typeof dt.imported !== 'undefined' ) {
                    jQuery('#import_data_' + dt.imported).attr('data-status', 'imported');
                    install_demo();
                } else if ( typeof dt !== 'undefined' && typeof dt.title !== 'undefined' && typeof dt.url !== 'undefined' ) {
                    installation = false;
                    setPopupCloseBtnState('active');

                    jQuery('.demo_progress_bar .progress_bar').removeClass('active');
                    $demo_start.removeClass('installing').addClass('primary');
                    $demo_start.text(dt.title);
                    $demo_start.attr('href', dt.url);
                    $demo_start.attr('target', '_blank');

                    /* Privacy Policy */
                    /* Analytics */
                    if (!dev_mode && jQuery('#send_api').is(':checked')) {
                        jQuery.ajax({
                            url: 'https://panel.stylemixthemes.com/api/active/',
                            type: 'post',
                            dataType: 'json',
                            data: {
                                theme: theme_slug,
                                layout: layout_name,
                                website: site_url,
                            }
                        });
                    }

                    if(dt.hasOwnProperty('redirect')) {
                        window.location = dt.redirect;
                    }

                } else {
                    $current_import.removeClass('installed').addClass('import_error');
                    $current_import.find('.status').text('Error ' + data.status);

                    jQuery('.demo_progress_bar .progress_bar').removeClass('active');
                    jQuery('.demo_status').html('Error occurred: ' + data.status + ' - ' + data.statusText + '!');
                    jQuery('.demo_error').show();

                    $demo_start.removeClass('installing');
                    $demo_start.html(skip_continue_text);

                    installation = false;
                    setPopupCloseBtnState('active');
                }
            }
        });
    }

    function setPopupCloseBtnState(state){
        jQuery('.stm_install__demo_popup_close').toggleClass('disabled', state === 'disabled');
    }

    function show_popup() {
        jQuery('.stm_demo_import_choices .install').on('click', function (e) {
            e.preventDefault();
            jQuery('.stm_install__demo_popup .privacy_policy').hide();
            jQuery('.stm_install__demo_popup .demo_install').show();
            layout          = get_importing_layout( jQuery(this).attr('data-layout') );
            layout_builder  = get_importing_layout( jQuery(this).attr('data-builder') );
            layout_name     = get_importing_layout_name( jQuery(this).attr('data-slug') );

            // Set Builder
            set_builder();
            hide_plugins(layout);
            if (jQuery(this).parents().find('.builder').is('.checked')) {
                jQuery(this).parents('.stm-admin-demos-screen').removeClass('builder-no-active');
                jQuery('.stm_install__demo_popup').addClass('active');
            } else {
                jQuery(this).parents('.stm-admin-demos-screen').addClass('builder-no-active');
                jQuery(this).parents('.stm-admin-demos-screen').removeClass('builder-active');
                jQuery('.stm_install__demo_popup').removeClass('active');
            }
            jQuery('.layout_name').text(jQuery('.install[data-layout="' + layout + '"]').attr('data-name'));

            jQuery('.builder').on('click', function () {
                if (jQuery(this).find('input').is(':checked')) {
                    if((typeof layout_builder == 'undefined') || (typeof layout_builder != 'undefined' && layout_builder == jQuery(this).find('input').val())) {
                        jQuery('.stm_install__demo_popup').addClass('active');
                    }

                    set_builder();
                } else {
                    jQuery('.stm_install__demo_popup').removeClass('active');
                }
            });
        });

        jQuery('.stm_install__demo_popup_close').on('click', function (e) {
            e.preventDefault();
            if (!installation) {
                jQuery('.stm_install__demo_popup').removeClass('active');
            }
        });
    }

    function next_installable() {
        jQuery('.stm_plugin_info').each(function () {
            var active = jQuery(this).attr('data-active');
            var currentPlugin = jQuery(this).attr('data-slug');
            var requiredPlugin = jQuery(this).attr('data-required');
            if ( active === 'not-installed' &&
                !plugin &&
                layout_plugins.indexOf(currentPlugin) !== -1 &&
                ( requiredPlugin === 'required' || jQuery(this).find('input:checkbox[value="' + currentPlugin + '"]').is(':checked') )
            ) {
                plugin = currentPlugin;
            }
        });
    }

    function hide_plugins(layout) {
        layout_plugins = stm_layouts[layout];
        install_plugins = get_install_plugins();

        jQuery('.stm_plugin_info').removeClass('visible clear-both');

        jQuery('.stm_plugin_info').each(function () {
            var plugin_slug = jQuery(this).attr('data-slug');
            if (layout_plugins.indexOf(plugin_slug) === -1) {
                jQuery(this).hide().removeClass('visible');
            } else {
                jQuery(this).show().addClass('visible');
            }
        });

        jQuery('.stm_plugin_info.visible').each(function (i) {
            if (i % 2 === 0) {
                jQuery(this).addClass('clear-both');
            }
        });
    }

    /** Set Page Builder */
    function set_builder() {
        /* Remove Old Builder */
        var builder_index = stm_layouts[layout].indexOf('js_composer');
        builder_index = ( builder_index > -1 ) ? builder_index : stm_layouts[layout].indexOf('elementor');
        if ( builder_index > -1 ) {
            stm_layouts[layout].splice(builder_index, 1);
        }
        /* Remove Elementor Addon */
        var elementor_addon_index = stm_layouts[layout].indexOf( elementor_addon );
        if ( elementor_addon_index > -1 ) {
            stm_layouts[layout].splice(elementor_addon_index, 1);
        }

        // New Builder Value
        builder = jQuery('input:radio[name="builder"]:checked').val();

        stm_layouts[layout].push(builder);

        if ( builder === 'elementor' ) {
            stm_layouts[layout].push( elementor_addon );
            jQuery('.builder_name').text('Elementor');
        } else {
            jQuery('.builder_name').text('WP Bakery');
        }

        next_installable();
        hide_plugins(layout);
    }

    /** Get Enabled Plugins */
    function get_install_plugins() {
        if ( typeof importing_install_plugins !== 'undefined' ) {
            return importing_install_plugins;
        }

        return jQuery('input[name="install_plugins[]"]:visible:checked').map(function() {
            return jQuery(this).val();
        }).get()
    }

    /** Get Enabled Import Data */
    function get_import_data() {
        return jQuery('input[name="import_data[]"]:checked').map(function() {
            return jQuery(this).val();
        }).get()
    }

    /** Capitalize String */
    function capitalize_string(string) {
        return string.replace(/\b\w/g, function( char ) { return char.toUpperCase() });
    }

    /** Sticky Top Panel */
    function adjust_scroll_class() {
        var offset = jQuery('.stm-about-text-wrap').innerHeight() + 160;
        if ( jQuery(window).scrollTop() >= offset && jQuery('#send_api').is(':checked') ) {
            jQuery('.top-panel').addClass('top-panel-sticky');
        } else {
            jQuery('.top-panel').removeClass('top-panel-sticky');
        }
    }

    /** Calculate Import Progress */
    function calculate_progress() {
        var progress_elements   = jQuery('.stm_plugin_info.visible input[name="install_plugins[]"]:checked').length + import_data.length;
        var progress_passed     = jQuery('.stm_plugin_info.installed.visible input[name="install_plugins[]"]:checked').length + jQuery('.stm_content_info.installed').length;

        var progress = ( progress_passed / progress_elements ) * 100;
        progress = parseFloat(progress).toFixed(0);

        jQuery('.demo_percent').text(progress);
        jQuery('.progress_bar').css('width', progress + '%');
    }

    /** Hide Reset and Show Demo Import Sections */
    function skip_reset() {
        jQuery('.show_reset_demo').hide();
        jQuery('.show_demo').show();
        install_plugins = get_install_plugins();
    }

    /** Return Importing Layout */
    function get_importing_layout( layout ) {
        if ( typeof importing_layout !== 'undefined' ) {
            layout = importing_layout;
        }
        return layout;
    }

    /** Return Importing Layout Name */
    function get_importing_layout_name( layout_name ) {
        if ( typeof importing_layout_name !== 'undefined' ) {
            layout_name = importing_layout_name;
        }
        return layout_name;
    }

    /** Return Importing Builder */
    function get_importing_builder( builder ) {
        if ( typeof importing_builder !== 'undefined' ) {
            builder = importing_builder;
            if ( builder === 'elementor' ) {
                layout_plugins.push( elementor_addon );
            }
        }
        return builder;
    }
