<?php
/**
 * Title: Kêu gọi hành động – Đăng ký nhận thông tin
 * Slug: play2grow/cta
 * Categories: play2grow, call-to-action
 * Keywords: cta, đăng ký, liên hệ
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section"><!-- wp:group {"className":"p2g-cta-panel","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-cta-panel"><!-- wp:heading -->
<h2 class="wp-block-heading">Cùng Play2Grow tìm lớp học phù hợp cho con</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Để lại thông tin, Play2Grow sẽ liên hệ tư vấn về chương trình, lịch học và học phí phù hợp với độ tuổi và sở thích của con.</p>
<!-- /wp:paragraph -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( 'Đăng ký tư vấn', p2g_contact_url_any() ),
	),
	'p2g-center'
);
?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->
