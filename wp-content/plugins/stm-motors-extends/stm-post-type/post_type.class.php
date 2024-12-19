<?php

add_action( 'init', array( 'STM_PostType', 'init' ), 1 );

class STM_PostType {

	protected static $PostTypes        = array();
	protected static $Metaboxes        = array();
	protected static $Metaboxes_fields = array();
	protected static $Taxonomies       = array();

	public static function init() {

		self::register_custom_post_types();
		self::register_taxonomies();

		add_action( 'save_post', array( get_class(), 'save_metaboxes' ) );
		add_action( 'add_meta_boxes', array( get_class(), 'add_metaboxes' ) );

	}

	public static function registerPostType( $postType, $title, $args ) {

		$plural_title = empty( $args['pluralTitle'] ) ? $title . 's' : $args['pluralTitle'];
		$labels       = array(
			'name'               => $plural_title,
			'singular_name'      => $title,
			'add_new'            => __( 'Add New', 'stm_motors_extends' ),
			'add_new_item'       => 'Add New ' . $title,
			'edit_item'          => 'Edit ' . $title,
			'new_item'           => 'New ' . $title,
			'all_items'          => 'All ' . $plural_title,
			'view_item'          => 'View ' . $title,
			'search_items'       => 'Search ' . $plural_title,
			'not_found'          => 'No ' . $plural_title . ' found',
			'not_found_in_trash' => 'No ' . $plural_title . '  found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => $plural_title,
		);

		$defaults = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'query_var'          => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => null,
			'supports'           => array( 'title', 'editor' ),
		);

