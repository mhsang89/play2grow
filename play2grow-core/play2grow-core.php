<?php
/**
 * Plugin Name:       Play2Grow Core
 * Plugin URI:        https://play2grow.edu.vn/
 * Description:       Chức năng dữ liệu cho website Play2Grow, độc lập với giao diện: form liên hệ / đăng ký nhận thông tin (chống spam, lưu trữ có thời hạn, xuất/xóa dữ liệu cá nhân), thông tin doanh nghiệp, liên kết mạng xã hội và các shortcode nội dung [p2g_…].
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Play2Grow
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       play2grow-core
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

define( 'P2GC_VERSION', '1.0.0' );
define( 'P2GC_FILE', __FILE__ );
define( 'P2GC_DIR', plugin_dir_path( __FILE__ ) );
define( 'P2GC_URL', plugin_dir_url( __FILE__ ) );

require_once P2GC_DIR . 'includes/functions.php';
require_once P2GC_DIR . 'includes/customizer.php';
require_once P2GC_DIR . 'includes/leads.php';
require_once P2GC_DIR . 'includes/contact-form.php';
require_once P2GC_DIR . 'includes/shortcodes.php';
require_once P2GC_DIR . 'includes/admin.php';

/**
 * Load translations (strings are written in Vietnamese).
 */
function p2gc_load_textdomain() {
	load_plugin_textdomain( 'play2grow-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'p2gc_load_textdomain' );

/**
 * Activation: make sure the clean-up job is scheduled.
 */
function p2gc_activate() {
	p2gc_register_lead_type();
	if ( ! wp_next_scheduled( 'p2g_purge_leads' ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'p2g_purge_leads' );
	}
}
register_activation_hook( __FILE__, 'p2gc_activate' );

/**
 * Deactivation: stop the clean-up job. Stored leads and settings are kept; delete them
 * from "Đăng ký liên hệ" before removing the plugin if they are no longer needed.
 */
register_deactivation_hook( __FILE__, 'p2gc_unschedule_purge' );

/**
 * Settings link on the Plugins screen.
 *
 * @param array $links Links.
 * @return array
 */
function p2gc_action_links( $links ) {
	array_unshift( $links, '<a href="' . esc_url( admin_url( 'options-general.php?page=p2gc-settings' ) ) . '">' . esc_html__( 'Cài đặt', 'play2grow-core' ) . '</a>' );

	return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'p2gc_action_links' );
