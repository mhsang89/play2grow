<?php
/**
 * Archives: categories, tags, authors, dates.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

$p2g_eyebrow = '';
if ( is_category() ) {
	$p2g_eyebrow = __( 'Chủ đề', 'play2grow-theme' );
} elseif ( is_tag() ) {
	$p2g_eyebrow = __( 'Thẻ', 'play2grow-theme' );
} elseif ( is_author() ) {
	$p2g_eyebrow = __( 'Tác giả', 'play2grow-theme' );
}

$p2g_title = ( is_category() || is_tag() || is_tax() ) ? single_term_title( '', false ) : ( is_author() ? get_the_author_meta( 'display_name', (int) get_query_var( 'author' ) ) : wp_strip_all_tags( get_the_archive_title() ) );
$p2g_desc  = is_author() ? get_the_author_meta( 'description', (int) get_query_var( 'author' ) ) : get_the_archive_description();

p2g_page_header( $p2g_title, $p2g_desc, $p2g_eyebrow );
get_template_part( 'template-parts/post-list' );
get_template_part( 'template-parts/cta-band' );

get_footer();
