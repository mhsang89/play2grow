<?php
/**
 * Floating contact buttons (phone / Zalo, only when configured) and the mobile
 * sticky call-to-action bar (only when a contact page exists).
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

if ( ! get_theme_mod( 'p2g_show_floating', true ) ) {
	return;
}

$p2g_phone   = p2g_company( 'phone' );
$p2g_zalo    = p2g_social_url( 'zalo' );
$p2g_cta_url = p2g_contact_url();
$p2g_on_cta  = $p2g_cta_url && is_page() && untrailingslashit( get_permalink() ) === untrailingslashit( $p2g_cta_url );

if ( $p2g_phone || $p2g_zalo ) :
	?>
	<div class="floating-contact" aria-label="<?php esc_attr_e( 'Liên hệ nhanh', 'play2grow' ); ?>" role="group">
		<?php if ( $p2g_zalo ) : ?>
			<a class="floating-contact__btn floating-contact__btn--zalo" href="<?php echo esc_url( $p2g_zalo ); ?>" target="_blank" rel="noopener"><span aria-hidden="true">Zalo</span><span class="screen-reader-text"><?php esc_html_e( 'Nhắn tin Zalo cho Play2Grow (mở trong thẻ mới)', 'play2grow' ); ?></span></a>
		<?php endif; ?>
		<?php if ( $p2g_phone ) : ?>
			<a class="floating-contact__btn floating-contact__btn--phone" href="<?php echo esc_url( p2g_tel( $p2g_phone ), array( 'tel' ) ); ?>"><?php p2g_the_icon( 'phone' ); ?><span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: phone. */ __( 'Gọi %s', 'play2grow' ), $p2g_phone ) ); ?></span></a>
		<?php endif; ?>
	</div>
	<?php
endif;

if ( $p2g_cta_url && ! $p2g_on_cta ) :
	?>
	<div class="mobile-cta">
		<a class="btn btn--primary btn--block" href="<?php echo esc_url( $p2g_cta_url ); ?>"><?php echo esc_html( p2g_cta_label() ); ?></a>
	</div>
	<?php
endif;
