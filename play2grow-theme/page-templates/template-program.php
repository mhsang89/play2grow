<?php
/**
 * Template Name: Chi tiết chương trình
 * Template Post Type: page
 *
 * One class / program. Facts come from the "Thông tin chương trình / lớp học" box
 * on the edit screen; empty fields show "Đang cập nhật".
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$p2g_status   = get_post_meta( get_the_ID(), '_p2g_prog_status', true );
	$p2g_statuses = p2g_program_statuses();
	$p2g_groups   = p2g_program_groups();
	$p2g_group    = get_post_meta( get_the_ID(), '_p2g_prog_group', true );
	$p2g_facts    = array();
	$p2g_missing  = array();
	// Parents always look for age, schedule and fee: show them even when not filled in yet.
	$p2g_key_facts = array( '_p2g_prog_audience', '_p2g_prog_schedule', '_p2g_prog_fee' );
	foreach ( p2g_program_fields() as $p2g_key => $p2g_field ) {
		$p2g_value = get_post_meta( get_the_ID(), $p2g_key, true );
		if ( '' !== (string) $p2g_value ) {
			$p2g_facts[] = array( $p2g_field[1], $p2g_field[0], $p2g_value, false );
		} elseif ( in_array( $p2g_key, $p2g_key_facts, true ) ) {
			$p2g_facts[]   = array( $p2g_field[1], $p2g_field[0], __( 'Đang cập nhật – liên hệ để được tư vấn', 'play2grow-theme' ), true );
			$p2g_missing[] = $p2g_field[0];
		}
	}
	?>
	<header class="page-hero page-hero--program">
		<div class="container">
			<?php p2g_breadcrumbs(); ?>
			<?php if ( $p2g_status && ! empty( $p2g_statuses[ $p2g_status ] ) ) : ?>
				<p><span class="status-badge status-badge--<?php echo esc_attr( $p2g_status ); ?>"><?php echo esc_html( $p2g_statuses[ $p2g_status ] ); ?></span></p>
			<?php endif; ?>
			<?php if ( $p2g_group && isset( $p2g_groups[ $p2g_group ] ) ) : ?>
				<p class="eyebrow"><?php echo esc_html( $p2g_groups[ $p2g_group ] ); ?></p>
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
						<?php
						the_post_thumbnail(
							'p2g-wide',
							array(
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'sizes'         => '(min-width: 1024px) 760px, 94vw',
							)
						);
						?>
					</figure>
				<?php endif; ?>
				<div class="entry-content entry-content--flush">
					<?php the_content(); ?>
				</div>
			</div>
			<aside class="program-layout__aside" aria-label="<?php esc_attr_e( 'Thông tin nhanh', 'play2grow-theme' ); ?>">
				<div class="facts-card">
					<h2 class="facts-card__title"><?php esc_html_e( 'Thông tin nhanh', 'play2grow-theme' ); ?></h2>
					<?php if ( $p2g_facts ) : ?>
						<dl class="facts-card__list">
							<?php foreach ( $p2g_facts as $p2g_fact ) : ?>
								<div class="facts-card__row<?php echo $p2g_fact[3] ? ' facts-card__row--pending' : ''; ?>">
									<dt><?php p2g_the_icon( $p2g_fact[0] ); ?><?php echo esc_html( $p2g_fact[1] ); ?></dt>
									<dd><?php echo esc_html( $p2g_fact[2] ); ?></dd>
								</div>
							<?php endforeach; ?>
						</dl>
					<?php else : ?>
						<p class="facts-card__empty"><?php esc_html_e( 'Thông tin chi tiết đang được cập nhật.', 'play2grow-theme' ); ?></p>
					<?php endif; ?>
					<?php
					if ( $p2g_missing ) {
						/* translators: %s: list of empty fields. */
						echo p2g_editor_note( esc_html( sprintf( __( 'Chưa điền: %s. Điền trong hộp "Thông tin chương trình / lớp học" bên dưới trình soạn thảo.', 'play2grow-theme' ), implode( ', ', $p2g_missing ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
					}
					?>
					<?php p2g_cta_button( 'btn btn--primary btn--block' ); ?>
				</div>
			</aside>
		</div>
	</article>

	<?php
	$p2g_siblings = p2g_get_programs(
		array(
			'limit'   => 3,
			'group'   => $p2g_group,
			'exclude' => array( get_the_ID() ),
		)
	);
	if ( count( $p2g_siblings ) < 3 ) {
		$p2g_siblings = array_merge(
			$p2g_siblings,
			p2g_get_programs(
				array(
					'limit'   => 3 - count( $p2g_siblings ),
					'exclude' => array_merge( array( get_the_ID() ), wp_list_pluck( $p2g_siblings, 'ID' ) ),
				)
			)
		);
	}
	if ( $p2g_siblings ) :
		?>
		<section class="section section--soft" aria-labelledby="siblings-title">
			<div class="container">
				<h2 id="siblings-title" class="section__title"><?php esc_html_e( 'Chương trình khác', 'play2grow-theme' ); ?></h2>
				<div class="card-grid card-grid--3">
					<?php
					foreach ( $p2g_siblings as $p2g_sibling ) {
						get_template_part( 'template-parts/card', 'program', array( 'post' => $p2g_sibling ) );
					}
					?>
				</div>
			</div>
		</section>
		<?php
	endif;
endwhile;

get_template_part( 'template-parts/cta-band' );
get_footer();
