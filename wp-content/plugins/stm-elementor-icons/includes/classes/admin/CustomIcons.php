<?php

namespace SEI\Classes\Admin;

class CustomIcons {

	private $wp_filesystem;
	private $svg_file;
	private $json_file;
	private $font_name      = '';
	private $json_config    = [];
	private $custom_fonts   = [];
	private const OPTION    = 'stm_fonts';
	public $paths           = [];

	/**
	 * Init CustomIcons Class
	 */
	public function __construct() {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . '/wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$this->wp_filesystem    = $wp_filesystem;
		$this->custom_fonts     = get_option( self::OPTION, [] );
		$this->paths            = wp_upload_dir();
		$this->paths['fonts']   = self::OPTION;
		$this->paths['temp']    = trailingslashit( $this->paths['fonts'] ) . 'stm_temp';
		$this->paths['fontdir'] = trailingslashit( $this->paths['basedir'] ) . $this->paths['fonts'];
		$this->paths['tempdir'] = trailingslashit( $this->paths['basedir'] ) . $this->paths['temp'];
		$this->paths['fonturl'] = set_url_scheme( trailingslashit( $this->paths['baseurl'] ) . $this->paths['fonts'] );
		$this->paths['tempurl'] = trailingslashit( $this->paths['baseurl'] ) . trailingslashit( $this->paths['temp'] );
		$this->paths['config']  = 'charmap.php';
		$this->paths['json']    = 'selection.json';

		add_action( 'wp_ajax_cei_upload_font_archive', [ $this, 'upload_font_archive' ] );
		add_action( 'wp_ajax_cei_remove_font_archive', [ $this, 'remove_font_archive' ] );
	}

	/**
	 * Upload new Icons Font
	 */
	public function upload_font_archive() {
		check_ajax_referer( 'cei_upload_font_archive', 'nonce' );

		if ( ! current_user_can( apply_filters( 'avf_file_upload_capability', 'update_plugins' ) ) ) {
			die( esc_html__( "You unfortunately don't have the necessary permissions.", "stm-elementor-icons" ) );
		}

		$path = realpath( get_attached_file( (int) sanitize_text_field( $_POST['values']['id'] ) ) );

		if ( $this->unpack_zip( $path, ['\.eot', '\.svg', '\.ttf', '\.woff', '\.json', '\.css'] ) ) {
			$this->create_new_config();
		}

		if ( $this->font_name == 'unknown' ) {
			$this->delete_directory( $this->paths['tempdir'] );
			die( esc_html__( "Failed to get Font name from downloaded folder", "stm-elementor-icons" ) );
		}

		$path   = trailingslashit( $this->paths[ 'fontdir' ] ) . $this->font_name;
		$file   = json_decode( $this->wp_filesystem->get_contents( $path . '/' . 'selection.json' ), true );
		$prefix = $file[ 'preferences' ][ 'fontPref' ][ 'prefix' ];
		$icons  = [];

		foreach ( $file[ 'icons' ] as $icon ) {
			$icons[] = $prefix . $icon[ 'properties' ][ 'name' ];
		}

		$json = json_encode( [ 'icons' => $icons ] );

		$this->wp_filesystem->put_contents( $path . '/charmap.json', $json, FS_CHMOD_FILE );

		die( esc_html__( 'stm_font_added:', 'stm-elementor-icons' ) . $this->font_name );
	}

	/**
	 * Delete Icons Font
	 */
	public function remove_font_archive() {
		check_ajax_referer( 'cei_remove_font_archive', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			die( esc_html__( "You unfortunately don't have the necessary permissions.", "stm-elementor-icons" ) );
		}

		$font   = sanitize_text_field( $_POST['delete_font'] );
		$list   = $this->get_fonts_list();

		if ( isset( $list[ $font ] ) ) {
			$this->delete_directory( $list[ $font ]['include'] );
			$this->delete_font( $font );
			die( esc_html__( "stm_font_removed", "stm-elementor-icons" ) );
		}

		die( esc_html__( "Failed to remove the Font", "stm-elementor-icons" ) );
	}

