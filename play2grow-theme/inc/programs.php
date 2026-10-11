<?php
/**
 * Product pages ("Sản phẩm" children) and latest-post lists rendered by the theme.
 * The plugin's [p2g_programs] / [p2g_latest_posts] shortcodes delegate to these
 * functions so the markup matches the design.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Find the page that holds the product list.
 *
 * @param string|int $parent Page ID or path.
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
 * Published product pages.
 *
 * @param int $parent_id Parent.
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
 * Product cards (or an honest empty state).
 *
 * @param int $parent_id Parent page (0 = auto).
 * @param int $limit     Max items.
 * @param int $columns   2–4.
 * @return string
 */
function p2g_render_programs( $parent_id = 0, $limit = 12, $columns = 3 ) {
	$parent_id = $parent_id ? (int) $parent_id : p2g_programs_parent_id();
	$programs  = p2g_get_programs( $parent_id, $limit );
	$columns   = max( 2, min( 4, (int) $columns ) );

	ob_start();
	if ( ! $programs ) {
		echo '<div class="empty-state">';
		echo '<p class="empty-state__title">' . esc_html__( 'Thông tin chi tiết về sản phẩm Play2Grow sẽ được cập nhật tại đây.', 'play2grow-theme' ) . '</p>';
		if ( p2g_contact_url() ) {
			echo '<p>' . esc_html__( 'Để lại thông tin liên hệ để Play2Grow gửi thông báo khi có cập nhật mới.', 'play2grow-theme' ) . '</p>';
			p2g_cta_button( 'btn btn--secondary' );
		}
		echo '</div>';
		echo p2g_editor_note( __( 'Chưa có trang sản phẩm nào được xuất bản. Tạo trang con của trang "Sản phẩm", chọn mẫu "Chi tiết sản phẩm", điền hộp "Thông tin sản phẩm / trải nghiệm học tập" rồi xuất bản. Chỉ mô tả tính năng đã kiểm chứng (xem PRODUCT_AUDIT.md).', 'play2grow-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
		return ob_get_clean();
	}

	echo '<div class="card-grid card-grid--' . esc_attr( (string) $columns ) . '">';
	foreach ( $programs as $program ) {
		get_template_part( 'template-parts/card', 'program', array( 'post' => $program ) );
	}
	echo '</div>';

	return ob_get_clean();
}

/**
 * Latest post cards (or an empty state).
 *
 * @param int    $count    Number of posts.
 * @param string $category Category slug.
 * @return string
 */
function p2g_render_latest_posts( $count = 3, $category = '' ) {
	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => max( 1, min( 12, (int) $count ) ),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	);
	if ( '' !== $category ) {
		$args['category_name'] = sanitize_title( $category );
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
		echo '<div class="empty-state"><p class="empty-state__title">' . esc_html__( 'Bài viết đang được biên soạn và sẽ sớm ra mắt.', 'play2grow-theme' ) . '</p></div>';
	}

	return ob_get_clean();
}

/**
 * Topic cards ("Khám phá theo chủ đề") built from post categories that have published
 * posts. Empty topics stay hidden from visitors so no thin pages are promoted.
 *
 * @param string $slugs Comma-separated category slugs in display order ('' = all).
 * @return string
 */
function p2g_render_topics( $slugs = '' ) {
	$icons = array(
		'kham-pha-thien-nhien'        => 'spot-explore.svg',
		'con-vat-quanh-em'            => 'spot-grow.svg',
		'do-an-va-bua-an'             => 'spot-home.svg',
		'hoc-qua-hoat-dong-hang-ngay' => 'spot-home.svg',
		'dia-diem-quanh-ta'           => 'spot-talk.svg',
		'tro-choi-tieng-anh'          => 'spot-play.svg',
		'dong-hanh-cung-con'          => 'spot-talk.svg',
		'phuong-phap-hoc-ngon-ngu'    => 'spot-create.svg',
		'cong-nghe-va-tre-em'         => 'spot-create.svg',
	);

	$wanted = array_filter( array_map( 'sanitize_title', explode( ',', (string) $slugs ) ) );
	$terms  = array();
	if ( $wanted ) {
		foreach ( $wanted as $slug ) {
			$term = get_category_by_slug( $slug );
			if ( $term ) {
				$terms[] = $term;
			}
		}
	} else {
		$terms = get_categories(
			array(
				'hide_empty' => false,
				'exclude'    => array( (int) get_option( 'default_category' ) ),
			)
		);
	}

	$visible = array();
	$empty   = array();
	foreach ( $terms as $term ) {
		if ( $term->count > 0 ) {
			$visible[] = $term;
		} else {
			$empty[] = $term->name;
		}
	}

	ob_start();
	if ( $visible ) {
		echo '<ul class="topic-grid">';
		foreach ( $visible as $term ) {
			$icon = isset( $icons[ $term->slug ] ) ? $icons[ $term->slug ] : 'spot-grow.svg';
			printf(
				'<li class="topic-card"><img src="%1$s" alt="" width="56" height="56" loading="lazy" decoding="async"><h3 class="topic-card__title"><a href="%2$s">%3$s</a></h3>%4$s<span class="topic-card__count">%5$s</span></li>',
				esc_url( p2g_image_url( $icon ) ),
				esc_url( get_category_link( $term ) ),
				esc_html( $term->name ),
				$term->description ? '<p class="topic-card__desc">' . esc_html( wp_strip_all_tags( $term->description ) ) . '</p>' : '',
				/* translators: %d: number of articles. */
				esc_html( sprintf( _n( '%d bài viết', '%d bài viết', $term->count, 'play2grow-theme' ), $term->count ) )
			);
		}
		echo '</ul>';
	}
	if ( $empty ) {
		echo p2g_editor_note( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
			esc_html(
				sprintf(
					/* translators: %s: topic names. */
					__( 'Các chủ đề chưa có bài viết nên đang được ẩn: %s. Chúng sẽ tự hiện khi có bài viết được xuất bản trong chuyên mục tương ứng.', 'play2grow-theme' ),
					implode( ', ', $empty )
				)
			)
		);
	}

	return ob_get_clean();
}
