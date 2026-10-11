<?php
/**
 * Small helper functions shared across the theme.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether a dedicated SEO plugin is active. When it is, the theme stops
 * printing its own meta description, Open Graph tags and JSON-LD to avoid duplicates.
 *
 * @return bool
 */
function p2g_seo_plugin_active() {
	$active = defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'THE_SEO_FRAMEWORK_VERSION' )
		|| defined( 'SLIM_SEO_VER' );

	/**
	 * Filter whether the theme should consider an SEO plugin active.
	 *
	 * @param bool $active Detected state.
	 */
	return (bool) apply_filters( 'p2g_seo_plugin_active', $active );
}

/**
 * Whether the current visitor may see internal editor notes ("cần xác nhận").
 *
 * @return bool
 */
function p2g_can_see_notes() {
	return is_user_logged_in() && current_user_can( 'edit_posts' );
}

/**
 * Return the ID of a published page by its path, or 0.
 *
 * @param string $path Page path, e.g. "lien-he" or "chuong-trinh/ten".
 * @return int
 */
function p2g_page_id_by_path( $path ) {
	$page = get_page_by_path( $path );

	return ( $page && 'publish' === $page->post_status ) ? (int) $page->ID : 0;
}

/**
 * URL of the consultation / contact page used by every CTA.
 * Returns an empty string when no such page is published, so templates can
 * hide the button instead of linking to a 404.
 *
 * @return string
 */
function p2g_contact_url() {
	$page_id = absint( get_theme_mod( 'p2g_cta_page', 0 ) );

	if ( ! $page_id || 'publish' !== get_post_status( $page_id ) ) {
		$page_id = p2g_page_id_by_path( 'lien-he' );
	}
	if ( ! $page_id ) {
		$page_id = p2g_page_id_by_path( 'dang-ki' ); // Existing registration page on play2grow.edu.vn.
	}

	if ( ! $page_id ) {
		$pages   = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => 'page-templates/template-contact.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'fields'         => 'ids',
			)
		);
		$page_id = $pages ? (int) $pages[0] : 0;
	}

	$url = $page_id ? get_permalink( $page_id ) : '';

	return (string) apply_filters( 'p2g_contact_url', $url );
}

/**
 * Label of the main call-to-action button.
 *
 * @return string
 */
function p2g_cta_label() {
	$label = trim( (string) get_theme_mod( 'p2g_cta_label', '' ) );

	return '' !== $label ? $label : __( 'Đăng ký nhận thông tin', 'play2grow-theme' );
}

/**
 * Inline SVG icon. Icons are decorative (aria-hidden) unless a label is given.
 *
 * @param string $name  Icon name.
 * @param string $label Optional accessible label.
 * @return string
 */
function p2g_icon( $name, $label = '' ) {
	$paths = array(
		'arrow-right'  => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'chevron-down' => '<path d="M6 9l6 6 6-6"/>',
		'menu'         => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'        => '<path d="M6 6l12 12M18 6L6 18"/>',
		'phone'        => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 005 5L15 13l5 2v4a2 2 0 01-2 2A16 16 0 013 6a2 2 0 012-2"/>',
		'mail'         => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'map-pin'      => '<path d="M12 21s-7-6.2-7-11a7 7 0 0114 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
		'building'     => '<path d="M4 21V5a2 2 0 012-2h8a2 2 0 012 2v16M16 9h2a2 2 0 012 2v10M8 7h4M8 11h4M8 15h4M3 21h18"/>',
		'id'           => '<rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="9" cy="11" r="2"/><path d="M6 16c.6-1.5 1.7-2 3-2s2.4.5 3 2M15 10h3M15 13h3"/>',
		'globe'        => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 010 18M12 3a14 14 0 000 18"/>',
		'calendar'     => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
		'clock'        => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'user'         => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0116 0"/>',
		'users'        => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0113 0M16 4.5a3.5 3.5 0 010 7M18 14a6 6 0 013.5 6"/>',
		'search'       => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
		'check'        => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
		'info'         => '<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7.5v.5"/>',
		'alert'        => '<path d="M12 3l10 18H2L12 3z"/><path d="M12 10v5M12 18v.5"/>',
		'book'         => '<path d="M4 5a2 2 0 012-2h13v16H6a2 2 0 00-2 2V5z"/><path d="M4 19a2 2 0 012-2h13"/>',
		'layers'       => '<path d="M12 3l9 5-9 5-9-5 9-5z"/><path d="M3 13l9 5 9-5"/>',
		'monitor'      => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
		'tag'          => '<path d="M3 12V4a1 1 0 011-1h8l9 9-9 9-9-9z"/><circle cx="8" cy="8" r="1.5"/>',
		'facebook'     => '<path d="M14 8h3V4h-3a4 4 0 00-4 4v2H7v4h3v7h4v-7h3l1-4h-4V8z"/>',
		'youtube'      => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10 9.5v5l4.5-2.5L10 9.5z"/>',
		'instagram'    => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><path d="M17.5 6.5v.01"/>',
		'tiktok'       => '<path d="M14 3v11.5a3.5 3.5 0 11-3.5-3.5M14 3c.5 2.8 2.4 4.5 5 4.6"/>',
		'chat'         => '<path d="M4 5h16v11H9l-5 4V5z"/>',
		'linkedin'     => '<rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 10v7M8 7v.01M12 17v-4a2 2 0 014 0v4M12 10v7"/>',
	);

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	$a11y = '' !== $label
		? 'role="img" aria-label="' . esc_attr( $label ) . '"'
		: 'aria-hidden="true" focusable="false"';

	return '<svg class="p2g-icon p2g-icon--' . esc_attr( $name ) . '" ' . $a11y . ' width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $paths[ $name ] . '</svg>';
}

