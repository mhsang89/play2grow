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
<h2 class="wp-block-heading">Cùng con biến những điều quen thuộc thành cơ hội học tiếng Anh</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Để lại thông tin để nhận cập nhật khi Play2Grow giới thiệu sản phẩm mới, cùng những gợi ý hoạt động học tiếng Anh tại nhà.</p>
<!-- /wp:paragraph -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( 'Đăng ký nhận thông tin', p2g_page_link( 'lien-he' ) ),
	),
	'p2g-center'
);
?></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
