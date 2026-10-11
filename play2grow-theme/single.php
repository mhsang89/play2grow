<?php
/**
 * Single post.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-article' ); ?>>
		<header class="article-header">
			<div class="container container--narrow">
				<?php p2g_breadcrumbs(); ?>
				<div class="article-header__badges"><?php p2g_category_badge(); ?></div>
				<h1 class="article-header__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="article-header__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<?php p2g_post_meta( true ); ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="article-media container">
				<?php
				the_post_thumbnail(
					'p2g-wide',
					array(
						'loading'       => 'eager',
						'fetchpriority' => 'high',
						'sizes'         => '(min-width: 1240px) 1100px, 94vw',
					)
				);
				?>
				<?php if ( get_the_post_thumbnail_caption() ) : ?>
					<figcaption><?php the_post_thumbnail_caption(); ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages(
				array(
					'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Các trang của bài viết', 'play2grow-theme' ) . '">' . esc_html__( 'Trang:', 'play2grow-theme' ),
					'after'  => '</nav>',
				)
			);
			?>
		</div>

		<footer class="article-footer container container--narrow">
			<?php
			$p2g_tags = get_the_tag_list( '<ul class="tag-list"><li>', '</li><li>', '</li></ul>' );
			if ( $p2g_tags && ! is_wp_error( $p2g_tags ) ) {
				echo '<div class="article-footer__tags"><span class="article-footer__label">' . esc_html__( 'Thẻ:', 'play2grow-theme' ) . '</span>' . wp_kses_post( $p2g_tags ) . '</div>';
			}

			$p2g_bio = get_the_author_meta( 'description' );
			if ( $p2g_bio ) :
				?>
				<section class="author-box" aria-label="<?php esc_attr_e( 'Về tác giả', 'play2grow-theme' ); ?>">
					<?php echo get_avatar( get_the_author_meta( 'ID' ), 72, '', '', array( 'class' => 'author-box__avatar' ) ); ?>
					<div>
						<p class="author-box__name"><?php the_author_posts_link(); ?></p>
						<p class="author-box__bio"><?php echo esc_html( $p2g_bio ); ?></p>
					</div>
				</section>
			<?php endif; ?>

			<?php
			the_post_navigation(
				array(
					'prev_text'          => '<span class="post-nav__label">' . esc_html__( 'Bài trước', 'play2grow-theme' ) . '</span><span class="post-nav__title">%title</span>',
					'next_text'          => '<span class="post-nav__label">' . esc_html__( 'Bài tiếp theo', 'play2grow-theme' ) . '</span><span class="post-nav__title">%title</span>',
					'screen_reader_text' => __( 'Điều hướng bài viết', 'play2grow-theme' ),
					'aria_label'         => __( 'Bài viết khác', 'play2grow-theme' ),
				)
			);
			?>
		</footer>
	</article>

	<?php
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="container container--narrow">';
		comments_template();
		echo '</div>';
	}

	get_template_part( 'template-parts/related-posts' );
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
