<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'kihiti' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'sEJJ-5&zv[,pJN/919Xtu{3WA!>!&bvvo98rXn$%U5js)t6s>K*7~WOsfA !SWFq' );
define( 'SECURE_AUTH_KEY',  'n3Qp61HpF0zX1,;O7Z/iyN^/j^JsYJwOqO2q),rc2m=$5]EA:MT_x<fG|hAxB~)8' );
define( 'LOGGED_IN_KEY',    'De=W$;9PA@{#$I<un:*}2&ETFAD86n.5~-Era_zX5fTm;sXC=Vr7>QhHj-D^M^LY' );
define( 'NONCE_KEY',        '>A/(m(W01Gxd}{+Ad%mX/8l/RIWEZ.-%,w2WFc?6ZEb?}*_6BDD[&+o</=ca3GUK' );
define( 'AUTH_SALT',        'sb`_=g*Y+fS<<]+@7qY]p6^}b-/]sD^8LSa?c$6=CT4ssED!v1a<H>;w<=)tU>aT' );
define( 'SECURE_AUTH_SALT', '|jx8,:Yy>5^Ug5c]d:z+/N&OA.p!LIP}4I%~6sy!H!Q1?i~%m;LL0p_r+MAcI00t' );
define( 'LOGGED_IN_SALT',   '{JI5o]LzH29r4P>K:9Z,_0ZbHFGTsAO4UP]P[;KX-[ID+tgOky Ys53Mihvw`u9G' );
define( 'NONCE_SALT',       '8$CqD@r?$h:mhNL 9rv%lC2-NNO~p{~YtSW;z&|=rto&1j.,LZ?s7ZJPG&$ILGZ-' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
