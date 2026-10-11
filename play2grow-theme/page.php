<?php
/**
 * Default page template.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	p2g_page_header( get_the_title(), has_excerpt() ? get_the_excerpt() : '' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="page-article__media container">
				<?php
				the_post_thumbnail(
					'p2g-wide',
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '(min-width: 1240px) 1200px, 94vw',
					)
				);
				?>
			</figure>
		<?php endif; ?>
		<div class="entry-content<?php echo p2g_is_elementor() ? ' entry-content--canvas' : ''; ?>">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Các trang của nội dung', 'play2grow-theme' ) . '">' . esc_html__( 'Trang:', 'play2grow-theme' ),
					'after'  => '</nav>',
				)
			);
			?>
		</div>
	</article>
	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="container container--narrow">';
		comments_template();
		echo '</div>';
	}
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
