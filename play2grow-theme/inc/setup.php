<?php
/**
 * Theme supports, menus, image sizes.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register theme features.
 */
function p2g_setup() {
	load_theme_textdomain( 'play2grow-theme', P2G_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 96,
			'width'       => 320,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Brand palette available inside the block editor.
	add_theme_support(
		'editor-color-palette',
		array(
			array(
				'name'  => __( 'Xanh Play2Grow', 'play2grow-theme' ),
				'slug'  => 'primary',
				'color' => '#0E6B5C',
			),
			array(
				'name'  => __( 'Xanh đậm', 'play2grow-theme' ),
				'slug'  => 'primary-dark',
				'color' => '#0A4F44',
			),
			array(
				'name'  => __( 'Vàng nắng', 'play2grow-theme' ),
				'slug'  => 'accent',
				'color' => '#FFB938',
			),
			array(
				'name'  => __( 'Cam san hô', 'play2grow-theme' ),
				'slug'  => 'coral',
				'color' => '#F0694B',
			),
			array(
				'name'  => __( 'Xanh bạc hà nhạt', 'play2grow-theme' ),
				'slug'  => 'mint',
				'color' => '#E3F4EC',
			),
			array(
				'name'  => __( 'Xanh trời nhạt', 'play2grow-theme' ),
				'slug'  => 'sky',
				'color' => '#E2F0F7',
			),
			array(
				'name'  => __( 'Kem', 'play2grow-theme' ),
				'slug'  => 'cream',
				'color' => '#FFF8EC',
			),
			array(
				'name'  => __( 'Trắng', 'play2grow-theme' ),
				'slug'  => 'white',
				'color' => '#FFFFFF',
			),
			array(
				'name'  => __( 'Mực', 'play2grow-theme' ),
				'slug'  => 'ink',
				'color' => '#17282B',
			),
		)
	);
	add_theme_support(
		'editor-font-sizes',
		array(
			array(
				'name' => __( 'Nhỏ', 'play2grow-theme' ),
				'slug' => 'small',
				'size' => 15,
			),
			array(
				'name' => __( 'Thường', 'play2grow-theme' ),
				'slug' => 'normal',
				'size' => 17,
			),
			array(
				'name' => __( 'Lớn', 'play2grow-theme' ),
				'slug' => 'large',
				'size' => 21,
			),
			array(
				'name' => __( 'Rất lớn', 'play2grow-theme' ),
				'slug' => 'huge',
				'size' => 30,
			),
		)
	);

	add_editor_style( array( 'assets/css/main.css', 'assets/css/editor.css' ) );

	register_nav_menus(
		array(
			'topbar'          => __( 'Thanh trên cùng (liên kết nhanh)', 'play2grow-theme' ),
			'primary'         => __( 'Menu chính (đầu trang)', 'play2grow-theme' ),
			'footer_explore'  => __( 'Chân trang – Khám phá', 'play2grow-theme' ),
			'footer_support'  => __( 'Chân trang – Hỗ trợ', 'play2grow-theme' ),
			'legal'           => __( 'Chân trang – Chính sách (dòng cuối)', 'play2grow-theme' ),
		)
	);

	add_image_size( 'p2g-card', 720, 480, true );
	add_image_size( 'p2g-wide', 1440, 720, true );
}
add_action( 'after_setup_theme', 'p2g_setup' );

/**
 * Content width for embeds.
 */
function p2g_content_width() {
	$GLOBALS['content_width'] = (int) apply_filters( 'p2g_content_width', 760 );
}
add_action( 'after_setup_theme', 'p2g_content_width', 0 );

/**
 * Human readable names for custom image sizes in the media modal.
 *
 * @param array $sizes Sizes.
 * @return array
 */
function p2g_image_size_names( $sizes ) {
	$sizes['p2g-card'] = __( 'Thẻ (3:2)', 'play2grow-theme' );
	$sizes['p2g-wide'] = __( 'Ảnh rộng (2:1)', 'play2grow-theme' );

	return $sizes;
}
add_filter( 'image_size_names_choose', 'p2g_image_size_names' );

/**
 * Register widget areas. Optional – the footer works without widgets.
 */
function p2g_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Thanh bên bài viết', 'play2grow-theme' ),
			'id'            => 'sidebar-blog',
			'description'   => __( 'Hiển thị bên cạnh danh sách bài viết và trang chi tiết bài viết trên màn hình lớn. Để trống nếu không cần.', 'play2grow-theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'p2g_widgets_init' );

/**
 * Body classes.
 *
 * @param array $classes Classes.
 * @return array
 */
function p2g_body_classes( $classes ) {
	if ( is_singular() && ! is_front_page() && has_post_thumbnail() ) {
		$classes[] = 'has-featured-image';
	}
	if ( is_active_sidebar( 'sidebar-blog' ) && ( is_home() || is_archive() || is_singular( 'post' ) || is_search() ) ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'p2g_body_classes' );

/**
 * Shorter, cleaner excerpts.
 *
 * @return int
 */
function p2g_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'p2g_excerpt_length' );

/**
 * Replace "[...]" with an ellipsis.
 *
 * @return string
 */
function p2g_excerpt_more() {
	return '…';
}
add_filter( 'excerpt_more', 'p2g_excerpt_more' );

/**
 * Allow excerpts on pages (used for cards and meta descriptions).
 */
function p2g_page_excerpts() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'p2g_page_excerpts' );

/**
 * Skip link target + pingback header.
 */
function p2g_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'p2g_pingback_header' );
