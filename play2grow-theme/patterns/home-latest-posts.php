<?php
/**
 * Title: Góc học tập – Bài viết mới
 * Slug: play2grow/latest-posts
 * Categories: play2grow, query
 * Keywords: bài viết, blog, góc học tập
 * Viewport Width: 1400
 *
 * @package Play2Grow
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"p2g-section","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull p2g-section"><!-- wp:group {"className":"p2g-section__head p2g-section__head--center","layout":{"type":"default"}} -->
<div class="wp-block-group p2g-section__head p2g-section__head--center"><!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Góc học tập</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Ý tưởng để tiếng Anh có mặt mỗi ngày</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Hoạt động tại nhà, trò chơi ngôn ngữ và kinh nghiệm đồng hành cùng con – được viết để phụ huynh có thể áp dụng ngay.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:shortcode -->
[p2g_latest_posts count="3"]
<!-- /wp:shortcode -->

<?php
echo p2g_pattern_buttons( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
	array(
		array( 'Xem tất cả bài viết', p2g_posts_page_link(), 'outline' ),
	),
	'p2g-center'
);
?>
</div>
<!-- /wp:group -->
