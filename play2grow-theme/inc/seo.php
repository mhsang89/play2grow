<?php
/**
 * Lightweight on-page SEO: meta description, canonical for archives, Open Graph,
 * robots and JSON-LD. All of it switches off automatically when a dedicated SEO
 * plugin (Yoast, Rank Math, AIOSEO, SEOPress, The SEO Framework, Slim SEO) is active.
 *
 * Structured data only uses data that really exists on the site (site name, URL,
 * logo, and company fields the owner has filled in). No ratings or reviews.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Meta description for the current request.
 *
 * @return string
 */
function p2g_meta_description() {
	$desc = '';

	if ( is_singular() ) {
		$post = get_queried_object();
		$desc = (string) get_post_meta( $post->ID, '_p2g_meta_description', true );
		if ( '' === $desc && has_excerpt( $post ) ) {
			$desc = get_the_excerpt( $post );
		}
		if ( '' === $desc ) {
			$desc = wp_strip_all_tags( strip_shortcodes( excerpt_remove_blocks( $post->post_content ) ) );
		}
	} elseif ( is_front_page() || is_home() ) {
		$page_for_posts = (int) get_option( 'page_for_posts' );
		if ( is_home() && $page_for_posts ) {
			$desc = (string) get_post_meta( $page_for_posts, '_p2g_meta_description', true );
			if ( '' === $desc ) {
				$desc = get_the_excerpt( $page_for_posts );
			}
		}
		if ( '' === $desc ) {
			$desc = get_bloginfo( 'description' );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$desc = term_description();
	} elseif ( is_author() ) {
		$desc = get_the_author_meta( 'description', (int) get_query_var( 'author' ) );
	}

	$desc = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $desc ) ) );
	if ( mb_strlen( $desc ) > 160 ) {
		$desc = rtrim( mb_substr( $desc, 0, 157 ), " ,.;:-" ) . '…';
	}

	return (string) apply_filters( 'p2g_meta_description', $desc );
}

/**
 * Canonical URL for the current request ('' when WordPress core already prints one).
 *
 * @return string
 */
function p2g_archive_canonical() {
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$url   = '';

	if ( is_front_page() && is_home() ) {
		$url = home_url( '/' );
	} elseif ( is_home() ) {
		$page_for_posts = (int) get_option( 'page_for_posts' );
		$url            = $page_for_posts ? get_permalink( $page_for_posts ) : home_url( '/' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$link = $term instanceof WP_Term ? get_term_link( $term ) : '';
		$url  = is_wp_error( $link ) ? '' : $link;
	} elseif ( is_author() ) {
		$url = get_author_posts_url( (int) get_query_var( 'author' ) );
	}

	if ( $url && $paged > 1 ) {
		global $wp_rewrite;
		$url = $wp_rewrite->using_permalinks()
			? trailingslashit( $url ) . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $paged, 'paged' )
			: add_query_arg( 'paged', $paged, $url );
	}

	return $url;
}

/**
 * Social share image for the current request.
 *
 * @return array|null [url, width, height]
 */
function p2g_share_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		if ( $img ) {
			return array( $img[0], $img[1], $img[2] );
		}
	}
	if ( has_custom_logo() ) {
		$img = wp_get_attachment_image_src( (int) get_theme_mod( 'custom_logo' ), 'full' );
		if ( $img ) {
			return array( $img[0], $img[1], $img[2] );
		}
	}

	return null;
}

/**
 * Print meta tags in <head>.
 */
function p2g_head_meta() {
	if ( p2g_seo_plugin_active() || is_404() ) {
		return;
	}

	$desc = p2g_meta_description();
	if ( '' !== $desc && ! is_search() ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
	}

	$canonical = p2g_archive_canonical();
	if ( $canonical ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );
	}

	if ( is_search() ) {
		return;
	}

	$title = wp_get_document_title();
	$url   = is_singular() ? get_permalink() : ( $canonical ? $canonical : home_url( add_query_arg( array() ) ) );
	$type  = is_singular( 'post' ) ? 'article' : 'website';

	$tags = array(
		'og:locale'    => 'vi_VN',
		'og:type'      => $type,
		'og:site_name' => get_bloginfo( 'name' ),
		'og:title'     => $title,
		'og:url'       => $url,
	);
	if ( '' !== $desc ) {
		$tags['og:description'] = $desc;
	}

	$image = p2g_share_image();
	if ( $image ) {
		$tags['og:image']        = $image[0];
		$tags['og:image:width']  = (string) $image[1];
		$tags['og:image:height'] = (string) $image[2];
	}

	if ( 'article' === $type ) {
		$tags['article:published_time'] = get_the_date( 'c' );
		$tags['article:modified_time']  = get_the_modified_date( 'c' );
	}

	foreach ( $tags as $property => $content ) {
		$is_url = in_array( $property, array( 'og:url', 'og:image' ), true );
		printf(
			'<meta property="%1$s" content="%2$s">' . "\n",
			esc_attr( $property ),
			$is_url ? esc_url( $content ) : esc_attr( $content )
		);
	}
	printf( '<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary' );
}
add_action( 'wp_head', 'p2g_head_meta', 3 );

