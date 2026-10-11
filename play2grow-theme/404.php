<?php
/**
 * 404 page.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section error-404">
	<div class="container container--narrow">
		<p class="error-404__code" aria-hidden="true">404</p>
		<h1 class="error-404__title"><?php esc_html_e( 'Trang bạn tìm không còn ở đây', 'play2grow-theme' ); ?></h1>
		<p class="error-404__lead"><?php esc_html_e( 'Đường dẫn có thể đã thay đổi hoặc trang đã được gỡ. Bạn có thể tìm kiếm nội dung hoặc quay về trang chủ.', 'play2grow-theme' ); ?></p>
		<?php get_search_form(); ?>
		<p class="error-404__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( p2g_home_url() ); ?>"><?php esc_html_e( 'Về trang chủ', 'play2grow-theme' ); ?></a>
			<?php
			$p2g_blog = (int) get_option( 'page_for_posts' );
			if ( $p2g_blog ) :
				?>
				<a class="btn btn--outline" href="<?php echo esc_url( get_permalink( $p2g_blog ) ); ?>"><?php echo esc_html( get_the_title( $p2g_blog ) ); ?></a>
			<?php endif; ?>
		</p>
	</div>
</section>
<?php
get_footer();
