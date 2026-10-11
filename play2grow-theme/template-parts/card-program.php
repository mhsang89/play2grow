<?php
/**
 * Product / learning-experience card (used by [p2g_programs]).
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_program = isset( $args['post'] ) ? get_post( $args['post'] ) : get_post();
if ( ! $p2g_program ) {
	return;
}
$p2g_audience = get_post_meta( $p2g_program->ID, '_p2g_prog_audience', true );
$p2g_format   = get_post_meta( $p2g_program->ID, '_p2g_prog_format', true );
$p2g_status   = get_post_meta( $p2g_program->ID, '_p2g_prog_status', true );
$p2g_statuses = p2g_program_statuses();
?>
<article class="card card--program">
	<?php if ( has_post_thumbnail( $p2g_program ) ) : ?>
		<a class="card__media" href="<?php echo esc_url( get_permalink( $p2g_program ) ); ?>" tabindex="-1" aria-hidden="true">
			<?php echo get_the_post_thumbnail( $p2g_program, 'p2g-card', array( 'sizes' => '(min-width: 1024px) 360px, (min-width: 640px) 45vw, 92vw' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="card__body">
		<?php if ( $p2g_status && ! empty( $p2g_statuses[ $p2g_status ] ) ) : ?>
			<p class="card__meta"><span class="status-badge status-badge--<?php echo esc_attr( $p2g_status ); ?>"><?php echo esc_html( $p2g_statuses[ $p2g_status ] ); ?></span></p>
		<?php endif; ?>
		<h3 class="card__title"><a href="<?php echo esc_url( get_permalink( $p2g_program ) ); ?>"><?php echo esc_html( get_the_title( $p2g_program ) ); ?></a></h3>
		<?php if ( has_excerpt( $p2g_program ) ) : ?>
			<p class="card__excerpt"><?php echo esc_html( get_the_excerpt( $p2g_program ) ); ?></p>
		<?php endif; ?>
		<?php if ( $p2g_audience || $p2g_format ) : ?>
			<ul class="card__facts">
				<?php if ( $p2g_audience ) : ?>
					<li><?php p2g_the_icon( 'users' ); ?><?php echo esc_html( $p2g_audience ); ?></li>
				<?php endif; ?>
				<?php if ( $p2g_format ) : ?>
					<li><?php p2g_the_icon( 'monitor' ); ?><?php echo esc_html( $p2g_format ); ?></li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
		<a class="card__link" href="<?php echo esc_url( get_permalink( $p2g_program ) ); ?>"><?php esc_html_e( 'Xem chi tiết', 'play2grow-theme' ); ?><span class="screen-reader-text">: <?php echo esc_html( get_the_title( $p2g_program ) ); ?></span> <?php p2g_the_icon( 'arrow-right' ); ?></a>
	</div>
</article>
