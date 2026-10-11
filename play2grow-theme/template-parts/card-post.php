<?php
/**
 * Post card used in archives, search results and the [p2g_latest_posts] shortcode.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'card card--post' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php the_post_thumbnail( 'p2g-card', array( 'sizes' => '(min-width: 1024px) 360px, (min-width: 640px) 45vw, 92vw' ) ); ?>
		</a>
	<?php else : ?>
		<a class="card__media card__media--placeholder" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<span class="card__media-mark"></span>
		</a>
	<?php endif; ?>
	<div class="card__body">
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="card__meta"><?php p2g_category_badge(); ?></div>
		<?php endif; ?>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php if ( 'post' === get_post_type() ) : ?>
			<?php p2g_post_meta( false ); ?>
		<?php endif; ?>
	</div>
</article>
