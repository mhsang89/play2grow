<?php
/**
 * Fallback template – also renders the blog index ("Góc học tập").
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

$p2g_posts_page = (int) get_option( 'page_for_posts' );
if ( is_home() && $p2g_posts_page ) {
	$p2g_title = get_the_title( $p2g_posts_page );
	$p2g_desc  = has_excerpt( $p2g_posts_page ) ? get_the_excerpt( $p2g_posts_page ) : '';
} else {
	$p2g_title = is_home() ? __( 'Bài viết mới', 'play2grow-theme' ) : wp_strip_all_tags( get_the_archive_title() );
	$p2g_desc  = '';
}

p2g_page_header( $p2g_title, $p2g_desc );
get_template_part( 'template-parts/post-list' );
get_template_part( 'template-parts/cta-band' );

get_footer();
