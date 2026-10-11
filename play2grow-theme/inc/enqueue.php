<?php
/**
 * Styles and scripts. Everything is self-hosted – no third-party requests.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Asset version: theme version, or file time while WP_DEBUG is on.
 *
 * @param string $relative Relative path inside the theme.
 * @return string
 */
function p2g_asset_version( $relative ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG && file_exists( P2G_DIR . '/' . $relative ) ) {
		return P2G_VERSION . '.' . filemtime( P2G_DIR . '/' . $relative );
	}

	return P2G_VERSION;
}

/**
 * Front-end assets.
 */
function p2g_enqueue_assets() {
	wp_enqueue_style( 'p2g-main', P2G_URI . '/assets/css/main.css', array(), p2g_asset_version( 'assets/css/main.css' ) );
	wp_add_inline_style( 'p2g-main', p2g_custom_colors_css() );

	wp_enqueue_script(
		'p2g-main',
		P2G_URI . '/assets/js/main.js',
		array(),
		p2g_asset_version( 'assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_add_inline_script(
		'p2g-main',
		'window.p2gI18n = ' . wp_json_encode(
			array(
				'openMenu'    => __( 'Mở menu', 'play2grow' ),
				'closeMenu'   => __( 'Đóng menu', 'play2grow' ),
				'openSub'     => __( 'Mở menu con', 'play2grow' ),
				'closeSub'    => __( 'Đóng menu con', 'play2grow' ),
				'sending'     => __( 'Đang gửi…', 'play2grow' ),
				'networkErr'  => __( 'Không gửi được do lỗi kết nối. Vui lòng kiểm tra mạng và thử lại.', 'play2grow' ),
				'fixErrors'   => __( 'Vui lòng kiểm tra lại các trường được đánh dấu.', 'play2grow' ),
				'required'    => __( 'Vui lòng điền thông tin này.', 'play2grow' ),
				'nameLen'     => __( 'Họ và tên cần từ 2 đến 80 ký tự.', 'play2grow' ),
				'contactReq'  => __( 'Vui lòng nhập số điện thoại hoặc email để Play2Grow có thể liên hệ lại.', 'play2grow' ),
				'phoneFormat' => __( 'Số điện thoại chưa đúng định dạng (ví dụ: 0912 345 678).', 'play2grow' ),
				'emailFormat' => __( 'Email chưa đúng định dạng (ví dụ: ten@email.com).', 'play2grow' ),
				'consentReq'  => __( 'Vui lòng đồng ý để Play2Grow xử lý thông tin để liên hệ lại.', 'play2grow' ),
			)
		) . ';',
		'before'
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'p2g_enqueue_assets' );

/**
 * Swap the "no-js" class as early as possible (avoids a flash of the open mobile menu).
 */
function p2g_js_class() {
	wp_print_inline_script_tag( "document.documentElement.className=document.documentElement.className.replace('no-js','js');" );
}
add_action( 'wp_head', 'p2g_js_class', 0 );

/**
 * Preload the two most used font files to reduce text layout shift.
 */
function p2g_preload_fonts() {
	$fonts = array( 'be-vietnam-pro-regular.woff2', 'be-vietnam-pro-bold.woff2' );
	foreach ( $fonts as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( P2G_URI . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'p2g_preload_fonts', 2 );

/**
 * Block editor: brand fonts/colors inside the editor canvas come from editor.css
 * (registered through add_editor_style in setup.php).
 */
function p2g_enqueue_block_editor_assets() {
	wp_add_inline_style( 'wp-edit-blocks', p2g_custom_colors_css( '.editor-styles-wrapper' ) );
}
add_action( 'enqueue_block_editor_assets', 'p2g_enqueue_block_editor_assets' );

/**
 * CSS variables for colors chosen in the Customizer.
 *
 * @param string $selector Root selector.
 * @return string
 */
function p2g_custom_colors_css( $selector = ':root' ) {
	$vars    = array();
	$primary = sanitize_hex_color( get_theme_mod( 'p2g_color_primary', '' ) );
	$accent  = sanitize_hex_color( get_theme_mod( 'p2g_color_accent', '' ) );

	if ( $primary && '#0e6b5c' !== strtolower( $primary ) ) {
		$vars[] = '--p2g-primary:' . $primary;
		$vars[] = '--p2g-primary-dark:color-mix(in srgb,' . $primary . ' 75%,#000)';
	}
	if ( $accent && '#ffb938' !== strtolower( $accent ) ) {
		$vars[] = '--p2g-accent:' . $accent;
	}

	return $vars ? $selector . '{' . implode( ';', $vars ) . '}' : '';
}
