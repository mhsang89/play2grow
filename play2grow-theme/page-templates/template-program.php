<?php
/**
 * Template Name: Chi tiết sản phẩm
 * Template Post Type: page
 *
 * One product / learning experience. Facts come from the "Thông tin sản phẩm /
 * trải nghiệm học tập" box on the edit screen; empty fields are hidden.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$p2g_status   = get_post_meta( get_the_ID(), '_p2g_prog_status', true );
	$p2g_statuses = p2g_program_statuses();
	$p2g_facts    = array();
	foreach ( p2g_program_fields() as $p2g_key => $p2g_field ) {
		$p2g_value = get_post_meta( get_the_ID(), $p2g_key, true );
		if ( '' !== (string) $p2g_value ) {
			$p2g_facts[] = array( $p2g_field[1], $p2g_field[0], $p2g_value );
		}
	}
	?>
	<header class="page-hero page-hero--program">
		<div class="container">
			<?php p2g_breadcrumbs(); ?>
			<?php if ( $p2g_status && ! empty( $p2g_statuses[ $p2g_status ] ) ) : ?>
				<p><span class="status-badge status-badge--<?php echo esc_attr( $p2g_status ); ?>"><?php echo esc_html( $p2g_statuses[ $p2g_status ] ); ?></span></p>
			<?php endif; ?>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<div class="page-hero__lead"><p><?php echo esc_html( get_the_excerpt() ); ?></p></div>
			<?php endif; ?>
		</div>
	</header>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article page-article--program' ); ?>>
		<div class="container program-layout">
			<div class="program-layout__main">
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="program-layout__media">
						<?php the_post_thumbnail( 'p2g-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(min-width: 1024px) 760px, 94vw' ) ); ?>
					</figure>
				<?php endif; ?>
				<div class="entry-content entry-content--flush">
					<?php the_content(); ?>
				</div>
			</div>
			<aside class="program-layout__aside" aria-label="<?php esc_attr_e( 'Thông tin nhanh', 'play2grow' ); ?>">
				<div class="facts-card">
					<h2 class="facts-card__title"><?php esc_html_e( 'Thông tin nhanh', 'play2grow' ); ?></h2>
					<?php if ( $p2g_facts ) : ?>
						<dl class="facts-card__list">
							<?php foreach ( $p2g_facts as $p2g_fact ) : ?>
								<div class="facts-card__row">
									<dt><?php p2g_the_icon( $p2g_fact[0] ); ?><?php echo esc_html( $p2g_fact[1] ); ?></dt>
									<dd><?php echo esc_html( $p2g_fact[2] ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					<?php else : ?>
						<p class="facts-card__empty"><?php esc_html_e( 'Thông tin chi tiết đang được cập nhật.', 'play2grow' ); ?></p>
						<?php echo p2g_editor_note( __( 'Điền hộp "Thông tin sản phẩm / trải nghiệm học tập" bên dưới trình soạn thảo để hiện thông tin nhanh.', 'play2grow' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
					<?php endif; ?>
					<?php p2g_cta_button( 'btn btn--primary btn--block' ); ?>
				</div>
			</aside>
		</div>
	</article>

	<?php
	$p2g_parent = wp_get_post_parent_id( get_the_ID() );
	if ( $p2g_parent ) :
		$p2g_siblings = array_filter(
			p2g_get_programs( $p2g_parent, 6 ),
			static function ( $p ) {
				return get_the_ID() !== $p->ID;
			}
		);
		if ( $p2g_siblings ) :
			?>
			<section class="section section--soft" aria-labelledby="siblings-title">
				<div class="container">
					<h2 id="siblings-title" class="section__title"><?php esc_html_e( 'Khám phá thêm', 'play2grow' ); ?></h2>
					<div class="card-grid card-grid--3">
						<?php
						foreach ( array_slice( $p2g_siblings, 0, 3 ) as $p2g_sibling ) {
							get_template_part( 'template-parts/card', 'program', array( 'post' => $p2g_sibling ) );
						}
						?>
					</div>
				</div>
			</section>
			<?php
		endif;
	endif;
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
