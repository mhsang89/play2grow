<?php
/**
 * Bridge to the Play2Grow Core plugin, which owns business data (contact form, leads,
 * company information, social links, content shortcodes). Every call is guarded so the
 * theme keeps working – with those features hidden – when the plugin is not active.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether Play2Grow Core is active.
 *
 * @return bool
 */
function p2g_core_active() {
	return function_exists( 'p2gc_company' );
}

/**
 * One company field (see the plugin for keys), '' without the plugin.
 *
 * @param string $key Key, e.g. "phone", "email", "legal_name".
 * @return string
 */
function p2g_company( $key ) {
	return p2g_core_active() ? p2gc_company( $key ) : '';
}

/**
 * Social links [url, label, icon, key].
 *
 * @return array[]
 */
function p2g_social_links() {
	return p2g_core_active() ? p2gc_social_links() : array();
}

/**
 * URL of one social profile by key ("zalo", "facebook"…).
 *
 * @param string $key Key.
 * @return string
 */
function p2g_social_url( $key ) {
	foreach ( p2g_social_links() as $link ) {
		if ( isset( $link[3] ) && $key === $link[3] ) {
			return $link[0];
		}
	}

	return '';
}

/**
 * Company information list.
 *
 * @param string $variant "footer" or "card".
 */
function p2g_company_info( $variant = 'footer' ) {
	if ( p2g_core_active() ) {
		p2gc_company_info( $variant );
		return;
	}
	echo p2g_editor_note( p2g_core_missing_text() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
}

/**
 * Contact form markup ('' without the plugin, plus a note for editors).
 *
 * @param string $title Optional form title.
 * @return string
 */
function p2g_contact_form( $title = '' ) {
	if ( ! shortcode_exists( 'p2g_contact_form' ) ) {
		return p2g_editor_note( p2g_core_missing_text() );
	}

	return do_shortcode( '[p2g_contact_form title="' . esc_attr( $title ) . '"]' );
}

/**
 * Explanation shown to editors when the plugin is missing.
 *
 * @return string
 */
function p2g_core_missing_text() {
	return __( 'Plugin <strong>Play2Grow Core</strong> chưa được kích hoạt nên form liên hệ, thông tin doanh nghiệp và mạng xã hội đang bị ẩn. Cài file play2grow-core.zip tại Plugin → Cài mới → Tải plugin lên, rồi kích hoạt.', 'play2grow-theme' );
}

/**
 * The plugin decides the contact page by itself; make it follow the theme's CTA setting.
 *
 * @param string $url URL from the plugin.
 * @return string
 */
function p2g_filter_core_contact_url( $url ) {
	$theme_url = p2g_contact_url();

	return $theme_url ? $theme_url : $url;
}
add_filter( 'p2gc_contact_url', 'p2g_filter_core_contact_url' );

/**
 * Without the plugin, remove "[p2g_…]" shortcodes from content so visitors never see
 * raw shortcode text. Editor notes ([p2g_note]…[/p2g_note]) are removed completely.
 *
 * @param string $content Content.
 * @return string
 */
function p2g_strip_orphan_shortcodes( $content ) {
	if ( shortcode_exists( 'p2g_note' ) || false === strpos( $content, '[p2g_' ) ) {
		return $content;
	}
	$content = preg_replace( '#\[p2g_note\].*?\[/p2g_note\]#s', '', $content );

	return preg_replace( '#\[/?p2g_[a-z_]+[^\]]*\]#', '', $content );
}
add_filter( 'the_content', 'p2g_strip_orphan_shortcodes', 9 );

/**
 * Admin notice when the plugin is missing.
 */
function p2g_core_missing_notice() {
	if ( p2g_core_active() || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>' . wp_kses_post( p2g_core_missing_text() ) . '</p></div>';
}
add_action( 'admin_notices', 'p2g_core_missing_notice' );
