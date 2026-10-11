<?php
/**
 * Program (class) pages and latest-post lists rendered by the theme.
 * The plugin's [p2g_programs] / [p2g_latest_posts] shortcodes delegate to these
 * functions so the markup matches the design.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Published program pages = pages using the "Chi tiết chương trình" template,
 * wherever they sit in the page tree (so existing URLs such as /steam-english/ keep working).
 *
 * @param array $args limit (int), group (string), exclude (int[]).
 * @return WP_Post[]
 */
function p2g_get_programs( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'limit'   => 24,
			'group'   => '',
			'exclude' => array(),
		)
	);

	$meta = array(
		array(
			'key'   => '_wp_page_template',
			'value' => 'page-templates/template-program.php',
		),
	);
	if ( '' !== $args['group'] ) {
		$meta[] = array(
			'key'   => '_p2g_prog_group',
			'value' => $args['group'],
		);
	}

	return get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => max( 1, min( 60, (int) $args['limit'] ) ),
			'post__not_in'   => array_map( 'absint', (array) $args['exclude'] ),
			'meta_query'     => $meta, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			'orderby'        => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
		)
	);
}

/**
 * Program cards (optionally grouped by "Nhóm chương trình"), or an honest empty state.
 *
 * @param int  $limit   Max items.
 * @param int  $columns 2–4.
 * @param bool $grouped Group under headings.
 * @return string
 */
function p2g_render_programs( $limit = 24, $columns = 3, $grouped = false ) {
	$programs = p2g_get_programs( array( 'limit' => $limit ) );
	$columns  = max( 2, min( 4, (int) $columns ) );

	ob_start();
	if ( ! $programs ) {
		echo '<div class="empty-state">';
		echo '<p class="empty-state__title">' . esc_html__( 'Thông tin các chương trình học đang được cập nhật.', 'play2grow-theme' ) . '</p>';
		if ( p2g_contact_url() ) {
			echo '<p>' . esc_html__( 'Anh/chị vui lòng để lại thông tin để được tư vấn về lớp học phù hợp với con.', 'play2grow-theme' ) . '</p>';
			p2g_cta_button( 'btn btn--secondary' );
		}
		echo '</div>';
		echo p2g_editor_note( __( 'Chưa có trang chương trình nào được xuất bản với mẫu "Chi tiết chương trình". Mở trang lớp học (ví dụ /steam-english/), chọn Mẫu = "Chi tiết chương trình", điền hộp "Thông tin chương trình / lớp học" rồi cập nhật.', 'play2grow-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
		return ob_get_clean();
	}

	$sets = array( '' => $programs );
	if ( $grouped ) {
		$sets   = array();
		$groups = p2g_program_groups();
		foreach ( $programs as $program ) {
			$key = get_post_meta( $program->ID, '_p2g_prog_group', true );
			$key = isset( $groups[ $key ] ) ? $key : 'other';
			$sets[ $key ][] = $program;
		}
		uksort(
			$sets,
			static function ( $a, $b ) use ( $groups ) {
				return array_search( $a, array_keys( $groups ), true ) - array_search( $b, array_keys( $groups ), true );
			}
		);
	}

	foreach ( $sets as $key => $items ) {
		if ( $grouped ) {
			$groups = p2g_program_groups();
			echo '<h2 class="program-group__title">' . esc_html( $groups[ $key ] ) . '</h2>';
		}
		echo '<div class="card-grid card-grid--' . esc_attr( (string) $columns ) . '">';
		foreach ( $items as $program ) {
			get_template_part( 'template-parts/card', 'program', array( 'post' => $program ) );
		}
		echo '</div>';
	}

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
		'exploring-nature'            => 'spot-explore.svg',
		'animals-around-us'           => 'spot-grow.svg',
		'food-and-mealtimes'          => 'spot-home.svg',
		'everyday-english'            => 'spot-home.svg',
		'places-around-us'            => 'spot-talk.svg',
		'english-games'               => 'spot-play.svg',
		'learning-together'           => 'spot-talk.svg',
		'language-learning-methods'   => 'spot-create.svg',
		'technology-and-children'     => 'spot-create.svg',
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

/**
 * Category slugs shown in "Khám phá theo chủ đề", per language (slugs differ per language).
 *
 * @param string $lang Language code.
 * @return string Comma-separated slugs.
 */
function p2g_topic_slugs( $lang = '' ) {
	$lang  = $lang ? $lang : p2g_current_lang();
	$slugs = array(
		'vi' => 'kham-pha-thien-nhien,con-vat-quanh-em,do-an-va-bua-an,hoc-qua-hoat-dong-hang-ngay,dia-diem-quanh-ta,tro-choi-tieng-anh,dong-hanh-cung-con',
		'en' => 'exploring-nature,animals-around-us,food-and-mealtimes,everyday-english,places-around-us,english-games,learning-together',
	);

	return (string) apply_filters( 'p2g_topic_slugs', isset( $slugs[ $lang ] ) ? $slugs[ $lang ] : $slugs['vi'], $lang );
}
