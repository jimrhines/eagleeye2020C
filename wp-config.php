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
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\Users\jrhin\Local Sites\eepowers\app\public\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'YFyBvSD9h3p2tGvrKgL5hvNbnmNQ7iyz2daFH/qMpjw9HlC7QjNZOB9CMFkVnKcF0EMRa8GBizYhNJaX1d5meg==');
define('SECURE_AUTH_KEY',  'Q2JJuq2/o+yz32M7xQMX5EOYZJG9GlGsh+DRFyirQ84lgvp3p4aESDHSPVxDVsRuA1k6MDyJx80lpSHu61hLEQ==');
define('LOGGED_IN_KEY',    'XfMMfRmxiS5+hbxx7S2U8gCpFNuZGVOxTDp3F4hN/WWDRXd5jz15WIytvCFSuSxxpcSyQAPjTLKisUALnadrOA==');
define('NONCE_KEY',        'lvcwTLxhoQiJODdeJMc42Yq8xytSug9c2jfOP8PTBXIpUW7MHGq76z/qH3NnfRohzvbn368xlcMLYdupF+p79w==');
define('AUTH_SALT',        'FY9D5y0JlipLQu48Ayed9YbpMLnHuopZ4AyG2hS5YKiMNLjCILMAZxTPwsjd8httVSMvvkK6iDV51AYqCz7e1A==');
define('SECURE_AUTH_SALT', '8dlDt9AaAtv6x654wsaN+ECOmlcF8bnweY67YBnnX+URbb/twxeZq/h/OsOD8fRc64+sr/LIwGGZdbrNfdPaOw==');
define('LOGGED_IN_SALT',   'lmchx3fQVkYXcFdynU6GjYsBc0OSy+rVliypi/Mnk/p6N5K+Hy5DdB7ZLTgIxDb84YsXITj88VtYSG5wkFa5ow==');
define('NONCE_SALT',       '6EhtjbXCbLDils0MedzCh2whfrb9DlwDPHYEQAXyfhz7WzkWk8Z1G1Pk2mHCuF1y41fCU4LworXLkRq2tSGP1g==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
