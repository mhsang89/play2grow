<?php
/**
 * Shortcodes used inside page content and block patterns.
 *
 * [p2g_programs]       – cards of published product pages (children of the "Sản phẩm" page)
 * [p2g_latest_posts]   – latest articles
 * [p2g_cta_button]     – button to the consultation page (hidden if the page does not exist)
 * [p2g_company_info]   – verified company details entered in the Customizer
 * [p2g_note]…[/p2g_note] – internal note visible only to logged-in editors
 * [p2g_contact_form]   – see inc/contact-form.php
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Find the page that holds the program list.
 *
 * @param string $parent Page ID or path from the shortcode attribute.
 * @return int
 */
function p2g_programs_parent_id( $parent = '' ) {
	if ( is_numeric( $parent ) && (int) $parent > 0 ) {
		return (int) $parent;
	}
	if ( '' !== $parent ) {
		$page = get_page_by_path( $parent );
		return $page ? (int) $page->ID : 0;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => 'page-templates/template-programs.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			'fields'         => 'ids',
		)
	);
	if ( $pages ) {
		return (int) $pages[0];
	}

	$page = get_page_by_path( 'san-pham' );

	return $page ? (int) $page->ID : 0;
}

/**
 * Query published program pages.
 *
 * @param int $parent_id Parent page.
 * @param int $limit     Max items.
 * @return WP_Post[]
 */
function p2g_get_programs( $parent_id, $limit = 12 ) {
	if ( ! $parent_id ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'post_parent'    => $parent_id,
			'posts_per_page' => max( 1, min( 50, (int) $limit ) ),
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
}

/**
 * Render program cards.
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2g_programs_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'parent'  => '',
			'limit'   => 12,
			'columns' => 3,
		),
		$atts,
		'p2g_programs'
	);

	$parent_id = p2g_programs_parent_id( $atts['parent'] );
	$programs  = p2g_get_programs( $parent_id, (int) $atts['limit'] );
	$columns   = max( 2, min( 4, (int) $atts['columns'] ) );

	ob_start();

	if ( ! $programs ) {
		echo '<div class="empty-state">';
		echo '<p class="empty-state__title">' . esc_html__( 'Thông tin chi tiết về sản phẩm Play2Grow sẽ được cập nhật tại đây.', 'play2grow' ) . '</p>';
		$url = p2g_contact_url();
		if ( $url ) {
			echo '<p>' . esc_html__( 'Để lại thông tin liên hệ để Play2Grow gửi thông báo khi có cập nhật mới.', 'play2grow' ) . '</p>';
			p2g_cta_button( 'btn btn--secondary' );
		}
		echo '</div>';
		echo p2g_editor_note( __( 'Chưa có trang sản phẩm nào được xuất bản. Tạo trang con của trang "Sản phẩm", chọn mẫu "Chi tiết sản phẩm", điền hộp "Thông tin sản phẩm / trải nghiệm học tập" rồi xuất bản. Chỉ mô tả tính năng đã kiểm chứng (xem PRODUCT_AUDIT.md). Thẻ sản phẩm sẽ tự xuất hiện tại đây.', 'play2grow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
		return ob_get_clean();
	}

	echo '<div class="card-grid card-grid--' . esc_attr( (string) $columns ) . '">';
	foreach ( $programs as $program ) {
		get_template_part( 'template-parts/card', 'program', array( 'post' => $program ) );
	}
	echo '</div>';

	return ob_get_clean();
}
add_shortcode( 'p2g_programs', 'p2g_programs_shortcode' );

/**
 * Latest posts.
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2g_latest_posts_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'count'    => 3,
			'category' => '',
		),
		$atts,
		'p2g_latest_posts'
	);

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, min( 12, (int) $atts['count'] ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( '' !== $atts['category'] ) {
		$args['category_name'] = sanitize_title( $atts['category'] );
	}

	$query = new WP_Query( $args );

	ob_start();
	if ( $query->have_posts() ) {
		echo '<div class="card-grid card-grid--3">';
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/card', 'post' );
		}
		echo '</div>';
		wp_reset_postdata();
	} else {
		echo '<div class="empty-state"><p class="empty-state__title">' . esc_html__( 'Bài viết đang được biên soạn và sẽ sớm ra mắt.', 'play2grow' ) . '</p></div>';
		echo p2g_editor_note( __( 'Chưa có bài viết nào được xuất bản. Khi bạn xuất bản bài viết, khối này tự hiển thị các bài mới nhất.', 'play2grow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	}

	return ob_get_clean();
}
add_shortcode( 'p2g_latest_posts', 'p2g_latest_posts_shortcode' );

/**
 * CTA button.
 *
 * @param array $atts Attributes.
 * @return string
 */
function p2g_cta_button_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'label' => '',
			'style' => 'primary',
		),
		$atts,
		'p2g_cta_button'
	);

	$style = in_array( $atts['style'], array( 'primary', 'secondary', 'light', 'outline' ), true ) ? $atts['style'] : 'primary';

	ob_start();
	p2g_cta_button( 'btn btn--' . $style, sanitize_text_field( $atts['label'] ) );
	$html = ob_get_clean();

	if ( '' === $html ) {
		return p2g_editor_note( __( 'Nút đăng ký đang bị ẩn vì chưa có trang liên hệ được xuất bản (đường dẫn /lien-he/ hoặc trang chọn trong Tùy biến → Play2Grow → Đầu trang & nút đăng ký).', 'play2grow' ) );
	}

	return '<p class="p2g-cta-wrap">' . $html . '</p>';
}
add_shortcode( 'p2g_cta_button', 'p2g_cta_button_shortcode' );

/**
 * Company info.
 *
 * @return string
 */
function p2g_company_info_shortcode() {
	ob_start();
	p2g_company_info( 'card' );

	return ob_get_clean();
}
add_shortcode( 'p2g_company_info', 'p2g_company_info_shortcode' );

/**
 * Editor-only note.
 *
 * @param array  $atts    Attributes.
 * @param string $content Note.
 * @return string
 */
function p2g_note_shortcode( $atts, $content = '' ) {
	return p2g_editor_note( $content );
}
add_shortcode( 'p2g_note', 'p2g_note_shortcode' );

/**
 * Retention period text for the privacy policy, read from the theme settings so the
 * policy always matches what the site actually does.
 *
 * @return string
 */
function p2g_retention_shortcode() {
	$days = (int) p2g_setting( 'lead_retention_days' );
	if ( $days <= 0 ) {
		return esc_html__( 'thời hạn cần thiết cho mục đích đã nêu', 'play2grow' );
	}

	/* translators: %d: number of days. */
	return esc_html( sprintf( _n( '%d ngày', '%d ngày', $days, 'play2grow' ), $days ) );
}
add_shortcode( 'p2g_retention', 'p2g_retention_shortcode' );
