<?php
/**
 * The base configuration for ClassicPress
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
 * @link https://docs.classicpress.net/user-guides/editing-wp-config-php/
 *
 * @package ClassicPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for ClassicPress */
define( 'DB_NAME', 'qkobcfli_clas408' );

/** Database username */
define( 'DB_USER', 'qkobcfli_clas408' );

/** Database password */
define( 'DB_PASSWORD', 'pS69(Z4J-2' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.classicpress.net/secret-key/1.0/salt/ ClassicPress.net secret-key service}
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '2g2pnafvzul6tvjsgwyxs4xyypeandqb8noaqy0z0pplc0abgbg1jl4gls8p5lwo' );
define( 'SECURE_AUTH_KEY',  '8vizp3klquzkkwt9b9aa2grfmvfqm3o6u8clrottahhejej4i4jpjvarzxvkflq6' );
define( 'LOGGED_IN_KEY',    '48aky68msoqparmtvylslb7db7zcxddz5di58ahruf2hul7bonfeqwqdt9kh4sao' );
define( 'NONCE_KEY',        'e5vexgr4iaiyxr52li5xvui69xsedqaks4kbpkfbltu1jyhwrgv88u7k6dx4ylx8' );
define( 'AUTH_SALT',        'knwhgannqvydgggghvehcrelidz0fml5qvp0j8sqafboveptmqr3vlepasdxse2e' );
define( 'SECURE_AUTH_SALT', 'zzmrugecsgiphpsuur3jbpgh0ztst6qu1kv5sxppuiqrn7dfsuon6uhzvhprxbao' );
define( 'LOGGED_IN_SALT',   '5msjyf0wp21sjuqttnwvrzpgzslpbbiebfnatupbayiq6g4qnhaeke8yxoafilvx' );
define( 'NONCE_SALT',       'hadqyj4nwkyfgvkiwrc3wcvjeqepqefr0xkeddybsimj51yjmi1fjkisalfaqghj' );

/**#@-*/

/**
 * ClassicPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'cpma_';

/**
 * ClassicPress File Editor
 * 
 * The File Editor for plugins and themes is disabled in new installations.
 * If you want to enable the File Editor, simply remove the line below or
 * set it to "false".
 * 
 * @link https://docs.classicpress.net/user-guides/using-classicpress/editing-files/
 * 
 * @since CP-2.0.0
 */
define( 'DISALLOW_FILE_EDIT', false );

/**
 * For developers: ClassicPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://docs.classicpress.net/user-guides/debugging-in-classicpress/
 */
define( 'WP_DEBUG', true ); 
if ( WP_DEBUG ) {
    define( 'WP_DEBUG_LOG', true );
    define( 'WP_DEBUG_DISPLAY', true );
    @ini_set( 'display_errors', 1 );
    define('SAVEQUERIES', true);
}

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the ClassicPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up ClassicPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
