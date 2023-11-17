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
define( 'DB_NAME', 'u928454653_mane' );

/** Database username */
define( 'DB_USER', 'u928454653_mane' );

/** Database password */
define( 'DB_PASSWORD', '&9?l^fQktvC' );

/** Database hostname */
define( 'DB_HOST', '' );

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
define( 'AUTH_KEY',         ']eh!W!7,GBVXIcUhoOkJOkvA>j`786jk<rlvRiuH-W-(-*r1q=aKx}*.Nqd8LUSs' );
define( 'SECURE_AUTH_KEY',  'd4GChcB1B||xI/K,h:@_~Nz;.bP[HUsjY:YI#t<fygY5{pn,r/2^3Fg*zFj;&_eT' );
define( 'LOGGED_IN_KEY',    '$i9g4>_nd}p%$-UV*m1!5]1LH djkrPLeW*x)ua(y6Y!qldBLo+[C~LpQFI7`:P1' );
define( 'NONCE_KEY',        '}@;G@_x6NP{I4~18v3dtFI-t*!yRP2W i-[USU]3;rI(@wd.-DW*}lmj)[k-_y}q' );
define( 'AUTH_SALT',        '#x8Xodk:KWln9~8UhXq+FG_V8B!9 2*pn<V[}ul,)mqMcZVysA4}0J=.ELuWY_f@' );
define( 'SECURE_AUTH_SALT', 'H3:V<W:QHi!?UA:T,ra-}=YPG!iPPR(h|WDjMM?*E-sKngX?>v9)QB62a88_r5Sr' );
define( 'LOGGED_IN_SALT',   'hnsdM&dz,f2pCk3y5-e_*`;acb}!,_S@N}NZxt(2.= y-2K`f-w:0>4m4D#8#>mU' );
define( 'NONCE_SALT',       'peQ)4J*bH19xt^QY:&`y9z&&_DR+9(e/?G^b)?6C-A-5D~j<`%JCIcA/[a(V.g^*' );

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

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
