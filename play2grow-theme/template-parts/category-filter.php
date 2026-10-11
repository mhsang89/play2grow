<?php
/**
 * Category chips used as a simple filter on the blog index and category archives.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_categories = get_categories(
	array(
		'hide_empty' => true,
		'orderby'    => 'name',
		'exclude'    => array( (int) get_option( 'default_category' ) ),
	)
);

if ( count( $p2g_categories ) < 2 ) {
	return;
}

$p2g_blog_url = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
$p2g_current  = is_category() ? get_queried_object_id() : 0;
?>
<nav class="chip-nav" aria-label="<?php esc_attr_e( 'Lọc theo chủ đề', 'play2grow' ); ?>">
	<ul>
		<li><a class="chip<?php echo $p2g_current ? '' : ' is-active'; ?>" href="<?php echo esc_url( $p2g_blog_url ); ?>"<?php echo $p2g_current ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'Tất cả', 'play2grow' ); ?></a></li>
		<?php foreach ( $p2g_categories as $p2g_cat ) : ?>
			<li><a class="chip<?php echo $p2g_current === $p2g_cat->term_id ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_category_link( $p2g_cat ) ); ?>"<?php echo $p2g_current === $p2g_cat->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $p2g_cat->name ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>
