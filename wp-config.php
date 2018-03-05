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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
if($_SERVER['SERVER_NAME'] =="localhost"){

    define( 'DB_NAME', 'martin_wp' );

    /** MySQL database username */
    define( 'DB_USER', 'root' );

    /** MySQL database password */
    define( 'DB_PASSWORD', '' );

    /** MySQL hostname */
    define( 'DB_HOST', 'localhost' );

}else{
    define( 'DB_NAME', 'admin_martin' );

    /** MySQL database username */
    define( 'DB_USER', 'admin_martin' );

    /** MySQL database password */
    define( 'DB_PASSWORD', 'ameena30NOV*' );

    /** MySQL hostname */
    define( 'DB_HOST', 'localhost' );
}


/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'CC7=978^}BY==Smjp+F$^zbU;98WBDn|T3I,E;?&N/~A`ote 5X{gjQaFLs6qfuk');
define('SECURE_AUTH_KEY',  '3,E@I1jh/re6z6<8<8K>7V8PtuO#E%|V^[X(}=-&&v)bndV!C|51$;/-D/dKUrQ1');
define('LOGGED_IN_KEY',    '!dC9&X+1+s$W N5fW~?nF|)pzIk7R~m#,9pt%_Xun>B?>io.QOqiVKe<c@& @c)P');
define('NONCE_KEY',        'dx3(@d$k1Za2,]^?cmdRg1_H9,MaXc}r]l]K_]LBNg*Xefa|}~kcS=b&]=3kF+]b');
define('AUTH_SALT',        'kV]8x`BN_hbZgf-n,!t/V+Z`Yld>Gw@OFiVO8Zr;{R_i%G?eL+MgO0z[3YxAWb.X');
define('SECURE_AUTH_SALT', '.gMOMl#QWGoAPCb;AUWf<1 z~Hh 1X1fn`KWry>&vz.9p+ #6=}we[e.YvuvCgr|');
define('LOGGED_IN_SALT',   '-#M1ruz{2?<WlDwKG9D~pEv~k5+2;hf#QZ][^Gc?$t9HGAqm0s1s.kBo)?6E*qh=');
define('NONCE_SALT',       '8SXd2eVsq,Xz]&+`$Zp5|?h>2W12o&t47,]S}wnO,qvWiL,jlwKe&_ll;N_H!lC@');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'martin_wp';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
