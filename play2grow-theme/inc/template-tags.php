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
	$items = array( array( __( 'Trang chủ', 'play2grow' ), home_url( '/' ) ) );

	$blog_page = (int) get_option( 'page_for_posts' );
	$blog_item = $blog_page ? array( get_the_title( $blog_page ), get_permalink( $blog_page ) ) : null;

	if ( is_home() ) {
		$items[] = array( $blog_page ? get_the_title( $blog_page ) : __( 'Bài viết', 'play2grow' ), '' );
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
		foreach ( array_reverse( get_post_ancestors( get_the_ID() ) ) as $ancestor_id ) {
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
		$items[] = array( __( 'Kết quả tìm kiếm', 'play2grow' ), '' );
	} elseif ( is_404() ) {
		$items[] = array( __( 'Không tìm thấy trang', 'play2grow' ), '' );
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
		$items[] = array( sprintf( __( 'Trang %d', 'play2grow' ), max( 1, (int) get_query_var( 'paged' ) ) ), '' );
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
		yoast_breadcrumb( '<nav class="breadcrumbs breadcrumbs--plugin" aria-label="' . esc_attr__( 'Đường dẫn', 'play2grow' ) . '">', '</nav>' );
		return;
	}
	if ( function_exists( 'rank_math_the_breadcrumbs' ) && class_exists( '\RankMath\Helper' ) && \RankMath\Helper::is_breadcrumbs_enabled() ) {
		echo '<nav class="breadcrumbs breadcrumbs--plugin" aria-label="' . esc_attr__( 'Đường dẫn', 'play2grow' ) . '">';
		rank_math_the_breadcrumbs();
		echo '</nav>';
		return;
	}

	$items = p2g_breadcrumb_items();
	if ( count( $items ) < 2 ) {
		return;
	}

	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Đường dẫn', 'play2grow' ) . '"><ol>';
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
		esc_html__( 'Ngày đăng:', 'play2grow' ),
		esc_attr( $published ),
		esc_html( get_the_date() )
	);

	// Show "updated" only when edited at least one day after publishing.
	if ( $full && ( get_the_modified_time( 'U' ) - get_the_time( 'U' ) ) > DAY_IN_SECONDS ) {
		$out[] = sprintf(
			'<span class="post-meta__item">%1$s <time datetime="%2$s">%3$s</time></span>',
			esc_html__( 'Cập nhật:', 'play2grow' ),
			esc_attr( $modified ),
			esc_html( get_the_modified_date() )
		);
	}

	$out[] = sprintf(
		'<span class="post-meta__item">%1$s%2$s</span>',
		p2g_icon( 'clock' ),
		/* translators: %d: minutes. */
		esc_html( sprintf( __( '%d phút đọc', 'play2grow' ), p2g_reading_time() ) )
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
			'prev_text'          => __( '← Trang trước', 'play2grow' ),
			'next_text'          => __( 'Trang sau →', 'play2grow' ),
			'screen_reader_text' => __( 'Phân trang bài viết', 'play2grow' ),
			'aria_label'         => __( 'Phân trang', 'play2grow' ),
		)
	);
}

/**
 * Company info list (footer + contact page). Only fields with values are printed.
 *
 * @param string $variant "footer" or "card".
 */
function p2g_company_info( $variant = 'footer' ) {
	$rows = array();

	$legal = p2g_company( 'legal_name' );
	if ( $legal ) {
		$rows[] = array( 'building', '', '<strong>' . esc_html( $legal ) . '</strong>' );
	}
	$tax = p2g_company( 'tax_code' );
	if ( $tax ) {
		$reg    = p2g_company( 'registration' );
		$rows[] = array( 'id', __( 'Mã số doanh nghiệp:', 'play2grow' ), esc_html( $tax ) . ( $reg ? '<br><span class="company-info__sub">' . esc_html( $reg ) . '</span>' : '' ) );
	}
	$rep = p2g_company( 'representative' );
	if ( $rep ) {
		$rows[] = array( 'user', __( 'Người đại diện:', 'play2grow' ), esc_html( $rep ) );
	}
	$hq = p2g_company( 'hq_address' );
	if ( $hq ) {
		$rows[] = array( 'map-pin', __( 'Trụ sở:', 'play2grow' ), nl2br( esc_html( $hq ) ) );
	}
	$op = p2g_company( 'operating_address' );
	if ( $op ) {
		$rows[] = array( 'map-pin', __( 'Cơ sở hoạt động:', 'play2grow' ), nl2br( esc_html( $op ) ) );
	}
	$phone = p2g_company( 'phone' );
	if ( $phone ) {
		$rows[] = array( 'phone', __( 'Hotline:', 'play2grow' ), '<a href="' . esc_url( p2g_tel( $phone ), array( 'tel' ) ) . '">' . esc_html( $phone ) . '</a>' );
	}
	$email = p2g_company( 'email' );
	if ( $email && is_email( $email ) ) {
		$rows[] = array( 'mail', __( 'Email:', 'play2grow' ), '<a href="' . esc_url( 'mailto:' . antispambot( $email ) ) . '">' . esc_html( antispambot( $email ) ) . '</a>' );
	}
	$hours = p2g_company( 'hours' );
	if ( $hours ) {
		$rows[] = array( 'clock', __( 'Giờ làm việc:', 'play2grow' ), esc_html( $hours ) );
	}
	$license = p2g_company( 'license' );
	if ( $license ) {
		$rows[] = array( 'book', __( 'Giấy phép:', 'play2grow' ), nl2br( esc_html( $license ) ) );
	}
	$map = p2g_company( 'map_url' );
	if ( $map ) {
		$rows[] = array( 'globe', '', '<a href="' . esc_url( $map ) . '" target="_blank" rel="noopener">' . esc_html__( 'Xem chỉ đường trên bản đồ', 'play2grow' ) . '<span class="screen-reader-text"> ' . esc_html__( '(mở trong thẻ mới)', 'play2grow' ) . '</span></a>' );
	}

	if ( ! $rows ) {
		if ( p2g_can_see_notes() ) {
			echo p2g_editor_note( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
				sprintf(
					/* translators: %s: Customizer URL. */
					__( 'Chưa có thông tin doanh nghiệp nào được nhập. Vào <a href="%s">Giao diện → Tùy biến → Play2Grow → Thông tin doanh nghiệp</a> để điền các thông tin đã xác minh.', 'play2grow' ),
					esc_url( admin_url( 'customize.php?autofocus[section]=p2g_company' ) )
				)
			);
		}
		return;
	}

	echo '<ul class="company-info company-info--' . esc_attr( $variant ) . '">';
	foreach ( $rows as $row ) {
		echo '<li>';
		p2g_the_icon( $row[0] );
		echo '<span>';
		if ( $row[1] ) {
			echo '<span class="company-info__label">' . esc_html( $row[1] ) . '</span> ';
		}
		echo wp_kses_post( $row[2] );
		echo '</span></li>';
	}
	echo '</ul>';
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
			esc_html( sprintf( __( '%s của Play2Grow (mở trong thẻ mới)', 'play2grow' ), $link[1] ) )
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
