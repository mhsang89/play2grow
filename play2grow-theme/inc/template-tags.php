<?php
/**
 * Template tags used across templates.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Site branding (custom logo or text wordmark).
 *
 * @param string $context "header" or "footer".
 */
function p2g_site_branding( $context = 'header' ) {
	$name = get_bloginfo( 'name' );

	if ( has_custom_logo() ) {
		$logo_id = (int) get_theme_mod( 'custom_logo' );
		$img     = wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'class'    => 'site-logo__img',
				'alt'      => $name,
				'loading'  => 'header' === $context ? 'eager' : 'lazy',
				'decoding' => 'async',
			)
		);
		printf(
			'<a class="site-logo site-logo--image" href="%1$s" rel="home"%2$s>%3$s</a>',
			esc_url( home_url( '/' ) ),
			is_front_page() && 'header' === $context ? ' aria-current="page"' : '',
			$img // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- core-generated img markup.
		);
		return;
	}

	/**
	 * Use the bundled Play2Grow logo when no custom logo is uploaded.
	 * Return false from this filter to fall back to a text wordmark (e.g. after renaming the site).
	 */
	if ( apply_filters( 'p2g_use_default_logo', true ) ) {
		printf(
			'<a class="site-logo site-logo--image" href="%1$s" rel="home"%2$s><img class="site-logo__img" src="%3$s" alt="%4$s" width="559" height="122" decoding="async"%5$s></a>',
			esc_url( home_url( '/' ) ),
			is_front_page() && 'header' === $context ? ' aria-current="page"' : '',
			esc_url( p2g_image_url( 'footer' === $context ? 'logo-white.svg' : 'logo.svg' ) ),
			esc_attr( $name ),
			'header' === $context ? ' fetchpriority="high"' : ' loading="lazy"'
		);
		return;
	}

	printf(
		'<a class="site-logo site-logo--text" href="%1$s" rel="home"%2$s><span class="site-logo__mark" aria-hidden="true"></span><span class="site-logo__name">%3$s</span></a>',
		esc_url( home_url( '/' ) ),
		is_front_page() && 'header' === $context ? ' aria-current="page"' : '',
		p2g_wordmark( $name ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	);
}

/**
 * Highlight the "2" in "Play2Grow" for the text wordmark. Other names are just escaped.
 *
 * @param string $name Site name.
 * @return string
 */
function p2g_wordmark( $name ) {
	$escaped = esc_html( $name );

	return preg_replace( '/^(Play)(2)(Grow)/i', '$1<span class="site-logo__two">$2</span>$3', $escaped, 1 );
}

/**
 * Format a phone number for a tel: link.
 *
 * @param string $phone Phone as typed.
 * @return string
 */
function p2g_tel( $phone ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $phone );
}

/**
 * Breadcrumb items for the current request.
 *
 * @return array[] Each item: [label, url]. Last item has an empty url.
 */
