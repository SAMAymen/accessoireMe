<?php

class STM_Theme_Changelog {
	private static $chlgTransientName = 'theme_changelog';

	public static function init() {
		add_action( 'admin_init', array( self::class, 'request_theme_changelog' ) );
	}

	public static function request_theme_changelog() {
		$res     = get_transient( self::$chlgTransientName );
		$updated = get_option( 'stm_changelog_udated' );

		if ( ! $res || is_wp_error( $res ) || ! $updated ) {
			$res = wp_remote_request( STM_SERVICE_URL . strtolower( STM_THEME_NAME ) . '_new.json', array( 'sslverify' => false ) );

			if ( ! is_wp_error( $res ) && isset( $res['body'] ) && ! empty( $res['body'] ) ) {
				set_transient( self::$chlgTransientName, $res['body'], DAY_IN_SECONDS );
				update_option( 'stm_changelog_udated', true );
			}
		}
	}

	public static function get_theme_changelog() {
		$res = json_decode( get_transient( self::$chlgTransientName ) );

		$newChangelogData = array();
		$chlg             = array();

		if ( ! empty( $res ) ) {
			foreach ( $res->document->nodes as $node ) {
				$matches = null;
				preg_match('/(heading)/', $node->type, $matches, PREG_OFFSET_CAPTURE);

				if ( count($matches) > 0 ) {
					$chlg['heading'] = $node->nodes[0]->leaves[0]->text;
				} elseif ( $node->type == 'paragraph' ) {
					$chlg['date'] = $node->nodes[0]->leaves[0]->text;
				} elseif ( $node->type == 'list-unordered' ) {
					foreach ( $node->nodes as $node_2 ) {
						foreach ( $node_2->nodes as $node_2 ) {
							$str = '';
							foreach ( $node_2->nodes as $k => $node_3 ) {
								if ( ! empty( $node_3->leaves ) ) {
									foreach ( $node_3->leaves as $list_item ) {
										if ( count( $list_item->marks ) > 0 && ! preg_match( "/[0-9]+$/", $list_item->text ) ) {
											$str .= sprintf( '<b>%s:</b>', str_replace( array(
												':',
												'ED'
											), '', $list_item->text ) );
										} else {
											$str .= str_replace( ':', '', $list_item->text );
										}
									}
								} else {
									foreach ( $node_3->nodes as $node_4 ) {
										if ( ! empty( $node_4->leaves ) ) {
											foreach ( $node_4->leaves as $list_item ) {
												$str .= str_replace( ':', '', $list_item->text );
											}
										}
									}

								}

							}

							$chlg['list'][] = $str;
						}
					}
					$newChangelogData[] = $chlg;
					$chlg               = array();
				}

			}
		}

		return $newChangelogData;
	}
}