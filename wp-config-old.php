<?php
/** 
 * The base configurations of the WordPress.
 *
 **************************************************************************
 * Do not try to create this file manually. Read the README.txt and run the 
 * web installer.
 **************************************************************************
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information by
 * visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '735473_BachPress_jdhdf');

/** MySQL database username */
define('DB_USER', '5437_dlksdf');

/** MySQL database password */
define('DB_PASSWORD', 'xor4hic2');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define('VHOST', 'yes'); 
$base = '/';
define('DOMAIN_CURRENT_SITE', 'bachpress.com' );
define('PATH_CURRENT_SITE', '/' );
define('SITE_ID_CURRENT_SITE', 1);
define('BLOGID_CURRENT_SITE', 1 );
define('WP_ALLOW_MULTISITE', true);



define( 'MULTISITE', true );

/**#@+
 * Authentication Unique Keys.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link http://api.wordpress.org/secret-key/1.1/wpmu/salt WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'd5c8321272ddd83fc314c7b2d77b4da0e6b84d15258602e94cc847c98bb44709');
define('SECURE_AUTH_KEY', '1ef6cef144e1610855bc87912e6f6be790201710733f7b78bde66901bfe29153');
define('LOGGED_IN_KEY', '3d85bb44db95b6cdd66b143fb589400a314ea5d10b9089c196cc0800bc7ed971');
define('NONCE_KEY', '8903bc5c726f3255ad40b92affbe39a05e87c4c8f063087e4f28521d7aa3381b');
define('AUTH_SALT', '69bd57f93d459753923de2dd587c6270490de8b6cf790383a443d7784e15025d');
define('LOGGED_IN_SALT', '7f9bf43d4efd96e393e3c9e19b88c2c4aec0099df0dce12087fabe57eebde2a7');
define('SECURE_AUTH_SALT', 'd13d2f598523b7eab1c4a9ca6b59faec249941fd1321d40b929dff7e6645d1b2');
define( 'NONCE_SALT', 'cqs7K!qRr8!25v3nSP(o(W)P' );
/**#@-*/

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
define('EMPTY_TRASH_DAYS',7);
define('WP_ALLOW_REPAIR',true);

// double check $base
if( $base == 'BASE' )
	die( 'Problem in wp-config.php - $base is set to BASE when it should be the path like "/" or "/blogs/"! Please fix it!' );

// uncomment this to enable WP_CONTENT_DIR/sunrise.php support
//define( 'SUNRISE', 'on' );

// uncomment to move wp-content/blogs.dir to another relative path
// remember to change WP_CONTENT too.
// define( "UPLOADBLOGSDIR", "fileserver" );

// If VHOST is 'yes' uncomment and set this to a URL to redirect if a blog does not exist or is a 404 on the main blog. (Useful if signup is disabled)
// For example, the browser will redirect to http://examples.com/ for the following: define( 'NOBLOGREDIRECT', 'http://example.com/' );
// Set this value to %siteurl% to redirect to the root of the site
// define( 'NOBLOGREDIRECT', '' );
// On a directory based install you must use the theme 404 handler.

// Location of mu-plugins
// define( 'WPMU_PLUGIN_DIR', '' );
// define( 'WPMU_PLUGIN_URL', '' );
// define( 'MUPLUGINDIR', 'wp-content/mu-plugins' );

define( "WP_USE_MULTIPLE_DB", false );

/* That's all, stop editing! Happy blogging. */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
?>