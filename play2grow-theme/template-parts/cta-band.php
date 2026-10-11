<?php
/**
 * Closing call-to-action band. Hidden when no contact page exists.
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;

if ( ! p2g_contact_url() ) {
	return;
}

$p2g_cta = apply_filters(
	'p2g_cta_band',
	array(
		'title' => __( 'Mỗi ngày đều có thể là một bài học tiếng Anh nhỏ', 'play2grow' ),
		'text'  => __( 'Để lại thông tin để nhận cập nhật về sản phẩm và những gợi ý hoạt động mới từ Play2Grow.', 'play2grow' ),
	)
);
?>
<section class="cta-band" aria-labelledby="cta-band-title">
	<div class="container cta-band__inner">
		<div class="cta-band__text">
			<h2 id="cta-band-title" class="cta-band__title"><?php echo esc_html( $p2g_cta['title'] ); ?></h2>
			<p><?php echo esc_html( $p2g_cta['text'] ); ?></p>
		</div>
		<?php p2g_cta_button( 'btn btn--accent' ); ?>
	</div>
</section>
