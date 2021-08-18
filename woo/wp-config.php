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
define( 'DB_NAME', 'woo' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '%?Ra .r>??N#M29pr&t]-sX/8(X=tN`yk%l$h(TXJJMvo%GT[znn&M7l%?9tm{+d' );
define( 'SECURE_AUTH_KEY',  '3qOm]}^D:0.!rOG*xGeKGQ^D88E2eieubTr8M}ixM7[|O8qE`UPwQy!{}9KhM^8L' );
define( 'LOGGED_IN_KEY',    'CSB3O(qTV=%Kx=/hGtJ~|M<VnO71HI53;B%5]QasL1t]LoS[PS=PzlXB]KI:Urk}' );
define( 'NONCE_KEY',        '_y8}5fc9<i~[({GVPxA9Q6bt2JDZbI<9G:&[zo~-/s*Ilfj8Zo2EiFp0`F1Wg`@q' );
define( 'AUTH_SALT',        '<.FJ.,o(+O;K8_4sw?S9QKw;D<(`#A6czz a}Z^Q_UW8x<=79&gcMq_G[UO.%n=.' );
define( 'SECURE_AUTH_SALT', 'JrW&(a^Bu+R7ZDDw-/PI)]]t<t*Dxa`7|RwnnN0aN9-Oa1dZc9ks$@e4 e1!bJ,8' );
define( 'LOGGED_IN_SALT',   '<BW<B1jQ3:Of0~k_6@_KK<Iz*7`6.;:$h)}0@bH~vs|HJt`2IMoIj3}Z@T+1~Q-U' );
define( 'NONCE_SALT',       ',PNA8[RlVz|<G6tO-Vg~iW2TISQ2qKk.,Te.XBRc>)`rYOvSY1WSF@G{*KC_tGBI' );

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
    define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
