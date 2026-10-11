<?php
/**
 * Title: Chương trình học – thẻ các lớp đang mở
 * Slug: play2grow/products
 * Categories: play2grow, featured
 * Keywords: chương trình, lớp học, khóa học, danh sách
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Chương trình học</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Các lớp học tại Play2Grow</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Tiếng Anh qua hoạt động STEAM, cùng các lớp vẽ, thủ công, đàn và nhảy – để con được khám phá nhiều niềm yêu thích trong cùng một môi trường.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[p2g_programs limit="8" columns="4"]
<!-- /wp:shortcode -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( 'Xem tất cả chương trình', p2g_page_link( 'chuong-trinh' ), 'outline' ),
	),
	'p2g-center'
);
?>
</div>
<!-- /wp:group -->
