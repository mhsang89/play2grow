<?php
/**
 * Template Name: Toàn chiều rộng (không tiêu đề)
 * Template Post Type: page
 *
 * For pages built entirely with blocks or a page builder (e.g. Elementor).
 * The page title is not printed – make sure the content contains one H1.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article page-article--canvas' ); ?>>
		<div class="entry-content entry-content--canvas">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
