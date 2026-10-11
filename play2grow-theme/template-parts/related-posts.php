<?php
/**
 * Related posts (same categories, newest first).
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_cats = wp_get_post_categories( get_the_ID() );
if ( ! $p2g_cats ) {
	return;
}

$p2g_related = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'category__in'        => $p2g_cats,
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $p2g_related->have_posts() ) {
	$p2g_related = new WP_Query(
		array(
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'post__not_in'        => array( get_the_ID() ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);
}

if ( ! $p2g_related->have_posts() ) {
	return;
}
?>
<section class="related section" aria-labelledby="related-title">
	<div class="container">
		<h2 id="related-title" class="section__title"><?php esc_html_e( 'Bài viết liên quan', 'play2grow' ); ?></h2>
		<div class="card-grid card-grid--3">
			<?php
			while ( $p2g_related->have_posts() ) {
				$p2g_related->the_post();
				get_template_part( 'template-parts/card', 'post' );
			}
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
