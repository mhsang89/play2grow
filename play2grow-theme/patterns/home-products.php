<?php
/**
 * Title: Sản phẩm – Danh sách sản phẩm đã công bố
 * Slug: play2grow/products
 * Categories: play2grow, featured
 * Keywords: sản phẩm, chương trình, danh sách
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Sản phẩm</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Sản phẩm và trải nghiệm học tập của Play2Grow</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Mỗi sản phẩm được giới thiệu kèm trạng thái rõ ràng – đang hoạt động, đang thử nghiệm hay sắp ra mắt – để phụ huynh biết chính xác điều gì đã có thể sử dụng.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[p2g_programs limit="3"]
<!-- /wp:shortcode -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( 'Xem trang Sản phẩm', p2g_page_link( 'san-pham' ), 'outline' ),
	),
	'p2g-center'
);
?>
</div>
<!-- /wp:group -->