/**
 * Allowed tags for printing p2g_icon() output through wp_kses.
 *
 * @return array
 */
function p2g_kses_svg() {
	$common = array(
		'd'               => true,
		'cx'              => true,
		'cy'              => true,
		'r'               => true,
		'x'               => true,
		'y'               => true,
		'width'           => true,
		'height'          => true,
		'rx'              => true,
		'fill'            => true,
		'stroke'          => true,
		'stroke-width'    => true,
		'stroke-linecap'  => true,
		'stroke-linejoin' => true,
	);

	return array(
		'svg'    => array_merge(
			$common,
			array(
				'class'       => true,
				'role'        => true,
				'aria-label'  => true,
				'aria-hidden' => true,
				'focusable'   => true,
				'viewbox'     => true,
				'xmlns'       => true,
			)
		),
		'path'   => $common,
		'circle' => $common,
		'rect'   => $common,
	);
}

/**
 * Echo an icon safely.
 *
 * @param string $name  Icon name.
 * @param string $label Optional accessible label.
 */
function p2g_the_icon( $name, $label = '' ) {
	echo wp_kses( p2g_icon( $name, $label ), p2g_kses_svg() );
}

/**
 * Render an internal editor-only note. Visitors never see it.
 *
 * @param string $text Note text (may contain basic HTML).
 * @return string
 */
function p2g_editor_note( $text ) {
	if ( ! p2g_can_see_notes() ) {
		return '';
	}

	return '<div class="p2g-editor-note" role="note"><strong>' . esc_html__( 'Ghi chú nội bộ (khách truy cập không nhìn thấy):', 'play2grow-theme' ) . '</strong> ' . wp_kses_post( $text ) . '</div>';
}

/**
 * Estimated reading time in minutes for Vietnamese text (~220 words/minute).
 *
 * @param int|WP_Post|null $post Post.
 * @return int
 */
function p2g_reading_time( $post = null ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return 0;
	}

	$text  = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
	$words = count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );

	return max( 1, (int) ceil( $words / 220 ) );
}

/**
 * Permalink of a published page by path, or '' when it does not exist.
 * Used by block patterns so they never output links to missing pages.
 *
 * @param string $path Page path.
 * @return string
 */
function p2g_page_link( $path ) {
	// While the starter content importer runs, link to pages it just created (may be drafts).
	if ( ! empty( $GLOBALS['p2g_importing'] ) && function_exists( 'p2g_starter_link' ) ) {
		$page = get_page_by_path( $path );
		return ( $page && 'trash' !== $page->post_status ) ? p2g_starter_link( $page->ID ) : '';
	}

	$id = p2g_page_id_by_path( $path );

	return $id ? (string) get_permalink( $id ) : '';
}

/**
 * Permalink of a post by slug ('' when not published). Import-aware like p2g_page_link().
 *
 * @param string $slug Post slug.
 * @return string
 */
function p2g_post_link( $slug ) {
	$posts = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => 'post',
			'post_status'    => ! empty( $GLOBALS['p2g_importing'] ) ? array( 'publish', 'draft', 'pending', 'future', 'private' ) : 'publish',
			'posts_per_page' => 1,
		)
	);
	if ( ! $posts ) {
		return '';
	}

	return ! empty( $GLOBALS['p2g_importing'] ) && function_exists( 'p2g_starter_link' ) ? p2g_starter_link( $posts[0]->ID ) : (string) get_permalink( $posts[0] );
}

/**
 * Link to the blog index ("Góc học tập"), import-aware.
 *
 * @return string
 */
function p2g_posts_page_link() {
	if ( ! empty( $GLOBALS['p2g_importing'] ) ) {
		return p2g_page_link( 'goc-hoc-tap' );
	}
	$id = (int) get_option( 'page_for_posts' );

	return ( $id && 'publish' === get_post_status( $id ) ) ? (string) get_permalink( $id ) : '';
}

/**
 * Block markup for a row of buttons. Buttons with an empty URL are skipped, so
 * patterns never contain links to pages that do not exist.
 *
 * @param array $buttons Each: [label, url, style] where style is '' or 'outline'.
 * @param string $class  Extra class for the wrapper.
 * @return string
 */
function p2g_pattern_buttons( $buttons, $class = '' ) {
	$items = '';
	foreach ( $buttons as $button ) {
		list( $label, $url ) = $button;
		$style               = isset( $button[2] ) ? $button[2] : '';
		if ( '' === (string) $url ) {
			continue;
		}
		$attrs  = 'outline' === $style ? ' {"className":"is-style-outline"}' : '';
		$wclass = 'outline' === $style ? 'wp-block-button is-style-outline' : 'wp-block-button';
		$items .= '<!-- wp:button' . $attrs . ' --><div class="' . $wclass . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></div><!-- /wp:button -->';
	}
	if ( '' === $items ) {
		return '';
	}
	$json = $class ? ' {"className":"' . esc_attr( $class ) . '"}' : '';

	return '<!-- wp:buttons' . $json . ' --><div class="wp-block-buttons' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">' . $items . '</div><!-- /wp:buttons -->';
}

/**
 * URL of a theme image (used by block patterns).
 *
 * @param string $file File name inside assets/images.
 * @return string
 */
function p2g_image_url( $file ) {
	return P2G_URI . '/assets/images/' . $file;
}

/**
 * Whether a post is built with Elementor. Such content gets a full-width, unconstrained
 * container so Elementor sections keep their own layout.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function p2g_is_elementor( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	return $post_id && 'builder' === get_post_meta( $post_id, '_elementor_edit_mode', true ) && defined( 'ELEMENTOR_VERSION' );
}
