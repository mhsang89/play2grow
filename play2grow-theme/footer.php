<?php
/**
 * Site footer.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

$p2g_about = trim( (string) get_theme_mod( 'p2g_footer_about', '' ) );
if ( '' === $p2g_about ) {
	$p2g_about = get_bloginfo( 'description' );
}
$p2g_copyright = trim( (string) get_theme_mod( 'p2g_copyright', '' ) );
if ( '' === $p2g_copyright ) {
	$p2g_copyright = '© ' . wp_date( 'Y' ) . ' ' . get_bloginfo( 'name' );
}
?>
</main>

<footer class="site-footer">
	<div class="container site-footer__grid">
		<div class="site-footer__col site-footer__brand">
			<?php p2g_site_branding( 'footer' ); ?>
			<?php if ( $p2g_about ) : ?>
				<p class="site-footer__about"><?php echo nl2br( esc_html( $p2g_about ) ); ?></p>
			<?php endif; ?>
			<?php p2g_social_list(); ?>
		</div>

		<?php p2g_footer_menu( 'footer_explore', __( 'Khám phá', 'play2grow-theme' ) ); ?>
		<?php p2g_footer_menu( 'footer_support', __( 'Hỗ trợ', 'play2grow-theme' ) ); ?>

		<div class="site-footer__col site-footer__company">
			<h2 class="site-footer__heading"><?php esc_html_e( 'Thông tin liên hệ', 'play2grow-theme' ); ?></h2>
			<?php p2g_company_info( 'footer' ); ?>
			<?php
			$p2g_cta_url = p2g_contact_url();
			if ( $p2g_cta_url ) :
				?>
				<a class="site-footer__cta" href="<?php echo esc_url( $p2g_cta_url ); ?>"><?php echo esc_html( p2g_cta_label() ); ?> <?php p2g_the_icon( 'arrow-right' ); ?></a>
			<?php endif; ?>
		</div>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p class="site-footer__copy"><?php echo esc_html( $p2g_copyright ); ?></p>
			<?php
			if ( has_nav_menu( 'legal' ) ) {
				wp_nav_menu(
					array(
						'theme_location'       => 'legal',
						'container'            => 'nav',
						'container_class'      => 'legal-nav',
						'container_aria_label' => __( 'Chính sách', 'play2grow-theme' ),
						'depth'                => 1,
						'fallback_cb'          => false,
					)
				);
			} elseif ( get_privacy_policy_url() ) {
				echo '<nav class="legal-nav" aria-label="' . esc_attr__( 'Chính sách', 'play2grow-theme' ) . '">';
				the_privacy_policy_link();
				echo '</nav>';
			}
			?>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/floating-contact' ); ?>

<?php wp_footer(); ?>
</body>
</html>
