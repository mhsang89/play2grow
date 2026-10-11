<?php
/**
 * Template Name: Danh sách sản phẩm
 * Template Post Type: page
 *
 * Overview page: shows the page content, then cards for every published child page
 * (each child = one product / learning experience). Add [p2g_programs] anywhere in
 * the content to control the position of the cards yourself.
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
			<section class="section section--tight" aria-label="<?php esc_attr_e( 'Danh sách sản phẩm', 'play2grow' ); ?>">
				<div class="container">
					<?php echo p2g_render_programs( get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the renderer. ?>
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
