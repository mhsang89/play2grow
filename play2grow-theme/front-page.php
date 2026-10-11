<?php
/**
 * Front page.
 *
 * When a static front page is set and has content, that content (built from the
 * Play2Grow block patterns) is shown full width. Otherwise a minimal fallback uses
 * only data that already exists: site title, tagline, product pages and posts.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

if ( 'posts' === get_option( 'show_on_front' ) && is_paged() ) {
	require P2G_DIR . '/index.php';
	return;
}

get_header();

$p2g_has_page_content = false;
if ( 'page' === get_option( 'show_on_front' ) && have_posts() ) {
	the_post();
	$p2g_has_page_content = '' !== trim( get_the_content() );
}

if ( $p2g_has_page_content ) :
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'home-article' ); ?>>
		<?php if ( false === stripos( get_the_content(), '<h1' ) ) : ?>
			<h1 class="screen-reader-text"><?php echo esc_html( get_bloginfo( 'name' ) . ( get_bloginfo( 'description' ) ? ' – ' . get_bloginfo( 'description' ) : '' ) ); ?></h1>
		<?php endif; ?>
		<div class="entry-content entry-content--home">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
else :
	?>
	<section class="home-fallback-hero">
		<div class="container">
			<h1 class="home-fallback-hero__title"><?php bloginfo( 'name' ); ?></h1>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="home-fallback-hero__lead"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
			<?php p2g_cta_button( 'btn btn--primary btn--lg' ); ?>
			<?php
			echo p2g_editor_note( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
				sprintf(
					/* translators: %s: settings URL. */
					__( 'Đây là trang chủ dự phòng. Để dùng trang chủ đầy đủ, vào <a href="%s">Giao diện → Play2Grow</a> để tạo nội dung khởi đầu, hoặc tạo một trang, chèn các mẫu khối "Play2Grow" rồi chọn trang đó làm Trang chủ trong Cài đặt → Đọc.', 'play2grow' ),
					esc_url( admin_url( 'themes.php?page=p2g-settings' ) )
				)
			);
			?>
		</div>
	</section>

	<section class="section">
		<div class="container">
			<h2 class="section__title"><?php esc_html_e( 'Bài viết mới', 'play2grow' ); ?></h2>
			<?php echo do_shortcode( '[p2g_latest_posts count="3"]' ); ?>
		</div>
	</section>
	<?php
	get_template_part( 'template-parts/cta-band' );
endif;

get_footer();
