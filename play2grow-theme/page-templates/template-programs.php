<?php
/**
 * Template Name: Danh sách chương trình
 * Template Post Type: page
 *
 * Overview page: cards for every published page that uses the "Chi tiết chương trình"
 * template, grouped by "Nhóm chương trình", followed by the page content. Add
 * [p2g_programs grouped="1"] in the content to control the position yourself.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	p2g_page_header( get_the_title(), has_excerpt() ? get_the_excerpt() : '' );
	$p2g_inline = has_shortcode( get_the_content(), 'p2g_programs' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article' ); ?>>
		<?php if ( ! $p2g_inline ) : ?>
			<section class="section section--tight" aria-label="<?php esc_attr_e( 'Danh sách chương trình', 'play2grow-theme' ); ?>">
				<div class="container">
					<?php echo p2g_render_programs( 60, 3, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the renderer. ?>
				</div>
			</section>
		<?php endif; ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