function p2g_breadcrumb_items() {
	$items = array( array( __( 'Trang chủ', 'play2grow-theme' ), home_url( '/' ) ) );

	$blog_page = (int) get_option( 'page_for_posts' );
	$blog_item = $blog_page ? array( get_the_title( $blog_page ), get_permalink( $blog_page ) ) : null;

	if ( is_home() ) {
		$items[] = array( $blog_page ? get_the_title( $blog_page ) : __( 'Bài viết', 'play2grow-theme' ), '' );
	} elseif ( is_singular( 'post' ) ) {
		if ( $blog_item ) {
			$items[] = $blog_item;
		}
		$cats = get_the_category();
		if ( $cats ) {
			$cat = $cats[0];
			foreach ( array_reverse( get_ancestors( $cat->term_id, 'category' ) ) as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'category' );
				$items[]  = array( $ancestor->name, get_term_link( $ancestor ) );
			}
			$items[] = array( $cat->name, get_term_link( $cat ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		// Program pages keep their old root URLs (/steam-english/…) but belong under "Chương trình học".
		if ( ! $ancestors && 'page-templates/template-program.php' === get_page_template_slug() ) {
			$overview = get_posts(
				array(
					'post_type'      => 'page',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
					'meta_value'     => 'page-templates/template-programs.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				)
			);
			$ancestors = $overview;
		}
		foreach ( $ancestors as $ancestor_id ) {
			$items[] = array( get_the_title( $ancestor_id ), get_permalink( $ancestor_id ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( is_singular() ) {
		$pto = get_post_type_object( get_post_type() );
		if ( $pto && $pto->has_archive ) {
			$items[] = array( $pto->labels->name, get_post_type_archive_link( $pto->name ) );
		}
		$items[] = array( get_the_title(), '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		if ( $blog_item && ( is_category() || is_tag() ) ) {
			$items[] = $blog_item;
		}
		$term = get_queried_object();
		if ( $term instanceof WP_Term && is_taxonomy_hierarchical( $term->taxonomy ) ) {
			foreach ( array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) ) as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, $term->taxonomy );
				$items[]  = array( $ancestor->name, get_term_link( $ancestor ) );
			}
		}
		$items[] = array( single_term_title( '', false ), '' );
	} elseif ( is_author() ) {
		if ( $blog_item ) {
			$items[] = $blog_item;
		}
		$items[] = array( get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) ), '' );
	} elseif ( is_search() ) {
		$items[] = array( __( 'Kết quả tìm kiếm', 'play2grow-theme' ), '' );
	} elseif ( is_404() ) {
		$items[] = array( __( 'Không tìm thấy trang', 'play2grow-theme' ), '' );
	} elseif ( is_archive() ) {
		if ( $blog_item && is_date() ) {
			$items[] = $blog_item;
		}
		$items[] = array( wp_strip_all_tags( get_the_archive_title() ), '' );
	}

	if ( is_paged() ) {
		$last_index = count( $items ) - 1;
		if ( '' === $items[ $last_index ][1] ) {
			$items[ $last_index ][1] = get_pagenum_link( 1 );
		}
		/* translators: %d: page number. */
		$items[] = array( sprintf( __( 'Trang %d', 'play2grow-theme' ), max( 1, (int) get_query_var( 'paged' ) ) ), '' );
	}

	return apply_filters( 'p2g_breadcrumb_items', $items );
}

/**
 * Print breadcrumbs. Uses Yoast / Rank Math breadcrumbs when those plugins provide them.
 */
function p2g_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	if ( function_exists( 'yoast_breadcrumb' ) && class_exists( 'WPSEO_Options' ) && WPSEO_Options::get( 'breadcrumbs-enable' ) ) {
		yoast_breadcrumb( '<nav class="breadcrumbs breadcrumbs--plugin" aria-label="' . esc_attr__( 'Đường dẫn', 'play2grow-theme' ) . '">', '</nav>' );
		return;
	}
	if ( function_exists( 'rank_math_the_breadcrumbs' ) && class_exists( '\RankMath\Helper' ) && \RankMath\Helper::is_breadcrumbs_enabled() ) {
		echo '<nav class="breadcrumbs breadcrumbs--plugin" aria-label="' . esc_attr__( 'Đường dẫn', 'play2grow-theme' ) . '">';
		rank_math_the_breadcrumbs();
		echo '</nav>';
		return;
	}

	$items = p2g_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Đường dẫn', 'play2grow-theme' ) . '"><ol>';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		echo '<li>';
		if ( $i < $last && $item[1] ) {
			printf( '<a href="%1$s">%2$s</a>', esc_url( $item[1] ), esc_html( $item[0] ) );
		} else {
			printf( '<span aria-current="page">%s</span>', esc_html( $item[0] ) );
		}
		echo '</li>';
	}
	echo '</ol></nav>';
}

/**
 * Post meta line: author, published date, updated date (only when meaningfully different), reading time.
 *
 * @param bool $full Include author + updated date.
 */