	/**
	 * Extract the zip file to a flat folder
	 *
	 * @param $archive
	 * @param $filter
	 * @return bool
	 */
	protected function unpack_zip( $archive, $filter ) {
		if ( is_dir( $this->paths['tempdir'] ) ) {
			$this->delete_directory( $this->paths['tempdir'] );
		}

		if ( ! $this->create_directory( $this->paths['tempdir'] ) ) {
			die( esc_html__( "Failed to create temp folder", "stm-elementor-icons" ) );
		}

		$zip = new \ZipArchive;
		if ( $zip->open( $archive ) ) {
			for ( $i = 0; $i < $zip->numFiles; $i ++ ) {
				$entry = $zip->getNameIndex( $i );

				if ( ! empty( $filter ) ) {
					$delete  = true;
					$matches = array();
					foreach ( $filter as $regex ) {
						preg_match( "!" . $regex . "!", $entry, $matches );
						if ( ! empty( $matches ) ) {
							$delete = false;
							break;
						}
					}
				}

				if ( substr( $entry, - 1 ) == '/' || ! empty( $delete ) ) {
					continue;
				}

				$fp         = $zip->getStream( $entry );
				$contents   = '';

				if ( ! $fp ) {
					die( esc_html__( "Unable to extract the file.", "stm-elementor-icons" ) );
				}

				while ( ! feof( $fp ) ) {
					$contents .= fread( $fp, 8192 );
				}

				$this->wp_filesystem->put_contents( $this->paths['tempdir'] . '/' . basename( $entry ), $contents, FS_CHMOD_FILE );

				fclose( $fp );
			}
			$zip->close();
		} else {
			die( esc_html__( "Failed to work with Zip Archive", "stm-elementor-icons" ) );
		}

		return true;
	}

	/**
	 * Iterate over the file and extract the glyphs for the font
	 *
	 * @return bool
	 */
	protected function create_new_config() {
		$this->json_file = $this->find_config( 'json' );
		$this->svg_file  = $this->find_config( 'svg' );

		if ( empty( $this->json_file ) || empty( $this->svg_file ) ) {
			$this->delete_directory( $this->paths['tempdir'] );
			die( esc_html__( 'Failed to create the necessary config files', 'stm-elementor-icons' ) );
		}

		$content    = wp_remote_fopen( trailingslashit( $this->paths['tempurl'] ) . $this->svg_file );
		$json       = $this->wp_filesystem->get_contents( trailingslashit( $this->paths['tempdir'] ) . $this->json_file );

		if ( empty( $content ) ) {
			$content = $this->wp_filesystem->get_contents( trailingslashit( $this->paths['tempdir'] ) . $this->svg_file );
		}

		if ( ! is_wp_error( $json ) && ! empty( $json ) ) {
			$xml                = simplexml_load_string( $content );
			$font_attributes    = $xml->defs->font->attributes();
			$this->font_name    = (string) $font_attributes['id'];

			if ( is_dir( trailingslashit( $this->paths['fontdir'] ) . $this->font_name ) ) {
				$this->delete_directory( $this->paths['tempdir'] );
				die( esc_html__( "Seems Font Icon with the same name is already exist! Please upload the Font Icon with different name.", "stm-elementor-icons" ) );
			}

			$file_contents = json_decode( $json );

			if ( ! isset( $file_contents->IcoMoonType ) ) {
				$this->delete_directory( $this->paths['tempdir'] );
				die( esc_html__( "Font Icon is not from Icomoon. Please upload fonts created with the Icomoon.", "stm-elementor-icons" ) );
			}

			foreach ( $file_contents->icons as $icon ) {
				$icon_name  = $icon->properties->name;
				$icon_class = str_replace( ',', ' ', str_replace( ' ', '', $icon_name ) );
				$tags       = implode( ",", $icon->icon->tags );
				$this->json_config[ $this->font_name ][ $icon_name ] = [
					"class"   => $icon_class,
					"tags"    => $tags
				];
			}

			if ( ! empty( $this->json_config ) && $this->font_name != 'unknown' ) {
				$this->init_config();
			}
		}

		return false;
	}

	/**
	 * Init Created Config
	 */
	protected function init_config() {
		$this->add_config_file();
		$this->rewrite_css();
		$this->move_files();
		$this->rename_directory();
		$this->save_font();
	}

	/**
	 * Write the php config file for the font
	 */
	protected function add_config_file() {
		$charmap    = $this->paths['tempdir'] . '/' . $this->paths['config'];
		$content    = '<?php $icons = array();';
		$delimiter  = "'";

		foreach ( $this->json_config[ $this->font_name ] as $icon => $info ) {
			if ( ! empty( $info ) ) {
				$content .= "\r\n" . '$icons[\'' . $this->font_name . '\'][' . $delimiter . $icon . $delimiter . '] = array("class"=>' .
				            $delimiter . $info["class"] . $delimiter . ',"tags"=>' . $delimiter . $info["tags"] . $delimiter . ');';
			} else {
				$this->delete_directory( $this->paths['tempdir'] );
				die( esc_html__( "Failed to write Config file.", "stm-elementor-icons" ) );
			}
		}

		if ( ! $this->wp_filesystem->put_contents( $charmap, $content, FS_CHMOD_FILE ) ) {
			$this->delete_directory( $this->paths['tempdir'] );
			die( esc_html__( "Failed to write Config file.", "stm-elementor-icons" ) );
		}
	}

