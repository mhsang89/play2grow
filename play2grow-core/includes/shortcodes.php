<?php
/**
 * Shortcodes used inside page content and block patterns. They live in the plugin so
 * content keeps working (no raw "[p2g_…]" text) even if the theme is changed.
 *
 * [p2g_programs]           – published class/program pages (template "Chi tiết chương trình")
 * [p2g_latest_posts]       – latest articles
 * [p2g_cta_button]         – button to the contact page (hidden if that page does not exist)
 * [p2g_company_info]       – verified company details (Tùy biến → Play2Grow – Doanh nghiệp)
 * [p2g_note]…[/p2g_note]   – internal note visible only to logged-in editors
 * [p2g_retention]          – lead retention period from the settings
 * [p2g_topics slugs=""]     – topic cards from post categories (theme rendering only)
 * [p2g_contact_form]       – see includes/contact-form.php
 *
 * When the Play2Grow theme is active, rendering is delegated to the theme's template
 * functions (p2g_render_programs, p2g_render_latest_posts, p2g_cta_button) so markup
 * matches the design. Otherwise a simple, theme-neutral fallback is printed.
 *
 * @package Play2Grow_Core
 */

defined( 'ABSPATH' ) || exit;

/**
 * Published program pages (pages using the "Chi tiết chương trình" template).
 *
 * @param int $limit Max items.
 * @return WP_Post[]
 */
function p2gc_get_programs( $limit = 24 ) {
	return get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, min( 60, (int) $limit ) ),
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => 'page-templates/template-program.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
}

/**
 * [p2g_programs limit="24" columns="3" grouped="0"].
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2gc_programs_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'limit'   => 24,
			'columns' => 3,
			'grouped' => 0,
		),
		$atts,
		'p2g_programs'
	);

	if ( function_exists( 'p2g_render_programs' ) ) {
		return p2g_render_programs( (int) $atts['limit'], (int) $atts['columns'], (bool) $atts['grouped'] );
	}

	$programs = p2gc_get_programs( (int) $atts['limit'] );
	if ( ! $programs ) {
		return '<p class="p2g-empty">' . esc_html__( 'Thông tin các chương trình học đang được cập nhật.', 'play2grow-core' ) . '</p>';
	}
	$out = '<ul class="p2g-program-list">';
	foreach ( $programs as $program ) {
		$out .= '<li><a href="' . esc_url( get_permalink( $program ) ) . '">' . esc_html( get_the_title( $program ) ) . '</a></li>';
	}

	return $out . '</ul>';
}
add_shortcode( 'p2g_programs', 'p2gc_programs_shortcode' );

/**
 * [p2g_latest_posts count="3" category=""].
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2gc_latest_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'count'    => 3,
			'category' => '',
		),
		$atts,
		'p2g_latest_posts'
	);

	$count    = max( 1, min( 12, (int) $atts['count'] ) );
	$category = sanitize_title( $atts['category'] );

	if ( function_exists( 'p2g_render_latest_posts' ) ) {
		return p2g_render_latest_posts( $count, $category );
	}

	$posts = get_posts(
		array(
			'posts_per_page' => $count,
			'category_name'  => $category,
		)
	);
	if ( ! $posts ) {
		return '';
	}
	$out = '<ul class="p2g-latest-posts">';
	foreach ( $posts as $post ) {
		$out .= '<li><a href="' . esc_url( get_permalink( $post ) ) . '">' . esc_html( get_the_title( $post ) ) . '</a></li>';
	}

	return $out . '</ul>';
}
add_shortcode( 'p2g_latest_posts', 'p2gc_latest_posts_shortcode' );

/**
 * [p2g_cta_button label="" style="primary"].
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2gc_cta_button_shortcode( $atts ) {
	$atts  = shortcode_atts(
		array(
			'label' => '',
			'style' => 'primary',
		),
		$atts,
		'p2g_cta_button'
	);
	$style = in_array( $atts['style'], array( 'primary', 'secondary', 'light', 'outline', 'accent' ), true ) ? $atts['style'] : 'primary';
	$label = sanitize_text_field( $atts['label'] );

	if ( function_exists( 'p2g_cta_button' ) ) {
		ob_start();
		p2g_cta_button( 'btn btn--' . $style, $label );
		$html = ob_get_clean();
	} else {
		$url  = p2gc_contact_url();
		$html = $url ? '<a class="wp-element-button" href="' . esc_url( $url ) . '">' . esc_html( $label ? $label : __( 'Đăng ký nhận thông tin', 'play2grow-core' ) ) . '</a>' : '';
	}

	if ( '' === $html ) {
		return p2gc_editor_note( __( 'Nút đăng ký đang bị ẩn vì chưa có trang Liên hệ được xuất bản (đường dẫn /lien-he/ hoặc trang chứa form [p2g_contact_form]).', 'play2grow-core' ) );
	}

	return '<p class="p2g-cta-wrap">' . $html . '</p>';
}
add_shortcode( 'p2g_cta_button', 'p2gc_cta_button_shortcode' );

/**
 * [p2g_company_info].
 *
 * @return string
 */
function p2gc_company_info_shortcode() {
	ob_start();
	p2gc_company_info( 'card' );

	return ob_get_clean();
}
add_shortcode( 'p2g_company_info', 'p2gc_company_info_shortcode' );

/**
 * [p2g_note]…[/p2g_note] – editor-only note.
 *
 * @param array  $atts    Attributes.
 * @param string $content Note.
 * @return string
 */
function p2gc_note_shortcode( $atts, $content = '' ) {
	return p2gc_editor_note( $content );
}
add_shortcode( 'p2g_note', 'p2gc_note_shortcode' );

/**
 * [p2g_retention] – retention period from the settings, so the privacy policy
 * always matches what the site actually does.
 *
 * @return string
 */
function p2gc_retention_shortcode() {
	$days = (int) p2gc_setting( 'lead_retention_days' );
	if ( $days <= 0 ) {
		return esc_html__( 'thời hạn cần thiết cho mục đích đã nêu', 'play2grow-core' );
	}

	/* translators: %d: number of days. */
	return esc_html( sprintf( _n( '%d ngày', '%d ngày', $days, 'play2grow-core' ), $days ) );
}
add_shortcode( 'p2g_retention', 'p2gc_retention_shortcode' );

/**
 * [p2g_topics slugs="a,b,c"] – topic cards. Needs the Play2Grow theme for the design;
 * other themes get a plain list of non-empty categories.
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2gc_topics_shortcode( $atts ) {
	$atts = shortcode_atts( array( 'slugs' => '' ), $atts, 'p2g_topics' );

	if ( function_exists( 'p2g_render_topics' ) ) {
		return p2g_render_topics( $atts['slugs'] );
	}

	return '<ul class="p2g-topics">' . wp_list_categories(
		array(
			'echo'       => false,
			'title_li'   => '',
			'hide_empty' => true,
		)
	) . '</ul>';
}
add_shortcode( 'p2g_topics', 'p2gc_topics_shortcode' );
