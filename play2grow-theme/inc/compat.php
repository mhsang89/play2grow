<?php
/**
 * Compatibility with common plugins and small performance tweaks.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Elementor: let Elementor Pro's Theme Builder know the theme's core locations exist.
 * Pages built with Elementor work with the "Toàn chiều rộng (không tiêu đề)" template
 * or Elementor's own "Elementor Canvas" / "Elementor Full Width" templates.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Manager.
 */
function p2g_elementor_locations( $manager ) {
	if ( is_object( $manager ) && method_exists( $manager, 'register_all_core_location' ) ) {
		$manager->register_all_core_location();
	}
}
add_action( 'elementor/theme/register_locations', 'p2g_elementor_locations' );

/**
 * Remove the emoji detection script and styles (one less request on every page).
 * Modern browsers render emoji natively. Disable with:
 * add_filter( 'p2g_disable_emoji', '__return_false' );
 */
function p2g_disable_emoji() {
	if ( ! apply_filters( 'p2g_disable_emoji', true ) ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'p2g_disable_emoji' );

/**
 * Do not lazy-load the first content image on singular pages (likely the LCP element).
 *
 * @param int $count Number of images to skip.
 * @return int
 */
function p2g_omit_lazy_count( $count ) {
	return max( 1, (int) $count );
}
add_filter( 'wp_omit_loading_attr_threshold', 'p2g_omit_lazy_count' );