function p2g_post_meta( $full = true ) {
	$published = get_the_date( 'c' );
	$modified  = get_the_modified_date( 'c' );
	$out       = array();

	if ( $full ) {
		$out[] = sprintf(
			'<span class="post-meta__item post-meta__author">%1$s<a href="%2$s">%3$s</a></span>',
			p2g_icon( 'user' ),
			esc_url( get_author_posts_url( (int) get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}

	$out[] = sprintf(
		'<span class="post-meta__item">%1$s<span class="screen-reader-text">%2$s </span><time datetime="%3$s">%4$s</time></span>',
		p2g_icon( 'calendar' ),
		esc_html__( 'Ngày đăng:', 'play2grow-theme' ),
		esc_attr( $published ),
		esc_html( get_the_date() )
	);

	// Show "updated" only when edited at least one day after publishing.
	if ( $full && ( get_the_modified_time( 'U' ) - get_the_time( 'U' ) ) > DAY_IN_SECONDS ) {
		$out[] = sprintf(
			'<span class="post-meta__item">%1$s <time datetime="%2$s">%3$s</time></span>',
			esc_html__( 'Cập nhật:', 'play2grow-theme' ),
			esc_attr( $modified ),
			esc_html( get_the_modified_date() )
		);
	}

	$out[] = sprintf(
		'<span class="post-meta__item">%1$s%2$s</span>',
		p2g_icon( 'clock' ),
		/* translators: %d: minutes. */
		esc_html( sprintf( __( '%d phút đọc', 'play2grow-theme' ), p2g_reading_time() ) )
	);

	echo '<div class="post-meta">' . wp_kses( implode( '', $out ), array_merge( wp_kses_allowed_html( 'post' ), p2g_kses_svg(), array( 'time' => array( 'datetime' => true ) ) ) ) . '</div>';
}

/**
 * First category badge.
 */
function p2g_category_badge() {
	$cats = get_the_category();
	if ( ! $cats ) {
		return;
	}
	printf( '<a class="badge" href="%1$s">%2$s</a>', esc_url( get_term_link( $cats[0] ) ), esc_html( $cats[0]->name ) );
}

/**
 * Numbered pagination.
 */
function p2g_pagination() {
	the_posts_pagination(
		array(
			'mid_size'           => 1,
			'prev_text'          => __( '← Trang trước', 'play2grow-theme' ),
			'next_text'          => __( 'Trang sau →', 'play2grow-theme' ),
			'screen_reader_text' => __( 'Phân trang bài viết', 'play2grow-theme' ),
			'aria_label'         => __( 'Phân trang', 'play2grow-theme' ),
		)
	);
}

/**
 * Social links list.
 */
function p2g_social_list() {
	$links = p2g_social_links();
	if ( ! $links ) {
		return;
	}
	echo '<ul class="social-links">';
	foreach ( $links as $link ) {
		printf(
			'<li><a href="%1$s" target="_blank" rel="noopener">%2$s<span class="screen-reader-text">%3$s</span></a></li>',
			esc_url( $link[0] ),
			wp_kses( p2g_icon( $link[2] ), p2g_kses_svg() ),
			/* translators: %s: network name. */
			esc_html( sprintf( __( '%s của Play2Grow (mở trong thẻ mới)', 'play2grow-theme' ), $link[1] ) )
		);
	}
	echo '</ul>';
}

/**
 * Call-to-action button, only when the target page exists.
 *
 * @param string $class Extra classes.
 * @param string $label Optional label.
 */
function p2g_cta_button( $class = 'btn btn--primary', $label = '' ) {
	$url = p2g_contact_url();
	if ( ! $url ) {
		return;
	}
	printf(
		'<a class="%1$s" href="%2$s">%3$s</a>',
		esc_attr( $class ),
		esc_url( $url ),
		esc_html( $label ? $label : p2g_cta_label() )
	);
}

/**
 * Archive / page header block.
 *
 * @param string $title       Title.
 * @param string $description Optional description (HTML allowed).
 * @param string $eyebrow     Optional small label above the title.
 */
function p2g_page_header( $title, $description = '', $eyebrow = '' ) {
	?>
	<header class="page-hero">
		<div class="container">
			<?php p2g_breadcrumbs(); ?>
			<?php if ( $eyebrow ) : ?>
				<p class="eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<h1 class="page-hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $description ) : ?>
				<div class="page-hero__lead"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
			<?php endif; ?>
		</div>
	</header>
	<?php
}
