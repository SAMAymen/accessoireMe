<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', '74bc7343d89b589e55f97c3a9d0748d133726b7003184a14' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );


/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'XPt(E<oWMzyK6Em+6fVibC1U6 _z4 VzdTX,&[4hc{K|oCMQ=<oF{)ui.a-yX|cu' );
define( 'SECURE_AUTH_KEY',  's/f^RsMZEp,*Sqe_SolYyKF4)qWUrMlA)@6Bo/G)>pylm5nQ[=0O`,( J) mEgdV' );
define( 'LOGGED_IN_KEY',    'C1+HecIMgN^kI#~Ko1+l(8>MgeN*05::[EC%Djb^C5[q< |=j{4*h70KJ|,<R0S,' );
define( 'NONCE_KEY',        '[[jZjJ_h5BS&.xN<`}F;*0toIo&|N}c=813yBqSnwL@bp;o~Ub{R?Qd5Q;;2EsZj' );
define( 'AUTH_SALT',        'A$J ZHoR!qI?CQ Zb%su=!S]Ao,Od1?QXoi3]kcuqFBDHcz39j2<*hv+hB.F2ti8' );
define( 'SECURE_AUTH_SALT', ']|a(#e8:E0cc #Jc<w|G:$1LdU6OOFcK-$=@Uc,x62H&aQ:w_,[;Sd]=3[y,Af8@' );
define( 'LOGGED_IN_SALT',   'AT){]GkE8LwBih( <R5FiA2ykIq3yc<=^;|g? ,`U3wGJ-N6rtubC/Gn}E=)Mg}>' );
define( 'NONCE_SALT',       'Z@tBsUfTTbOCZ=]?%+%5p<Ho0s}RE0_3;[Q]j/h3}be4WCr6=i.P#j6!2]uHJ4*Z' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */
define('WP_MEMORY_LIMIT', '256M');
@ini_set( 'post_max_size', '64M' );
@ini_set( 'upload_max_filesize', '64M' );
@ini_set( 'max_execution_time', '300' );
@ini_set( 'max_input_vars', '2004' );
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