	/**
	 * Rewrite the php config file for the font
	 */
	protected function rewrite_css() {
		$style = $this->paths['tempdir'] . '/style.css';
		$file  = $this->wp_filesystem->get_contents( $style );
		if ( $file ) {
			$str = str_replace( 'fonts/', '', $file );
			$str = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $str );
			$str = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $str );

			$this->wp_filesystem->put_contents( $style, $str, FS_CHMOD_FILE );
		} else {
			die( esc_html__( "Failed to write CSS. Please upload icons downloaded only from Icomoon", "stm-elementor-icons" ) );
		}
	}

	/**
	 * Get Icon-Fonts List
	 * @return array|mixed
	 */
	protected function get_fonts_list() {
		$font_configs   = $this->custom_fonts;
		$path           = trailingslashit( $this->paths['basedir'] );
		$url            = trailingslashit( $this->paths['baseurl'] );

		foreach ( $font_configs as $key => $config ) {
			if ( empty( $config['full_path'] ) ) {
				$font_configs[ $key ]['include'] = $path . $font_configs[ $key ]['include'];
				$font_configs[ $key ]['folder']  = $url . $font_configs[ $key ]['folder'];
			}
		}

		return $font_configs;
	}

	/**
	 * Add Icons Font
	 */
	protected function save_font() {
		$this->custom_fonts[ $this->font_name ] = array(
			'include'   => trailingslashit( $this->paths['fonts'] ) . $this->font_name,
			'folder'    => trailingslashit( $this->paths['fonts'] ) . $this->font_name,
			'style'     => $this->font_name . '/' . $this->font_name . '.css',
			'config'    => $this->paths['config'],
			'json'      => $this->paths['json']
		);
		update_option( self::OPTION, $this->custom_fonts );
	}

	/**
	 * Remove Icons Font
	 * @param $font
	 */
	protected function delete_font( $font ) {
		if ( isset( $this->custom_fonts[ $font ] ) ) {
			unset( $this->custom_fonts[ $font ] );
			update_option( self::OPTION, $this->custom_fonts );
		}
	}

	/**
	 * Create Folder
	 *
	 * @param $folder
	 * @return bool
	 */
	public function create_directory( &$folder ) {
		if ( is_dir( $folder ) ) {
			return true;
		}

		$created = wp_mkdir_p( trailingslashit( $folder ) );
		$this->wp_filesystem->chmod( $folder, 0777 );

		return $created;
	}

	/**
	 * Delete Folder
	 *
	 * @param $folder
	 */
	protected function delete_directory( $folder ) {
		if ( is_dir( $folder ) ) {
			$objects = scandir( $folder );

			foreach ( $objects as $object ) {
				$filename = $folder . "/" . $object;
				if ( $object != "." && $object != ".." ) {
					if ( is_dir( $filename ) ) {
						$this->delete_directory( $filename );
					} else {
						unlink( $filename );
					}
				}
			}

			rmdir( $folder );
		}
	}

	/**
	 * Rename the temp folder and all its font files
	 *
	 * @return bool
	 */
	protected function rename_directory() {
		$new_name = trailingslashit( $this->paths['fontdir'] ) . $this->font_name;
		$this->delete_directory( $new_name );

		if ( rename( $this->paths['tempdir'], $new_name ) ) {
			return true;
		} else {
			$this->delete_directory( $this->paths['tempdir'] );
			die( esc_html__( "Failed to add this font. Please try again!", "stm-elementor-icons" ) );
		}
	}

	/**
	 * Rename Icon Font Files
	 */
	protected function move_files() {
		foreach ( glob( trailingslashit( $this->paths['tempdir'] ) . '*' ) as $file ) {
			$path_parts = pathinfo( $file );
			if ( strpos( $path_parts['filename'], '.dev' ) === false
			     && isset( $path_parts['extension'] )
			     && in_array( $path_parts['extension'], [ 'eot', 'svg', 'ttf', 'woff', 'css' ] )
			) {
				if ( $path_parts['filename'] !== $this->font_name ) {
					rename( $file, trailingslashit( $path_parts['dirname'] ) . $this->font_name . '.' . $path_parts['extension'] );
				}
			}
		}
	}

	/**
	 * Find SVG|JSON config files
	 * @param $ext
	 * @return mixed
	 */
	protected function find_config( $ext ) {
		foreach ( scandir( $this->paths['tempdir'] ) as $file ) {
			if ( strpos( strtolower( $file ), '.' . $ext ) !== false && $file[0] != '.' ) {
				return $file;
			}
		}
	}

}