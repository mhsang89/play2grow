<?php
/**
 * Template Name: Liên hệ
 * Template Post Type: page
 *
 * Page content + verified company details on the left, contact form on the right.
 * If the content already contains [p2g_contact_form], the form is not added twice.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	p2g_page_header( get_the_title(), has_excerpt() ? get_the_excerpt() : '' );
	$p2g_inline_form = has_shortcode( get_the_content(), 'p2g_contact_form' );
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-article page-article--contact' ); ?>>
		<div class="container contact-layout<?php echo $p2g_inline_form ? ' contact-layout--single' : ''; ?>">
			<div class="contact-layout__info">
				<div class="entry-content entry-content--flush">
					<?php the_content(); ?>
				</div>
				<div class="contact-card">
					<h2 class="contact-card__title"><?php esc_html_e( 'Thông tin liên hệ', 'play2grow-theme' ); ?></h2>
					<?php p2g_company_info( 'card' ); ?>
					<?php p2g_social_list(); ?>
				</div>
			</div>
			<?php if ( ! $p2g_inline_form ) : ?>
				<div class="contact-layout__form">
					<?php echo p2g_contact_form( __( 'Gửi thông tin cho Play2Grow', 'play2grow-theme' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup from the plugin shortcode. ?>
				</div>
			<?php endif; ?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
