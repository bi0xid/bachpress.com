<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '84653_BachPress_kasdh');

/** MySQL database username */
define('DB_USER', 'bachpress');

/** MySQL database password */
define('DB_PASSWORD', 'Lyn7u0T09E');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '/@t5j>y`?_)fP+g)k:;HitHkyO%5Mn^~h&|*zISa;1c=ug.E24?%G,uyn><i+dJ2');
define('SECURE_AUTH_KEY',  '){l=,{uq }=<?o|w!P]wMVM4^p>@Oar[izJ-8}#}=#ered.3KuS7!:KTHG~+_y}{');
define('LOGGED_IN_KEY',    'm=+%6%T?A5%bn$AC B83S<%s=>1p?gX-[oYhrRaITqj[XIL9&<%4Gu@>F u%/`.8');
define('NONCE_KEY',        'oFzn-- .IU]y-@Zo.TMABj~HdUT``?OD{,dLa++l|+|Y7:tFa37rxTxI2kEo/D<f');
define('AUTH_SALT',        'L(hiAKt!)~k:vCg$l1xY8M.WR*h#)x^)xC61r=%eq$%Lig<z]@71FYK[ly)P5jn|');
define('SECURE_AUTH_SALT', 'ws1jGl_&c|l/Q<Ww6OJ)T7Lg4>+{HeyXS:/E+&X$DWr. lInK^?3?zjM$#_3.Dn^');
define('LOGGED_IN_SALT',   '*UB,#lW`3&V-7U1<_s/W?+ALp[plq;LzoN4!R %?l?J_]wx#6XJ^R@4kvcYu#IC>');
define('NONCE_SALT',       'J82^Sk|/Y)ZA=4nQ/H)n#]xX$P!c,*/0?-F?yi!zFrf#*[_:p$B <i}R{!g|JA?:');

/**#@-*/


/* MULTISITE */

define('WP_ALLOW_MULTISITE', true);

define( 'MULTISITE', true );
define( 'VHOST', 'yes' );
$base = '/';
define( 'DOMAIN_CURRENT_SITE', 'bachpress.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );

define('FTP_USER', 'bachpress');
define('FTP_PASS', 'mecus687');
define('FTP_HOST', '205.186.146.245:21');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', 'es_ES');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
