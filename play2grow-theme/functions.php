<?php
/**
 * Play2Grow theme bootstrap.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

define( 'P2G_VERSION', '1.1.0' );
define( 'P2G_DIR', get_template_directory() );
define( 'P2G_URI', get_template_directory_uri() );

$p2g_includes = array(
	'inc/helpers.php',
	'inc/setup.php',
	'inc/enqueue.php',
	'inc/customizer.php',
	'inc/template-tags.php',
	'inc/navigation.php',
	'inc/meta-boxes.php',
	'inc/seo.php',
	'inc/plugin-bridge.php',
	'inc/programs.php',
	'inc/patterns.php',
	'inc/admin.php',
	'inc/starter-content.php',
	'inc/compat.php',
);

foreach ( $p2g_includes as $p2g_file ) {
	require_once P2G_DIR . '/' . $p2g_file;
}
unset( $p2g_includes, $p2g_file );