		$args                         = wp_parse_args( $args, $defaults );
		self::$PostTypes[ $postType ] = $args;

	}

	public static function register_custom_post_types() {
		foreach ( self::$PostTypes as $postType => $args ) {
			register_post_type( $postType, $args );

			if ( ! empty( $args['sub_types'] ) ) {
				foreach ( $args['sub_types'] as $args ) {
					$sub_type   = $args;
					$sub_labels = self::post_type_labels( $sub_type['name'], $sub_type['plural'] );

					$sub_args = array(
						'labels'             => $sub_labels,
						'public'             => false,
						'publicly_queryable' => false,
						'show_ui'            => true,
						'show_in_menu'       => 'edit.php?post_type=' . $postType,
						'query_var'          => false,
						'rewrite'            => array( 'slug' => $sub_type['slug'] ),
						'capability_type'    => 'post',
						'has_archive'        => false,
						'hierarchical'       => false,
						'supports'           => $sub_type['supports'],
					);

					register_post_type( $sub_type['slug'], $sub_args );
				}
			}
		}
	}

	private function post_type_labels( $name, $plural ) {
		$name   = sanitize_text_field( $name );
		$plural = sanitize_text_field( $plural );
		$labels = array(
			'name'               => sprintf( '%s', $plural ),
			'singular_name'      => sprintf( '%s', $name ),
			'menu_name'          => sprintf( '%s', $plural ),
			'name_admin_bar'     => sprintf( '%s', $name ),
			/* translators: pt sigular name */
			'add_new'            => __( 'Add New', 'stm_domain' ),
			/* translators: pt sigular name */
			'add_new_item'       => sprintf( __( 'Add new %s', 'stm_domain' ), $name ),
			/* translators: pt sigular name */
			'new_item'           => sprintf( __( 'New %s', 'stm_domain' ), $name ),
			/* translators: pt sigular name */
			'edit_item'          => sprintf( __( 'Edit %s', 'stm_domain' ), $name ),
			/* translators: pt sigular name */
			'view_item'          => sprintf( __( 'View %s', 'stm_domain' ), $name ),
			/* translators: pt sigular name */
			'all_items'          => sprintf( __( 'All %s', 'stm_domain' ), $plural ),
			/* translators: pt sigular name */
			'search_items'       => sprintf( __( 'Search %s', 'stm_domain' ), $plural ),
			/* translators: pt sigular name */
			'parent_item_colon'  => sprintf( __( 'Parent %s', 'stm_domain' ), $plural ),
			/* translators: pt sigular name */
			'not_found'          => sprintf( __( 'No %s found', 'stm_domain' ), $plural ),
			/* translators: pt sigular name */
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'stm_domain' ), $plural ),
		);

		return apply_filters( 'stm_post_type_labels', $labels );
	}

	public static function addMetaBox( $id, $title, $post_types, $callback = '', $context = '', $priority = '', $callback_args = '' ) {

		foreach ( $post_types as $post_type ) {
			$title                                     = empty( $title ) ? __( 'Options', 'stm_motors_extends' ) : $title;
			self::$Metaboxes[ $post_type . '_' . $id ] = array(
				'title'         => $title,
				'callback'      => $callback,
				'post_type'     => $post_type,
				'context'       => empty( $context ) ? 'normal' : $context,
				'priority'      => $priority,
				'callback_args' => $callback_args,
			);
			self::$Metaboxes_fields[ $id ]             = $callback_args['fields'];
		}

	}

	public static function add_metaboxes() {

		foreach ( self::$Metaboxes as $boxId => $args ) {
			add_meta_box(
				$boxId,
				$args['title'],
				empty( $args['callback'] ) ? array( get_class(), 'display_metaboxes' ) : $args['callback'],
				$args['post_type'],
				$args['context'],
				$args['priority'],
				$args['callback_args']
			);
		}
	}

	public static function display_metaboxes( $post, $metabox ) {

		$fields = $metabox['args']['fields'];

		$metabox_key = array_key_first( $fields );

		echo '<input type="hidden" name="stm_custom_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';
		echo '<table class="form-table stm ' . esc_attr( $metabox_key ) . '">';
		foreach ( $fields as $key => $field ) {
			$meta = get_post_meta( $post->ID, $key, true );
			if ( 'hidden' !== $field['type'] ) {
				if ( 'separator' !== $field['type'] ) {
					echo '<tr class="stm_admin_' . esc_attr( $key ) . '"><th><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
				} else {
					echo '<tr><th><h3>' . esc_html( $field['label'] ) . '</h3></th><td>';
				}
			}
			switch ( $field['type'] ) {
				case 'text':
					if ( empty( $meta ) && ! empty( $field['default'] ) && 'auto-draft' === $post->post_status ) {
						$meta = $field['default'];
					}
					echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '" />';
					if ( isset( $field['description'] ) ) {
						echo '<p class="textfield-description">' . esc_html( $field['description'] ) . '</p>';
					}
					break;
				case 'hidden':
					if ( empty( $meta ) && ! empty( $field['default'] ) && 'auto-draft' === $post->post_status ) {
						$meta = $field['default'];
					}
					echo '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '" />';
					break;
				case 'file':
					$file = __( 'No file chosen', 'stm_motors_extends' );
					if ( $meta ) {
						$file = basename( get_attached_file( $meta ) );
					}

					echo '<div class="stm_metabox_image stm_metabox_file">
							<input name="' . esc_attr( $key ) . '" type="hidden" class="custom_upload_image" value="' . esc_attr( $meta ) . '" />
							<span class="custom_preview_file">' . esc_html( $file ) . '</span>
							<input class="stm_upload_image upload_button_' . esc_attr( $key ) . ' button-primary" type="button" value="' . esc_attr( __( 'Choose PDF', 'stm_motors_extends' ) ) . '" />
							<a href="#" class="stm_remove_image button">' . esc_html( __( 'Remove PDF', 'stm_motors_extends' ) ) . '</a>
						</div>';
					//phpcs:disable
					echo '<script type="text/javascript">
							jQuery(function($) {
								$(".upload_button_' . $key . '").click(function(){
									var btnClicked = $(this);
									var custom_uploader = wp.media({
										title   : "' . __( 'Select file', 'stm_motors_extends' ) . '",
										library : { type : "application/pdf"},
										button  : {
											text: "' . __( 'Attach', 'stm_motors_extends' ) . '"
										},
										multiple: false
									}).on("select", function () {
										var attachment = custom_uploader.state().get("selection").first().toJSON();
										btnClicked.closest(".stm_metabox_image").find(".custom_upload_image").val(attachment.id);
										btnClicked.closest(".stm_metabox_image").find(".custom_preview_file").text(attachment.title);

									}).open();
								});
								$(".stm_remove_image").click(function(){
									$(this).closest(".stm_metabox_image").find(".custom_upload_image").val("");
									$(this).closest(".stm_metabox_image").find(".custom_preview_file").text("' . __( 'No file chosen', 'stm_motors_extends' ) . '");
									return false;
								});
							});
						</script>
					';
					//phpcs:enable
					break;
				case 'textarea':
					echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" cols="60" rows="4">' . esc_html( $meta ) . '</textarea>';
					break;
				case 'texteditor':
					if ( ! class_exists( '_WP_Editors', false ) ) {
						require ABSPATH . WPINC . '/class-wp-editor.php';
					}
					_WP_Editors::editor(
						$meta,
						$key,
						array(
							'textarea_name' => $key,
							'media_buttons' => false,
							'teeny'         => true,
							'quicktags'     => false,
						)
					);
					break;
				case 'table_two_column':
					echo '<div class="stm_table-two-columns">';

					if ( $meta ) {
						foreach ( $meta as $t_key => $t_value ) {
							echo '<div class="stm-table_row" row-id="' . esc_attr( $t_key ) . '">';
							foreach ( $t_value as $r_key => $r_value ) {
								echo '<input type="" name="' . esc_attr( $key ) . '[' . esc_attr( $t_key ) . '][' . esc_attr( $r_key ) . ']" value="' . esc_attr( $r_value ) . '" />';
							}
							echo '<input type="button" class="button button-primary remove-row" value="Delete" /></div>';
						}
					}

					echo '</div>';
					echo '<input type="button" class="button button-primary add-row" value="Add"/>';

					//phpcs:disable
					echo '<script>
						    jQuery(function($) {
						    	var i = 0;
								$(".add-row").on("click", function() {
									if($(".stm-table_row").length) {
										i = parseInt($(".stm-table_row").last().attr("row-id")) + 1;
									}

									$(".stm_table-two-columns").append(
										"<div class=\"stm-table_row\" row-id="+i+">" +
										"<input name=\"' . $key . '["+i+"][label]\"  placeholder=\"Label\" />" +
										"<input name=\"' . $key . '["+i+"][value]\" placeholder=\"Value\" />" +
										"<input type=\"button\" class=\"button button-primary remove-row\" value=\"Delete\" />" +
										"</div>"
									);

									i++;
								});

								$(".remove-row").live("click", function() {
									$(this).parent().remove();
								});
						    });
						    </script>';
					//phpcs:enable
					break;
				case 'repeat_single_text':
					echo '<div class="stm_table-two-columns">';

					if ( $meta ) {

						foreach ( $meta as $t_key => $t_value ) {
							echo '<div class="stm-table_row stm-repeat-table-row" row-id="' . esc_attr( $t_key ) . '">' .
								'<input type="text" name="' . esc_attr( $key ) . '[' . esc_attr( $t_key ) . ']" value="' . esc_attr( $t_value ) . '" />' .
								'<input type="button" class="button button-primary remove-row" value="Delete" />' .
								'</div>';
						}
					}

					echo '</div>';
					echo '<input type="button" class="button button-primary add-row" value="Add"/>';

					//phpcs:disable
					echo '<script>
                            jQuery(function($) {
                                var i = 0;
                                $(".add-row").on("click", function() {
                                    if($(".stm-table_row").length) {
                                        i = parseInt($(".stm-table_row").last().attr("row-id")) + 1;
                                    }

                                    $(".stm_table-two-columns").append(
                                        "<div class=\"stm-table_row\" row-id="+i+">" +
                                        "<input name=\"' . $key . '["+i+"]\" placeholder=\"Value\" />" +
                                        "<input type=\"button\" class=\"button button-primary remove-row\" value=\"Delete\" />" +
                                        "</div>"
                                    );

                                    i++;
                                });

                                $(".remove-row").live("click", function() {
                                    $(this).parent().remove();
                                });
                            });
                            </script>';
					//phpcs:enable
					break;
				case 'repeat_single_image':
					echo '<div class="stm_table-two-columns-image">';

					if ( $meta ) {

						foreach ( $meta as $t_key => $t_value ) {
							echo '<div class="stm-table_row-images stm-repeat-table-row" row-id="' . esc_attr( $t_key ) . '">' .
								'<label>' . esc_html__( 'Image ID', 'motors' ) . '</label>' .
								'<input type="text" name="' . esc_attr( $key ) . '[' . esc_attr( $t_key ) . ']" value="' . esc_attr( $t_value ) . '" placeholder=""/>' .
								'<input type="button" class="button button-primary remove-row" value="Delete" />' .
								'</div>';
						}
					}

					echo '</div>';
					echo '<input type="button" class="button button-primary add-row-image" value="Add"/>';

					//phpcs:disable
					echo '<script>
                            jQuery(function($) {
                                var i = 0;
                                $(".add-row-image").on("click", function() {
                                    if($(".stm-table_row-images").length) {
                                        i = parseInt($(".stm-table_row-images").last().attr("row-id")) + 1;
                                    }

                                    $(".stm_table-two-columns-image").append(
                                        "<div class=\"stm-table_row-images stm-repeat-table-row\" row-id="+i+">" +
                                        "<input name=\"' . $key . '["+i+"]\" type=\"text\" placeholder=\"Open Media Library\" />" +
                                        "<input type=\"button\" class=\"button button-primary remove-row-image\" value=\"Delete\" />" +
                                        "</div>"
                                    );

                                    i++;
                                });

                                $(".remove-row-image").live("click", function() {
                                    $(this).parent().remove();
                                });

								$(".stm_table-two-columns-image input[type=\'text\']").live("click", function() {
									var $this = $(this);
									var custom_uploader = wp.media({
										title   : "' . __( 'Select file', 'stm_motors_extends' ) . '",
										button  : {
											text: "' . __( 'Attach', 'stm_motors_extends' ) . '"
										},
										multiple: false
									}).on("select", function () {
										var attachment = custom_uploader.state().get("selection").first().toJSON();
										$this.val(attachment.id);
									}).open();
								});
                            });
                            </script>';
					//phpcs:enable
					break;
				case 'two_sep_field':
					if ( empty( $meta ) && ! empty( $field['default'] ) && 'auto-draft' === $post->post_status ) {
						$meta = $field['default'];
					}
					echo '<input type="number" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '" />';
					if ( isset( $field['description'] ) ) {
						echo '<p class="textfield-description">' . esc_html( $field['description'] ) . '</p>';
					}
					break;
				case 'location':
					echo '<div class="stm-location-search-unit">';
					echo '<input type="text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '" />';
					echo '</div>';
					if ( isset( $field['description'] ) ) {
						echo '<p class="textfield-description">' . esc_html( $field['description'] ) . '</p>';
					}
					break;
				case 'iconpicker':
					$icons = json_decode( file_get_contents( get_template_directory() . '/assets/icons_json/theme_icons.json' ), true );
					foreach ( $icons['icons'] as $icon ) {
						$fonts[] = 'stm-icon-' . $icon['properties']['name'];
					}
					echo '<input type="text" id="stm-iconpicker-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '"/>';

					//phpcs:disable
					echo '<script type="text/javascript">
								jQuery(document).ready(function ($) {
									$("#stm-iconpicker-' . $key . '").fontIconPicker({
										theme: "fip-darkgrey",
										emptyIcon: false,
										source: ' . json_encode( $fonts ) . '
									});
								});
							</script>
						';
					//phpcs:enable
					break;
				case 'checkbox':
					echo '<input type="checkbox" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" ' , $meta ? ' checked="checked"' : '', '/>';
					break;
				case 'images':
					echo '<div class="stm-metabox-media">';
					if ( $meta ) {
						foreach ( $meta as $array_key => $val ) {
							$image = wp_get_attachment_image_src( $val, 'medium' );
							$image = $image[0];
							echo '<div class="stm-uploaded-file">' .
								'<img src="' . esc_url( $image ) . '"/>' .
								'<input type="hidden" class="stm-upload-field" value="' . esc_attr( $val ) . '" name="' . esc_attr( $key ) . '[' . esc_attr( $array_key ) . ']" />' .
								'</div>';
						}
					}
					echo '</div><div class="stm-add-media button button-primary">' . esc_html( __( 'Add', 'stm_motors_extends' ) ) . '</div>';
					//phpcs:disable
					echo '<script>
						    jQuery(function($) {
						       var insertImage = wp.media.controller.Library.extend({
								    defaults :  _.defaults({
								            id: "insert-image",
								            title: "Choose Images",
								            allowLocalEdits: true,
								            displaySettings: true,
								            displayUserSettings: true,
								            multiple : true,
								            type : "image"
								      }, wp.media.controller.Library.prototype.defaults )
								});

								//Setup media frame
								var frame = wp.media({
								    button : { text : "Select" },
								    state : "insert-image",
								    states : [
								        new insertImage()
								    ]
								});

								//on close, if there is no select files, remove all the files already selected in your main frame
								frame.on("close",function() {
								    var selection = frame.state("insert-image").get("selection");
								    if(!selection.length){
								    }
								});

								frame.on( "select",function() {
								    var state = frame.state("insert-image");
								    var selection = state.get("selection");
								    var imageArray = [],
								        i = 0;

								    if ( ! selection ) return;

								    $(".stm-metabox-media").html("");

								    selection.each(function(attachment) {
								        var display = state.display( attachment ).toJSON();
								        var obj_attachment = attachment.toJSON()
								        var caption = obj_attachment.caption, options, html;

								        // If captions are disabled, clear the caption.
								        if ( ! wp.media.view.settings.captions )
								            delete obj_attachment.caption;

								        display = wp.media.string.props( display, obj_attachment );

								        options = {
								            id:        obj_attachment.id,
								            post_content: obj_attachment.description,
								            post_excerpt: caption
								        };

								        if ( display.linkUrl )
								            options.url = display.linkUrl;

								        if ( "image" === obj_attachment.type ) {
								            html = wp.media.string.image( display );
								            _.each({
								            align: "align",
								            size:  "image-size",
								            alt:   "image_alt",
								            src:   "url"
								            }, function( option, prop ) {
								            if ( display[ prop ] )
								                options[ option ] = display[ prop ];
								            });
								        } else if ( "video" === obj_attachment.type ) {
								            html = wp.media.string.video( display, obj_attachment );
								        } else if ( "audio" === obj_attachment.type ) {
								            html = wp.media.string.audio( display, obj_attachment );
								        } else {
								            html = wp.media.string.link( display );
								            options.post_title = display.title;
								        }

								        //attach info to attachment.attributes object
								        attachment.attributes["nonce"] = wp.media.view.settings.nonce.sendToEditor;
								        attachment.attributes["attachment"] = options;
								        attachment.attributes["html"] = html;
								        attachment.attributes["post_id"] = wp.media.view.settings.post.id;

								        var attachmentHtml = attachment.attributes["html"],
								            attachmentID = attachment.attributes["id"];


								            $(".stm-metabox-media").append("<div class=\"stm-uploaded-file\"><img src="+attachment.attributes["attachment"]["url"]+" /> <input type=\"hidden\" value="+attachmentID+" name=\"' . $key . '["+i+"] \" /></div>");
								            i++;

								    });
								});

								frame.on("open",function() {
								    var selection = frame.state("insert-image").get("selection");

								    selection.each(function(image) {
								        var attachment = wp.media.attachment( image.attributes.id );
								        attachment.fetch();
								        selection.remove( attachment ? [ attachment ] : [] );
								    });


								    $(".stm-uploaded-file").find("input[type=\"hidden\"]").each(function(){
								         var input_id = $(this);
								        if( input_id.val() ){
								            attachment = wp.media.attachment( input_id.val() );
								            attachment.fetch();
								            selection.add( attachment ? [ attachment ] : [] );
								        }
								    });
								});

								$(".stm-add-media").on("click", function() {
									frame.open();
								});

								$(".stm-media-remove-all").on("click", function() {
									$(this).closest("tr").find(".stm-metabox-media").html("");
								});

							 });' .
						 '</script>';
					//phpcs:enable
					echo '<div class="stm-media-remove-all button button-primary">Remove</div>';
					break;
				case 'select':
					echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '">';
					foreach ( $field['options'] as $key => $value ) {
						echo '<option', $meta == $key ? ' selected="selected"' : '', ' value="' . esc_attr( $key ) . '">' . esc_html( $value ) . '</option>'; //phpcs:ignore
					}
					echo '</select>';
					break;
				case 'listing_select':
					$currentValues = explode( ',', $meta );
					echo '<select class="stm-multiselect" multiple="multiple" name="' . esc_attr( $key ) . '[]" id="' . esc_attr( $key ) . '">';
					foreach ( $field['options'] as $key => $value ) {
						$disabled = '';
						if ( 'none' === $key ) {
							$disabled = 'disabled';
						}
						echo '<option', in_array( $key, $currentValues, true ) ? ' selected="selected"' : '', ' value="' . esc_attr( $key ) . '" ' . esc_attr( $disabled ) . '>' . esc_html( $value ) . '</option>';
					}
					echo '</select>';
					break;
				case 'color_picker':
					echo '<input type="text" class="colorpicker-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $meta ) . '" />
						<script type="text/javascript">
							jQuery(function($) {
							    $(function() {
							        $(".colorpicker-' . esc_js( $key ) . '").wpColorPicker();
							    });

							});
						</script>
					';
					break;
				case 'date_picker':
					$date_format = get_option( 'date_format' );
					$time_format = get_option( 'time_format' );
					echo '<input class="form-control" id="stm-timedatetimepicker-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '"  value="' . esc_attr( $meta ) . '" />
					     <script type="text/javascript">
						     jQuery(document).ready(function($){
								$("#stm-timedatetimepicker-' . esc_js( $key ) . '").stm_datetimepicker({
									format: "' . esc_js( $date_format . ' ' . $time_format ) . '"
								});
							});
						</script>
						';
					break;
				case 'datepicker':
					echo '<input class="form-control" id="stm-timedatetimepicker-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '"  value="' . esc_attr( $meta ) . '" />
					     <script type="text/javascript">
						     jQuery(document).ready(function($){
								$("#stm-timedatetimepicker-' . esc_js( $key ) . '").stm_datetimepicker({
									format: "m/Y"
								});
							});
						</script>
						';
					break;
				case 'image':
					$default_image = STM_MOTORS_EXTENDS_URL . '/assets/images/default_170x50.gif';
					$image         = '';
					if ( $meta ) {
						$src = wp_get_attachment_image_src( $meta, 'medium' );
						if ( is_array( $src ) && ! empty( $src ) ) {
							$image = $src[0];
						}
					}

					if ( empty( $image ) ) {
						$image = $default_image;
					}

					echo '
						<div class="stm_metabox_image">
							<input name="' . esc_attr( $key ) . '" type="hidden" class="custom_upload_image" value="' . esc_attr( $meta ) . '" />
							<img src="' . esc_url( $image ) . '" class="custom_preview_image" alt="" />
							<input class="stm_upload_image upload_button_' . esc_attr( $key ) . ' button-primary" type="button" value="' . esc_attr( __( 'Choose Image', 'stm_motors_extends' ) ) . '" />
							<a href="#" class="stm_remove_image button">' . esc_html( __( 'Remove Image', 'stm_motors_extends' ) ) . '</a>
						</div>
						<script type="text/javascript">
							jQuery(function($) {
								$(".upload_button_' . esc_js( $key ) . '").click(function(){
									var btnClicked = $(this);
									var custom_uploader = wp.media({
										title   : "' . esc_html( __( 'Select image', 'stm_motors_extends' ) ) . '",
										button  : {
											text: "' . esc_html( __( 'Attach', 'stm_motors_extends' ) ) . '"
										},
										multiple: true
									}).on("select", function () {
										var attachment = custom_uploader.state().get("selection").first().toJSON();
										btnClicked.closest(".stm_metabox_image").find(".custom_upload_image").val(attachment.id);
										btnClicked.closest(".stm_metabox_image").find(".custom_preview_image").attr("src", attachment.url);

									}).open();
								});
								$(".stm_remove_image").click(function(){
									$(this).closest(".stm_metabox_image").find(".custom_upload_image").val("");
									$(this).closest(".stm_metabox_image").find(".custom_preview_image").attr("src", "' . esc_url( $default_image ) . '");
									return false;
								});
							});
						</script>
					';
					break;
			}
			echo '</td></tr>';
		}
		echo '</table>';

	}

	public static function save_metaboxes( $post_id ) {

		if ( ! isset( $_POST['stm_custom_nonce'] ) ) { //phpcs:ignore 
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		$metaboxes = self::$Metaboxes_fields;

		foreach ( $metaboxes as $stm_field_key => $fields ) {

			foreach ( $fields as $field => $data ) {
				$old = get_post_meta( $post_id, $field, true );
				if ( isset( $_POST[ $field ] ) ) { //phpcs:ignore
					$new = ('listing_seller_note' === $field) ? wp_kses_post($_POST[ $field ]) : sanitize_text_field( $_POST[ $field ] );//phpcs:ignore

					if ( $new && $new != $old ) {//phpcs:ignore
						if ( 'listing_select' === $data['type'] ) {
							update_post_meta( $post_id, $field, implode( ',', $new ) );
						} else {
							update_post_meta( $post_id, $field, $new );
						}
					} elseif ( '' === $new && $old ) {
						delete_post_meta( $post_id, $field, $old );
					}
				} else {
					delete_post_meta( $post_id, $field, $old );
				}
			}

			if ( 'listing_filter' === $stm_field_key ) {
				foreach ( $fields as $field => $data ) {

					if ( 'listing_select' === $data['type'] ) {
						if ( isset( $_POST[ $field ] ) ) { //phpcs:ignore
							$new = sanitize_text_field( $_POST[ $field ] );//phpcs:ignore
							if ( 'none' !== $new ) {
								wp_set_object_terms( $post_id, $new, $field );
							}
						}
					}
				}
			}
		}
	}

	public static function addTaxonomy( $slug, $taxonomy_name, $post_type, $args = '' ) {

		$plural_name = empty( $args['plural'] ) ? $taxonomy_name . 's' : $args['plural'];
		$labels      = array(
			'name'              => $taxonomy_name,
			'singular_name'     => $taxonomy_name,
			'search_items'      => 'Search ' . $plural_name,
			'all_items'         => 'All ' . $plural_name,
			'parent_item'       => 'Parent ' . $taxonomy_name,
			'parent_item_colon' => 'Parent ' . $taxonomy_name . ':',
			'edit_item'         => 'Edit ' . $taxonomy_name,
			'update_item'       => 'Update ' . $taxonomy_name,
			'add_new_item'      => 'Add New ' . $taxonomy_name,
			'new_item_name'     => 'New ' . $taxonomy_name . 'Name',
			'menu_name'         => $taxonomy_name,
		);

		$defaults = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $slug ),
		);

		$args                      = wp_parse_args( $defaults, $args );
		self::$Taxonomies[ $slug ] = array(
			'post_type' => $post_type,
			'args'      => $args,
		);

	}


	public static function register_taxonomies() {

		foreach ( self::$Taxonomies as $taxonomy_name => $taxonomy ) {
			register_taxonomy( $taxonomy_name, $taxonomy['post_type'], $taxonomy['args'] );
		}

	}

	public static function getUsers() {
		if ( ! is_admin() ) {
			return array(
				'no' => esc_html__( 'Not assigned', 'stm_motors_extends' ),
			);
		}

		if ( ! function_exists( 'cache_users' ) ) {
			require_once ABSPATH . 'wp-includes/pluggable.php';
		}

		$users_args     = array(
			'blog_id'      => $GLOBALS['blog_id'],
			'role'         => '',
			'meta_key'     => '',
			'meta_value'   => '',
			'meta_compare' => '',
			'meta_query'   => array(),
			'date_query'   => array(),
			'include'      => array(),
			'exclude'      => array(),
			'orderby'      => 'registered',
			'order'        => 'ASC',
			'offset'       => '',
			'search'       => '',
			'number'       => '',
			'count_total'  => false,
			'fields'       => 'all',
			'who'          => '',
		);
		$users          = get_users( $users_args );
		$users_dropdown = array(
			'no' => esc_html__( 'Not assigned', 'stm_motors_extends' ),
		);

		if ( ! is_wp_error( $users ) ) {
			foreach ( $users as $user ) {
				$users_dropdown[ $user->data->ID ] = $user->data->user_login;
			}
		}

		return $users_dropdown;
	}

}