/**
 * Robots directives: per-post noindex switch.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function p2g_wp_robots( $robots ) {
	if ( p2g_seo_plugin_active() ) {
		return $robots;
	}
	if ( is_singular() && get_post_meta( get_queried_object_id(), '_p2g_noindex', true ) ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}

	return $robots;
}
add_filter( 'wp_robots', 'p2g_wp_robots' );

/**
 * Leave noindex posts out of the core XML sitemap (wp-sitemap.xml).
 *
 * @param array  $args      Query args.
 * @param string $post_type Post type.
 * @return array
 */
function p2g_sitemap_exclude_noindex( $args, $post_type ) {
	if ( p2g_seo_plugin_active() || ! in_array( $post_type, array( 'post', 'page' ), true ) ) {
		return $args;
	}
	$args['meta_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		array(
			'key'     => '_p2g_noindex',
			'compare' => 'NOT EXISTS',
		),
	);

	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'p2g_sitemap_exclude_noindex', 10, 2 );

/**
 * Remove the "users" provider from the core sitemap (author archives rarely help a school site).
 *
 * @param WP_Sitemaps_Provider $provider Provider.
 * @param string               $name     Name.
 * @return WP_Sitemaps_Provider|false
 */
function p2g_sitemap_providers( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'p2g_sitemap_providers', 10, 2 );

/**
 * Build the JSON-LD graph.
 *
 * @return array
 */
function p2g_schema_graph() {
	$home  = home_url( '/' );
	$org   = array(
		'@type' => 'Organization',
		'@id'   => $home . '#organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => $home,
	);
	$legal = p2g_company( 'legal_name' );
	if ( $legal ) {
		$org['legalName'] = $legal;
	}
	$tax = p2g_company( 'tax_code' );
	if ( $tax ) {
		$org['taxID'] = $tax;
	}
	if ( has_custom_logo() ) {
		$logo = wp_get_attachment_image_src( (int) get_theme_mod( 'custom_logo' ), 'full' );
		if ( $logo ) {
			$org['logo'] = array(
				'@type'  => 'ImageObject',
				'url'    => $logo[0],
				'width'  => $logo[1],
				'height' => $logo[2],
			);
		}
	}
	$phone = p2g_company( 'phone' );
	if ( $phone ) {
		$org['telephone'] = $phone;
	}
	$email = p2g_company( 'email' );
	if ( $email && is_email( $email ) ) {
		$org['email'] = $email;
	}
	$hq = p2g_company( 'hq_address' );
	if ( $hq ) {
		$org['address'] = array(
			'@type'          => 'PostalAddress',
			'streetAddress'  => preg_replace( '/\s+/u', ' ', $hq ),
			'addressCountry' => 'VN',
		);
	}
	$same_as = wp_list_pluck( p2g_social_links(), 0 );
	if ( $same_as ) {
		$org['sameAs'] = array_values( $same_as );
	}

	$graph = array(
		$org,
		array(
			'@type'           => 'WebSite',
			'@id'             => $home . '#website',
			'url'             => $home,
			'name'            => get_bloginfo( 'name' ),
			'inLanguage'      => 'vi-VN',
			'publisher'       => array( '@id' => $home . '#organization' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		),
	);

	if ( is_singular( 'post' ) ) {
		$post    = get_queried_object();
		$article = array(
			'@type'            => 'BlogPosting',
			'@id'              => get_permalink( $post ) . '#article',
			'headline'         => wp_strip_all_tags( get_the_title( $post ) ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'inLanguage'       => 'vi-VN',
			'mainEntityOfPage' => get_permalink( $post ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', (int) $post->post_author ),
				'url'   => get_author_posts_url( (int) $post->post_author ),
			),
			'publisher'        => array( '@id' => $home . '#organization' ),
		);
		$desc = p2g_meta_description();
		if ( $desc ) {
			$article['description'] = $desc;
		}
		if ( has_post_thumbnail( $post ) ) {
			$img = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'large' );
			if ( $img ) {
				$article['image'] = $img[0];
			}
		}
		$graph[] = $article;
	}

	$items = is_front_page() ? array() : p2g_breadcrumb_items();
	if ( count( $items ) > 1 ) {
		$list = array();
		foreach ( $items as $i => $item ) {
			$entry = array(
				'@type'    => 'ListItem',
				'position' => $i + 1,
				'name'     => wp_strip_all_tags( $item[0] ),
			);
			if ( $item[1] ) {
				$entry['item'] = $item[1];
			}
			$list[] = $entry;
		}
		$graph[] = array(
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $list,
		);
	}

	return apply_filters( 'p2g_schema_graph', $graph );
}

/**
 * Print JSON-LD.
 */
function p2g_print_schema() {
	if ( p2g_seo_plugin_active() || is_404() || is_search() ) {
		return;
	}

	$data = array(
		'@context' => 'https://schema.org',
		'@graph'   => p2g_schema_graph(),
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ) . "</script>\n";
}
add_action( 'wp_head', 'p2g_print_schema', 30 );
