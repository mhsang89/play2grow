<?php
/**
 * Search results.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
$p2g_query = get_search_query();
$p2g_desc  = '' !== $p2g_query
	/* translators: 1: number of results, 2: search term. */
	? sprintf( _n( 'Tìm thấy %1$s kết quả cho "%2$s".', 'Tìm thấy %1$s kết quả cho "%2$s".', (int) $wp_query->found_posts, 'play2grow-theme' ), number_format_i18n( (int) $wp_query->found_posts ), $p2g_query )
	: __( 'Nhập từ khóa để tìm bài viết và trang trên website.', 'play2grow-theme' );
?>
<header class="page-hero">
	<div class="container">
		<?php p2g_breadcrumbs(); ?>
		<h1 class="page-hero__title"><?php esc_html_e( 'Tìm kiếm', 'play2grow-theme' ); ?></h1>
		<p class="page-hero__lead"><?php echo esc_html( $p2g_desc ); ?></p>
		<div class="page-hero__search"><?php get_search_form(); ?></div>
	</div>
</header>
<?php
get_template_part( 'template-parts/post-list' );

get_footer();
